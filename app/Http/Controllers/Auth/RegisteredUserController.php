<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Providers\RouteServiceProvider;
use App\Models\Student\Students;
use Illuminate\Support\Facades\DB;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $userType = null;
        
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    'unique:users',
                    function ($attribute, $value, $fail) use (&$userType) {
                        $domain = substr(strrchr($value, "@"), 1);
                        if (!in_array($domain, ['student.buksu.edu.ph', 'buksu.edu.ph'])) {
                            $fail('The email must be a valid BukSU email address.');
                        }
                        
                        $userType = $domain === 'student.buksu.edu.ph' ? 'student' : 'instructor';
                    },
                ],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            return DB::transaction(function () use ($request, $userType) {
                try {
                    // First check if student record exists for student email
                    if ($userType === 'student') {
                        $existingStudent = Students::where('email', $request->email)->first();
                        if ($existingStudent) {
                            throw new \Exception('Student record already exists with this email.');
                        }
                    }

                    // Create user
                    $user = User::create([
                        'name' => $request->name,
                        'email' => $request->email,
                        'password' => Hash::make($request->password),
                        'user_type' => $userType,
                    ]);

                    // Create student record if user type is student
                    if ($userType === 'student') {
                        $student = Students::create([
                            'student_id' => 'STD' . time(),
                            'name' => $request->name,
                            'email' => $request->email,
                            'status' => 'active'
                        ]);

                        if (!$student) {
                            throw new \Exception('Failed to create student record');
                        }
                    }

                    event(new Registered($user));
                    Auth::login($user);

                    return $user->user_type === 'student'
                        ? redirect()->intended(route('student.dashboard'))
                        : redirect()->intended(route('dashboard'));

                } catch (\Exception $e) {
                    throw $e;
                }
            });

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['email' => 'Registration failed: ' . $e->getMessage()]);
        }
    }
}
