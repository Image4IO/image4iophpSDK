<?php
require_once(__DIR__."\\..\\vendor\\autoload.php");

use Image4IO\Image4IOApi;

$api_key="ENTER_API_KEY";
$api_secret="ENTER_API_SECRET=";
try {
    $client = new Image4IOApi($api_key, $api_secret);
    $result = $client->createFolder('/test1234');
    var_dump( $result);
} catch (\Exception $e) {
    //echo $e->getMessage();
    $respCode = http_response_code();
    var_dump( $e);
    print_r($e->getMessage());
}