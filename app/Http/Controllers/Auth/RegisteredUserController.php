<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
        return view('auth.register', compact('states'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                function ($attribute, $value, $fail) use ($request) {
                    $role = $request->input('role');
                    $isLecturer = $role === 'lecturer';
                    $educationalRegex = '/^[\w.%+-]+@([a-zA-Z0-9-]+\.)*edu(\.[a-zA-Z]{2,})?$/'; // validate for edu

                    // Only apply educational email validation for lecturers
                    if ($isLecturer && !preg_match($educationalRegex, $value)) {
                        $fail(__('Lecturers must use an educational email address ending with .edu'));
                    }
                },
                'unique:' . User::class,
            ],
            'role' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'documents' =>  'required|array',
            'documents.*' => 'file|mimes:pdf,doc,docx,jpg,png|max:2048',
        ], [
            'email.regex' => 'Lecturers must use an educational email address ending with .edu',
            'email.*' => 'The email provided does not meet the requirements for your selected role.',
            'documents.required' => 'Please upload supporting documents.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        // Handle document uploads
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $document) {
                $path = $document->store('verification_documents', 'public');
                Document::create([
                    'user_id' => $user->id,
                    'path' => $path,
                    'type' => 'verification'
                ]);
            }
        }

        // Fire Registered event
        event(new Registered($user));

        // Log in the user
        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
