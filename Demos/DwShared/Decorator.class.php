<?php
namespace DwShared;

class Decorator
{
    public static function ArticleLink($title, $link)
    {
        return "<a href=\"{$link}\">{$title}</a><br/>";
    }

    public static function RssDivWithTitle($id, $title)
    {
        return "<div class=\"rss\" id=\"{$id}\"><h3>{$title}</h3><p></p></div>";
    }
}
?>