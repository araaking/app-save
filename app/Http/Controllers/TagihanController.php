<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use App\Models\Siswa;
use App\Models\BiayaSekolah;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\DB;

class TagihanController extends Controller
{
    public function index()
    {
        $tahunAktif = TahunAjaran::where('is_active', true)->firstOrFail();
        
        // Get all tagihan and eager load relationships
        $tagihans = Tagihan::with(['siswa.kelas', 'tahunAjaran'])
            ->whereHas('siswa', function($query) {
                $query->where('status', 'Aktif');
            })
            ->where('tahun_ajaran_id', $tahunAktif->id)
            ->get()
            ->groupBy('siswa_id');
    
        // Manual pagination for grouped data
        $page = request()->get('page', 1);
        $perPage = 10;
        $items = $tagihans->forPage($page, $perPage);
        
        // Create a new paginator instance
        $tagihans = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $tagihans->count(),
            $perPage,
            $page,
            ['path' => request()->url()]
        );
    
        return view('tagihan.index', compact('tagihans', 'tahunAktif'));
    }

    public function generateBills(Request $request)
    {
        $tahunAktif = TahunAjaran::where('is_active', true)->firstOrFail();
        $siswaAktif = Siswa::with(['kelas', 'academicYear'])->where('status', 'Aktif')
            ->where('academic_year_id', $tahunAktif->id)
            ->get();
    
        $isRefresh = $request->has('refresh');
    
        if ($isRefresh) {
            foreach ($siswaAktif as $siswa) {
                // Get existing tagihan
                $existingTagihans = Tagihan::where('siswa_id', $siswa->id)
                    ->where('tahun_ajaran_id', $tahunAktif->id)
                    ->get();
    
                foreach ($existingTagihans as $tagihan) {
                    // Get total payments for this tagihan
                    $totalPayments = Pembayaran::where('siswa_id', $siswa->id)
                        ->where('jenis_biaya', $tagihan->jenis_biaya)
                        ->sum('jumlah');
    
                    // Update sisa by subtracting payments from original amount
                    $tagihan->sisa = $tagihan->jumlah - $totalPayments;
                    $tagihan->save();
                }
            }
        }
    
            foreach ($siswaAktif as $siswa) {
                // SPP - All students (TK to Grade 6)
                $this->generateSPPBill($siswa, $tahunAktif);
            
                // IKK - All students (TK to Grade 6)
                $this->generateIKKBill($siswa, $tahunAktif);
            
                // THB - Only Grade 1-6 (tingkat 2-7)
                if ($siswa->kelas->tingkat >= 2 && $siswa->kelas->tingkat <= 7) {
                    $this->generateTHBBill($siswa, $tahunAktif);
                }
            
                // UAM - Only Grade 6 (tingkat 7)
                if ($siswa->kelas->tingkat == 7) {
                    $this->generateUAMBill($siswa, $tahunAktif);
                    $this->generateSeragamBill($siswa, $tahunAktif);
                }
            
                // Graduation - Only for TK (tingkat 1) who graduated from IQRA
                if ($siswa->kelas->tingkat == 1 && $siswa->status_iqra == 'Alumni TK') {
                    $this->generateGraduationBill($siswa, $tahunAktif);
                }
            
                // Initial Fee - Only for new students
                if ($siswa->status_siswa == 'Baru') {
                    $this->generateInitialFeeBill($siswa, $tahunAktif);
                }
            
                // Photo - Only for TK and Grade 6 (tingkat 1 and 7)
                if (in_array($siswa->kelas->tingkat, [1, 7])) {
                    $this->generatePhotoBill($siswa, $tahunAktif);
                }
            
                // Raport - Only for students without a raport
                // Remove conditional check and call generateRaportBill directly
                $this->generateRaportBill($siswa, $tahunAktif);
            }
            
            return redirect()->route('tagihan.index')
                ->with('success', 'Tagihan berhasil dibuat untuk semua siswa aktif.');
        }
        
    
        private function generateRaportBill($siswa, $tahunAktif)
    {
        // Skip if student already has a report
        if ($siswa->memiliki_raport) {
            return;
        }
    
        $existing = Tagihan::where('siswa_id', $siswa->id)
            ->where('tahun_ajaran_id', $tahunAktif->id)
            ->where('jenis_biaya', 'Raport')
            ->exists();
    
        if (!$existing) {
            $biaya = BiayaSekolah::where('tahun_ajaran_id', $tahunAktif->id)
                ->where('jenis_biaya', 'Raport')
                ->first();
    
            if ($biaya) {
                Tagihan::create([
                    'siswa_id' => $siswa->id,
                    'tahun_ajaran_id' => $tahunAktif->id,
                    'jenis_biaya' => 'Raport',
                    'jumlah' => $biaya->jumlah,
                    'sisa' => $biaya->jumlah,
                    'status' => 'Belum Lunas'
                ]);
            }
        }
    }

        private function generateSPPBill($siswa, $tahunAktif)
        {
            $existing = Tagihan::where('siswa_id', $siswa->id)
                ->where('tahun_ajaran_id', $tahunAktif->id)
                ->where('jenis_biaya', 'SPP')
                ->first();
        
            if (!$existing) {
                $biaya = BiayaSekolah::where('tahun_ajaran_id', $tahunAktif->id)
                    ->where('jenis_biaya', 'SPP')
                    ->where('kategori_siswa', $siswa->category)
                    ->first();
        
                if ($biaya) {
                    $jumlah = $biaya->jumlah * 12;
                    
                    Tagihan::updateOrCreate(
                        [
                            'siswa_id' => $siswa->id,
                            'tahun_ajaran_id' => $tahunAktif->id,
                            'jenis_biaya' => 'SPP',
                            'status' => 'Belum Lunas'
                        ],
                        [
                            'jumlah' => $jumlah,
                            'sisa' => $jumlah
                        ]
                    );
                }
            }
        }

        private function generateIKKBill($siswa, $tahunAktif)
        {
            $existing = Tagihan::where('siswa_id', $siswa->id)
                ->where('tahun_ajaran_id', $tahunAktif->id)
                ->where('jenis_biaya', 'IKK')
                ->first();
        
            if (!$existing) {
                $biaya = BiayaSekolah::where('tahun_ajaran_id', $tahunAktif->id)
                    ->where('jenis_biaya', 'IKK')
                    ->where('kategori_siswa', $siswa->category)
                    ->first();
        
                if ($biaya) {
                    Tagihan::create([
                        'siswa_id' => $siswa->id,
                        'tahun_ajaran_id' => $tahunAktif->id,
                        'jenis_biaya' => 'IKK',
                        'jumlah' => $biaya->jumlah,
                        'sisa' => $biaya->jumlah,
                        'status' => 'Belum Lunas'
                    ]);
                }
            }
        }

        private function generateTHBBill($siswa, $tahunAktif)
        {
            $existing = Tagihan::where('siswa_id', $siswa->id)
                ->where('tahun_ajaran_id', $tahunAktif->id)
                ->where('jenis_biaya', 'THB')
                ->first();
        
            if (!$existing) {
                $biaya = BiayaSekolah::where('tahun_ajaran_id', $tahunAktif->id)
                    ->where('jenis_biaya', 'THB')
                    ->where('tingkat', $siswa->kelas->tingkat)
                    ->first();
        
                if ($biaya) {
                    Tagihan::create([
                        'siswa_id' => $siswa->id,
                        'tahun_ajaran_id' => $tahunAktif->id,
                        'jenis_biaya' => 'THB',
                        'jumlah' => $biaya->jumlah,
                        'sisa' => $biaya->jumlah,
                        'status' => 'Belum Lunas'
                    ]);
                }
            }
        }

        private function generateUAMBill($siswa, $tahunAktif)
        {
            $existing = Tagihan::where('siswa_id', $siswa->id)
                ->where('tahun_ajaran_id', $tahunAktif->id)
                ->where('jenis_biaya', 'UAM')
                ->first();
        
            if (!$existing) {
                $biaya = BiayaSekolah::where('tahun_ajaran_id', $tahunAktif->id)
                    ->where('jenis_biaya', 'UAM')
                    ->where('tingkat', $siswa->kelas->tingkat)  // Add this line to match student's grade
                    ->first();
        
                if ($biaya) {
                    Tagihan::create([
                        'siswa_id' => $siswa->id,
                        'tahun_ajaran_id' => $tahunAktif->id,
                        'jenis_biaya' => 'UAM',
                        'jumlah' => $biaya->jumlah,
                        'sisa' => $biaya->jumlah,
                        'status' => 'Belum Lunas'
                    ]);
                }
            }
        }

        private function generateGraduationBill($siswa, $tahunAktif)
        {
            $existing = Tagihan::where('siswa_id', $siswa->id)
                ->where('tahun_ajaran_id', $tahunAktif->id)
                ->where('jenis_biaya', 'Wisuda')
                ->first();
        
            if (!$existing) {
                $biaya = BiayaSekolah::where('tahun_ajaran_id', $tahunAktif->id)
                    ->where('jenis_biaya', 'Wisuda')
                    ->first();
        
                if ($biaya) {
                    Tagihan::create([
                        'siswa_id' => $siswa->id,
                        'tahun_ajaran_id' => $tahunAktif->id,
                        'jenis_biaya' => 'Wisuda',
                        'jumlah' => $biaya->jumlah,
                        'sisa' => $biaya->jumlah,
                        'status' => 'Belum Lunas'
                    ]);
                }
            }
        }

        private function generateInitialFeeBill($siswa, $tahunAktif)
        {
            if ($siswa->status_siswa != 'Baru') {
                return;
            }
    
            $existing = Tagihan::where('siswa_id', $siswa->id)
                ->where('tahun_ajaran_id', $tahunAktif->id)
                ->where('jenis_biaya', 'Uang Pangkal')
                ->first();
        
            if (!$existing) {
                $biaya = BiayaSekolah::where('tahun_ajaran_id', $tahunAktif->id)
                    ->where('jenis_biaya', 'Uang Pangkal')
                    ->where('kategori_siswa', $siswa->category)
                    ->first();
        
                if ($biaya) {
                    Tagihan::create([
                        'siswa_id' => $siswa->id,
                        'tahun_ajaran_id' => $tahunAktif->id,
                        'jenis_biaya' => 'Uang Pangkal',
                        'jumlah' => $biaya->jumlah,
                        'sisa' => $biaya->jumlah,
                        'status' => 'Belum Lunas'
                    ]);
                }
            }
        }

        private function generatePhotoBill($siswa, $tahunAktif)
        {
            $existing = Tagihan::where('siswa_id', $siswa->id)
                ->where('tahun_ajaran_id', $tahunAktif->id)
                ->where('jenis_biaya', 'Foto')
                ->first();
        
            if (!$existing) {
                $biaya = BiayaSekolah::where('tahun_ajaran_id', $tahunAktif->id)
                    ->where('jenis_biaya', 'Foto')
                    ->where('tingkat', $siswa->kelas->tingkat)
                    ->first();
        
                if ($biaya) {
                    Tagihan::create([
                        'siswa_id' => $siswa->id,
                        'tahun_ajaran_id' => $tahunAktif->id,
                        'jenis_biaya' => 'Foto',
                        'jumlah' => $biaya->jumlah,
                        'sisa' => $biaya->jumlah,
                        'status' => 'Belum Lunas'
                    ]);
                }
            }
        }

    // Add this new method at the end of the class
    private function generateSeragamBill($siswa, $tahunAktif)
    {
        $existing = Tagihan::where('siswa_id', $siswa->id)
            ->where('tahun_ajaran_id', $tahunAktif->id)
            ->where('jenis_biaya', 'Seragam')
            ->first();
    
        if (!$existing) {
            $biaya = BiayaSekolah::where('tahun_ajaran_id', $tahunAktif->id)
                ->where('jenis_biaya', 'Seragam')
                ->where('tingkat', 7)
                ->where('jenis_kelamin', $siswa->jenis_kelamin)
                ->first();
    
            if ($biaya) {
                Tagihan::create([
                    'siswa_id' => $siswa->id,
                    'tahun_ajaran_id' => $tahunAktif->id,
                    'jenis_biaya' => 'Seragam',
                    'jumlah' => $biaya->jumlah,
                    'sisa' => $biaya->jumlah,
                    'status' => 'Belum Lunas'
                ]);
            }
        }
    }
}