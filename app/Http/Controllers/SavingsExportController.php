<?php

namespace App\Http\Controllers;

use App\Models\BukuTabungan;
use App\Models\BiayaSekolah;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Models\Pembayaran;

class SavingsExportController extends Controller
{
    // Add constants at the top of the class
    private const FEE_EXEMPTIONS = [
        'Anak Guru' => ['SPP', 'IKK', 'Uang Pangkal'],
        'Anak Yatim' => ['SPP'],
    ];

    private const DISCOUNTS = [
        'Kakak Beradik' => [
            'SPP' => 0.2,
            'IKK' => 0.2
        ]
    ];

    public function preview($id)
    {
        $data = $this->prepareData($id);
        return view('exports.savings-withdrawal', $data);
    }

    public function exportPDF($id)
    {
        try {
            $data = $this->prepareData($id);
            $pdf = PDF::loadView('exports.savings-withdrawal', $data);
            
            $filename = 'Laporan-Tabungan-' 
                . $data['bukuTabungan']->siswa->name 
                . '-' . now()->format('Ymd') . '.pdf';
            return $pdf->download($filename);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'Gagal generate PDF: ' . $e->getMessage()
            ]);
        }
    }

    private function getAdminPercentage($category)
    {
        $isEarlyWithdrawal = $this->isEarlyWithdrawal();
    
        return match($category) {
            'Anak Guru' => 5,
            'Anak Yatim', 'Kakak Beradik', 'Anak Normal' => $isEarlyWithdrawal ? 10 : 8,
            default => 8,
        };
    }

    private function isEarlyWithdrawal()
    {
        return now()->month < 6;
    }

    private function calculateDeductions($bukuTabungan)
    {
        $siswa = $bukuTabungan->siswa;
        $category = $siswa->category;
        
        // Get all unpaid tagihan for the student
        $tagihans = $siswa->tagihan()
            ->where('tahun_ajaran_id', $siswa->academic_year_id)
            ->get()
            ->keyBy('jenis_biaya');

        // Calculate unpaid SPP months
        $unpaidMonths = $this->calculateUnpaidMonths($siswa);
        
        // Get loan from transaksi table
        $loan = $bukuTabungan->transaksis()
            ->where('jenis', 'penarikan')
            ->where('sumber_penarikan', 'simpanan')
            ->sum('jumlah');
        
        $deductions = [
            'SPP' => (int)($tagihans->get('SPP')->sisa ?? 0),
            'IKK' => (int)($tagihans->get('IKK')->sisa ?? 0),
            'THB' => (int)($tagihans->get('THB')->sisa ?? 0),
            'UAM' => (int)($tagihans->get('UAM')->sisa ?? 0),
            'Wisuda' => (int)($tagihans->get('Wisuda')->sisa ?? 0),
            'Uang Pangkal' => (int)($tagihans->get('Uang Pangkal')->sisa ?? 0),
            'Foto' => (int)($tagihans->get('Foto')->sisa ?? 0),
            'Raport' => (int)($tagihans->get('Raport')->sisa ?? 0),
            'Seragam' => (int)($tagihans->get('Seragam')->sisa ?? 0),
            'previous_arrears' => (int)($bukuTabungan->previous_arrears ?? 0),
            'loan' => (int)$loan,
        ];
        
        $deductions['total'] = array_sum($deductions);
        return $deductions;
    }

    private function calculateUnpaidFee($jenisBiaya, $biayaSekolah, $paidFees, $category)
    {
        // If student category doesn't need to pay this fee
        if ($this->isExemptFromFee($category, $jenisBiaya)) {
            return 0;
        }
    
        // Get the standard fee amount
        $standardFee = $biayaSekolah->get($jenisBiaya)->jumlah ?? 0;
    
        // Get total paid amount for this fee type
        $paidAmount = $paidFees->get($jenisBiaya, collect())->sum('jumlah') ?? 0;
    
        // Apply category-based discount if applicable
        $requiredAmount = $this->applyDiscount($standardFee, $category, $jenisBiaya);
    
        // Return remaining unpaid amount
        return max($requiredAmount - $paidAmount, 0);
    }

    private function calculateUnpaidMonths($siswa)
    {
        $tahunAjaran = $siswa->academicYear;
        
        if (!$tahunAjaran) {
            return 0;
        }
        
        // Count unique months paid
        $paidMonths = (int)Pembayaran::whereHas('tagihan', function ($query) use ($tahunAjaran) {
                $query->where('tahun_ajaran_id', $tahunAjaran->id);
            })
            ->where('siswa_id', $siswa->id)
            ->where('jenis_biaya', 'SPP')
            ->distinct()
            ->pluck('bulan_hijri')
            ->unique()
            ->count();
        
        return $paidMonths; // Return number of months paid
    }

    private function isExemptFromFee($category, $jenisBiaya)
    {
        return in_array($jenisBiaya, self::FEE_EXEMPTIONS[$category] ?? []);
    }

    private function applyDiscount($amount, $category, $jenisBiaya)
    {
        $discount = self::DISCOUNTS[$category][$jenisBiaya] ?? 0;
        return $amount * (1 - $discount);
    }

    private function prepareData($id)
    {
        $bukuTabungan = BukuTabungan::with(['siswa.kelas', 'siswa.academicYear'])->findOrFail($id);
        
        // Calculate total savings
        $totalSimpanan = $bukuTabungan->transaksis()
            ->where('jenis', 'simpanan')
            ->sum('jumlah');
    
        // Calculate remaining installments
        $totalCicilan = $bukuTabungan->transaksis()
            ->where('jenis', 'cicilan')
            ->sum('jumlah');
        $totalPenarikanCicilan = $bukuTabungan->transaksis()
            ->where('jenis', 'penarikan')
            ->where('sumber_penarikan', 'cicilan')
            ->sum('jumlah');
        $sisaCicilan = $totalCicilan - $totalPenarikanCicilan;
    
        // Add remaining installments to total savings
        $totalSimpanan += $sisaCicilan;
        $bukuTabungan->total_simpanan = $totalSimpanan;
    
        // Get early withdrawal status
        $isEarlyWithdrawal = $this->isEarlyWithdrawal();
        
        // Get admin percentage based on student category
        $adminPercentage = $this->getAdminPercentage($bukuTabungan->siswa->category);
        $adminFee = ($totalSimpanan * $adminPercentage) / 100;
    
        // Calculate deductions
        $deductions = $this->calculateDeductions($bukuTabungan);
        
        // Calculate remaining savings
        $remainingSavings = ($totalSimpanan - $adminFee) - $deductions['total'];
    
        return [
            'bukuTabungan' => $bukuTabungan,
            'adminPercentage' => $adminPercentage,
            'adminFee' => $adminFee,
            'deductions' => $deductions,
            'remainingSavings' => $remainingSavings,
            'sisaCicilan' => $sisaCicilan,
            'isEarlyWithdrawal' => $isEarlyWithdrawal, // Add this line
            'unpaidMonths' => $this->calculateUnpaidMonths($bukuTabungan->siswa) // Add this if not already included
        ];
    }
}