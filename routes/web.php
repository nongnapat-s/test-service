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
        'base_uri' => config('app.storage_service_uri'),
        'timeout'  => 8.0,
    ]);
    $response = $client->post('upload', [
            'headers' => [
                'Accept' => 'application/json',
                'token'  => config('app.storage_service_token'), 
                'secret' => config('app.storage_service_secret')
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
                    'contents' => 'pdf',
                ]
            ]
    ]);  
    // $data = json_decode($response->getBody(), true);
    // App\File::create($data + ['file_id' => $data['id']]);
    return json_decode($response->getBody(), true);
});

Route::post('/put-file', function() {
    $client = new GuzzleHttp\Client([
        'base_uri' => config('app.storage_service_uri'),
        'timeout'  => 8.0,
    ]);
    $response = $client->post('update', [
            'headers' => [
                'Accept' => 'application/json',
                'token'  => config('app.storage_service_token'), 
                'secret' => config('app.storage_service_secret')
            ],
            'multipart' => [
                [
                    'name'     => '_method',
                    'contents' => 'PUT'
                ],
                [
                    'name'     => 'slug',
                    'contents' => '86c5d842-d82a-11e9-b6ab-107b44f16ccf',
                ],
                [
                    'name'     => 'file',
                    'contents' => fopen(request()->file('file'),'r+'),
                ],
            ]
    ]);  
    return json_decode($response->getBody(), true);

});

Route::get('/delete-file', function() {
    $client = new GuzzleHttp\Client([
        'base_uri' => config('app.storage_service_uri'),
        'timeout'  => 8.0,
    ]);
    $response = $client->post('/delete-file', [
            'headers' => [
                'Accept' => 'application/json',
                'token'  => config('app.storage_service_token'), 
                'secret' => config('app.storage_service_secret')
            ],
            'multipart' => [
                [
                    'name' => '_method',
                    'contents' => 'delete'
                ],
                [
                    'name'     => 'slug',
                    'contents' => '6f142b6e-d9e6-11e9-a0f6-74d02b07df73',
                ],
            ]
    ]);  
    return json_decode($response->getBody(), true);
});

Route::get('/delete-folder', function() {
    $client = new GuzzleHttp\Client([
        'base_uri' => config('app.storage_service_uri'),
        'timeout'  => 8.0,
    ]);
    $response = $client->post('delete-folder', [
            'headers' => [
                'Accept' => 'application/json',
                'token'  => config('app.storage_service_token'), 
                'secret' => config('app.storage_service_secret')
            ],
            'multipart' => [
                [
                    'name' => '_method',
                    'contents' => 'delete'
                ],
                [
                    'name' => 'state',
                    'contents' => 'local'
                ],
                [
                    'name'     => 'folder',
                    'contents' => '/pdf',
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
