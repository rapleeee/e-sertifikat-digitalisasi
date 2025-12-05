<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\LaporanMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LaporanController extends Controller
{
    // ====== Public (user) ======

    public function publicForm(): View
    {
        return view('laporan.public');
    }

    public function publicStore(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'nis' => ['nullable', 'string', 'max:50'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $laporan = Laporan::create([
            'nama' => $data['nama'],
            'email' => $data['email'],
            'nis' => $data['nis'] ?? null,
            'subject' => $data['subject'] ?? null,
            'status' => 'open',
        ]);

        LaporanMessage::create([
            'laporan_id' => $laporan->id,
            'sender_type' => 'user',
            'sender_id' => null,
            'message' => $data['message'],
        ]);

        return redirect()
            ->route('laporan.public.form')
            ->with('success', 'Laporan kamu sudah terkirim. Admin akan meninjau laporan tersebut.');
    }

    // ====== Admin ======

    public function index(): View
    {
        $laporans = Laporan::withCount('messages')
            ->orderByRaw("FIELD(status, 'open', 'closed')")
            ->orderByDesc('updated_at')
            ->paginate(10);

        return view('laporan.index', compact('laporans'));
    }

    public function show(Laporan $laporan): View
    {
        $laporan->load(['messages.sender']);

        return view('laporan.show', compact('laporan'));
    }

    public function reply(Request $request, Laporan $laporan): RedirectResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:5000'],
        ]);

        LaporanMessage::create([
            'laporan_id' => $laporan->id,
            'sender_type' => 'admin',
            'sender_id' => Auth::id(),
            'message' => $validated['message'],
        ]);

        $laporan->touch();

        return redirect()
            ->route('laporan.show', $laporan)
            ->with('success', 'Balasan berhasil dikirim.');
    }

    public function updateStatus(Request $request, Laporan $laporan): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:open,closed'],
        ]);

        $laporan->update([
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('laporan.show', $laporan)
            ->with('success', 'Status laporan berhasil diperbarui.');
    }
}

