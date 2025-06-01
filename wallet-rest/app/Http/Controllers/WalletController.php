<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class WalletController extends Controller
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


    public function loadWallet(Request $request)
    {
        try {
            $this->validate($request, [
                'document' => 'required|string|max:255',
                'celPhone' => 'required|string|max:20',
                'amount' => 'required|numeric|min:0'
            ], [
                'document.required' => 'El documento es obligatorio.',
                'celPhone.required' => 'El teléfono celular es obligatorio.',
                'amount.required' => 'El monto es obligatorio.',
                'amount.numeric' => 'El monto debe ser un número válido.',
                'amount.min' => 'El monto debe ser mayor o igual a 0.'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        }
        $data = $request->all();
        try {
            $response = $this->callSoap('loadWallet', [
                $data['document'],
                $data['celPhone'],
                $data['amount']
            ]);

            if ($response['success'] === false) {

                return response()->json([
                    'message' => $response['message'] ?? 'Error al cargar.',
                    'data' => $response['data'] ?? null
                ], 404);
            }
            return response()->json([
                'message' => $response['message'] ?? 'Monto ingresado exitosamente.',
                'data' => $response['data'] ?? null
            ],);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    public function payWithWallet(Request $request)
    {
        try {
            $this->validate($request, [
                'document' => 'required|string|max:255',
                'celPhone' => 'required|string|max:20',
                'amount' => 'required|numeric|min:0',
                'description' => 'required|string|max:255'
            ], [
                'document.required' => 'El documento es obligatorio.',
                'celPhone.required' => 'El teléfono celular es obligatorio.',
                'amount.required' => 'El monto es obligatorio.',
                'amount.numeric' => 'El monto debe ser un número válido.',
                'amount.min' => 'El monto debe ser mayor o igual a 0.',
                'description.required' => 'La descripción es obligatoria.'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        }
        $this->validate($request, [
            'document' => 'required|string|max:255',
            'celPhone' => 'required|string|max:20',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:255'
        ], [
            'document.required' => 'El documento es obligatorio.',
            'celPhone.required' => 'El teléfono celular es obligatorio.',
            'amount.required' => 'El monto es obligatorio.',
            'amount.numeric' => 'El monto debe ser un número válido.',
            'amount.min' => 'El monto debe ser mayor o igual a 0.',
            'description.required' => 'La descripción es obligatoria.'
        ]);

        $data = $request->all();

        try {
            $response = $this->callSoap('payWithWallet', [
                $data['document'],
                $data['celPhone'],
                $data['amount'],
                $data['description']
            ]);

            if ($response['success'] === false) {

                return response()->json([
                    'message' => $response['message'] ?? 'Error al iniciar compra.',
                    'data' => $response['data'] ?? null
                ], 500);
            }

            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function confirmPayment(Request $request)
    {
        try {
            $this->validate($request, [
                'token' => 'required|string|max:255',
                'sessionId' => 'required|string|max:255'
            ], [
                'token.required' => 'El token es obligatorio.',
                'sessionId.required' => 'El ID de sesión es obligatorio.'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        }

        $data = $request->all();

        try {
            $response = $this->callSoap('confirmPayment', [
                $data['token'],
                $data['sessionId']
            ]);

            if ($response['success'] === false) {

                return response()->json([
                    'message' => $response['message'] ?? 'Error al cargar.',
                    'data' => $response['data'] ?? null
                ], 500);
            }
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    public function getWalletBalance(Request $request)
    {
        try {
            $this->validate($request, [
                'document' => 'required|string|max:255',
                'celPhone' => 'required|string|max:20'
            ], [
                'document.required' => 'El documento es obligatorio.',
                'celPhone.required' => 'El teléfono celular es obligatorio.'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        }


        $data = $request->all();

        try {
            $response = $this->callSoap('getWalletBalance', [
                $data['document'],
                $data['celPhone']
            ]);

            if ($response['success'] === false) {

                return response()->json([
                    'message' => $response['message'] ?? 'Error al cargar.',
                    'data' => $response['data'] ?? null
                ], 500);
            }
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getTransactions(Request $request)
    {
        $this->validate($request, [
            'document' => 'required|string|max:255',
            'celPhone' => 'required|string|max:20'
        ], [
            'document.required' => 'El documento es obligatorio.',
            'celPhone.required' => 'El teléfono celular es obligatorio.'
        ]);

        $data = $request->all();

        try {
            $response = $this->callSoap('getWalletTransactions', [
                $data['document'],
                $data['celPhone']
            ]);

            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
