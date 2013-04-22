<?php
include 'DwApiRss.class.php';

class DwApiOpen extends DwApiRss
{
    /**
     * Get posts for a specific article.
     *
     * @param int $articleId Article ID (required).
     * @param int $page Page number (optional), default 1.
     * @throws DwApiException EX_INVALID_INT thrown on invalid article ID or page number.
     * @return string JSON result.
     */
    public function GetArticlePosts($articleId, $page = 1)
    {
        if (!$this->IsValidId($articleId))
        {
            throw new DwApiException('$articleId', DwApiException::EX_INVALID_INT);
        }

        if (!$this->IsValidId($page))
        {
            throw new DwApiException('$page', DwApiException::EX_INVALID_INT);
        }

        return $this->GetUrl("/api/articles/{$articleId}/posts", array ('page' => $page));
    }

    /**
     * Get a list of (specific) articles.
     *
     * @param null|int|array $articleIds Article ID as int, or array of int (optional).
     * @param null|string $articleType Article type filter (optional).
     * @param bool $newestFirst Newest post first when true, oldest post first when false, default true.
     * @param int $page Page number (optional), default 1.
     * @throws DwApiException EX_INVALID_INT thrown on invalid page number.
     * @return string JSON result.
     */
    public function GetArticles($articleIds = null, $articleType = null, $newestFirst = true, $page = 1)
    {
        $articleIdString = $this->IdsToString($articleIds);
        if (($articleIds != null) and empty($articleIdString))
        {
            throw new DwApiException('$articleIds', DwApiException::EX_INVALID_INT_ARRAY);
        }

        if (($articleType != null) and !$this->IsArticleType($articleType))
        {
            throw new DwApiException('$articleType', DwApiException::EX_INVALID_TYPE_ARTICLE);
        }

        if (!is_bool($newestFirst))
        {
            throw new DwApiException('$newestFirst', DwApiException::EX_INVALID_BOOL);
        }

        if (!$this->IsValidId($page))
        {
            throw new DwApiException('$page', DwApiException::EX_INVALID_INT);
        }

        $url = "/api/articles";

        if (!empty($articleIdString)) {
            $url .= "/{$articleIdString}";
        }

        $getParameters = array ('page' => $page);

        if ($articleType != null)
        {
            $getParameters['filter'] = $articleType;
        }

        // todo change to orderBy type
        $getParameters['orderby'] = $newestFirst ? 'lastpost' : 'firstpost';

        return $this->GetUrl($url, $getParameters);
    }

    /**
     * Get a list of articles for a specific forum ID, or an array of forum IDs.
     *
     * @param int|array $forumIds Forum ID as int, or array of int (required).
     * @param null|string $articleType Article type filter (optional).
     * @param bool $newestFirst Newest post first when true, Oldest post first when false, default true.
     * @param int $page Page number (optional), default 1.
     * @throws DwApiException EX_INVALID_INT_ARRAY thrown on invalid forum IDs.
     * @throws DwApiException EX_INVALID_TYPE_ARTICLE thrown on non-null invalid article type.
     * @throws DwApiException EX_INVALID_INT thrown on invalid page number.
     * @return string JSON result.
     */
    public function GetForumArticles($forumIds, $articleType = null, $newestFirst = true, $page = 1)
    {
        $forumIdString = $this->IdsToString($forumIds);
        if (empty($forumIdString))
        {
            throw new DwApiException($forumIds, DwApiException::EX_INVALID_INT_ARRAY);
        }

        if (($articleType != null) and !$this->IsArticleType($articleType))
        {
            throw new DwApiException('$articleType', DwApiException::EX_INVALID_TYPE_ARTICLE);
        }

        if (!is_bool($newestFirst))
        {
            throw new DwApiException('$newestFirst', DwApiException::EX_INVALID_BOOL);
        }

        if (!$this->IsValidId($page))
        {
            throw new DwApiException('$page', DwApiException::EX_INVALID_INT);
        }

        $getParameters = array ('page' => $page);

        if ($articleType != null)
        {
            $getParameters['filter'] = $articleType;
        }

        // todo change to orderBy type
        $getParameters['orderby'] = $newestFirst ? 'lastpost' : 'firstpost';

        return $this->GetUrl("/api/forums/{$forumIdString}/articles", $getParameters);
    }

    /**
     * Get posts for a specific forum.
     *
     * @param int $forumId Forum ID (required).
     * @param int $page Page number (optional), default 1.
     * @throws DwApiException EX_INVALID_INT thrown on invalid forum ID or page number.
     * @return string JSON result.
     */
    public function GetForumPosts($forumId, $page = 1)
    {
        if (!$this->IsValidId($forumId))
        {
            throw new DwApiException('$forumId', DwApiException::EX_INVALID_INT);
        }

        if (!$this->IsValidId($page))
        {
            throw new DwApiException('$page', DwApiException::EX_INVALID_INT);
        }

        return $this->GetUrl("/api/forums/{$forumId}/posts", array ('page' => $page));
    }

