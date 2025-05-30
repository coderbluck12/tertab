<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaystackService
{
    protected $secretKey;
    protected $baseUrl = 'https://api.paystack.co';

    public function __construct()
    {
        $this->secretKey = config('services.paystack.secret_key');
    }

    public function initializeTransaction($amount, $email, $reference)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type' => 'application/json'
            ])->post($this->baseUrl . '/transaction/initialize', [
                'amount' => $amount * 100, // Convert to kobo
                'email' => $email,
                'reference' => $reference,
                'callback_url' => route('payment.callback'),
                'currency' => 'NGN'
            ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Paystack initialization error: ' . $e->getMessage());
            return null;
        }
    }

    public function verifyTransaction($reference)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type' => 'application/json'
            ])->get($this->baseUrl . '/transaction/verify/' . $reference);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Paystack verification error: ' . $e->getMessage());
            return null;
        }
    }
} 