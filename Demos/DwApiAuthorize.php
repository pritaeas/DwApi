<?php
session_start();

$client_id = 0;		// Your DaniWeb API client ID
$client_secret = '';	// Your DaniWeb API secret
$current_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

if (!isset($_REQUEST['code'])) 
{ 
	header("Location: https://www.daniweb.com/api/oauth?response_type=code&client_id=$client_id&redirect_uri=".urlencode($current_url)); 
    exit();
}

$ch = curl_init('https://www.daniweb.com/api/access_token'); 

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
curl_setopt($ch, CURLOPT_POST, true); 
curl_setopt($ch, CURLOPT_POSTFIELDS, array( 
	'code' => $_REQUEST['code'], 
	'redirect_uri' => $current_url, 
	'client_id' => $client_id, 
	'client_secret' => $client_secret,
	'grant_type' => 'authorization_code'
)); 

$response = json_decode(curl_exec($ch)); 

curl_close($ch);

$_SESSION['DwApiAccessToken'] = $response->access_token;
header('Location: index.php');	
?>
