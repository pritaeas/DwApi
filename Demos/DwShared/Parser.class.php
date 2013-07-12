<?php
namespace DwShared;

class Parser
{
    public function ParseForumsResult($forumObject, $callback)
    {
        $result = array ();

        if (is_callable($callback)) {
            if (!isset($forumObject->no_access) or !$forumObject->no_access)
            {
                $result[] = call_user_func($callback, $forumObject->id, $forumObject->title);
            }
        }

        if (isset($forumObject->relatives) and isset($forumObject->relatives->children))
        {
            foreach ($forumObject->relatives->children as $subForum)
            {
                $result = array_merge($result, $this->ParseForumsResult($subForum, $callback));
            }
        }

        return $result;
    }

    public function ParseForumFeed($feed, $callback)
    {
        $result = '';
        if ($feed)
        {
            try
            {
                $articles = @new \SimpleXMLElement($feed);
                $postCount = 0;
                if (count($articles->channel->item) > 0)
                {
                    foreach ($articles->channel->item as $article)
                    {
                        if (is_callable($callback)) {
                            $result .= call_user_func($callback, $article->title, $article->link);
                        }

                        $postCount++;
                        if ($postCount == 5)
                        {
                            break;
                        }
                    }
                }
            }
            catch (\Exception $e)
            {
                $result = '<span class="error">' . $e->getMessage() . '</span>';
            }
        }
        return $result;
    }
}
?>