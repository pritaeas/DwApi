<?php
namespace DwRssDashboard;

use DwApi\DwApiException;
use DwApi\DwApiToken;
use DwShared\Parser;

session_start();

include '../../DwApiToken.class.php';
include '../DwShared/Decorator.class.php';
include '../DwShared/Parser.class.php';

$dwapi = new DwApiToken(null);
$parser = new Parser();

$divs = array ();
$forums = $dwapi->GetForums();
if ($forums)
{
    $forums = json_decode($forums);
    foreach ($forums->data as $mainForum)
    {
        $divs = array_merge($divs, $parser->ParseForumsResult($mainForum, array ('DwShared\Decorator' , 'RssDivWithTitle')));
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>DaniWeb API Demo - RSS Dashboard</title>
    <link rel="stylesheet" href="../DwShared/default.css" />
</head>
<body>
    <h1>DaniWeb API Demo - RSS Dashboard</h1>
    <?php
    foreach ($divs as $div)
    {
        echo $div;
    }
    ?>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>
        $(function() {
            $('.rss').each(function () {
                var url = 'getRss.php?forum=' + this.id;
                $.ajax({
                    url: url,
                    dataType: 'json'
                }).done(function (data) {
                    $('#' + data.forum + ' > p').html(data.links);
                });
            });
        });
    </script>
</body>
</html>