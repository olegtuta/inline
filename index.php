<?php

use App\Controllers\Db;
use App\Controllers\ParseFromUrl;
use App\Controllers\RetrievePosts;

include "app/Controllers/Db.php";
include "app/Controllers/ParseFromUrl.php";
include "app/Controllers/RetrievePosts.php";

/* Подключаемся к базе данных */
$db = Db::connect();

/* Если скрипт запускается впервые */
if (!$db) {
    /* Тогда создадим схему БД */
    $db = Db::makeSchema();
    /* Затем спарсим все данные из ссылок и заполними ими нашу БД */
    ParseFromUrl::index($db);
}

/* Если есть get запрос на поиск по слову */
if (isset($_GET["search"])) {
    $posts = new RetrievePosts();
    echo json_encode($posts->index($_GET["search"], $db));
} else {
    header("Location: views/index.html");
}


