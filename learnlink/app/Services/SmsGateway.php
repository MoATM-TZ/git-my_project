<?php
namespace App\Services;
use Illuminate\Support\Facades\Http; use Illuminate\Support\Facades\Log;
class SmsGateway { public function send(string $phone, string $message): void { $url=config('services.sms.url'); $token=config('services.sms.token'); if (!$url || !$token) { Log::info('SMS notification skipped: configure SMS_URL and SMS_TOKEN.', compact('phone','message')); return; } Http::withToken($token)->post($url, ['to'=>$phone,'message'=>$message])->throw(); } }
