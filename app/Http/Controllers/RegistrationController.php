<?php

namespace App\Http\Controllers;

use App\Models\Events;
use App\Models\Payments;
use App\Models\Registrations;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    public function checkout($id)
    {
        $event = Events::where('status', 'published')->findOrFail($id);

        $alreadyRegistered = Registrations::where('user_id', Auth::id())
            ->where('event_id', $id)
            ->first();

        if ($alreadyRegistered) {
            return redirect()->route('landing')->with('error_register', 'Anda sudah mendaftar di event pelatihan ini!');
        }

        return view('pages.registrations.checkout', compact('event'));
    }

    public function storePayment(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'bukti'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', 
        ]);

        $event = Events::findOrFail($request->event_id);

        if ($event->harga > 0 && !$request->hasFile('bukti')) {
            return redirect()->back()->withErrors(['bukti' => 'Bukti transfer wajib diunggah untuk event berbayar.']);
        }

        $registration = Registrations::create([
            'user_id'        => Auth::id(),
            'event_id'       => $event->id,
            'tanggal_daftar' => Carbon::now(),
            'status'         => $event->harga == 0 ? 'Disetujui' : 'Pending', 
        ]);

        $pathBukti = null;
        if ($request->hasFile('bukti')) {
            $pathBukti = $request->file('bukti')->store('payments', 'public');
        }

        Payments::create([
            'registration_id' => $registration->id,
            'tanggal_bayar'   => Carbon::now(),
            'jumlah'          => $event->harga,
            'bukti'           => $pathBukti,
            'status'          => $event->harga == 0 ? 'Success' : 'Pending', 
        ]);

        return redirect()->route('landing')->with('success_register', 'Pendaftaran Anda berhasil diproses!');
    }

    public function adminIndex(Request $request)
    {
        $search = $request->input('search');

        $registrations = Registrations::with(['user', 'event', 'payment'])
            ->when($search, function ($query, $search) {
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhereHas('event', function ($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pages.adminRegistrations.index', compact('registrations', 'search'));
    }

    public function verify(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Diterima,Ditolak' 
        ]);

        $registration = Registrations::findOrFail($id);
        
        $registration->update([
            'status' => $request->status
        ]);

        if ($registration->payment) {
            $paymentStatus = $request->status === 'Diterima' ? 'Success' : 'Failed';
            $registration->payment->update([
                'status' => $paymentStatus
            ]);
        }

        return redirect()->back()->with('success', 'Status pendaftaran berhasil diperbarui!');
    }

    public function history()
    {
        $registrations = Registrations::with(['event', 'payment'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('pages.registrations.history', compact('registrations'));
    }
}
