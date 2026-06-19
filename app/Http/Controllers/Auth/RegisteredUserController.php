<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'string', 'size:16', 'unique:' . User::class],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:relawan,desa,kabid']
        ]);

        $user = User::create([
            'nama' => $request->nama,
            'nik' => $request->nik,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 'nonaktif',
        ]);

        $user->assignRole($request->role);

        return redirect()->route('login')->with('success', 'Akun berhasil didaftarkan. Silakan tunggu persetujuan admin untuk mengaktifkan akun Anda.');
    }
}
