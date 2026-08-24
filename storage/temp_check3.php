<?php

$user = App\Models\User::where('role', 'customer')->first();
auth()->login($user);

$resp = app()->handle(Illuminate\Http\Request::create('/customer/my-bookings/studio', 'GET'));
echo 'STATUS: ' . $resp->getStatusCode() . PHP_EOL;
$html = $resp->getContent();
preg_match('/<title>(.*?)<\/title>/s', $html, $m);
echo 'TITLE: ' . ($m[1] ?? '-') . PHP_EOL;
// tampilkan potongan pesan error bila ada
if (preg_match('/(Undefined|Call to|Attempt to|syntax|ParseError|ViewException)[^<]{0,160}/', $html, $e2)) {
    echo 'MSG: ' . trim($e2[0]) . PHP_EOL;
}
