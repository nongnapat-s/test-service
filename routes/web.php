<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('test');
});


Route::post('/upload', function() {
    $client = new GuzzleHttp\Client([
        'base_uri' => env('STORATE_SERVICE_URL'),
        'timeout'  => 8.0,
    ]);
    $response = $client->post('upload', [
            'headers' => [
                'Accept' => 'application/json',
                'token'  => env('STORAGE_SERVICE_TOKEN'), 
                'secret' => env('STORAGE_SERVICE_SECRET')
            ],
            'multipart' => [
                [
                    'name'     => 'state',
                    'contents' => 'public'
                ],
                [
                    'name'     => 'file',
                    'contents' => fopen(request()->file('file'),'r+'),
                    'filename' => request()->file('file')->getClientOriginalName() //$filename
                ]
            ]
    ]);  
    return json_decode($response->getBody(), true);
});