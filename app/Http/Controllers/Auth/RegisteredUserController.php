<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class RegisteredUserController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register'); // kita akan buat ini
    }
    public function create(): View
{
    return view('auth.register');
}

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

   Auth::login($user);

   return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
}
}


