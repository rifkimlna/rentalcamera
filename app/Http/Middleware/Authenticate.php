<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        // Tamu yang mencoba aksi sewa (cart/checkout/booking/transaksi) diarahkan
        // ke login dengan pesan, URL tujuan disimpan otomatis sebagai intended.
        if (! $request->session()->has('error')) {
            $request->session()->flash('error', 'Silakan login terlebih dahulu untuk menyewa.');
        }

        return route('login');
    }
}