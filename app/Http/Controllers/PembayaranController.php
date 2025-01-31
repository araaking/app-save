<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    public function index()
    {
        $pembayarans = Pembayaran::with(['siswa', 'tagihan'])->latest()->paginate(10);
        return view('pembayaran.index', compact('pembayarans'));
    }

    public function create(Request $request)
    {
        $kelasList = Kelas::all();
        $siswas = collect();

        if ($request->has('kelas_id')) {
            $siswas = Siswa::where('class_id', $request->kelas_id) // Changed from kelas_id to class_id
                          ->orderBy('name')
                          ->get();
        }

        return view('pembayaran.create', compact('kelasList', 'siswas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'jenis_biaya' => 'required|in:' . implode(',', Pembayaran::JENIS_BIAYA),
            'jumlah' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|in:' . implode(',', Pembayaran::METODE_PEMBAYARAN),
            'bulan_hijri' => 'required_if:jenis_biaya,SPP|nullable|in:' . implode(',', Pembayaran::BULAN_HIJRI),
        ]);

        try {
            DB::beginTransaction();

            // Get the related tagihan
            $tagihan = Tagihan::where('siswa_id', $request->siswa_id)
                            ->where('jenis_biaya', $request->jenis_biaya)
                            ->firstOrFail();

            // Create payment record
            $pembayaran = Pembayaran::create([
                'siswa_id' => $request->siswa_id,
                'tagihan_id' => $tagihan->id,
                'jenis_biaya' => $request->jenis_biaya,
                'bulan_hijri' => $request->bulan_hijri,
                'jumlah' => $request->jumlah,
                'metode_pembayaran' => $request->metode_pembayaran,
                'keterangan' => $request->keterangan,
            ]);

            // Update tagihan based on payment method
            $tagihan->sisa -= $request->jumlah;
            $tagihan->save();

            DB::commit();
            return redirect()->route('tagihan.index')->with('success', 'Pembayaran berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

public function getTagihan($siswaId)
{
    $tagihan = Tagihan::where('siswa_id', $siswaId)
                     ->where('sisa', '>', 0)
                     ->get(['id', 'jenis_biaya', 'sisa']);

    return response()->json($tagihan);
}
}