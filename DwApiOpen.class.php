<?php
class DwApiOpen extends DwApiBase
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
        if (!$this->IsValidId($articleId))
        {
            return false;
        }

        $url = "http://www.daniweb.com/api/articles/{$articleId}/posts" . $this->GetPageParameter($page);

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

        $articleIdString = $this->IdsToString($articleIds);
        if (!empty($articleIdString))
        {
            $url .= "/{$articleIdString}";
        }

        $url .= $this->GetPageParameter($page);

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
        $forumIdString = $this->IdsToString($forumIds);
        if (empty($forumIdString))
        {
            return false;
        }

        $url = "http://www.daniweb.com/api/forums/{$forumIdString}/articles" . $this->GetPageParameter($page);

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
        if (!$this->IsValidId($forumId))
        {
            return false;
        }

        $url = "http://www.daniweb.com/api/forums/{$forumId}/posts" . $this->GetPageParameter($page);

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

        $forumIdString = $this->IdsToString($forumIds);
        if (!empty($forumIdString))
        {
            $url .= "/{$forumIdString}";
        }

        if ($this->IsRelationType($relation))
        {
            $url .= "/{$relation}";
        }

        if (is_bool($includeSelf))
        {
            $url .= '?include_self=' . ($includeSelf ? 'true' : 'false');
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
        if (!$this->IsValidId($memberId))
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
        $memberIdString = $this->IdsToString($memberIds);
        if (empty($memberIdString))
        {
            return false;
        }

        $url = "http://www.daniweb.com/api/members/{$memberIdString}/articles" . $this->GetPageParameter($page);

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
        if (!$this->IsValidId($memberId))
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
        if (!$this->IsValidId($memberId))
        {
            return false;
        }

        $url = "http://www.daniweb.com/api/members/{$memberId}/posts";

        if ($this->IsPostType($postType))
        {
            $url .= "?filter={$postType}";
        }

        $url .= $this->GetPageParameter($page, $this->IsPostType($postType) ? '&' : '?');

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
        if (!$this->IsValidId($memberId))
        {
            return false;
        }

        $url = "http://www.daniweb.com/api/members/{$memberId}/comments" . $this->GetPageParameter($page);

        return $this->GetUrl($url);
    }

    /**
     * Get a list of members, or a list of specific members.
     *
     * @param mixed $members Member as string, member ID as int or array of int (optional).
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
            $memberIdString = $this->IdsToString($members);
            $url .= (empty($memberIdString) ? '' : "/{$memberIdString}") . $this->GetPageParameter($page);
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
        if (!$this->IsValidId($postId))
        {
            return false;
        }

        $url = "http://www.daniweb.com/api/posts/{$postId}/comments" . $this->GetPageParameter($page);

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

        $postIdString = $this->IdsToString($postIds);
        $url .= (empty($postIdString) ? '' : "/{$postIdString}") . $this->GetPageParameter($page);

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

        $url = 'http://www.daniweb.com/api/articles/search?query=' . urlencode($query) .
            $this->GetPageParameter($page, '&');

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

        $url = 'http://www.daniweb.com/api/members/search?query=' . urlencode($memberName) .
            $this->GetPageParameter($page, '&');

        return $this->GetUrl($url);
    }
}
?>