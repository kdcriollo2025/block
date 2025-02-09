<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMedicoRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'cedula' => 'required|string|size:10|unique:medicos|unique:users',
            'email' => 'required|string|email|max:255|unique:medicos|unique:users',
            'specialty' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'cedula.required' => 'La cédula es obligatoria',
            'cedula.size' => 'La cédula debe tener exactamente 10 dígitos',
            'cedula.unique' => 'Esta cédula ya está registrada',
        ];
    }

    protected function validarCedula($cedula)
    {
        // Verificar longitud
        if (strlen($cedula) !== 10) {
            return false;
        }

        // Verificar que todos sean números
        if (!ctype_digit($cedula)) {
            return false;
        }

        // Algoritmo de validación
        $coeficientes = [2, 1, 2, 1, 2, 1, 2, 1, 2];
        $provincia = (int)substr($cedula, 0, 2);

        // Verificar código de provincia
        if ($provincia < 1 || $provincia > 24) {
            return false;
        }

        $suma = 0;
        for ($i = 0; $i < 9; $i++) {
            $resultado = (int)$cedula[$i] * $coeficientes[$i];
            if ($resultado > 9) {
                $resultado -= 9;
            }
            $suma += $resultado;
        }

        $decenaSuperior = ceil($suma / 10) * 10;
        $digitoVerificador = $decenaSuperior - $suma;

        if ($digitoVerificador === 10) {
            return (int)$cedula[9] === 0;
        }

        return (int)$cedula[9] === $digitoVerificador;
    }
} 