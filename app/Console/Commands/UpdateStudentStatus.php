<?php

namespace App\Console\Commands;

use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Console\Command;

class UpdateStudentStatus extends Command
{
    protected $signature = 'students:update-status';
    protected $description = 'Update student status from Baru to Lama when academic year changes';

    public function handle()
    {
        $tahunAktif = TahunAjaran::where('is_active', true)->first();
        
        if (!$tahunAktif) {
            $this->error('No active academic year found!');
            return 1;
        }

        $updatedCount = Siswa::where('status', 'Aktif')
            ->where('status_siswa', 'Baru')
            ->where('academic_year_id', '!=', $tahunAktif->id)
            ->update(['status_siswa' => 'Lama']);

        $this->info("Successfully updated {$updatedCount} student(s) status from Baru to Lama.");
        return 0;
    }
}
