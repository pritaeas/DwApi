<?php
include '../DwApiToken.class.php';

use DwApi\DwApiException;
use DwApi\DwApiToken;

session_start();

$accessToken = isset($_SESSION['DwApiAccessToken']) ? $_SESSION['DwApiAccessToken'] : false;
$whoAmI = false;
if ($accessToken)
{
    $dwapi = new DwApiToken($accessToken);
    try
    {
        $whoAmI = json_decode($dwapi->WhoAmI());
    }
    catch (DwApiException $exception)
    {
        $whoAmI = false;
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>DaniWeb API Demos</title>
    <link rel="stylesheet" href="DwShared/default.css" />
</head>
<body>
    <h1>DaniWeb API Demos</h1>
<?php
if ($whoAmI)
{
    echo "<p>Hello {$whoAmI->data->username}</p>";
}
else
{
    echo '<p>You can login <a href="DwApiAuthorize.php">here</a>.</p>';
}
?>
<ul>
    <li><p><a href="DwRssDashboard/index.php">DwRssDashboard</a></li>
    <li><p><a href="DwWatchedArticles/index.php">DwWatchedArticles</a></li>
</ul>
</body>
</html>