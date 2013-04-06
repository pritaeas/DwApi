<?php
class ReadOnly extends Base
{
    /**
     * Get posts for a specific article.
     *
     * @param int $articleId Article ID.
     * @param int $page Page number (optional).
     * @return mixed JSON result, false on error.
     */
    public function GetArticlePosts($articleId, $page = null)
    {
        if (!is_int($articleId) or ($articleId < 1))
        {
            return false;
        }

        $url = "http://www.daniweb.com/api/articles/{$articleId}/posts";
        if (is_int($page) and ($page > 0))
        {
            $url .= "?page={$page}";
        }
        return $this->GetUrl($url);
    }

    /**
     * Get a list of (specific) articles.
     *
     * @param mixed $articleIds Article ID as int, or array of int (optional).
     * @param int|null $page Page number (optional).
     * @return bool|string JSON result, false on error.
     */
    public function GetArticles($articleIds = null, $page = null)
    {
        $url = "http://www.daniweb.com/api/articles";

        $articleIdList = array();
        if (is_int($articleIds) and ($articleIds > 0))
        {
            $articleIdList[] = $articleIds;
        }
        else if (is_array($articleIds))
        {
            foreach ($articleIds as $forumId)
            {
                if (is_int($forumId) and ($forumId > 0))
                {
                    $articleIdList[] = $forumId;
                }
            }
        }

        if (count($articleIdList) > 0)
        {
            $articleIdString = implode(';', $articleIdList);
            $url .= "/{$articleIdString}";
        }

        if (is_int($page) and ($page > 0))
        {
            $url .= "?page={$page}";
        }

        return $this->GetUrl($url);
    }

    /**
     * Get a list of articles for a specific forum ID, or an array of forum IDs.
     *
     * @param mixed $forumIds Forum ID as int, or array of int.
     * @param int|null $page Page number.
     * @return bool|string JSON result, false on error.
     */
    public function GetForumArticles($forumIds, $page = null)
    {
        $forumIdList = array();

        if (is_int($forumIds) and ($forumIds > 0))
        {
            $forumIdList[] = $forumIds;
        }
        else if (is_array($forumIds))
        {
            foreach ($forumIds as $forumId)
            {
                if (is_int($forumId) and ($forumId > 0))
                {
                    $forumIdList[] = $forumId;
                }
            }
        }

        if (count($forumIdList) < 1)
        {
            return false;
        }

        $forumIdString = implode(';', $forumIdList);
        $url = "http://www.daniweb.com/api/forums/{$forumIdString}/articles";
        if (is_int($page) and ($page > 0))
        {
            $url .= "?page={$page}";
        }

        return $this->GetUrl($url);
    }

    /**
     * Get posts for a specific forum.
     *
     * @param int $forumId Forum ID.
     * @param int|null $page Page number (optional).
     * @return bool|string JSON result, false on error.
     */
    public function GetForumPosts($forumId, $page = null)
    {
        if (!is_int($forumId) or ($forumId < 1))
        {
            return false;
        }

        $url = "http://www.daniweb.com/api/forums/{$forumId}/posts";
        if (is_int($page) and ($page > 0))
        {
            $url .= "?page={$page}";
        }
        return $this->GetUrl($url);
    }

    /**
     * Get a list of (filtered) forums.
     *
     * @param mixed $forumIds Forum ID as int, or array of int (optional).
     * @param null|string $relation Forum relation type (optional).
     * @param bool|null $includeSelf Include the forumID in the result (optional), default false.
     * @return bool|string JSON result, false on error.
     */
    public function GetForums($forumIds = null, $relation = null, $includeSelf = null)
    {
        $url = 'http://www.daniweb.com/api/forums';

        $forumIdList = array();
        if (is_int($forumIds) and ($forumIds > 0))
        {
            $forumIdList[] = $forumIds;
        }
        else if (is_array($forumIds))
        {
            foreach ($forumIds as $forumId)
            {
                if (is_int($forumId) and ($forumId > 0))
                {
                    $forumIdList[] = $forumId;
                }
            }
        }
        if (count($forumIdList) > 0)
        {
            $url .= '/' . implode(';', $forumIdList);
        }

        if (is_string($relation) and in_array($relation, $this->relationTypes))
        {
            $url .= "/$relation";
        }

        if (is_bool($includeSelf))
        {
            $url .= '?' . ($includeSelf ? 'true' : 'false');
        }

        return $this->GetUrl($url);
    }

