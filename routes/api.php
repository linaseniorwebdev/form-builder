<?php

use Illuminate\Http\Request;
Use App\Form;
Use App\School;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('forms', 'FormController@index');
Route::get('form/{form}', 'FormController@show');
Route::get('forms/{school}', 'FormController@school');
Route::post('forms', 'FormController@store');
Route::put('forms/{form}', 'FormController@update');
Route::get('form/testform', 'FormController@test');
Route::get('defaultform', 'FormController@default');

Route::get('fields', 'FormController@fields');

Route::get('schools', 'SchoolController@index');
Route::post('schools', 'SchoolController@store');
// TODO - Clean up, move out of routes file
Route::get('variants', function () {

	$client = new GuzzleHttp\Client;

    $response = $client->post('http://staging.polsone.cds-store.com/oauth/token', [
        'form_params' => [
            'client_id' => 3,
            'client_secret' => 'F2FDNu1ZVmRdc3pMOVRbMIc6EmuQapUwlnrQdfNz',
            'grant_type' => 'client_credentials',
            'scope' => '*',
        ]
    ]);

    $auth = json_decode( (string) $response->getBody() );

    $response = $client->get('http://staging.polsone.cds-store.com/api/v1/variants', [
        'headers' => [
            'Authorization' => 'Bearer ' . $auth->access_token,
        ]
    ]);

    $variants = $response->getBody();

    header('Content-type: application/json');
    //echo $variants;

	try {

	} catch (GuzzleHttp\Exception\BadResponseException $e) {

	    echo "Unable to retrieve access token.";

	}

    return $variants;

});
// TODO - Clean up, move out of routes file
Route::get('variant/{id}', function ($id) {

    //return $id;

    $client = new GuzzleHttp\Client;

    $response = $client->post('http://staging.polsone.cds-store.com/oauth/token', [
        'form_params' => [
            'client_id' => 3,
            'client_secret' => 'F2FDNu1ZVmRdc3pMOVRbMIc6EmuQapUwlnrQdfNz',
            'grant_type' => 'client_credentials',
            'scope' => '*',
        ]
    ]);

    $auth = json_decode( (string) $response->getBody() );

    $response = $client->get('http://staging.polsone.cds-store.com/api/v1/variants/' . $id, [
        'headers' => [
            'Authorization' => 'Bearer ' . $auth->access_token,
        ]
    ]);

    $variants = $response->getBody();

    header('Content-type: application/json');
    //echo $variants;

    try {

    } catch (GuzzleHttp\Exception\BadResponseException $e) {

        echo "Unable to retrieve access token.";

    }

    return $variants;

});
// TODO - Clean up, move out of routes file
Route::post('variant', function (Request $request) {

    $client = new GuzzleHttp\Client;

    $response = $client->post('http://staging.polsone.cds-store.com/oauth/token', [
        'form_params' => [
            'client_id' => 3,
            'client_secret' => 'F2FDNu1ZVmRdc3pMOVRbMIc6EmuQapUwlnrQdfNz',
            'grant_type' => 'client_credentials',
            'scope' => '*',
        ]
    ]);

    $auth = json_decode( (string) $response->getBody() );

    $response = $client->request('POST', 'http://staging.polsone.cds-store.com/api/v1/variants', [
        'form_params' => [
            'form_type' => 'step-form',
            'slug' => $request->slug,
            'form_id' => $request->id,
            'is_draft' => true,
            'form_json' => json_encode($request->steps),
            'description' => $request->description
        ],
        'headers' => [
            'Authorization' => 'Bearer ' . $auth->access_token,
            'Accept'        => 'application/json',
        ],
    ]);

    $variants = $response->getBody();

    header('Content-type: application/json');
    //echo $variants;
    try {
    } catch (GuzzleHttp\Exception\BadResponseException $e) {
        echo "Unable to retrieve access token.";
    }

    //Log::info($request->slug);
    //Log::info($request->id);
    //Log::info($request->description);
    //Log::info(json_encode($request->steps));
    
    return $request;

});
// TODO - Clean up, move out of routes file
Route::post('variantedit', function (Request $request) {

    $client = new GuzzleHttp\Client;

    //Log::info($request->slug);
    //Log::info($request->id);
    //Log::info($request->description);
    //Log::info(json_encode($request->steps));

    $response = $client->post('http://staging.polsone.cds-store.com/oauth/token', [
        'form_params' => [
            'client_id' => 3,
            'client_secret' => 'F2FDNu1ZVmRdc3pMOVRbMIc6EmuQapUwlnrQdfNz',
            'grant_type' => 'client_credentials',
            'scope' => '*',
        ]
    ]);

    $auth = json_decode( (string) $response->getBody() );

    $response = $client->request('PATCH', 'http://staging.polsone.cds-store.com/api/v1/variants/' . $request->id, [
        'form_params' => [
            'form_type' => 'step-form',
            'slug' => $request->slug,
            'form_id' => $request->form_id,
            'is_draft' => true,
            'form_json' => json_encode($request->steps),
            'description' => $request->description
        ],
        'headers' => [
            'Authorization' => 'Bearer ' . $auth->access_token,
            'Accept'        => 'application/json',
        ],
    ]);

    $variants = $response->getBody();

    header('Content-type: application/json');
    //echo $variants;
    try {
    } catch (GuzzleHttp\Exception\BadResponseException $e) {
        echo "Unable to retrieve access token.";
    }

    
    
    return $request;

});