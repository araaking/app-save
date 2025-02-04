<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\Tagihan;  // Add this at the top with other imports
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    /**
     * Menampilkan daftar siswa dengan pagination.
     */
    public function index(Request $request)
    {
        $query = Siswa::with(['kelas', 'academicYear']);

        // Ambil tahun ajaran aktif
        $tahunAktif = TahunAjaran::where('is_active', true)->first();

        // Filter otomatis berdasarkan tahun ajaran aktif
        if ($tahunAktif) {
            $query->where('academic_year_id', $tahunAktif->id);
        }

        // Filter pencarian nama
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter kelas
        if ($request->filled('kelas')) {
            $query->whereHas('kelas', function ($q) use ($request) {
                $q->where('name', $request->kelas);
            });
        }

        // Pagination 10 data per halaman
        $siswas = $query->paginate(10)->withQueryString();

        $allKelas = Kelas::all();

        return view('siswa.index', compact('siswas', 'allKelas'));
    }

    /**
     * Menampilkan form tambah siswa.
     */
    public function create()
    {
        $kelas = Kelas::all();
        $tahunAjaran = TahunAjaran::all();
        return view('siswa.create', compact('kelas', 'tahunAjaran'));
    }

    /**
     * Menyimpan data siswa baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nis' => 'nullable|string|max:20|unique:siswa',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'class_id' => 'required|exists:kelas,id',
            'academic_year_id' => 'required|exists:tahun_ajaran,id',
            'status' => 'required|in:Aktif,Lulus,Keluar',
            'category' => 'required|in:Anak Guru,Anak Yatim,Kakak Beradik,Anak Normal',
            'status_siswa' => 'required|in:Baru,Lama',
            'memiliki_raport' => 'required|boolean',
            'status_iqra' => 'required|in:Alumni TK,Bukan Alumni',
            'remarks' => 'nullable|string'
        ]);

        Siswa::create($validated);

        return redirect()->route('siswa.index')
            ->with('success', 'Data siswa berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit siswa.
     */
    public function edit($id)
    {
        $siswa = Siswa::findOrFail($id);
        $kelas = Kelas::all();
        $tahunAjaran = TahunAjaran::all();
        return view('siswa.edit', compact('siswa', 'kelas', 'tahunAjaran'));
    }

    /**
     * Update data siswa.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nis' => 'nullable|string|max:20|unique:siswa,nis,' . $id,
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'class_id' => 'required|exists:kelas,id',
            'academic_year_id' => 'required|exists:tahun_ajaran,id',
            'status' => 'required|in:Aktif,Lulus,Keluar',
            'category' => 'required|in:Anak Guru,Anak Yatim,Kakak Beradik,Anak Normal',
            'status_siswa' => 'required|in:Baru,Lama',
            'memiliki_raport' => 'required|boolean',
            'status_iqra' => 'required|in:Alumni TK,Bukan Alumni',
            'remarks' => 'nullable|string'
        ]);
    
        $siswa = Siswa::findOrFail($id);
        
        // Delete unpaid report bills if report status changes from false to true
        if ($request->boolean('memiliki_raport') && !$siswa->memiliki_raport) {
            Tagihan::where('siswa_id', $siswa->id)
                ->where('jenis_biaya', 'Raport')
                ->where('status', 'Belum Lunas')
                ->delete();
        }
    
        $siswa->update($validated);
    
        return redirect()->route('siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    /**
     * Hapus data siswa.
     */
    public function destroy($id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->delete();

        return redirect()->route('siswa.index')
            ->with('success', 'Siswa berhasil dihapus.');
    }
}