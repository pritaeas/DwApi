<?php
include '../Classes/DwApi/DwApiOAuth.class.php';

$dwApi = new DwApiOAuth('');

// DwApiBase
$openArticleTypes = $dwApi->GetArticleTypes(true, true);
$oauthArticleTypes = $dwApi->GetArticleTypes(false, true);

$result = $dwApi->GetArticleTypes(true, false);
$result = $dwApi->GetArticleTypes(false, false);
$result = $dwApi->GetArticleTypes('1', 2);

$result = $dwApi->GetPostTypes(true);
$result = $dwApi->GetPostTypes(false);
$result = $dwApi->GetPostTypes('1');

$result = $dwApi->GetRelationTypes(true);
$result = $dwApi->GetRelationTypes(false);
$result = $dwApi->GetRelationTypes(2);

// DwApiRss
$result = $dwApi->GetFeed();
$result = $dwApi->GetFeed(1);
$result = $dwApi->GetFeed(-1);
$result = $dwApi->GetFeed('1');

foreach ($openArticleTypes as $articleType)
{
    $result = $dwApi->GetFeed(1, $articleType);
}

$result = $dwApi->GetFeed(1, 'invalid');

// DwApiOpen

// DwApiOAuth
$result = $dwApi->GetPrivateMessages(true);
$result = $dwApi->GetPrivateMessages(false);
$result = $dwApi->GetPrivateMessages('1');

$result = $dwApi->VotePost();
$result = $dwApi->WatchArticle();
$result = $dwApi->WhoAmI();
?>