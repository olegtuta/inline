<?php

namespace App\Controllers;

class Db
{

    /* Метод для подключения к БД, возвращает либо объект PDO
     * с содинением к БД либо false, сигнализирующий о том,
     * что скрипт запускается впервые и такой БД еще не создано
     * */
    public static function connect()
    {

        /* Данные для подключения БД берем из файла config/database.php */
        $config = include("config/database.php");

        try {
            $db = new \PDO("mysql:host={$config['host']};dbname={$config['dbname']}", $config["user"], $config["password"]);
        } catch (\PDOException $e) {
            /* Если нет еще такой базы, то установим указатель firstTime на true */
            if (stripos($e, "Unknown database")) {
                return false;
            } else {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
        }

        return $db;

    }

    /* Метод для создания схемы базы данных.
    * Возвращает новое соединение с БД
    */
    public static function makeSchema(): Object
    {

        $config = include("config/database.php");
        $db = new \PDO("mysql:host={$config['host']}", $config["user"], $config["password"]);
        //Создаем БД из конфига настроек
        $db->query("CREATE DATABASE IF NOT EXISTS `{$config['dbname']}` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;");
        //Подключаемся к созданной БД
        $db = new \PDO("mysql:host={$config['host']};dbname={$config['dbname']}", $config["user"], $config["password"]);
        //Создаем схему БД, sql-запросы берем из файла create_bd.sql
        $db->query(file_get_contents("create_bd.sql"));

        return $db;

    }

}
