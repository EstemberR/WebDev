<?php
namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Student\Students;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
{
    try {
        $request->authenticate();
        $request->session()->regenerate();

        // Check if student record exists for student users
        if (Auth::user()->user_type === 'student') {
            $student = Students::where('email', Auth::user()->email)->first();
            
            if (!$student) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Student record not found. Please contact the administrator.',
                ]);
            }

            if ($student->status === 'inactive') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your student account is inactive. Please contact the administrator.',
                ]);
            }

            return redirect()->intended(route('student.dashboard'));
        }

        return redirect()->intended(route('dashboard'));

    } catch (\Exception $e) {
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
}

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
