<?php
session_start();

$clientId = 0;		// Your DaniWeb API client ID
$clientSecret = '';	// Your DaniWeb API secret
$currentUrl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];

if (!isset($_REQUEST['code']))
{
    header("Location: http://www.daniweb.com/api/oauth?client_id=$clientId&redirect_uri=" . urlencode($currentUrl));
    exit;
}

$ch = curl_init('http://www.daniweb.com/api/access_token');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_HEADER, true);

curl_setopt($ch, CURLOPT_POSTFIELDS, array(
    'code' => $_REQUEST['code'],
    'redirect_uri' => $currentUrl,
    'client_id' => $clientId,
    'client_secret' => $clientSecret
));

$result = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 301 or $httpCode == 302) {
    preg_match("@https?://([-\w\.]+)+(:\d+)?(/([\w/_\-\.]*(\?\S+)?)?)?@", $result, $matches);
    $targetUrl = $matches[0];
    $urlParts = parse_url($targetUrl);
    parse_str($urlParts['query'], $queryParts);
    $token = $queryParts['access_token'];
    $_SESSION['DwApiAccessToken'] =  $token;
    header('Location: index.php');
}
?>