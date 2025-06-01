<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;


class ClientController extends Controller
{


    private string $soapServerUrl = 'http://127.0.0.1:8000/soap/server';

    /**
     * Funcion para llamar al soap.
     *
     * @var string
     */
    private function callSoap(string $method, array $params)
    {
        $client = new \SoapClient(null, [
            'location' => $this->soapServerUrl,
            'uri' => $this->soapServerUrl,
            'trace' => 1,
            'exceptions' => true
        ]);

        return $client->__soapCall($method, $params);
    }

    public function registerClient(Request $request)
    {
        try {
            $this->validate($request, [
                'document' => 'required|string|max:255',
                'firstName' => 'required|string|max:255',
                'lastName' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'celPhone' => 'required|string|max:20',
            ], [
                'document.required' => 'El documento es obligatorio.',
                'firstName.required' => 'El nombre es obligatorio.',
                'lastName.required' => 'El apellido es obligatorio.',
                'email.required' => 'El correo electrónico es obligatorio.',
                'email.email' => 'El correo electrónico debe ser válido.',
                'celPhone.required' => 'El teléfono celular es obligatorio.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        }

        $data = $request->all();

        try {
            $response = $this->callSoap('registerClient', [
                $data['document'],
                $data['firstName'],
                $data['lastName'],
                $data['email'],
                $data['celPhone']
            ]);

            if ($response['success'] === false) {

                return response()->json([
                    'message' => $response['message'] ?? 'Error al registrar el cliente.',
                    'data' => $response['data'] ?? null
                ], 500);
            }

            return response()->json([
                'message' => $response['message'] ?? 'Cliente ingresado exitosamente.',
                'data' => $response['data'] ?? null
            ], 201);
        } catch (\SoapFault $e) {
            return response()->json(['error' => 'Error al llamar el servicio SOAP: ' . $e->getMessage()], 500);
        }
    }
}
