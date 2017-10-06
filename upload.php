<?php
// theis sample uses the Apache HTTP client from HTTP Components (http://hc.apache.org/httpcomponents-client-ga/)
require_once 'HTTP/Request2.php';
function analyze_image($url){
    $request = new Http_Request2('https://westcentralus.api.cognitive.microsoft.com/vision/v1.0/analyze');

    $headers = array(
        // Request headers
        'Content-Type' => 'application/json',
        'Ocp-Apim-Subscription-Key' => 'a8d721a6ec1749e6a6efe7272c1e9f40',
    );

    $request->setHeader($headers);

    $parameters = array(
        // Request parameters
        'visualFeatures' => 'tags',
        'details' => 'Landmarks',
        'language' => 'en',
    );

    $url->setQueryVariables($parameters);

    $request->setMethod(HTTP_Request2::METHOD_POST);

    // Request body
    $request->setBody("{'url': '$url'}");

    try
    {
        $response = $request->send();
        return $response->getBody();
    }
    catch (HttpException $ex)
    {
        return $ex;
    }
}
?>

