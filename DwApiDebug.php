<?php
include 'DwApiOAuth.class.php';

$dwApi = new DwApiOAuth('AccessToken');


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
$result = $dwApi->GetFeed(17);
$result = $dwApi->GetFeed(-1);
$result = $dwApi->GetFeed('1');

foreach ($openArticleTypes as $articleType)
{
    $result = $dwApi->GetFeed(1, $articleType);
}

$result = $dwApi->GetFeed(1, 'invalid');


// DwApiOpen

$result = $dwApi->GetArticlePosts(451816);
$result = $dwApi->GetArticles();
$result = $dwApi->GetForumArticles(17);
$result = $dwApi->GetForumPosts(17);
$result = $dwApi->GetForums();
$result = $dwApi->GetMemberActivityPoints(94719);
$result = $dwApi->GetMemberArticles(94719);
$result = $dwApi->GetMemberEndorsements(94719);
$result = $dwApi->GetMemberPosts(94719);
$result = $dwApi->GetMemberReputationComments(94719);
$result = $dwApi->GetMembers();
$result = $dwApi->GetPostReputationComments(1957463);
$result = $dwApi->GetPosts();
$result = $dwApi->SearchArticles('daniweb-api');
$result = $dwApi->SearchMembers('pritaeas');


// DwApiOAuth

$result = $dwApi->GetArticles();
$result = $dwApi->GetForumArticles(17);
$result = $dwApi->GetMemberArticles(94719);

$result = $dwApi->GetPrivateMessages(true);
$result = $dwApi->GetPrivateMessages(false);
$result = $dwApi->GetPrivateMessages('1');

$result = $dwApi->VotePost();
$result = $dwApi->WatchArticle();
$result = $dwApi->WhoAmI();
?>