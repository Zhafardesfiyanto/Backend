<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Submission;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Kreait\Firebase\Factory;
use Google\Auth\Credentials\ServiceAccountCredentials;
use GuzzleHttp\Client;

class SuperAdminController extends Controller
{
    // -------------------------------------------------------------------------
    // Dashboard
    // -------------------------------------------------------------------------

    public function index(): View
    {
        // Stats Cards
        $totalUsers       = User::count();
        $totalSubmissions = Submission::count();
        $totalClasses     = \App\Models\Classes::count();
        $totalExams       = \App\Models\Exam::count();

        // Orders Table — 10 submission terbaru
        $recentSubmissions = Submission::with('student')
            ->latest()
            ->take(10)
            ->get();

        // New Users List — 5 user terbaru
        $newUsers = User::latest()->take(5)->get();

        // Chart data
        $revenueData  = $this->getRevenueChartData();
        $categoryData = $this->getCategoryChartData();

        return view('hq-admin.dashboard', compact(
            'totalUsers',
            'totalSubmissions',
            'totalClasses',
            'totalExams',
            'recentSubmissions',
            'newUsers',
            'revenueData',
            'categoryData'
        ));
    }

    public function settings(): View
    {
        $settings = \App\Models\Setting::pluck('value', 'key')->toArray();
        return view('hq-admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'app_name'          => 'required|string|max:50',
            'support_email'     => 'required|email',
            'timezone'          => 'required|string',
            'registration_open' => 'nullable|boolean',
            'maintenance_mode'  => 'nullable|boolean',
        ]);

        // Fix booleans (since checkbox only sends value if checked)
        $data['registration_open'] = $request->has('registration_open') ? '1' : '0';
        $data['maintenance_mode']  = $request->has('maintenance_mode') ? '1' : '0';

        foreach ($data as $key => $value) {
            \App\Models\Setting::set($key, $value);
        }

        AuditLog::record(
            auth()->id(),
            'UPDATE_SETTINGS',
            'settings',
            null,
            "Admin memperbarui pengaturan sistem global"
        );

