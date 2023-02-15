<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDenunciaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'tipo_documento' => 'required',
            'nro_documento' => 'required',
            'nombres' => 'required',
            'apellidos' => 'required',
            'telefono' => 'required',
            'email_personal' => 'required',
            'domicilio' => 'required',
            'descripcion' => 'required',
            'url_archivo' => 'max:2048',
        ];
    }

    public function messages()
    {
        return [
            'tipo_documento.required' => 'El tipo de documento es obligatorio.',
            'nro_documento.required' => 'El número de documento es obligatorio.',
            'nombres.required' => 'Los nombres son obligatorios.',
            'apellidos.required' => 'Los apellidos son obligatorios.',
            'telefono.required' => 'El telefono es obligatorio.',
            'email_personal.required' => 'El email es obligatorio.',
            'domicilio.required' => 'El domicilio es obligatorio.',
            'descripcion.required' => 'La descripcion es obligatorio.',
            'url_archivo.max' => 'El archivo no debe pesar mas de 2MB'
        ];
    }
}
