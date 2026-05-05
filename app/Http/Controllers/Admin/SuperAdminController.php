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
        $totalRevenue     = 0; // placeholder
        $totalProducts    = 0; // placeholder

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
            'totalRevenue',
            'totalProducts',
            'recentSubmissions',
            'newUsers',
            'revenueData',
            'categoryData'
        ));
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

    /**
     * Ambil daftar akun dari Firebase Authentication (maks 1000).
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
                $list[] = [
                    'uid'           => $user->uid,
                    'email'         => $user->email ?? '(no email)',
                    'emailVerified' => $user->emailVerified,
                ];
            }

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

        $tickets = SupportTicket::with('user')
            ->when($status, fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('hq-admin.service-center', compact('tickets', 'status'));
    }

    public function updateTicket(Request $request, SupportTicket $ticket): RedirectResponse
    {
        $validated = $request->validate([
            'status'      => 'required|in:open,in_progress,closed',
            'admin_reply' => 'nullable|string|max:2000',
        ]);

        $ticket->update($validated);

        AuditLog::record(
            auth()->id(),
            'UPDATE_TICKET',
            'support_tickets',
            $ticket->id,
            "Admin mengubah status tiket #{$ticket->id} menjadi {$validated['status']}"
        );

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
}
