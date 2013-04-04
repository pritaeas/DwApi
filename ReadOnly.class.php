<?php
class ReadOnly extends Base
{
    public function GetArticlePosts($id, $page)
    {
        // http://www.daniweb.com/api/articles/{:ID}/posts
    }

    public function GetArticles($articleIds, $articleType, $orderBy, $page, $forumId)
    {
        // http://www.daniweb.com/api/articles
        // http://www.daniweb.com/api/articles/{:IDS}
        // http://www.daniweb.com/api/forums/{:IDS}/articles
        // http://www.daniweb.com/api/members/{:IDS}/articles?forum_id=
    }

    public function GetForumPosts($id, $page)
    {
        // http://www.daniweb.com/api/forums/{:ID}/posts
    }

    public function GetForums($forumsIds, $includeSelf)
    {
        // http://www.daniweb.com/api/forums
        // http://www.daniweb.com/api/forums/children?include_self=
        // http://www.daniweb.com/api/forums/descendants?include_self=
        // http://www.daniweb.com/api/forums/{:IDS}
        // http://www.daniweb.com/api/forums/{:IDS}/ancestors?include_self=
        // http://www.daniweb.com/api/forums/{:IDS}/children?include_self=
        // http://www.daniweb.com/api/forums/{:IDS}/descendants?include_self=
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

    public function GetMemberPosts($id, $page, $postType)
    {
        // http://www.daniweb.com/api/members/{:ID}/posts?filter=
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

    public function GetMembers($users, $page)
    {
        $url = 'http://www.daniweb.com/api/members';
        if (is_string($users))
        {
            $url .= '?username=' . $users;
        }
        else
        {
            if (is_int($users))
            {
                $url .= '/' . $users;
            }
            else if (is_array($users))
            {
                $userlist = array ();
                foreach ($users as $user)
                {
                    if (is_int($user))
                    {
                        $userlist[] = $user;
                    }
                }
                $url .= implode(';', $userlist);
            }

            if (is_int($page) and ($page > 0))
            {
                $url .= '?page=' . $page;
            }
        }
        return $this->GetUrl($url);
    }

    public function GetPostReputationComments($id, $page)
    {
        if (!is_int($id) or ($id < 1))
        {
            return false;
        }

        $url = "http://www.daniweb.com/api/posts/{$id}/comments";
        if (is_int($page) and ($page > 0))
        {
            $url .= '?page=' . $page;
        }
        return $this->GetUrl($url);
    }

    public function GetPosts($ids, $page)
    {
        // http://www.daniweb.com/api/posts
        // http://www.daniweb.com/api/posts/{:IDS}
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