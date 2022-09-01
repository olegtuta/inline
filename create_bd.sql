-- Хост: 127.0.0.1
-- Время создания: Авг 31 2022 г., 18:35
-- Версия сервера: 10.4.22-MariaDB
-- Версия PHP: 8.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Создаем БД `inline`
--

CREATE
DATABASE IF NOT EXISTS `inline` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

-- --------------------------------------------------------

--
-- Структура таблицы `comments`
--

CREATE TABLE `comments` (
                            `id` int(11) NOT NULL COMMENT 'Айди комментария.',
                            `post_id` int(11) NOT NULL COMMENT 'Айди поста, которому принадлежит комментарий.',
                            `user_id` int(11) DEFAULT NULL COMMENT 'Айди юзера, оставившего комментарий.',
                            `body` text DEFAULT NULL COMMENT 'Текст комментария.',
                            `created_at` date NOT NULL DEFAULT current_timestamp() COMMENT 'Дата создания записи.',
                            `updated_at` date NOT NULL DEFAULT current_timestamp() COMMENT 'Дата добавления записи.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Таблица комментариев к постам.';

-- --------------------------------------------------------

--
-- Структура таблицы `posts`
--

CREATE TABLE `posts` (
                         `id` int(11) NOT NULL COMMENT 'Айди поста.',
                         `user_id` int(11) NOT NULL COMMENT 'Айди владельца поста (foreign key).',
                         `title` text DEFAULT NULL COMMENT 'Заголовок поста.',
                         `body` text DEFAULT NULL COMMENT 'Тело поста.',
                         `created_at` date NOT NULL DEFAULT current_timestamp() COMMENT 'Дата создания записи.',
                         `updated_at` date NOT NULL DEFAULT current_timestamp() COMMENT 'Дата обновления записи.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Таблица постов.';

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
                         `id` int(11) NOT NULL COMMENT 'Айди пользователя.',
                         `name` text NOT NULL COMMENT 'Имя пользователя.',
                         `email` text NOT NULL COMMENT 'Email пользователя.',
                         `password` varchar(11) NOT NULL COMMENT 'Пароль пользователя.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `comments`
--
ALTER TABLE `comments`
    ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `posts`
--
ALTER TABLE `posts`
    ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
    ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `comments`
--
ALTER TABLE `comments`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Айди комментария.';

--
-- AUTO_INCREMENT для таблицы `posts`
--
ALTER TABLE `posts`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Айди поста.';

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Айди пользователя.';

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `comments`
--
ALTER TABLE `comments`
    ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `posts`
--
ALTER TABLE `posts`
    ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;