    /**
     * Get a list of (filtered) forums.
     *
     * @param null|int|array $forumIds Forum ID as int, or array of int (optional).
     * @param null|string $relation Forum relation type (optional).
     * @param bool $includeSelf Include the forumID in the result (optional), default false.
     * @throws DwApiException EX_INVALID_TYPE_RELATION thrown on non-null invalid relation type.
     * @return string JSON result.
     */
    public function GetForums($forumIds = null, $relation = null, $includeSelf = false)
    {
        if (($relation != null) and !$this->IsRelationType($relation))
        {
            throw new DwApiException('$relation', DwApiException::EX_INVALID_TYPE_RELATION);
        }

        if (!is_bool($includeSelf))
        {
            throw new DwApiException('$includeSelf', DwApiException::EX_INVALID_BOOL);
        }

        $url = '/api/forums';

        $forumIdString = $this->IdsToString($forumIds);
        if (!empty($forumIdString))
        {
            $url .= "/{$forumIdString}";
        }

        if ($relation != null)
        {
            $url .= "/{$relation}";
        }

        $getParameters = array ('include_self' => $includeSelf);

        return $this->GetUrl($url, $getParameters);
    }

    /**
     * Get activities for a specific member.
     *
     * @param int $memberId Member ID (required).
     * @throws DwApiException EX_INVALID_INT thrown on invalid member ID.
     * @return string JSON result.
     */
    public function GetMemberActivityPoints($memberId)
    {
        if (!$this->IsValidId($memberId))
        {
            throw new DwApiException('$memberId', DwApiException::EX_INVALID_INT);
        }

        return $this->GetUrl("/api/members/{$memberId}/activities");
    }

    /**
     * Get a list of articles for a specific member, or members.
     *
     * @param int|array $memberIds Member ID as int, or array of int (required).
     * @param null|int $forumId Forum ID (optional).
     * @param null|string $articleType Article type filter (optional).
     * @param bool $newestFirst Newest post first when true, Oldest post first when false, default true.
     * @param int $page Page number (optional), default 1.
     * @throws DwApiException EX_INVALID_INT_ARRAY thrown on invalid member IDs.
     * @throws DwApiException EX_INVALID_INT thrown on non-null invalid forum ID.
     * @throws DwApiException EX_INVALID_TYPE_ARTICLE thrown on non-null invalid article type.
     * @throws DwApiException EX_INVALID_BOOL thrown on invalid newest first.
     * @throws DwApiException EX_INVALID_INT thrown on invalid page number.
     * @return string JSON result.
     */
    public function GetMemberArticles($memberIds, $forumId = null, $articleType = null, $newestFirst = true, $page = 1)
    {
        $memberIdString = $this->IdsToString($memberIds);
        if (empty($memberIdString))
        {
            throw new DwApiException('$memberIds', DwApiException::EX_INVALID_INT_ARRAY);
        }

        if (($forumId != null) and !$this->IsValidId($forumId))
        {
            throw new DwApiException('$forumId', DwApiException::EX_INVALID_INT);
        }

        if (($articleType != null) and !$this->IsArticleType($articleType))
        {
            throw new DwApiException('$articleType', DwApiException::EX_INVALID_TYPE_ARTICLE);
        }

        if (!is_bool($newestFirst))
        {
            throw new DwApiException('$newestFirst', DwApiException::EX_INVALID_BOOL);
        }

        if (!$this->IsValidId($page))
        {
            throw new DwApiException('$page', DwApiException::EX_INVALID_INT);
        }

        $getParameters = array ('page' => $page);

        if ($forumId != null)
        {
            $getParameters['forum_id'] = $forumId;
        }

        if ($articleType != null)
        {
            $getParameters['filter'] = $articleType;
        }

        $getParameters['orderby'] = $newestFirst ? 'lastpost' : 'firstpost';

        return $this->GetUrl("/api/members/{$memberIdString}/articles", $getParameters);
    }

    /**
     * Get endorsements for a specific member.
     *
     * @param int $memberId Member ID (required).
     * @throws DwApiException EX_INVALID_INT thrown on invalid member ID.
     * @return string JSON result.
     */
    public function GetMemberEndorsements($memberId)
    {
        if (!$this->IsValidId($memberId))
        {
            throw new DwApiException('$memberId', DwApiException::EX_INVALID_INT);
        }

        return $this->GetUrl("/api/members/{$memberId}/endorsements");
    }

