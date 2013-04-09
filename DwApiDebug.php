<?php
include 'DwApiOAuth.class.php';

$dwApi = new DwApiOAuth('8e234f2b19801f35243fabe7bd73d822');

$testBase = false;
$testRss = false;
$testOpen = true;
$testOAuth = false;

if ($testBase)                                                  // DwApiBase
{
    $result = $dwApi->GetArticleTypes();                        // open only, sorted
    $result = $dwApi->GetArticleTypes(true);                    // open only, sorted

    $result = $dwApi->GetArticleTypes(true, true);              // open only, sorted
    $result = $dwApi->GetArticleTypes(false, true);             // oauth, sorted

    $result = $dwApi->GetArticleTypes(true, false);             // open only, unsorted
    $result = $dwApi->GetArticleTypes(false, false);            // oauth, unsorted

    $result = $dwApi->GetArticleTypes('1');                     // false
    $result = $dwApi->GetArticleTypes('1', 2);                  // false

    $result = $dwApi->GetPostTypes();                           // sorted
    $result = $dwApi->GetPostTypes(true);                       // sorted
    $result = $dwApi->GetPostTypes(false);                      // unsorted

    $result = $dwApi->GetPostTypes('1');                        // false

    $result = $dwApi->GetRelationTypes();                       // sorted
    $result = $dwApi->GetRelationTypes(true);                   // sorted
    $result = $dwApi->GetRelationTypes(false);                  // unsorted

    $result = $dwApi->GetRelationTypes(2);                      // false
}

if ($testRss)                                                   // DwApiRss
{
    $result = $dwApi->GetFeed();                                // site feed
    $result = $dwApi->GetFeed(17);                              // php feed

    $result = $dwApi->GetFeed(-1);                              // site feed
    $result = $dwApi->GetFeed(17, 'invalid');                   // php feed
    $result = $dwApi->GetFeed('1', 'invalid');                  // site feed

    $openArticleTypes = $dwApi->GetArticleTypes();
    foreach ($openArticleTypes as $articleType)
    {
        $result = $dwApi->GetFeed(17, $articleType);            // php feed filtered
    }
}

if ($testOpen)                                                  // DwApiOpen
{
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
}

if ($testOAuth)                                                 // DwApiOAuth
{
    $result = $dwApi->GetArticles();

    $result = $dwApi->GetForumArticles(17);

    $result = $dwApi->GetMemberArticles(94719);

    $result = $dwApi->GetPrivateMessages(true);
    $result = $dwApi->GetPrivateMessages(false);
    $result = $dwApi->GetPrivateMessages('1');

    $result = $dwApi->VotePost();

    $result = $dwApi->WatchArticle();

    $result = $dwApi->WhoAmI();
}
?>