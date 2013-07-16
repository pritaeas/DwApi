<?php
namespace DwShared;

class Decorator
{
    public static function ArticleDiv($article, $forumList, $highlight = false)
    {
        $date = date('Y-m-d H:i:s', $article->first_post->timestamp);

        if ($highlight)
        {
            $result = "<div class=\"article highlight\" id=\"{$article->id}\">";
        }
        else
        {
            $result = "<div class=\"article\" id=\"{$article->id}\">";
        }

        $title = htmlspecialchars($article->title);
        $result .= "<h5>{$title}</h5>";
        $result .= '<p>';

        $username = htmlspecialchars($article->first_post->poster->username);
        $result .= "<a href=\"{$article->uri}\">Post</a> from {$date} by <span class=\"op\">{$username}</span> ";

        $title = htmlspecialchars($forumList[$article->forum->id]->title);
        $result .= "in <a href=\"{$forumList[$article->forum->id]->uri}\">{$title}</a><br/>";

        if ($article->first_post->id != $article->last_post->id)
        {
            $date = date('Y-m-d H:i:s', $article->last_post->timestamp);
            $username = htmlspecialchars($article->last_post->poster->username);
            $result .= "<span id=\"{$article->last_post->id}\" class=\"post\"><a href=\"http://www.daniweb.com/posts/jump/{$article->last_post->id}\">Last reply</a> {$date} by <span class=\"lr\">{$username}</span></span>";
        }
        else
        {
            $result .= "<span id=\"{$article->first_post->id}\" class=\"post\"><span class=\"lr\"></span></span>";
        }

        $result .= '</p>';
        $result .= '</div>';

        return $result;
    }

    public static function ArticleLink($title, $link)
    {
        $title = htmlspecialchars($title);
        return "<a href=\"{$link}\">{$title}</a><br/>";
    }

    public static function LastReply($postId, $timestamp)
    {
        $date = date('Y-m-d H:i:s', $timestamp);
        return "<a href=\"http://www.daniweb.com/posts/jump/{$postId}\">Last reply</a> {$date} by <span class=\"lr\"></span>";
    }

    public static function RssDivWithTitle($id, $title)
    {
        return "<div class=\"rss\" id=\"{$id}\"><h3>{$title}</h3><p></p></div>";
    }
}
?>