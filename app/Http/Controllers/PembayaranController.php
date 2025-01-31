<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use App\Models\BukuTabungan;
use App\Models\Transaksi;
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
        $siswas = collect();
        
        if ($request->has('siswa_id')) {
            $siswas = Siswa::where('id', $request->siswa_id)->get();
        }

        return view('pembayaran.create', compact('siswas'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
    
            // Get the related tagihan
            $tagihan = Tagihan::where('siswa_id', $request->siswa_id)
                            ->where('jenis_biaya', $request->jenis_biaya)
                            ->where('sisa', '>', 0)
                            ->firstOrFail();
    
            // Validate payment amount doesn't exceed remaining balance
            if ($request->jumlah > $tagihan->sisa) {
                throw new \Exception('Jumlah pembayaran melebihi sisa tagihan.');
            }
    
            // Check available balance for cicilan/tabungan payments
            if (in_array($request->metode_pembayaran, ['cicilan', 'tabungan'])) {
                $bukuTabungan = BukuTabungan::where('siswa_id', $request->siswa_id)->first();
                
                if ($request->metode_pembayaran === 'cicilan') {
                    $saldoCicilan = $bukuTabungan->total_cicilan - $bukuTabungan->total_penarikan_cicilan;
                    if ($request->jumlah > $saldoCicilan) {
                        throw new \Exception("Saldo cicilan tidak mencukupi. Saldo tersedia: Rp " . number_format($saldoCicilan, 0, ',', '.'));
                    }
                } else {
                    $saldoSimpanan = $bukuTabungan->total_simpanan - $bukuTabungan->total_penarikan_simpanan;
                    if ($request->jumlah > $saldoSimpanan) {
                        throw new \Exception("Saldo tabungan tidak mencukupi. Saldo tersedia: Rp " . number_format($saldoSimpanan, 0, ',', '.'));
                    }
                }
            }
    
            // Create payment record
            $pembayaran = Pembayaran::create([
                'siswa_id' => $request->siswa_id,
                'tagihan_id' => $tagihan->id,
                'jenis_biaya' => $request->jenis_biaya,
                'jumlah' => $request->jumlah,
                'metode_pembayaran' => $request->metode_pembayaran,
                'bulan_hijri' => $request->bulan_hijri,
                'keterangan' => $request->keterangan
            ]);
    
            // Reduce tagihan
            $tagihan->sisa -= $request->jumlah;
            $tagihan->save();
    
            // Handle cicilan/simpanan payments
            if (in_array($request->metode_pembayaran, ['cicilan', 'tabungan'])) {
                $bukuTabungan = BukuTabungan::where('siswa_id', $request->siswa_id)->first();
                
                // Create withdrawal transaction
                Transaksi::create([
                    'buku_tabungan_id' => $bukuTabungan->id,
                    'jenis' => 'penarikan',
                    'jumlah' => $request->jumlah,
                    'tanggal' => now(),
                    'sumber_penarikan' => $request->metode_pembayaran === 'cicilan' ? 'cicilan' : 'simpanan',
                    'keterangan' => "Pembayaran {$request->jenis_biaya} via " . 
                        ($request->metode_pembayaran === 'cicilan' ? 'cicilan' : 'tabungan')
                ]);
    
                // Only reduce balance for cicilan payments
                if ($request->metode_pembayaran === 'cicilan') {
                    $bukuTabungan->increment('total_penarikan_cicilan', $request->jumlah);
                } else {
                    // For simpanan, we track it but don't reduce the actual balance
                    $bukuTabungan->increment('total_penarikan_simpanan', $request->jumlah);
                }
            }
    
            DB::commit();
            return redirect()->route('tagihan.index')
                ->with('success', 'Pembayaran berhasil disimpan');
    
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

// Add new method to get buku tabungan info
public function getBukuTabungan($siswaId)
{
    $bukuTabungan = BukuTabungan::where('siswa_id', $siswaId)->first();
    
    if (!$bukuTabungan) {
        return response()->json(['error' => 'Buku tabungan tidak ditemukan'], 404);
    }

    return response()->json([
        'total_simpanan' => $bukuTabungan->total_simpanan,
        'total_penarikan_simpanan' => $bukuTabungan->total_penarikan_simpanan,
        'total_cicilan' => $bukuTabungan->total_cicilan,
        'total_penarikan_cicilan' => $bukuTabungan->total_penarikan_cicilan
    ]);
}
public function getTagihan($siswaId)
{
    $tagihan = Tagihan::where('siswa_id', $siswaId)
                     ->where('sisa', '>', 0)
                     ->get(['id', 'jenis_biaya', 'sisa']);

    return response()->json($tagihan);
}
}