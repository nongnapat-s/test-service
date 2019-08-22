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
                ],
                [
                    'name'     => 'sub_path',
                    'contents' => 'images',
                ]
            ]
    ]);  
    return json_decode($response->getBody(), true);
    $data = json_decode($response->getBody(), true);
    $file = new App\File;
    $file->file_id = $data['file']['id'];
    $file->url = $data['url'];
    $file->save();
    return json_decode($response->getBody(), true);
});

Route::get('/download', function() {
    $client = new GuzzleHttp\Client([
        'base_uri' => env('STORATE_SERVICE_URL'),
        'timeout'  => 8.0,
    ]);
    $response = $client->post('download', [
            'headers' => [
                'Accept' => 'application/json',
                'token'  => env('STORAGE_SERVICE_TOKEN'), 
                'secret' => env('STORAGE_SERVICE_SECRET')
            ],
            'multipart' => [
                [
                    'name'     => 'id',
                    'contents' => 4,
                ],
            ]
    ]);  
    return json_decode($response->getBody(), true);

});

Route::get('/delete', function() {
    $client = new GuzzleHttp\Client([
        'base_uri' => env('STORATE_SERVICE_URL'),
        'timeout'  => 8.0,
    ]);
    $response = $client->post('delete', [
            'headers' => [
                'Accept' => 'application/json',
                'token'  => env('STORAGE_SERVICE_TOKEN'), 
                'secret' => env('STORAGE_SERVICE_SECRET')
            ],
            'multipart' => [
                [
                    'name'     => 'id',
                    'contents' => 8,
                ],
            ]
    ]);  
    return json_decode($response->getBody(), true);

});