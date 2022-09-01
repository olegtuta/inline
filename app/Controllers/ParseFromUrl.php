<?php

namespace App\Controllers;

class ParseFromUrl
{

    /* Метод для вытаскивания массива из ссылок */
    public static function getArrFromUrl($url): array
    {
        /* Парсим и вставляем в БД всех пользователей из комментов */
        $textContent = file_get_contents($url);
        /* Преобразуем json в массив */
        return json_decode($textContent);
    }

    /* Метод для вывода сообщения в консоль браузера */
    public static function console($data)
    {
        echo "<script>console.log('{$data}');</script>";
    }

    public static function index($db)
    {
        $jsonToArr = self::getArrFromUrl("https://jsonplaceholder.typicode.com/comments");
        /* Проходимся по всему массиву комментов */
        foreach ($jsonToArr as $item) {
            /* Подготовим запрос для безопасного пользования */
            $prepSql = $db->prepare("INSERT INTO users(name, email) VALUES (:name, :email)");
            /* Вставим данные в таблицу users */
            $prepSql->execute([
                "name" => $item->name,
                "email" => $item->email,
            ]);
        }

        /* Парсим и вставляем в БД все посты */
        $jsonToArr = self::getArrFromUrl("https://jsonplaceholder.typicode.com/posts");
        foreach ($jsonToArr as $item) {
            $prepSql = $db->prepare("INSERT INTO posts(id, user_id, title, body) VALUES (:id,:user_id,:title,:body)");
            $prepSql->execute([
                "id" => $item->id,
                "user_id" => $item->userId,
                "title" => $item->title,
                "body" => $item->body
            ]);
        }
        $postsLength = count($jsonToArr);

        //Парсим и вставляем в БД все комменты
        $jsonToArr = self::getArrFromUrl("https://jsonplaceholder.typicode.com/comments");
        foreach ($jsonToArr as $item) {
            //Получаем айди пользователя для текущего коммента
            $prepSql = $db->prepare("SELECT id FROM users WHERE email=:email");
            $prepSql->execute(["email" => $item->email]);
            $data = $prepSql->fetchAll(\PDO::FETCH_ASSOC);
            $id = $data[0]["id"];
            $prepSql = $db->prepare("INSERT INTO comments(id, post_id, user_id, body) VALUES (:id, :post_id, :user_id, :body)");
            $prepSql->execute([
                "id" => $item->id,
                "post_id" => $item->postId,
                "user_id" => $id,
                "body" => $item->body
            ]);
        }
        $commentsLength = count($jsonToArr);

        //Выводим инфу в консоль браузера
        self::console("Загружено {$postsLength} записей и {$commentsLength} комментариев");

    }

}
