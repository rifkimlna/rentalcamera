<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::routes(['middleware' => ['web', 'auth']]);

Broadcast::channel('notifications.{userId}', function (User $user, int $userId) {
    return $user->id === $userId;
});
