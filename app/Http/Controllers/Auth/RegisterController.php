<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Validator for registration input.
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user with default is_approved = 0.
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'profile_picture' => null,  // Default profile picture
            'pin_code' => $data['password'],  // Use password as pin code
            'is_approved' => 0,  // User must be approved before login
        ]);

        $role = Role::where('slug', 'user')->first();
        if ($role) {
            $user->roles()->attach($role->id);

            DB::table('employees')->insert([
                'name' => $user->name,
                'email' => $user->email,
                'position' => 'Employee',
                'created_at' => now(),
                'pin_code' => Hash::make($data['password']),
                'updated_at' => now(),
            ]);
        }

        return $user;
    }

    /**
     * After successful registration, log out user and redirect to login with message.
     */
    protected function registered(\Illuminate\Http\Request $request, $user)
    {
        auth()->logout();

        return redirect('/login')->with('status', 'Registration successful! Please wait for account approval before logging in.');
    }
}
