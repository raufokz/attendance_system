<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Handle redirection after login based on user role.
     *
     * @return string
     */
    protected function redirectTo()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user || !$user->roles()->exists()) {
            Log::warning("Login redirection failed: User or role not found.");
            return RouteServiceProvider::HOME;
        }

        $roleName = $user->roles()->first()->name ?? 'unknown';

        Log::info("Login redirection: User ID {$user->id}, Role: {$roleName}");

        switch ($roleName) {
            case 'Admin':
                return '/admin';
            case 'User':
                return '/user';
            default:
                return RouteServiceProvider::HOME;
        }
    }

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Override login method to check approval before authentication.
     */
    public function login(\Illuminate\Http\Request $request)
    {
        $this->validateLogin($request);

        // Check if the user exists and is approved
        $user = \App\Models\User::where('email', $request->email)->first();

        if ($user && $user->is_approved == 0) {
            return redirect()->route('not-approved')
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Your account is not approved yet. Please wait for approval.']);
        }

        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * After successful authentication, double-check approval status.
     */
protected function authenticated(\Illuminate\Http\Request $request, $user)
{
    if (! $user->is_approved) {
        auth()->logout();

        return redirect()->route('not-approved');
    }
}

}
