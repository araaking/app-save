<?php

namespace App\Http\Controllers;

use App\Models\Penarikan;
use App\Models\BukuTabungan;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class PenarikanController extends Controller
{
    public function index()
    {
        // Get current active tahun ajaran
        $tahunAjaran = TahunAjaran::where('is_active', true)->firstOrFail();
        
        // Get all academic years for the dropdown filter
        $allTahunAjaran = TahunAjaran::orderBy('year_name', 'desc')->get();
        
        // Use selected academic year from query parameter or default to active
        $selectedTahunId = request('tahun_ajaran_id', $tahunAjaran->id);
        $selectedTahun = TahunAjaran::findOrFail($selectedTahunId);

        // Get withdrawals with pagination
        $penarikan = Transaksi::whereHas('bukuTabungan', function($query) use ($selectedTahun) {
            $query->where('tahun_ajaran_id', $selectedTahun->id);
        })
        ->where('jenis', 'penarikan')
        ->orderBy('tanggal', 'desc')
        ->paginate(10);

        return view('penarikan.index', compact('penarikan', 'tahunAjaran', 'selectedTahun', 'allTahunAjaran'));
    }

    public function create(Request $request)
    {
        // Cek tahun ajaran aktif
        $tahunAktif = TahunAjaran::where('is_active', true)->first();

        if (!$tahunAktif) {
            return redirect()->route('dashboard')
                ->with('error', 'Tidak ada tahun ajaran aktif!');
        }

        $kelas = Kelas::all();
        $selectedKelasId = $request->input('kelas_id');
        
        // Build query
        $query = BukuTabungan::query()
            ->where('tahun_ajaran_id', $tahunAktif->id)
            ->whereHas('siswa', function ($query) {
                $query->where('status', 'Aktif');
            });

        // Filter by class if selected
        if ($selectedKelasId) {
            $query->whereHas('siswa', function ($query) use ($selectedKelasId) {
                $query->where('class_id', $selectedKelasId);
            });
        }

        // Get data with relationships
        $bukuTabungans = $query->with(['siswa', 'transaksis'])->get()
            ->map(function ($buku) {
                // Calculate total savings and withdrawals
                $buku->totalSimpanan = $buku->transaksis->where('jenis', 'simpanan')->sum('jumlah');
                $buku->totalPenarikanSimpanan = $buku->transaksis
                    ->where('jenis', 'penarikan')
                    ->where('sumber_penarikan', 'simpanan')
                    ->sum('jumlah');
            
                // Calculate total installments and withdrawals
                $buku->totalCicilan = $buku->transaksis->where('jenis', 'cicilan')->sum('jumlah');
                $buku->totalPenarikanCicilan = $buku->transaksis
                    ->where('jenis', 'penarikan')
                    ->where('sumber_penarikan', 'cicilan')
                    ->sum('jumlah');
            
                return $buku;
            });

        return view('penarikan.create', compact('kelas', 'bukuTabungans', 'selectedKelasId', 'tahunAktif'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'buku_tabungan_id'  => 'required|exists:buku_tabungan,id',
            'jumlah'            => 'required|numeric|min:0',
            'sumber_penarikan'  => 'required|in:simpanan,cicilan',
            'keterangan'        => 'nullable|string|max:255'
        ]);

        if ($request->sumber_penarikan === 'cicilan') {
            // For cicilan, check available balance
            $totalCicilan = Transaksi::where('buku_tabungan_id', $request->buku_tabungan_id)
                ->where('jenis', 'cicilan')
                ->sum('jumlah');

            $totalPenarikanCicilan = Transaksi::where('buku_tabungan_id', $request->buku_tabungan_id)
                ->where('jenis', 'penarikan')
                ->where('sumber_penarikan', 'cicilan')
                ->sum('jumlah');

            $saldoTersedia = $totalCicilan - $totalPenarikanCicilan;

            if ($saldoTersedia < $request->jumlah) {
                return back()->with('error', 'Saldo cicilan tidak mencukupi! Saldo tersedia: ' . number_format($saldoTersedia, 2));
            }
        }

        // Save withdrawal transaction
        Transaksi::create([
            'buku_tabungan_id'  => $request->buku_tabungan_id,
            'jenis'             => 'penarikan',
            'jumlah'            => $request->jumlah,
            'tanggal'           => now(),
            'sumber_penarikan'  => $request->sumber_penarikan,
            'keterangan'        => $request->keterangan
        ]);

        return redirect()->route('penarikan.index')
            ->with('success', 'Penarikan berhasil dicatat!');
    }

    public function edit(Transaksi $penarikan)
    {
        return view('penarikan.edit', compact('penarikan'));
    }

    public function update(Request $request, Transaksi $penarikan)
    {
        $request->validate([
            'jumlah' => 'required|numeric|min:1',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $penarikan->update([
            'jumlah' => $request->jumlah,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()
            ->route('penarikan.index')
            ->with('success', 'Penarikan berhasil diperbarui');
    }

    public function destroy(Transaksi $penarikan)
    {
        $penarikan->delete();
        return redirect()->route('penarikan.index')
            ->with('success', 'Penarikan berhasil dihapus!');
    }
}