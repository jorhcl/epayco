<?php

namespace App\Http\Controllers\Soap;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SoapServer;

class SoapController extends Controller
{
    /**
     * Manejador de solicitudes SOAP.
     *
     * @param Request $request
     * @return void
     */

    public function server(Request $request): void
    {


        $clientSoapController = app(\Src\Interface\SOAP\ClientSoapController::class);
        $walletSoapController = app(\Src\Interface\SOAP\WalletSoapController::class);
        $soapFacadeController = app(\Src\Interface\SOAP\SoapFacadeController::class);

        $options = [
            'uri' => route('soap.server'),
            'location' => route('soap.server'),
            'trace' => true,
            'exceptions' => true,
        ];

        $server = new SoapServer(null, $options);
        $server->setClass(\Src\Interface\SOAP\SoapFacadeController::class, $walletSoapController, $clientSoapController);
        $server->handle($request->getContent());
    }
}
