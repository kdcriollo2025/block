<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MedicoController extends Controller
{
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:medicos'],
            'cedula' => ['required', 'string', 'size:10', 'unique:medicos'],
            'specialty' => ['required', 'string', 'max:100'],
            'phone_number' => ['required', 'string', 'max:15'],
        ]);
    }

    public function index()
    {
        // Implement the logic to retrieve and display a list of medicos
    }

    public function create()
    {
        // Implement the logic to display the form for creating a new medico
    }

    public function store(Request $request)
    {
        // Implement the logic to handle the form submission and store a new medico
    }

    public function show(Medico $medico)
    {
        // Implement the logic to retrieve and display a specific medico
    }

    public function edit(Medico $medico)
    {
        // Implement the logic to retrieve and display the form for editing a medico
    }

    public function update(Request $request, Medico $medico)
    {
        // Implement the logic to handle the form submission and update an existing medico
    }

    public function destroy(Medico $medico)
    {
        // Implement the logic to delete a medico
    }
} 