<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->options('{any:.*}', function () {
    return response('', 200)
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
});
$router->post('/register-client', 'ClientController@registerClient');
$router->post('/load-wallet', 'WalletController@loadWallet');
$router->post('/pay-with-wallet', 'WalletController@payWithWallet');
$router->post('/confirm-payment', 'WalletController@confirmPayment');
$router->post('/wallet-balance', 'WalletController@getWalletBalance');
$router->post('/get-history', 'WalletController@getTransactions');
