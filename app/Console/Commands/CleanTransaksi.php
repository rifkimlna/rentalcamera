<?php

namespace App\Console\Commands;

use App\Models\Transaksis;
use Illuminate\Console\Command;

class CleanTransaksi extends Command
{
    protected $signature = 'transaksi:clean';
    protected $description = 'Hapus semua data transaksi kecuali 5 transaksi terbaru';

    public function handle()
    {
        $keep = 5;

        $total = Transaksis::count();

        if ($total <= $keep) {
            $this->info("Total transaksi ($total) sudah <= $keep, tidak ada yang perlu dihapus.");
            return Command::SUCCESS;
        }

        $idsToKeep = Transaksis::orderBy('id', 'desc')
            ->take($keep)
            ->pluck('id');

        $deleted = Transaksis::whereNotIn('id', $idsToKeep)->delete();

        $this->info("Berhasil membersihkan data transaksi.");
        $this->info("Total awal: $total");
        $this->info("Transaksi dihapus: $deleted");
        $this->info("Transaksi dipertahankan: $keep");

        return Command::SUCCESS;
    }
}
