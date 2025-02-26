<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    protected function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'cedula' => 'required|string|unique:users',
            'especialidad' => 'required|string',
            'telefono' => 'required|string',
        ]);

        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'cedula' => $request->cedula,
            'type' => 'doctor',
            'first_login' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
} 