    /**
     * Get activities for a specific member.
     *
     * @param int $memberId Member ID.
     * @return bool|string JSON result, false on error.
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
     * Get a list of articles for a specific member, or members.
     *
     * @param mixed $memberIds Member ID as int, or array of int.
     * @param int|null $page Page number (optional).
     * @return bool|string JSON result, false on error.
     */
    public function GetMemberArticles($memberIds, $page = null)
    {
        $memberIdList = array();
        if (is_int($memberIds) and ($memberIds > 0))
        {
            $memberIdList[] = $memberIds;
        }
        else if (is_array($memberIds))
        {
            foreach ($memberIds as $forumId)
            {
                if (is_int($forumId) and ($forumId > 0))
                {
                    $memberIdList[] = $forumId;
                }
            }
        }

        if (count($memberIdList) < 1)
        {
            return false;
        }

        $memberIdString = implode(';', $memberIdList);
        $url = "http://www.daniweb.com/api/members/{$memberIdString}/articles";

        if (is_int($page) and ($page > 0))
        {
            $url .= "?page={$page}";
        }

        return $this->GetUrl($url);
    }

    /**
     * Get endorsements for a specific member.
     *
     * @param int $memberId Member ID.
     * @return bool|string JSON result, false on error.
     */
    public function GetMemberEndorsements($memberId)
    {
        if (!is_int($memberId) or ($memberId < 1))
        {
            return false;
        }

        return $this->GetUrl("http://www.daniweb.com/api/members/{$memberId}/endorsements");
    }

    /**
     * Get posts for a specific member ID, optionally filtered.
     *
     * @param int $memberId Member ID.
     * @param null|string $postType Post type to filter on (optional).
     * @param int|null $page Page number (optional).
     * @return bool|string JSON result, false on error.
     */
    public function GetMemberPosts($memberId, $postType = null, $page = null)
    {
        if (!is_int($memberId) or ($memberId < 1))
        {
            return false;
        }

        $filter = in_array($postType, $this->postTypes) ? $postType : '';
        $url = "http://www.daniweb.com/api/members/{$memberId}/posts";
        if (!empty($filter))
        {
            $url .= "?filter={$filter}";
        }

        if (is_int($page) and ($page > 0))
        {
            $url .= (empty($filter) ? '?' : '&') . "page={$page}";
        }

        return $this->GetUrl($url);
    }

    /**
     * Get reputation comments for a specific member.
     *
     * @param int $memberId Member ID.
     * @param int|null $page Page number (optional).
     * @return bool|string JSON result, false on error.
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
            $url .= "?page={$page}";
        }
        return $this->GetUrl($url);
    }

    /**
     * Get a list of members, or a list of specific members.
     *
     * @param mixed $members Member as string, member ID as int or array of int.
     * @param int|null $page Page number (optional).
     * @return bool|string JSON result, false on error.
     */
    public function GetMembers($members, $page = null)
    {
        $url = 'http://www.daniweb.com/api/members';
        if (is_string($members))
        {
            $url .= "?username={$members}";
        }
        else
        {
            if (is_int($members))
            {
                $url .= "/{$members}";
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
                $url .= "?page={$page}";
            }
        }
        return $this->GetUrl($url);
    }

    /**
     * Get reputation comments for a specific post.
     *
     * @param int $postId Post ID.
     * @param int|null $page Page number (optional).
     * @return bool|string JSON result, false on error.
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
            $url .= "?page={$page}";
        }
        return $this->GetUrl($url);
    }

    /**
     * Get a list of posts, or a list of specific posts.
     *
     * @param mixed $postIds Post ID as int, or array of int.
     * @param int|null $page Page number (optional).
     * @return bool|string JSON result, false on error.
     */
    public function GetPosts($postIds = null, $page = null)
    {
        $url = "http://www.daniweb.com/api/posts";
        if (is_int($postIds) and ($postIds > 0))
        {
            $url .= "/{$postIds}";
        }
        else if (is_array($postIds))
        {
            $postIdList = array();
            foreach ($postIds as $postId)
            {
                if (is_int($postId) and ($postId > 0))
                {
                    $postIdList[] = $postId;
                }
            }
            if (count($postIdList) > 0)
            {
                $url .= '/' . implode(';', $postIdList);
            }
        }

        if (is_int($page) and ($page > 0))
        {
            $url .= "?page={$page}";
        }
        return $this->GetUrl($url);
    }

    /**
     * Searches articles for the given query.
     *
     * @param string $query Search query.
     * @param int|null $page Page number (optional).
     * @return bool|string JSON result, false on error.
     */
    public function SearchArticles($query, $page = null)
    {
        if (empty($query) or !is_string($query))
        {
            return false;
        }

        $url = 'http://www.daniweb.com/api/articles/search?query=' . urlencode($query);
        if (is_int($page) and ($page > 0))
        {
            $url .= "&page={$page}";
        }
        return $this->GetUrl($url);
    }

    /**
     * Searches members for the given member name.
     *
     * @param string $memberName Member name to search.
     * @param int|null $page Page number (optional).
     * @return bool|string JSON result, false on error.
     */
    public function SearchMembers($memberName, $page = null)
    {
        if (empty($memberName) or !is_string($memberName))
        {
            return false;
        }

        $url = 'http://www.daniweb.com/api/members/search?query=' . urlencode($memberName);
        if (is_int($page) and ($page > 0))
        {
            $url .= "&page={$page}";
        }
        return $this->GetUrl($url);
    }
}
?>