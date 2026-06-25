<?php

namespace App\Console\Commands;

use App\Models\Notification;
use Illuminate\Console\Command;

class TestNotification extends Command
{
    protected $signature = 'notif:test {user? : User ID}';
    protected $description = 'Kirim notifikasi test realtime';

    public function handle()
    {
        $userId = $this->argument('user') ?? 1;

        Notification::create([
            'user_id' => $userId,
            'type' => 'system',
            'title' => 'Test Notifikasi Realtime',
            'message' => 'Ini adalah notifikasi realtime dari Reverb!',
            'data' => ['test' => true],
        ]);

        $this->info("Notifikasi test terkirim ke user #{$userId}");
    }
}
