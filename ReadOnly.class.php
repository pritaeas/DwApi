<?php
class ReadOnly extends Base
{
    public function GetArticles($articleIds, $articleType, $orderBy, $page, $forumId)
    {

    }

    public function GetForums($forumsIds, $includeSelf)
    {

    }

    public function GetMemberActivityPoints($id)
    {
        if (!is_int($id) or ($id < 1))
        {
            return false;
        }

        return $this->GetUrl("http://www.daniweb.com/api/members/{$id}/activities");
    }

    public function GetMemberEndorsements($id)
    {
        if (!is_int($id) or ($id < 1))
        {
            return false;
        }

        return $this->GetUrl("http://www.daniweb.com/api/members/{$id}/endorsements");
    }

    public function GetMemberReputationComments($id, $page)
    {
        if (!is_int($id) or ($id < 1))
        {
            return false;
        }

        $url = "http://www.daniweb.com/api/members/{$id}/comments";
        if (is_int($page) and ($page > 0))
        {
            $url .= '?page=' . $page;
        }
        return $this->GetUrl($url);
    }

    public function GetMembers($userIds, $page)
    {

    }

    public function GetPosts($page, $postType)
    {

    }

    public function SearchArticles($query, $page)
    {
        if (empty($query))
        {
            return false;
        }

        $url = 'http://www.daniweb.com/api/articles/search?query=' . urlencode($query);
        if (is_int($page) and ($page > 0))
        {
            $url .= '&page=' . $page;
        }
        return $this->GetUrl($url);
    }

    public function SearchMembers($username, $page)
    {
        if (empty($username))
        {
            return false;
        }

        $url = 'http://www.daniweb.com/api/members/search?query=' . urlencode($username);
        if (is_int($page) and ($page > 0))
        {
            $url .= '&page=' . $page;
        }
        return $this->GetUrl($url);
    }
}
?>