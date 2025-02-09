<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMedicoRequest extends FormRequest
{
    public function rules()
    {
        return [
            'cedula' => [
                'required',
                'string',
                'size:10',
                'unique:medicos',
                'unique:users',
                function ($attribute, $value, $fail) {
                    if (!$this->validarCedula($value)) {
                        $fail('La cédula ingresada no es válida.');
                    }
                },
            ],
            // ... otras reglas
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