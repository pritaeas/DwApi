<?php
class ReadOnly extends Base
{
    public function GetArticlePosts($articleId, $page)
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

    public function GetForumPosts($forumId, $page)
    {
        // http://www.daniweb.com/api/forums/{:ID}/posts
    }

    public function GetForums($forumIds, $includeSelf)
    {
        // http://www.daniweb.com/api/forums
        // http://www.daniweb.com/api/forums/children?include_self=
        // http://www.daniweb.com/api/forums/descendants?include_self=
        // http://www.daniweb.com/api/forums/{:IDS}
        // http://www.daniweb.com/api/forums/{:IDS}/ancestors?include_self=
        // http://www.daniweb.com/api/forums/{:IDS}/children?include_self=
        // http://www.daniweb.com/api/forums/{:IDS}/descendants?include_self=
    }

    /**
     * Get activities for a specific member.
     *
     * @param $memberId Member ID.
     * @return mixed JSON result, false on error.
     */
    public function GetMemberActivityPoints($memberId)
    {
        if (!is_int($memberId) or ($memberId < 1))
        {
            return false;
        }

        return $this->GetUrl("http://www.daniweb.com/api/members/{$memberId}/activities");
    }

    /**
     * Get endorsements for a specific member.
     *
     * @param $memberId Member ID.
     * @return mixed JSON result, false on error.
     */
    public function GetMemberEndorsements($memberId)
    {
        if (!is_int($memberId) or ($memberId < 1))
        {
            return false;
        }

        return $this->GetUrl("http://www.daniweb.com/api/members/{$memberId}/endorsements");
    }

    public function GetMemberPosts($memberId, $page, $postType)
    {
        // http://www.daniweb.com/api/members/{:ID}/posts?filter=
    }

    /**
     * Get reputation comments for a specific member.
     *
     * @param int $memberId Member ID.
     * @param int $page Page number (optional).
     * @return mixed JSON result, false on error.
     */
    public function GetMemberReputationComments($memberId, $page = null)
    {
        if (!is_int($memberId) or ($memberId < 1))
        {
            return false;
        }

        $url = "http://www.daniweb.com/api/members/{$memberId}/comments";
        if (is_int($page) and ($page > 0))
        {
            $url .= '?page=' . $page;
        }
        return $this->GetUrl($url);
    }

    /**
     * Get a list of members.
     *
     * @param mixed $members Member as string, member ID as int or int array.
     * @param int $page Page number (optional).
     * @return mixed JSON members result, false on error.
     */
    public function GetMembers($members, $page = null)
    {
        $url = 'http://www.daniweb.com/api/members';
        if (is_string($members))
        {
            $url .= '?username=' . $members;
        }
        else
        {
            if (is_int($members))
            {
                $url .= '/' . $members;
            }
            else if (is_array($members))
            {
                $memberList = array ();
                foreach ($members as $member)
                {
                    if (is_int($member))
                    {
                        $memberList[] = $member;
                    }
                }
                $url .= implode(';', $memberList);
            }

            if (is_int($page) and ($page > 0))
            {
                $url .= '?page=' . $page;
            }
        }
        return $this->GetUrl($url);
    }

    /**
     * Get reputation comments for a specific post.
     *
     * @param int $postId Post ID.
     * @param int $page Page number (optional).
     * @return mixed JSON result, false on error.
     */
    public function GetPostReputationComments($postId, $page = null)
    {
        if (!is_int($postId) or ($postId < 1))
        {
            return false;
        }

        $url = "http://www.daniweb.com/api/posts/{$postId}/comments";
        if (is_int($page) and ($page > 0))
        {
            $url .= '?page=' . $page;
        }
        return $this->GetUrl($url);
    }

    public function GetPosts($postIds, $page)
    {
        // http://www.daniweb.com/api/posts
        // http://www.daniweb.com/api/posts/{:IDS}
    }

    /**
     * Searches articles for the given query.
     *
     * @param string $query Search query.
     * @param int $page Page number (optional).
     * @return mixed JSON search result, false on error.
     */
    public function SearchArticles($query, $page = null)
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

    /**
     * Searches members for the given member name.
     *
     * @param string $memberName Member name to search.
     * @param int $page Page number (optional).
     * @return mixed JSON search result, false on error.
     */
    public function SearchMembers($memberName, $page = null)
    {
        if (empty($memberName))
        {
            return false;
        }

        $url = 'http://www.daniweb.com/api/members/search?query=' . urlencode($memberName);
        if (is_int($page) and ($page > 0))
        {
            $url .= '&page=' . $page;
        }
        return $this->GetUrl($url);
    }
}
?>