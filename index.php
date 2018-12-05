
<?php

require_once 'System.php';
var_dump(class_exists('System', false));

require_once 'HTTP/Request2.php';

//Obtain Authorization Code through oAuth2 credential flow
$request = new HTTP_Request2('{oAuth2Endpoint}', HTTP_Request2::METHOD_POST);
$request->addPostParameter(array('resource' => '{resourceId}', 'client_id' => '{clientId}', 'client_secret' => '{clientSecret}', 'grant_type' => 'client_credentials'));

try {
    $response = $request->send();
    if (200 == $response->getStatus()) {        
        $header = $response->getBody();

        $arr = json_decode($header);
        $token = $arr->access_token;

    } else {
        echo 'Unexpected HTTP status: ' . $response->getStatus() . ' ' .
             $response->getReasonPhrase();
    }
} catch (HTTP_Request2_Exception $e) {
    echo 'Error: ' . $e->getMessage();
}

//Example Neobi API Call for Products (Beta)
$request = new Http_Request2('https://neobi.azure-api.net/api/products');
$url = $request->getUrl();

$headers = array(
    // Request headers
    'Ocp-Apim-Subscription-Key' => '{subscriptionKey}',
    'Authorization' => $token,
);

$request->setHeader($headers);

$parameters = array(
    // Request parameters
    'page' => '1',
    'count' => '5',
    'sort' => 'updated',
    'rev' => 'v2'
);

$url->setQueryVariables($parameters);

$request->setMethod(HTTP_Request2::METHOD_GET);

try
{
    $response = $request->send();
    echo $response->getBody();
}
catch (HttpException $ex)
{
    echo $ex;
}

//phpinfo(); 
?>
