<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupportTicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $ticket = SupportTicket::create([
            'user_id' => $request->user()->id,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'open',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pesan berhasil dikirim',
            'ticket' => $ticket
        ], 201);
    }

    public function myTickets(Request $request)
    {
        $tickets = SupportTicket::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'tickets' => $tickets
        ]);
    }

    public function all(Request $request)
    {
        // Pastikan hanya admin yang bisa akses ini, bisa tambah gate/role check di sini
        $tickets = SupportTicket::with('user:id,name,email')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'tickets' => $tickets
        ]);
    }

    public function reply(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'reply' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $ticket = SupportTicket::findOrFail($id);
        $ticket->admin_reply = $request->reply;
        $ticket->status = 'in_progress'; // enum: open, in_progress, closed
        $ticket->save();

        return response()->json([
            'success' => true,
            'message' => 'Balasan berhasil dikirim',
            'ticket' => $ticket
        ]);
    }

    public function close(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);
        $ticket->status = 'closed';
        $ticket->save();

        return response()->json([
            'success' => true,
            'message' => 'Tiket berhasil ditutup',
            'ticket' => $ticket
        ]);
    }
}