        return back()->with('success', 'Pengaturan berhasil disimpan!');
    }

    /**
     * Revenue chart data — jumlah submission per bulan, 6 bulan terakhir.
     */
    private function getRevenueChartData(): array
    {
        $labels = [];
        $values = [];

        for ($i = 5; $i >= 0; $i--) {
            $date     = now()->subMonths($i);
            $labels[] = $date->format('M');
            $values[] = Submission::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }

        return ['labels' => $labels, 'values' => $values];
    }

    /**
     * Category chart data — distribusi user berdasarkan role.
     */
    private function getCategoryChartData(): array
    {
        $roles  = User::select('role', DB::raw('count(*) as total'))
            ->groupBy('role')
            ->pluck('total', 'role')
            ->toArray();

        return [
            'labels' => array_keys($roles),
            'values' => array_values($roles),
        ];
    }

    // -------------------------------------------------------------------------
    // User Management
    // -------------------------------------------------------------------------

    public function users(Request $request): View
    {
        $search = $request->query('search', '');

        $users = User::when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $firebaseUsers = $this->getFirebaseUsers();

        return view('hq-admin.users', compact('users', 'firebaseUsers', 'search'));
    }

    // -------------------------------------------------------------------------
    // Class & Exam Management
    // -------------------------------------------------------------------------

    public function classes(Request $request): View
    {
        $search = $request->query('search', '');

        $classes = \App\Models\Classes::with(['teacher', 'members'])
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(15);

        return view('hq-admin.classes', compact('classes', 'search'));
    }

    /**
     * Ambil daftar akun dari Firebase Authentication (maks 1000).
     * Termasuk info Google provider: nama, foto, kapan terakhir login.
     */
    private function getFirebaseUsers(): array
    {
        $credentialsPath = config('firebase.credentials');

        if (empty($credentialsPath)) {
            return ['error' => 'Konfigurasi Firebase belum diatur (FIREBASE_CREDENTIALS kosong).', 'data' => []];
        }

        try {
            $factory = (new Factory)->withServiceAccount($credentialsPath);
            $auth    = $factory->createAuth();
            $result  = $auth->listUsers(1000);

            $list = [];
            foreach ($result as $user) {
                // Cari provider Google di antara semua providerData
                $googleProvider = null;
                foreach ($user->providerData as $provider) {
                    if ($provider->providerId === 'google.com') {
                        $googleProvider = $provider;
                        break;
                    }
                }

                $list[] = [
                    'uid'           => $user->uid,
                    'email'         => $user->email ?? '(no email)',
                    'displayName'   => $user->displayName ?? ($googleProvider?->displayName ?? null),
                    'photoUrl'      => $user->photoUrl ?? ($googleProvider?->photoUrl ?? null),
                    'emailVerified' => $user->emailVerified,
                    'loginGoogle'   => $googleProvider !== null,
                    'lastLoginAt'   => $user->metadata->lastLoginAt ?? null,
                    'createdAt'     => $user->metadata->createdAt ?? null,
                    'disabled'      => $user->disabled,
                ];
            }

            // Urutkan: yang login Google duluan, lalu berdasarkan lastLoginAt terbaru
            usort($list, function ($a, $b) {
                if ($a['loginGoogle'] !== $b['loginGoogle']) {
                    return $b['loginGoogle'] <=> $a['loginGoogle'];
                }
                $timeA = $a['lastLoginAt'] ? $a['lastLoginAt']->getTimestamp() : 0;
                $timeB = $b['lastLoginAt'] ? $b['lastLoginAt']->getTimestamp() : 0;
                return $timeB <=> $timeA;
            });

            return ['error' => null, 'data' => $list];
        } catch (\Kreait\Firebase\Exception\AuthException $e) {
            return ['error' => 'Firebase Auth error: ' . $e->getMessage(), 'data' => []];
        } catch (\Kreait\Firebase\Exception\FirebaseException $e) {
            return ['error' => 'Firebase tidak dapat dijangkau: ' . $e->getMessage(), 'data' => []];
        } catch (\Exception $e) {
            return ['error' => 'Terjadi kesalahan tidak terduga: ' . $e->getMessage(), 'data' => []];
        }
    }

    public function destroyUser(int $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        AuditLog::record(
            auth()->id(),
            'DELETE_USER',
            'users',
            $user->id,
            "Menghapus akun permanen: {$user->email} ({$user->role})"
        );

        $user->delete();

        return back()->with('success', 'User berhasil dihapus!');
    }

    // -------------------------------------------------------------------------
    // Audit Logs
    // -------------------------------------------------------------------------

    public function logs(): View
    {
        $logs = AuditLog::with('user')->latest()->paginate(20);

        return view('hq-admin.logs', compact('logs'));
    }

    // -------------------------------------------------------------------------
    // Customer Service / Support Tickets
    // -------------------------------------------------------------------------

    public function serviceCenter(Request $request): View
    {
        $status = $request->query('status', '');
        $tickets = $this->getFirestoreSupportTickets($status);

        return view('hq-admin.service-center', compact('tickets', 'status'));
    }

    private function getFirestoreSupportTickets($statusFilter = '')
    {
        $credentialsPath = config('firebase.credentials');
        if (empty($credentialsPath) || !file_exists($credentialsPath)) return [];
        
        $keyData = json_decode(file_get_contents($credentialsPath), true);
        $projectId = $keyData['project_id'];
        $credentials = new ServiceAccountCredentials(
            ['https://www.googleapis.com/auth/datastore'], $keyData
        );
        $token = $credentials->fetchAuthToken()['access_token'] ?? null;
        if (!$token) return [];

        $url = "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/support_tickets";
        try {
            $client = new Client(['timeout' => 10]);
            $response = $client->get($url, ['headers' => ['Authorization' => "Bearer {$token}"]]);
            $body = json_decode($response->getBody(), true);
            $documents = $body['documents'] ?? [];

            $list = [];
            foreach ($documents as $doc) {
                $fields = $doc['fields'] ?? [];
                $get = fn($key, $type = 'stringValue') => $fields[$key][$type] ?? null;

                $status = $get('status') ?? 'open';
                if ($statusFilter && $status !== $statusFilter) continue;

                $updatedAt = null;
                if (isset($fields['updatedAt']['timestampValue'])) {
                    try {
                        $updatedAt = new \DateTime($fields['updatedAt']['timestampValue']);
                    } catch (\Exception $e) {}
                }

                // Fetch messages
                $messagesUrl = "https://firestore.googleapis.com/v1/{$doc['name']}/messages?orderBy=createdAt";
                try {
                    $msgResponse = $client->get($messagesUrl, ['headers' => ['Authorization' => "Bearer {$token}"]]);
                    $msgBody = json_decode($msgResponse->getBody(), true);
                    $messages = [];
                    foreach ($msgBody['documents'] ?? [] as $mDoc) {
                        $mFields = $mDoc['fields'] ?? [];
                        $mGet = fn($k, $t = 'stringValue') => $mFields[$k][$t] ?? null;
                        $messages[] = [
                            'text' => $mGet('text'),
                            'isFromSupport' => $mGet('isFromSupport', 'booleanValue') ?? false,
                        ];
                    }
                } catch (\Exception $e) {
                    $messages = []; // subcollection might not exist or be empty
                }

                $uid = basename($doc['name']);
                
                $ticket = new \stdClass();
                $ticket->id = $uid;
                $ticket->subject = "Tiket Support";
                $ticket->status = $status;
                $ticket->user = (object)['name' => $get('name') ?? 'Anonim', 'email' => $get('email') ?? ''];
                $ticket->created_at = $updatedAt;
                
                $chatHistory = "";
                foreach($messages as $msg) {
                    $sender = $msg['isFromSupport'] ? "Admin" : "User";
                    $chatHistory .= "[$sender]: {$msg['text']}\n\n";
                }
                $ticket->message = empty($chatHistory) ? "Belum ada pesan." : $chatHistory;
                $ticket->admin_reply = ""; 

                $list[] = $ticket;
            }

            usort($list, fn($a, $b) => ($b->created_at <=> $a->created_at));
            return $list;
        } catch (\Exception $e) {
            report($e);
            return [];
        }
    }

    public function updateTicket(Request $request, $ticketId): RedirectResponse
    {
        $validated = $request->validate([
            'status'      => 'required|in:open,in_progress,closed',
            'admin_reply' => 'nullable|string|max:2000',
        ]);

        $credentialsPath = config('firebase.credentials');
        if (empty($credentialsPath) || !file_exists($credentialsPath)) {
            return back()->with('error', 'Firebase credentials tidak ditemukan.');
        }

        $keyData = json_decode(file_get_contents($credentialsPath), true);
        $projectId = $keyData['project_id'];
        $credentials = new ServiceAccountCredentials(
            ['https://www.googleapis.com/auth/datastore'], $keyData
        );
        $token = $credentials->fetchAuthToken()['access_token'] ?? null;

        $client = new Client(['timeout' => 10]);

        try {
            // Update status
            $url = "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/support_tickets/{$ticketId}?updateMask.fieldPaths=status&updateMask.fieldPaths=updatedAt";
            $payload = [
                'fields' => [
                    'status' => ['stringValue' => $validated['status']],
                    'updatedAt' => ['timestampValue' => now()->timezone('UTC')->format('Y-m-d\TH:i:s.u\Z')]
                ]
            ];
            $client->patch($url, [
                'headers' => ['Authorization' => "Bearer {$token}"],
                'json' => $payload
            ]);

            // Add reply to messages
            if (!empty($validated['admin_reply'])) {
                $msgUrl = "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/support_tickets/{$ticketId}/messages";
                $msgPayload = [
                    'fields' => [
                        'text' => ['stringValue' => $validated['admin_reply']],
                        'isFromSupport' => ['booleanValue' => true],
                        'createdAt' => ['timestampValue' => now()->timezone('UTC')->format('Y-m-d\TH:i:s.u\Z')]
                    ]
                ];
                
                $msgResponse = $client->post($msgUrl, [
                    'headers' => ['Authorization' => "Bearer {$token}"],
                    'json' => $msgPayload,
                    'http_errors' => false
                ]);
                
                if ($msgResponse->getStatusCode() >= 400) {
                    throw new \Exception("Firestore error: " . $msgResponse->getBody()->getContents());
                }
            }

            AuditLog::record(
                auth()->id(),
                'UPDATE_TICKET',
                'support_tickets',
                $ticketId,
                "Admin membalas/mengubah status tiket Firestore #{$ticketId} menjadi {$validated['status']}"
            );

        } catch (\Exception $e) {
            report($e);
            return back()->with('error', 'Gagal update tiket di Firestore: ' . $e->getMessage());
        }

        return back()->with('success', 'Tiket berhasil diperbarui.');
    }

    // -------------------------------------------------------------------------
    // Legacy / Service Center Tools
    // -------------------------------------------------------------------------

    public function emergencyReset(): RedirectResponse
    {
        DB::table('submissions')->where('status', 'on-progress')->update(['status' => 'canceled']);

        AuditLog::record(auth()->id(), 'SYSTEM_RESET', 'submissions', null, 'Melakukan reset masal pada ujian aktif');

        return back()->with('info', 'Sistem berhasil di-reset!');
    }

    public function resetExamSession(int $userId): RedirectResponse
    {
        $submission = Submission::where('student_id', $userId)->where('status', 'on-progress')->first();

        if ($submission) {
            $submission->update(['status' => 'canceled']);

            AuditLog::record(
                auth()->id(),
                'SERVICE_RESET',
                'submissions',
                $submission->id,
                "Admin mereset sesi ujian User ID: $userId karena kendala teknis"
            );

            return back()->with('success', 'Sesi ujian berhasil di-reset!');
        }

        return back()->with('error', 'Tidak ada sesi aktif ditemukan.');
    }

    // -------------------------------------------------------------------------
    // Ratings & Firestore Introspection
    // -------------------------------------------------------------------------

    /**
     * Halaman rating dari Firestore — untuk introspeksi admin
     * GET /hq-admin/ratings
     */
    public function ratings(): View
    {
        $ratings    = $this->getFirestoreRatings();
        $avgRating  = collect($ratings)->avg('rating') ?? 0;
        $totalCount = count($ratings);

        // Distribusi bintang 1-5
        $distribution = array_fill(1, 5, 0);
        foreach ($ratings as $r) {
            $star = (int) ($r['rating'] ?? 0);
            if ($star >= 1 && $star <= 5) {
                $distribution[$star]++;
            }
        }

        return view('hq-admin.ratings', compact(
            'ratings',
            'avgRating',
            'totalCount',
            'distribution'
        ));
    }

    /**
     * Ambil semua dokumen dari koleksi app_ratings via Firestore REST API.
     * Tidak memerlukan ext-grpc — menggunakan HTTP + ServiceAccountCredentials.
     */
    private function getFirestoreRatings(): array
    {
        $credentialsPath = config('firebase.credentials');

        if (empty($credentialsPath) || !file_exists($credentialsPath)) {
            return [];
        }

        try {
            $keyData   = json_decode(file_get_contents($credentialsPath), true);
            $projectId = $keyData['project_id'];

            // Dapatkan access token via service account (google/auth sudah include di kreait)
            $scopes      = ['https://www.googleapis.com/auth/datastore'];
            $credentials = new ServiceAccountCredentials($scopes, $keyData);
            $tokenData   = $credentials->fetchAuthToken();
            $accessToken = $tokenData['access_token'] ?? null;

            if (!$accessToken) {
                return [];
            }

            // Panggil Firestore REST API
            $url = "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/app_ratings";

            $client   = new Client(['timeout' => 10]);
            $response = $client->get($url, [
                'headers' => ['Authorization' => "Bearer {$accessToken}"],
            ]);

            $body      = json_decode($response->getBody(), true);
            $documents = $body['documents'] ?? [];

            $list = [];
            foreach ($documents as $doc) {
                $fields = $doc['fields'] ?? [];

                // Helper: ambil nilai dari Firestore field format
                $get = fn($key, $type = 'stringValue') => $fields[$key][$type] ?? null;

                // Firestore timestamp → format tanggal
                $submittedAt = null;
                if (isset($fields['submittedAt']['timestampValue'])) {
                    try {
                        $dt          = new \DateTime($fields['submittedAt']['timestampValue']);
                        $submittedAt = $dt->format('d M Y H:i');
                    } catch (\Exception) {}
                }

                // Tags tersimpan sebagai arrayValue
                $tags = [];
                if (isset($fields['tags']['arrayValue']['values'])) {
                    foreach ($fields['tags']['arrayValue']['values'] as $v) {
                        $tags[] = $v['stringValue'] ?? '';
                    }
                }

                $list[] = [
                    'uid'         => basename($doc['name']),
                    'name'        => $get('name')          ?? 'Anonim',
                    'rating'      => (int) ($get('rating', 'integerValue') ?? $get('rating', 'doubleValue') ?? 0),
                    'review'      => $get('review')        ?? '',
                    'tags'        => $tags,
                    'submittedAt' => $submittedAt,
                ];
            }

            // Urutkan dari rating tertinggi
            usort($list, fn($a, $b) => $b['rating'] <=> $a['rating']);

            return $list;

        } catch (\Exception $e) {
            report($e);
            return [];
        }
    }
}

