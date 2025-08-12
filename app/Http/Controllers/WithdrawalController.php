<?php

namespace App\Http\Controllers;

use App\Models\Withdrawal;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WithdrawalController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display the withdrawal form for lecturers.
     */
    public function create()
    {
        // Temporary: Allow any authenticated user for testing
        // TODO: Re-enable role check after testing
        // if (Auth::user()->role !== 'lecturer') {
        //     abort(403, 'Unauthorized access.');
        // }

        return view('lecturer.withdrawal');
    }

    /**
     * Store a withdrawal request.
     */
    public function store(Request $request)
    {
        // Only allow lecturers to submit withdrawal requests
        if (Auth::user()->role !== 'lecturer') {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:20',
            'account_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1000', // Minimum withdrawal amount
            'withdrawal_reason' => 'nullable|string|max:500'
        ]);

        // Check if user has sufficient balance (you'll need to implement wallet balance check)
        $userBalance = Auth::user()->wallet_balance ?? 0;
        if ($request->amount > $userBalance) {
            return redirect()->back()
                ->withErrors(['amount' => 'Insufficient balance. Your current balance is ₦' . number_format($userBalance, 2)])
                ->withInput();
        }

        // Create withdrawal request
        $withdrawal = Withdrawal::create([
            'user_id' => Auth::id(),
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'account_name' => $request->account_name,
            'amount' => $request->amount,
            'withdrawal_reason' => $request->withdrawal_reason,
            'status' => 'pending',
            'request_date' => now()
        ]);

        // Create notification for user
        $this->notificationService->createNotification(
            Auth::id(),
            'Withdrawal Request Submitted',
            'Your withdrawal request for ₦' . number_format($request->amount, 2) . ' has been submitted and is being processed.',
            'info'
        );

        return redirect()->route('lecturer.withdrawal.create')
            ->with('success', 'Withdrawal request submitted successfully. It will be processed within 1-3 business days.');
    }

    /**
     * Display withdrawal history for the authenticated lecturer.
     */
    public function index()
    {
        // Temporary: Allow any authenticated user for testing
        // TODO: Re-enable role check after testing
        // if (Auth::user()->role !== 'lecturer') {
        //     abort(403, 'Unauthorized access.');
        // }

        $withdrawals = Withdrawal::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('lecturer.withdrawal-history', compact('withdrawals'));
    }
}
