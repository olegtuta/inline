<?php

namespace App\Controllers;

class RetrievePosts
{

    /* Получаем все посты, в которых комментарии
     * содержат искомое слово */
    public function index($needle, $db): array
    {
        $needle = trim($needle);
        $prepSql = $db->prepare("SELECT posts.id as post_id, comments.id as comment_id, posts.title, comments.body FROM comments JOIN posts ON comments.post_id = posts.id WHERE comments.body LIKE :needle;");
        $prepSql->execute(["needle" => "%{$needle}%"]);
        return $prepSql->fetchAll(\PDO::FETCH_ASSOC);
    }

}
