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
                    'name'     => 'file_name',
                    'contents' => request()->file('file')->getClientOriginalName(),
                ],
                [
                    'name'     => 'sub_path',
                    'contents' => 'images',
                ]
            ]
    ]);  
    $data = json_decode($response->getBody(), true);
    App\File::create($data + ['file_id' => $data['id']]);
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
                    'name'     => 'slug',
                    'contents' => 'bf23d3c8-d2bd-11e9-bb84-107b44f16ccf',
                ],
            ]
    ]);  
    return json_decode($response->getBody(), true);

});

Route::get('/store', function() {
    $file = \App\File::find(2);
    Illuminate\Support\Facades\Storage::put($file->name, file_get_contents('http://localhost:9000/download/'.$file->slug, 'r'));
    return 'OK !';
});