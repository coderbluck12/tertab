<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ReferralController;
use App\Mail\NotificationMail;
use App\Models\Document;
use App\Models\State;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $states = State::orderBy('name')->get();
        
        // Check for referral code in URL
        $referralCode = request('ref');
        if ($referralCode) {
            session(['referral_code' => $referralCode]);
        }
        
        return view('auth.register', compact('states'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Prevent double submission
        if ($request->session()->has('registration_processed')) {
            return redirect(route('dashboard', absolute: false));
        }

        // Additional check: prevent duplicate email registration attempts
        if (User::where('email', $request->email)->exists()) {
            return back()->withErrors(['email' => 'This email is already registered.'])->withInput();
        }
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:' . User::class,
            ],
            'role' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'g-recaptcha-response' => ['required', 'recaptcha'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'referral_code' => User::generateReferralCode(),
        ]);

        // Process referral if exists
        if (session('referral_code')) {
            ReferralController::processReferral($user, session('referral_code'));
            session()->forget('referral_code');
        }

         // Mark registration as processed
        $request->session()->put('registration_processed', true);

    // Fire Registered event (this automatically sends verification email)
    event(new Registered($user));

        // Log in the user
        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
