<?php
set_time_limit(0);

include 'DwApiOAuth.class.php';

$dwApi = new DwApiOAuth('ACCESS_TOKEN');

$testBase = false;
$testRss = false;
$testOpen = false;
$testOAuth = true;

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

    $result = $dwApi->GetFeed(-1);                              // site feed, -1 ignored
    $result = $dwApi->GetFeed('1', 'invalid');                  // site feed, '1' and 'invalid' ignored

    $openArticleTypes = $dwApi->GetArticleTypes();
    $openArticleTypes[] = null;
    $openArticleTypes[] = 'invalid';
    foreach ($openArticleTypes as $articleType)
    {
        $result = $dwApi->GetFeed(17, $articleType);            // php feed filtered
    }
}

if ($testOpen)                                                  // DwApiOpen
{
    $result = $dwApi->GetArticlePosts(451735);                  // posts for article 451735, page 1, 20 per page
    $result = $dwApi->GetArticlePosts(451735, 2);               // posts for article 451735, page 2
    $result = $dwApi->GetArticlePosts(451735, 'a');             // posts for article 451735, page 1, 'a' ignored
    $result = $dwApi->GetArticlePosts('a');                     // false

    $result = $dwApi->GetArticles();                            // list articles, page 1, 30 per page
    $result = $dwApi->GetArticles(null, 2);                     // list articles, page 2
    $result = $dwApi->GetArticles(451735);                      // specific article 451735
    $result = $dwApi->GetArticles(451735, 2);                   // false, no page 2
    $result = $dwApi->GetArticles(array (451735, 443382));      // specific articles 451735 and 443382
    $result = $dwApi->GetArticles(array (451735, 443382), 2);   // false
    $result = $dwApi->GetArticles(array ('a', 'b'), 2);         // list articles, page 2, invalid values ignored

    $result = $dwApi->GetForumArticles(17);                     // articles for forum 17 (php), page 1, 30 per page
    $result = $dwApi->GetForumArticles('a');                    // false
    $result = $dwApi->GetForumArticles(17, 2);                  // articles for forum 17 (php), page 2
    $result = $dwApi->GetForumArticles(17, 'a');                // articles for forum 17 (php), page 1, 'a' ignored
    $result = $dwApi->GetForumArticles('a', 'b');               // false

    $result = $dwApi->GetForumPosts(17);                        // posts for forum 17, page 1, 20 per page
    $result = $dwApi->GetForumPosts('a');                       // false
    $result = $dwApi->GetForumPosts(17, 2);                     // posts for forum 17, page 2
    $result = $dwApi->GetForumPosts(17, 'a');                   // posts for forum 17, page 1, 'a' ignored
    $result = $dwApi->GetForumPosts('a', 'b');                  // false

    $result = $dwApi->GetForums();                              // list forums
    $result = $dwApi->GetForums(31);                            // list web dev forums
    $result = $dwApi->GetForums(array (31, 3));                 // list web dev and community forums

    $relationTypes = $dwApi->GetRelationTypes();
    $relationTypes[] = null;
    $relationTypes[] = 'invalid';
    foreach ($relationTypes as $relationType)
    {
        $result = $dwApi->GetForums(31, $relationType);         // false (ancestors), list web dev forums, not include self
        $result = $dwApi->GetForums(31, $relationType, true);   // list web dev forums, include self
        $result = $dwApi->GetForums(31, $relationType, false);  // false (ancestors), list web dev forums, not include self
        $result = $dwApi->GetForums(31, $relationType, 'a');    // false (ancestors), list web dev forums, ignore 'a'
    }

    $result = $dwApi->GetForums(17, 'ancestors');               // list ancestor of php forum
    $result = $dwApi->GetForums(17, 'ancestors', true);         // list ancestor of php forum, include self

    $result = $dwApi->GetMemberActivityPoints(94719);           // activity for member 94719 (pritaeas)
    $result = $dwApi->GetMemberActivityPoints('a');             // false

    $result = $dwApi->GetMemberArticles(94719);                 // articles for member 94719, page 1, 30 per page
    $result = $dwApi->GetMemberArticles(94719, 2);              // articles for member 94719, page 2
    $result = $dwApi->GetMemberArticles('a');                   // false
    $result = $dwApi->GetMemberArticles(array (94719, 1));      // articles for members 1 and 94719, page 1
    $result = $dwApi->GetMemberArticles(array (94719, 1), 2);   // articles for members 1 and 94719, page 2
    $result = $dwApi->GetMemberArticles(array (94719, 1), 'a'); // articles for members 1 and 94719, page 1, ignore 'a'

    $result = $dwApi->GetMemberEndorsements(94719);             // endorsements for member 94719
    $result = $dwApi->GetMemberEndorsements('a');               // false

    $result = $dwApi->GetMemberPosts(94719);                    // posts for member 94719, 20 per page
    $result = $dwApi->GetMemberPosts('a');                      // false
    $result = $dwApi->GetMemberPosts(94719, null, 2);           // posts, page 2
    $result = $dwApi->GetMemberPosts(94719, null, 'a');         // posts, page 1, ignore 'a'

    $postTypes = $dwApi->GetPostTypes();
    $postTypes[] = null;
    $postTypes[] = 'invalid';
    foreach ($postTypes as $postType)
    {
        $result = $dwApi->GetMemberPosts(94719, $postType, 1);
        $result = $dwApi->GetMemberPosts(94719, $postType, 2);
    }

    $result = $dwApi->GetMemberReputationComments(94719);       // reputation for member 94719, page 1, 30 per page
    $result = $dwApi->GetMemberReputationComments(94719, 2);    // reputation for member 94719, page 2
    $result = $dwApi->GetMemberReputationComments(94719, 'a');  // reputation for member 94719, page 1, ignore 'a'
    $result = $dwApi->GetMemberReputationComments('a');         // false

    $result = $dwApi->GetMembers();                             // list members, 50 per page, page 1
    $result = $dwApi->GetMembers('pritaeas');                   // user 'pritaeas'
    $result = $dwApi->GetMembers('prit');                       // user 'prit'
    $result = $dwApi->GetMembers('pritaeas', 2);                // false
    $result = $dwApi->GetMembers('prit', 2);                    // false
    $result = $dwApi->GetMembers(94719);                        // user by id
    $result = $dwApi->GetMembers(array (94719, 1));             // users by id's
    $result = $dwApi->GetMembers(array (94719, 1), 2);          // todo EXPECTED FALSE !
    $result = $dwApi->GetMembers(array (94719, 1), 'a');        // users by id's, ignore 'a'
    $result = $dwApi->GetMembers(false);                        // user list, ignore false

    $result = $dwApi->GetPostReputationComments(1957463);       // reputation comments for post 1957463
    $result = $dwApi->GetPostReputationComments('a');           // false

    $result = $dwApi->GetPosts();                               // list posts, page 1, 20 per page
    $result = $dwApi->GetPosts(null, 2);                        // list posts, page 2
    $result = $dwApi->GetPosts(1957463);                        // get post
    $result = $dwApi->GetPosts('a');                            // ignore 'a'
    $result = $dwApi->GetPosts(array (1957463, 1));             // list posts
    $result = $dwApi->GetPosts(array (1957463, 1), 2);          // false

    $result = $dwApi->SearchArticles('daniweb-api');            // article search, page 1, 30 per page
    $result = $dwApi->SearchArticles('daniweb-api', 2);         // article search, page 2
    $result = $dwApi->SearchArticles('daniweb-api', 'a');       // article search, ignore 'a'
    $result = $dwApi->SearchArticles(1);                        // false

    $result = $dwApi->SearchMembers('prit');                    // member search, 50 per page
    $result = $dwApi->SearchMembers('prit', 2);                 // false (no page 2 )
    $result = $dwApi->SearchMembers('prit', 'a');               // member search, 50 per page
    $result = $dwApi->SearchMembers(1);                         // false
}

if ($testOAuth)                                                 // DwApiOAuth
{
    $result = $dwApi->GetArticles();                            // list articles

    $result = $dwApi->GetForumArticles(17);                     // articles for forum 17

    $result = $dwApi->GetMemberArticles(94719);                 // articles for member 94719

    $result = $dwApi->GetPrivateMessages(true);                 // received private messages for logged in user
    $result = $dwApi->GetPrivateMessages(false);                // sent private messages for logged in user
    $result = $dwApi->GetPrivateMessages('1');                  // false

    $result = $dwApi->VotePost();                               // ?

    $result = $dwApi->WatchArticle();                           // ?

    $result = $dwApi->WhoAmI();                                 // profile for logged in user
}
?>