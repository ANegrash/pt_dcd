-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Авг 01 2024 г., 17:36
-- Версия сервера: 10.1.37-MariaDB
-- Версия PHP: 8.1.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `i1583376_dcd`
--

-- --------------------------------------------------------

--
-- Структура таблицы `answers`
--

CREATE TABLE `answers` (
  `id_answer` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `type` enum('landing','banner','smm','3d','product','branding','copywrite','illustration','other') COLLATE utf8_unicode_ci NOT NULL,
  `state` enum('IN_PROGRESS','FINISHED','CANCELED') COLLATE utf8_unicode_ci NOT NULL,
  `question_number` int(11) NOT NULL,
  `q0` text COLLATE utf8_unicode_ci NOT NULL,
  `q1` text COLLATE utf8_unicode_ci NOT NULL,
  `q2` text COLLATE utf8_unicode_ci NOT NULL,
  `q3` text COLLATE utf8_unicode_ci NOT NULL,
  `q4` text COLLATE utf8_unicode_ci NOT NULL,
  `q5` text COLLATE utf8_unicode_ci NOT NULL,
  `q6` text COLLATE utf8_unicode_ci NOT NULL,
  `q7` text COLLATE utf8_unicode_ci NOT NULL,
  `q8` text COLLATE utf8_unicode_ci NOT NULL,
  `q9` text COLLATE utf8_unicode_ci NOT NULL,
  `q10` text COLLATE utf8_unicode_ci NOT NULL,
  `q11` text COLLATE utf8_unicode_ci NOT NULL,
  `q12` text COLLATE utf8_unicode_ci NOT NULL,
  `q13` text COLLATE utf8_unicode_ci NOT NULL,
  `q14` text COLLATE utf8_unicode_ci NOT NULL,
  `q15` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `answers`
--

INSERT INTO `answers` (`id_answer`, `id_user`, `type`, `state`, `question_number`, `q0`, `q1`, `q2`, `q3`, `q4`, `q5`, `q6`, `q7`, `q8`, `q9`, `q10`, `q11`, `q12`, `q13`, `q14`, `q15`) VALUES
(1, 828211050, 'landing', 'FINISHED', 12, '', 'Ответ на лендинг', 'Ответ 2', 'Ответ 3', 'Ответ 4', 'Ответ 5', 'Ответ 6', 'Ответ 7', 'Ответ 8', 'Ответ 9', 'Ответ 10', 'Ответ 11', 'Ответ 12', '', '', '');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`id_answer`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `answers`
--
ALTER TABLE `answers`
  MODIFY `id_answer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