    /**
     * Get posts for a specific member ID, optionally filtered.
     *
     * @param int $memberId Member ID (required).
     * @param null|string $postType Post type to filter on (optional).
     * @param int $page Page number (optional), default 1.
     * @throws DwApiException EX_INVALID_INT thrown on invalid member ID.
     * @throws DwApiException EX_INVALID_TYPE_POST thrown on non-null invalid post type.
     * @throws DwApiException EX_INVALID_INT thrown on invalid page number.
     * @return string JSON result.
     */
    public function GetMemberPosts($memberId, $postType = null, $page = 1)
    {
        if (!$this->IsValidId($memberId))
        {
            throw new DwApiException('$memberId', DwApiException::EX_INVALID_INT);
        }

        if (($postType != null) and !$this->IsPostType($postType))
        {
            throw new DwApiException('$postType', DwApiException::EX_INVALID_TYPE_POST);
        }

        if (!$this->IsValidId($page))
        {
            throw new DwApiException('$page', DwApiException::EX_INVALID_INT);
        }

        $url = "/api/members/{$memberId}/posts";

        $getParameters = array('page' => $page);

        if ($postType != null)
        {
            $getParameters['filter'] = $postType;
        }

        return $this->GetUrl($url, $getParameters);
    }

    /**
     * Get reputation comments for a specific member.
     *
     * @param int $memberId Member ID (required).
     * @param int $page Page number (optional), default 1.
     * @throws DwApiException EX_INVALID_INT thrown on invalid member ID or page number.
     * @return string JSON result.
     */
    public function GetMemberReputationComments($memberId, $page = 1)
    {
        if (!$this->IsValidId($memberId))
        {
            throw new DwApiException('$memberId', DwApiException::EX_INVALID_INT);
        }

        if (!$this->IsValidId($page))
        {
            throw new DwApiException('$page', DwApiException::EX_INVALID_INT);
        }

        return $this->GetUrl("/api/members/{$memberId}/comments", array ('page' => $page));
    }

    /**
     * Get a list of members, or a list of specific members.
     *
     * @param null|int|array $members Member as string, member ID as int or array of int (optional).
     * @param int $page Page number (optional), default 1.
     * @throws DwApiException EX_INVALID_INT thrown on invalid page number.
     * @return string JSON result.
     */
    public function GetMembers($members = null, $page = 1)
    {
        if (!$this->IsValidId($page))
        {
            throw new DwApiException('$page', DwApiException::EX_INVALID_INT);
        }

        $url = '/api/members';

        if (is_string($members))
        {
            $getParameters = array ('username' => $members);
        }
        else
        {
            $memberIdString = $this->IdsToString($members);
            $url .= empty($memberIdString) ? '' : "/{$memberIdString}";
            $getParameters = array ('page' => $page);
        }

        return $this->GetUrl($url, $getParameters);
    }

    /**
     * Get reputation comments for a specific post.
     *
     * @param int $postId Post ID (required).
     * @throws DwApiException EX_INVALID_INT thrown on invalid page number.
     * @return string JSON result.
     */
    public function GetPostReputationComments($postId)
    {
        if (!$this->IsValidId($postId))
        {
            throw new DwApiException('$postId', DwApiException::EX_INVALID_INT);
        }

        return $this->GetUrl("/api/posts/{$postId}/comments");
    }

    /**
     * Get a list of posts, or a list of specific posts.
     *
     * @param null|int|array $postIds Post ID as int, or array of int (optional).
     * @param int $page Page number (optional), default 1.
     * @throws DwApiException EX_INVALID_INT thrown on invalid page number.
     * @return string JSON result.
     */
    public function GetPosts($postIds = null, $page = 1)
    {
        if (!$this->IsValidId($page))
        {
            throw new DwApiException('$page', DwApiException::EX_INVALID_INT);
        }

        $url = "/api/posts";

        $postIdString = $this->IdsToString($postIds);
        $url .= empty($postIdString) ? '' : "/{$postIdString}";

        return $this->GetUrl($url, array ('page' => $page));
    }

    /**
     * Searches articles for the given query.
     *
     * @param string $query Search query (required).
     * @param int $page Page number (optional), default 1.
     * @throws DwApiException EX_INVALID_STRING thrown on invalid or empty query.
     * @throws DwApiException EX_INVALID_INT thrown on invalid page number.
     * @return string JSON result.
     */
    public function SearchArticles($query, $page = 1)
    {
        if (empty($query) or !is_string($query))
        {
            throw new DwApiException('$query', DwApiException::EX_INVALID_STRING);
        }

        if (!$this->IsValidId($page))
        {
            throw new DwApiException('$page', DwApiException::EX_INVALID_INT);
        }

        return $this->GetUrl('/api/articles/search', array ('query' => urlencode($query), 'page' => $page));
    }

    /**
     * Searches members for the given member name.
     *
     * @param string $memberName Member name to search (required).
     * @param int $page Page number (optional), default 1.
     * @throws DwApiException EX_INVALID_STRING thrown on invalid or empty member name.
     * @throws DwApiException EX_INVALID_INT thrown on invalid page number.
     * @return string JSON result.
     */
    public function SearchMembers($memberName, $page = 1)
    {
        if (empty($memberName) or !is_string($memberName))
        {
            throw new DwApiException('$memberName', DwApiException::EX_INVALID_STRING);
        }

        if (!$this->IsValidId($page))
        {
            throw new DwApiException('$page', DwApiException::EX_INVALID_INT);
        }

        return $this->GetUrl('/api/members/search', array ('query' => urlencode($memberName), 'page' => $page));
    }
}
?>