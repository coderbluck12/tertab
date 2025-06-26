<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Services\PaystackService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\WalletFundedMail;

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
        // Only allow students to fund their wallet
        if (auth()->user()->can('provide-a-reference')) {
            return back()->with('error', 'Lecturers cannot fund their wallet. Your balance increases automatically from reference requests.');
        }

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
        // Only allow students to fund their wallet
        if (auth()->user()->can('provide-a-reference')) {
            return redirect()->route('wallet.show')->with('error', 'Lecturers cannot fund their wallet.');
        }

        $reference = $request->reference;
        $response = $this->paystackService->verifyTransaction($reference);

        if (!$response || !isset($response['status']) || !$response['status']) {
            return redirect()->route('wallet.show')->with('error', 'Payment verification failed.');
        }

        if ($response['data']['status'] === 'success') {
            $amount = $response['data']['amount'] / 100; // Convert from kobo to naira
            $wallet = auth()->user()->wallet;
            $wallet->add($amount);

            // Send success email
            try {
                Mail::to(auth()->user()->email)->send(new WalletFundedMail(
                    $amount,
                    $wallet->balance,
                    auth()->user()->name
                ));
            } catch (\Exception $e) {
                // Log the error but don't prevent the user from seeing the success message
                \Log::error('Wallet funding email failed to send: ' . $e->getMessage());
            }
            
            // Check if there's a pending reference request in session
            if (session()->has('pending_reference_request')) {
                $pendingRequest = session('pending_reference_request');
                session()->forget('pending_reference_request');
                
                return redirect()->route('student.reference')
                    ->with('success', 'Payment successful! Your wallet has been credited. Please complete your reference request.')
                    ->withInput($pendingRequest);
            }
            
            return redirect()->route('wallet.show')->with('success', 'Payment successful! Your wallet has been credited.');
        }

        return redirect()->route('wallet.show')->with('error', 'Payment failed. Please try again.');
    }
} 