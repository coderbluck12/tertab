<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Services\PaystackService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WalletController extends Controller
{
    protected $paystackService;

    public function __construct(PaystackService $paystackService)
    {
        $this->paystackService = $paystackService;
    }

    public function show()
    {
        $wallet = auth()->user()->wallet;
        if (!$wallet) {
            $wallet = Wallet::create([
                'user_id' => auth()->id(),
                'balance' => 0,
                'currency' => 'NGN'
            ]);
        }
        return view('wallet.show', compact('wallet'));
    }

    public function initializePayment(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100'
        ]);

        $reference = 'WAL_' . Str::random(10);
        $response = $this->paystackService->initializeTransaction(
            $request->amount,
            auth()->user()->email,
            $reference
        );

        if (!$response || !isset($response['status']) || !$response['status']) {
            return back()->with('error', 'Failed to initialize payment. Please try again.');
        }

        return redirect($response['data']['authorization_url']);
    }

    public function handleCallback(Request $request)
    {
        $reference = $request->reference;
        $response = $this->paystackService->verifyTransaction($reference);

        if (!$response || !isset($response['status']) || !$response['status']) {
            return redirect()->route('wallet.show')->with('error', 'Payment verification failed.');
        }

        if ($response['data']['status'] === 'success') {
            $amount = $response['data']['amount'] / 100; // Convert from kobo to naira
            $wallet = auth()->user()->wallet;
            $wallet->add($amount);
            
            return redirect()->route('wallet.show')->with('success', 'Payment successful! Your wallet has been credited.');
        }

        return redirect()->route('wallet.show')->with('error', 'Payment failed. Please try again.');
    }
} 