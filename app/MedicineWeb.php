<?php
namespace App;

use \GuzzleHttp\Client;

class MedicineWeb {

    public function callService ($maxNumber = 100) {
        for ($i = 1; $i <= $maxNumber; $i++)
        {
            $client = new Client([
                'base_uri' => 'https://www.si.mahidol.ac.th/department/Medicine/home/',
                'timeout'  => 8.0,
                'verify'  => false,
            ]);
            $response = $client->post('api/portal-loadtest.asp', [
                    'headers' => [
                        // 'Accept' => 'application/json',
                        'token'  => 'dR66z1Ma3NxLL33FDjpNDFX9DEdXbCls', 
                    ],
            ]);  ;

            \Log::info($response->getBody());
        }

        echo 'Success';

        return true;
    }
}