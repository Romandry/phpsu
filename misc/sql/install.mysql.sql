-- phpMyAdmin SQL Dump
-- version 3.3.7deb7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 08, 2015 at 02:47 AM
-- Server version: 5.1.73
-- PHP Version: 5.3.3-7+squeeze19

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT=0;
START TRANSACTION;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `phpsu`
--

-- --------------------------------------------------------

--
-- Table structure for table `forum_forums`
--

DROP TABLE IF EXISTS `forum_forums`;
CREATE TABLE IF NOT EXISTS `forum_forums` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sort` bigint(20) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `forum_forums`
--

INSERT INTO `forum_forums` (`id`, `sort`, `title`, `description`) VALUES
(1, 1, 'PHP', 'Обсуждение различных вопросов программирования на PHP'),
(2, 2, 'Клиентская разработка', 'Вопросы клиентской [браузерной] разработки'),
(3, 3, 'Серверное администрирование', 'Вопросы связанные с установкой, настройкой и администрированием  серверного ПО'),
(4, 4, 'Разное', 'Новости, оповещения, ссылки на полезные ресурсы и литературу'),
(5, 5, ' Объявления', 'Предложение услуг, поиск исполнителей и прочие объявления');

-- --------------------------------------------------------

--
-- Table structure for table `forum_forums_stat`
--

DROP TABLE IF EXISTS `forum_forums_stat`;
CREATE TABLE IF NOT EXISTS `forum_forums_stat` (
  `forum_id` bigint(20) unsigned NOT NULL,
  `subforums_count` bigint(20) unsigned NOT NULL,
  UNIQUE KEY `forum_id` (`forum_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `forum_forums_stat`
--

INSERT INTO `forum_forums_stat` (`forum_id`, `subforums_count`) VALUES
(1, 5),
(2, 3),
(3, 0),
(4, 0),
(5, 0);

-- --------------------------------------------------------

--
-- Table structure for table `forum_members`
--

DROP TABLE IF EXISTS `forum_members`;
CREATE TABLE IF NOT EXISTS `forum_members` (
  `author_id` bigint(20) unsigned NOT NULL,
  `posts_count` bigint(20) unsigned NOT NULL DEFAULT '0',
  `topics_per_page` tinyint(2) unsigned NOT NULL DEFAULT '20',
  `posts_per_page` tinyint(2) unsigned NOT NULL DEFAULT '10',
  UNIQUE KEY `author_id` (`author_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `forum_members`
--

INSERT INTO `forum_members` (`author_id`, `posts_count`, `topics_per_page`, `posts_per_page`) VALUES
(9, 2, 20, 10),
(11, 11, 20, 10),
(14, 14, 20, 10),
(16, 9, 20, 10),
(17, 4, 20, 10);

-- --------------------------------------------------------

--
-- Table structure for table `forum_posts`
--

DROP TABLE IF EXISTS `forum_posts`;
CREATE TABLE IF NOT EXISTS `forum_posts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `topic_id` bigint(20) unsigned NOT NULL,
  `topic_start` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `authored_by` bigint(20) unsigned NOT NULL,
  `edited_by` bigint(20) unsigned DEFAULT NULL,
  `moderated_by` bigint(20) unsigned DEFAULT NULL,
  `creation_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `post_text` text NOT NULL,
  `post_html` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=41 ;

--
-- Dumping data for table `forum_posts`
--

INSERT INTO `forum_posts` (`id`, `topic_id`, `topic_start`, `authored_by`, `edited_by`, `moderated_by`, `creation_date`, `last_modified`, `post_text`, `post_html`) VALUES
(1, 1, 1, 14, NULL, 14, '2014-12-05 13:29:40', '2014-12-05 13:29:40', 'Жопка!\r\n\r\n ------------------ 13:31:08 ------------------ \r\n\r\nВот она [img]http://apikabu.ru/img_n/2012-08_5/cnf.jpg[/img]', 'Жопка!<br />\r\n<br />\r\n ------------------ 13:31:08 ------------------ <br />\r\n<br />\r\nВот она  <p>\r\n<a class="colorbox" href="http://apikabu.ru/img_n/2012-08_5/cnf.jpg"><img src="http://apikabu.ru/img_n/2012-08_5/cnf.jpg" /></a>\r\n\r\n<a class="colorbox" href="http://062012.imgbb.ru/8/4/c/84c3279bf6baeff86ead375cdece8a12.jpg"><img src="http://062012.imgbb.ru/8/4/c/84c3279bf6baeff86ead375cdece8a12.jpg" /></a>\r\n\r\n<a class="colorbox" href="http://cs10340.vk.me/u71959743/-14/x_1c0ee32e.jpg"><img src="http://cs10340.vk.me/u71959743/-14/x_1c0ee32e.jpg" /></a></p> '),
(2, 1, 0, 16, NULL, 16, '2014-12-05 13:34:52', '2014-12-05 13:34:52', 'Норм жопа. Ябывдул', 'Норм жопа. Ябывдул'),
(3, 2, 1, 16, NULL, 16, '2014-12-05 13:36:20', '2014-12-05 13:36:20', 'С++ сила', 'С++ сила'),
(4, 3, 1, 16, NULL, 16, '2014-12-05 13:37:31', '2014-12-05 13:37:31', 'Куплю самотык недорого. Бу. Диаметр 7-12 см', 'Куплю самотык недорого. Бу. Диаметр 7-12 см'),
(5, 2, 0, 14, NULL, 14, '2014-12-05 13:38:21', '2014-12-05 13:38:21', 'Жрать не просила', 'Жрать не просила'),
(6, 4, 1, 9, NULL, 9, '2014-12-05 13:39:56', '2014-12-05 13:39:56', 'Как, когда и где?', 'Как, когда и где?'),
(7, 4, 0, 11, NULL, 11, '2014-12-05 13:41:02', '2014-12-05 13:41:02', 'Ежедневно с 9 до 11 кроме выходных и праздничных', 'Ежедневно с 9 до 11 кроме выходных и праздничных'),
(8, 4, 0, 14, NULL, 14, '2014-12-05 13:41:37', '2014-12-05 13:41:37', 'Ормотеребоньки\r\n\r\n ------------------ 13:42:56 ------------------ \r\n\r\n[b]test9[/b] это биби джонс на аватарке?', 'Ормотеребоньки<br />\r\n<br />\r\n ------------------ 13:42:56 ------------------ <br />\r\n<br />\r\n <strong>test9</strong>  это биби джонс на аватарке?'),
(9, 3, 0, 11, NULL, 11, '2014-12-05 13:42:18', '2014-12-05 13:42:18', 'Продам недорого. Пользовались втроем', 'Продам недорого. Пользовались втроем'),
(10, 5, 1, 11, NULL, 11, '2014-12-05 13:43:05', '2014-12-05 13:43:05', 'Ыть', 'Ыть'),
(11, 6, 1, 11, NULL, 11, '2014-12-05 13:43:33', '2014-12-05 13:43:33', 'Ага', 'Ага'),
(12, 6, 0, 14, NULL, 14, '2014-12-05 13:44:43', '2014-12-05 13:44:43', 'Нупиздецнахуйвсесосцы', 'Нупиздецнахуйвсесосцы'),
(13, 7, 1, 14, NULL, 14, '2014-12-05 13:45:53', '2014-12-05 13:45:53', 'Это биби джонс на аватарке? Яб пофапал.', 'Это биби джонс на аватарке? Яб пофапал.'),
(14, 7, 0, 11, NULL, 11, '2014-12-05 13:47:49', '2014-12-05 13:47:49', 'Говно вопрос', 'Говно вопрос'),
(15, 8, 1, 11, NULL, 11, '2014-12-05 13:49:12', '2014-12-05 13:49:12', 'Не правильно работает счетчик сообщений в топе последних\r\nНужно сделать -1. Если автор только создал тему, то будет 0 сообщений, а сейчас 1 сообщение\r\n\r\n ------------------ 13:51:24 ------------------ \r\n\r\nну более привычно как на форуме. типа первое сообщение есть вопрос, а не ответ', 'Не правильно работает счетчик сообщений в топе последних<br />\r\nНужно сделать -1. Если автор только создал тему, то будет 0 сообщений, а сейчас 1 сообщение<br />\r\n<br />\r\n ------------------ 13:51:24 ------------------ <br />\r\n<br />\r\nну более привычно как на форуме. типа первое сообщение есть вопрос, а не ответ'),
(16, 8, 0, 14, NULL, 14, '2014-12-05 13:50:38', '2014-12-05 13:50:38', 'Ну, в целом корректное замечание. А чем старт топика не есть сообщение?', 'Ну, в целом корректное замечание. А чем старт топика не есть сообщение?'),
(17, 9, 1, 11, NULL, 11, '2014-12-05 13:52:00', '2014-12-05 13:52:00', 'Нужно сабмитить форму по контрол + энтер, а то не удобно клацать мышкой &quot;Отправить месагу&quot;\r\n\r\n ------------------ 13:53:46 ------------------ \r\n\r\nНихуя себе перделки. эТо юзабилити\r\n\r\n ------------------ 13:54:03 ------------------ \r\n\r\nэ, а че маты не отправляет?\r\n\r\n ------------------ 13:54:27 ------------------ \r\n\r\nглюк, щас, должно было добавить новый пост, а оно добавило к моему', 'Нужно сабмитить форму по контрол + энтер, а то не удобно клацать мышкой &quot;Отправить месагу&quot;<br />\r\n<br />\r\n ------------------ 13:53:46 ------------------ <br />\r\n<br />\r\nНихуя себе перделки. эТо юзабилити<br />\r\n<br />\r\n ------------------ 13:54:03 ------------------ <br />\r\n<br />\r\nэ, а че маты не отправляет?<br />\r\n<br />\r\n ------------------ 13:54:27 ------------------ <br />\r\n<br />\r\nглюк, щас, должно было добавить новый пост, а оно добавило к моему'),
(18, 9, 0, 14, NULL, 14, '2014-12-05 13:52:58', '2014-12-05 13:52:58', 'Дельное замечание. Ну это уже перделки.', 'Дельное замечание. Ну это уже перделки.'),
(19, 10, 1, 14, NULL, 14, '2014-12-05 13:55:20', '2014-12-05 13:55:20', 'Если вы искали тему &quot;[link=http://palmali.deep-host.ru/Форум/topic?id=8]Счетчик в [b][size=14]т[/size][/b]опе[/link]&quot; то попали не туда.\r\n\r\nЭта тема именно про счетчик в [b][size=14]п[/size][/b]опе!', 'Если вы искали тему &quot; <a target="_blank" href="http://palmali.deep-host.ru/Форум/topic?id=8">Счетчик в  <strong> <span style="line-height:14pt; font-size:14pt;">т</span> </strong> опе</a> &quot; то попали не туда.<br />\r\n<br />\r\nЭта тема именно про счетчик в  <strong> <span style="line-height:14pt; font-size:14pt;">п</span> </strong> опе!'),
(20, 10, 0, 11, NULL, 11, '2014-12-05 13:56:04', '2014-12-05 13:56:04', 'Вспомнился анек про &quot;а ручки то вот они&quot;', 'Вспомнился анек про &quot;а ручки то вот они&quot;'),
(21, 11, 1, 11, NULL, 11, '2014-12-05 13:57:33', '2014-12-05 13:57:49', 'Если в теме уже есть сообщение то должен создасться новый пост, а оно добавляет к существующему\r\n[link]http://palmali.deep-host.ru/%D0%A4%D0%BE%D1%80%D1%83%D0%BC/topic?id=9#post-17[/link]', 'Если в теме уже есть сообщение то должен создасться новый пост, а оно добавляет к существующему<br />\r\n <a target="_blank" href="http://palmali.deep-host.ru/%D0%A4%D0%BE%D1%80%D1%83%D0%BC/topic?id=9#post-17">http://palmali.deep-host.ru/%D0%A4%D0%BE%D1%80%D1%83%D0%BC/topic?id=9#post-17</a> '),
(22, 11, 0, 14, NULL, 14, '2014-12-05 13:58:33', '2014-12-05 13:58:33', 'Да, на пхпму так же. Если подождешь (вроде) пять минут (не помню точное время в конфиге), то не добпавит в текущее, а создаст новое сообщение.\r\n\r\n ------------------ 13:59:22 ------------------ \r\n\r\nА, понял в чем касяк! Да, исправить надо.\r\n\r\n ------------------ 13:59:38 ------------------ \r\n\r\nИли вообще отрубить это гавно\r\n\r\n ------------------ 14:07:16 ------------------ \r\n\r\nБля, кажца тут вообще старая версия, не та которая на официальном сайте, надо будет гит пулл тут сделать вечерком. Я ж вроде исправлял этот касяк с разбивкой по времени.', 'Да, на пхпму так же. Если подождешь (вроде) пять минут (не помню точное время в конфиге), то не добпавит в текущее, а создаст новое сообщение.<br />\r\n<br />\r\n ------------------ 13:59:22 ------------------ <br />\r\n<br />\r\nА, понял в чем касяк! Да, исправить надо.<br />\r\n<br />\r\n ------------------ 13:59:38 ------------------ <br />\r\n<br />\r\nИли вообще отрубить это гавно<br />\r\n<br />\r\n ------------------ 14:07:16 ------------------ <br />\r\n<br />\r\nБля, кажца тут вообще старая версия, не та которая на официальном сайте, надо будет гит пулл тут сделать вечерком. Я ж вроде исправлял этот касяк с разбивкой по времени.'),
(23, 11, 0, 16, NULL, 16, '2014-12-05 13:59:40', '2014-12-05 13:59:40', 'ну это хуйня яполная, так не юзабельно', 'ну это хуйня яполная, так не юзабельно'),
(24, 6, 0, 16, NULL, 16, '2014-12-05 14:00:50', '2014-12-05 14:00:50', 'Я сосец!', 'Я сосец!'),
(25, 6, 0, 14, NULL, 14, '2014-12-05 14:01:11', '2014-12-05 14:01:11', 'Оппа, фап-фап-фап!!!', 'Оппа, фап-фап-фап!!!'),
(26, 6, 0, 11, NULL, 11, '2014-12-05 14:01:48', '2014-12-05 14:01:48', 'Гавномесы', 'Гавномесы'),
(27, 4, 0, 11, NULL, 11, '2014-12-05 14:02:17', '2014-12-05 14:02:17', 'Пофапаем?\r\n\r\n ------------------ 14:07:56 ------------------ \r\n\r\nНу пошли', 'Пофапаем?<br />\r\n<br />\r\n ------------------ 14:07:56 ------------------ <br />\r\n<br />\r\nНу пошли'),
(28, 4, 0, 16, NULL, 16, '2014-12-05 14:02:32', '2014-12-05 14:02:32', 'Я с вами', 'Я с вами'),
(29, 12, 1, 14, NULL, 14, '2014-12-05 14:03:22', '2014-12-05 14:03:22', 'Может тут убрать все копирайты палмали и лишнее гавно и нагнать весь пхпму для теста?\r\nВыложить сорцы на гитхаб и все такое..', 'Может тут убрать все копирайты палмали и лишнее гавно и нагнать весь пхпму для теста?<br />\r\nВыложить сорцы на гитхаб и все такое..'),
(30, 12, 0, 16, NULL, 16, '2014-12-05 14:03:54', '2014-12-05 14:03:54', 'да, вместо говна палмали запилить сюды документацию', 'да, вместо говна палмали запилить сюды документацию'),
(31, 5, 0, 16, NULL, 16, '2014-12-05 14:05:30', '2014-12-05 14:05:30', 'Харе уже', 'Харе уже'),
(32, 4, 0, 9, NULL, 9, '2014-12-05 14:14:48', '2014-12-05 14:14:48', 'Настало... время... порно!!!', 'Настало... время... порно!!!'),
(33, 4, 0, 16, NULL, 16, '2014-12-05 14:17:10', '2014-12-05 14:17:10', 'Eeee', 'Eeee'),
(34, 13, 1, 17, NULL, 17, '2014-12-06 14:36:53', '2014-12-06 14:36:53', 'Куплю ИБП, входное питание AC 220V, выход DC 24V с долговременным током от 2А, обязательно поддержка использования стандартных батарей.\r\nРоссия, Санкт-Петербург.', 'Куплю ИБП, входное питание AC 220V, выход DC 24V с долговременным током от 2А, обязательно поддержка использования стандартных батарей.<br />\r\nРоссия, Санкт-Петербург.'),
(35, 14, 1, 17, NULL, 17, '2014-12-06 14:39:33', '2014-12-06 14:53:23', 'bug check [link=javascript:alert( document.cookie )]check[/link]\r\n\r\n ------------------ 14:44:51 ------------------ \r\n\r\nБаг подтверждён.\r\nopera 12.16, после создания темы нет переадресации со страницы &quot;тема создана&quot;\r\nПредположительная причина - редирект средствами deprecated meta-тега на эту же самую страницу.', 'bug check  <a target="_blank" href="javascript:alert( document.cookie )">check</a> <br />\r\n<br />\r\n ------------------ 14:44:51 ------------------ <br />\r\n<br />\r\nБаг подтверждён.<br />\r\nopera 12.16, после создания темы нет переадресации со страницы &quot;тема создана&quot;<br />\r\nПредположительная причина - редирект средствами deprecated meta-тега на эту же самую страницу.'),
(36, 14, 0, 17, NULL, 17, '2014-12-06 14:46:27', '2014-12-06 14:46:27', 'Добавление и редактирование сообщения - аналогично', 'Добавление и редактирование сообщения - аналогично'),
(37, 14, 0, 14, NULL, 14, '2014-12-06 15:17:16', '2014-12-06 15:17:16', 'после создания темы нет переадресации со страницы &quot;тема создана&quot;\r\nПредположительная причина - редирект средствами deprecated meta-тега на эту же самую страницу. \r\n\r\nОго, как интересно. метатег депрекейтед, а заголовок метарефреш не поддерживается всеми браузерами. Браво!\r\n\r\n ------------------ 15:23:44 ------------------ \r\n\r\nА насчет document.cookie да, средства фильтрации имеются, но тут видимо не подключены.\r\nВобщем, надо выкинуть лишнее, привести в порядок и положить сорцы на гитхаб.', 'после создания темы нет переадресации со страницы &quot;тема создана&quot;<br />\r\nПредположительная причина - редирект средствами deprecated meta-тега на эту же самую страницу. <br />\r\n<br />\r\nОго, как интересно. метатег депрекейтед, а заголовок метарефреш не поддерживается всеми браузерами. Браво!<br />\r\n<br />\r\n ------------------ 15:23:44 ------------------ <br />\r\n<br />\r\nА насчет document.cookie да, средства фильтрации имеются, но тут видимо не подключены.<br />\r\nВобщем, надо выкинуть лишнее, привести в порядок и положить сорцы на гитхаб.'),
(38, 14, 0, 14, NULL, 14, '2014-12-06 15:32:23', '2014-12-06 15:32:23', 'Ну или у меня есть личный гит-репозиторий на который ходят только по ключикам.', 'Ну или у меня есть личный гит-репозиторий на который ходят только по ключикам.'),
(39, 14, 0, 17, NULL, 17, '2014-12-06 17:33:44', '2014-12-06 17:33:44', 'Сарказм неуместен.\r\nТы в известной степени консервативен (gnome2), я тоже (opera 12).\r\nДа, метарефреш deprecated. Нет, опера его отлично умеет, имеющийся же форум работает. А вот редирект на эту же самую страницу с якорем очевидно воспринимает как только переход по якорю. Не вижу причин, почему бы точно так же не вести себя другим браузерам.', 'Сарказм неуместен.<br />\r\nТы в известной степени консервативен (gnome2), я тоже (opera 12).<br />\r\nДа, метарефреш deprecated. Нет, опера его отлично умеет, имеющийся же форум работает. А вот редирект на эту же самую страницу с якорем очевидно воспринимает как только переход по якорю. Не вижу причин, почему бы точно так же не вести себя другим браузерам.'),
(40, 14, 0, 14, NULL, 14, '2014-12-06 18:38:05', '2014-12-06 18:39:16', 'Сарказм не относился к тебе лично. Он был направлен в сторону тех кто задепрекейтил метатег, и тех разрабов клиентов, которые забили на новый хттп 1.1 заголовок метарефреша', 'Сарказм не относился к тебе лично. Он был направлен в сторону тех кто задепрекейтил метатег, и тех разрабов клиентов, которые забили на новый хттп 1.1 заголовок метарефреша');

-- --------------------------------------------------------

--
-- Table structure for table `forum_subforums`
--

DROP TABLE IF EXISTS `forum_subforums`;
CREATE TABLE IF NOT EXISTS `forum_subforums` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sort` bigint(20) unsigned NOT NULL DEFAULT '0',
  `forum_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `forum_subforums`
--

INSERT INTO `forum_subforums` (`id`, `sort`, `forum_id`, `title`, `description`) VALUES
(1, 1, 1, 'Программирование на PHP', 'Обсуждение различных вопросов программирования на PHP, включая общие вопросы, методики, ООП, работу с графикой, протоколами и др.'),
(2, 2, 1, 'Вопросы новичков', 'Если Вы - начинающий, и не знаете, в какую категорию задать вопрос - Вам сюда.'),
(3, 3, 1, 'Объектно-ориентированное программирование', 'ООП и все вопросы, принципы, парадигмы, задачи и их решения'),
(4, 1, 2, 'JavaScript & VBScript', 'Все вопросы связаные с JavaScript & VBScript'),
(5, 2, 2, 'HTML, Дизайн & CSS', 'Все вопросы по дизайнерской части'),
(6, 3, 2, 'Программное обеспечение', 'ПО для разработчиков [Flash, Photoshop, PHPExpertEditor, FrontPage.. Обсуждение и вопросы]'),
(7, 4, 1, 'Работа с сетью', 'Сокеты, curl, почта'),
(8, 5, 1, 'HTTP и PHP', 'AJAX, сессии, uploads, авторизация');

-- --------------------------------------------------------

--
-- Table structure for table `forum_subforums_stat`
--

DROP TABLE IF EXISTS `forum_subforums_stat`;
CREATE TABLE IF NOT EXISTS `forum_subforums_stat` (
  `subforum_id` bigint(20) unsigned NOT NULL,
  `topics_count` bigint(20) unsigned NOT NULL,
  `posts_count` bigint(20) unsigned NOT NULL,
  `last_post_id` bigint(20) unsigned NOT NULL,
  UNIQUE KEY `subforum_id` (`subforum_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `forum_subforums_stat`
--

INSERT INTO `forum_subforums_stat` (`subforum_id`, `topics_count`, `posts_count`, `last_post_id`) VALUES
(1, 2, 9, 33),
(2, 1, 2, 5),
(3, 4, 9, 23),
(4, 0, 0, 0),
(5, 0, 0, 0),
(6, 2, 3, 34),
(7, 4, 15, 40),
(8, 1, 2, 31);

-- --------------------------------------------------------

--
-- Table structure for table `forum_topics`
--

DROP TABLE IF EXISTS `forum_topics`;
CREATE TABLE IF NOT EXISTS `forum_topics` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `subforum_id` bigint(20) unsigned NOT NULL,
  `authored_by` bigint(20) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `creation_date` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `is_locked` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_important` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_closed` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `is_closed` (`is_closed`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `forum_topics`
--

INSERT INTO `forum_topics` (`id`, `subforum_id`, `authored_by`, `title`, `description`, `creation_date`, `last_modified`, `is_locked`, `is_important`, `is_closed`) VALUES
(1, 1, 14, 'Тестовая тема', 'Это описание', '2014-12-05 13:29:40', '2014-12-05 13:34:52', 0, 0, 0),
(2, 2, 16, 'Всё плохо', 'танунах', '2014-12-05 13:36:20', '2014-12-05 13:38:21', 0, 0, 0),
(3, 6, 16, 'Куплю Фаллоимитатор бу', '', '2014-12-05 13:37:31', '2014-12-05 13:42:18', 0, 0, 0),
(4, 1, 9, 'Орма и теребонькаенье', '', '2014-12-05 13:39:55', '2014-12-05 14:17:10', 0, 0, 0),
(5, 8, 11, 'Орма снова оттеребонькал', '', '2014-12-05 13:43:05', '2014-12-05 14:05:30', 0, 0, 0),
(6, 7, 11, 'Вы все сосцы', '', '2014-12-05 13:43:32', '2014-12-05 14:01:48', 0, 0, 0),
(7, 7, 14, 'К пользователю test9', 'Личный публичный вопрос', '2014-12-05 13:45:53', '2014-12-05 13:47:49', 0, 1, 0),
(8, 3, 11, 'Счетчик в топе', '', '2014-12-05 13:49:12', '2014-12-05 13:51:24', 1, 0, 0),
(9, 3, 11, 'Добавить ответ по контрол+ентер', '', '2014-12-05 13:52:00', '2014-12-05 13:54:27', 0, 0, 0),
(10, 3, 14, 'Счетчик в попе', '', '2014-12-05 13:55:20', '2014-12-05 13:56:04', 0, 0, 0),
(11, 3, 11, 'Сообщение не добавляется как новое', '', '2014-12-05 13:57:33', '2014-12-05 14:07:16', 1, 1, 0),
(12, 7, 14, 'Можт сюда нагнать весь пхпсу?', '', '2014-12-05 14:03:22', '2014-12-05 14:03:54', 0, 0, 0),
(13, 6, 17, 'UPS DC 24V', '', '2014-12-06 14:36:53', '2014-12-06 14:36:53', 0, 0, 0),
(14, 7, 17, 'bug check', '', '2014-12-06 14:39:33', '2014-12-06 18:38:05', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `forum_topics_stat`
--

DROP TABLE IF EXISTS `forum_topics_stat`;
CREATE TABLE IF NOT EXISTS `forum_topics_stat` (
  `topic_id` bigint(20) unsigned NOT NULL,
  `posts_count` bigint(20) unsigned NOT NULL,
  `views_count` bigint(20) unsigned NOT NULL DEFAULT '0',
  `last_post_id` bigint(20) unsigned NOT NULL,
  UNIQUE KEY `topic_id` (`topic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `forum_topics_stat`
--

INSERT INTO `forum_topics_stat` (`topic_id`, `posts_count`, `views_count`, `last_post_id`) VALUES
(1, 2, 51, 2),
(2, 2, 48, 5),
(3, 2, 84, 9),
(4, 7, 1191, 33),
(5, 2, 72, 31),
(6, 5, 358, 26),
(7, 2, 51, 14),
(8, 2, 56, 16),
(9, 2, 308, 18),
(10, 2, 53, 20),
(11, 3, 57, 23),
(12, 2, 65, 30),
(13, 1, 53, 34),
(14, 6, 117, 40);

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
CREATE TABLE IF NOT EXISTS `groups` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `is_protected` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `priority` smallint(4) unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `is_protected`, `priority`, `name`) VALUES
(1, 1, 1001, 'Guest'),
(2, 1, 1000, 'User'),
(3, 1, 0, 'CLI (cron)'),
(4, 1, 0, 'Root');

-- --------------------------------------------------------

--
-- Table structure for table `groups_permissions`
--

DROP TABLE IF EXISTS `groups_permissions`;
CREATE TABLE IF NOT EXISTS `groups_permissions` (
  `group_id` smallint(5) unsigned NOT NULL,
  `permission_id` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`group_id`,`permission_id`),
  UNIQUE KEY `pk_revert` (`permission_id`,`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `groups_permissions`
--


-- --------------------------------------------------------

--
-- Table structure for table `members`
--

DROP TABLE IF EXISTS `members`;
CREATE TABLE IF NOT EXISTS `members` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` smallint(5) unsigned NOT NULL,
  `cookie` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `email` varchar(255) NOT NULL,
  `login` varchar(255) NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `time_zone` char(6) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `creation_date` datetime NOT NULL,
  `last_ip` binary(16) NOT NULL COMMENT 'use php.net/inet_pton',
  `last_visit` datetime NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `activation_hash` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `avatar` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `login` (`login`),
  KEY `activation_hash` (`activation_hash`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `group_id`, `cookie`, `email`, `login`, `password`, `time_zone`, `creation_date`, `last_ip`, `last_visit`, `status`, `activation_hash`, `avatar`) VALUES
(2, 2, 'd5762eca435f5c89805498ff2f9f2204', 'admin@mail.ru', 'admin', '21232f297a57a5a743894a0e4a801fc3', '', '2014-03-09 07:43:03', '194.84.234.27\0\0\0', '2014-04-14 12:00:26', 0, '', NULL),
(7, 2, 'dcec1c5c9c5616ff587927e96b6ec7df', 'test5@mail.ru', 'test5', 'c4ca4238a0b923820dcc509a6f75849b', '+04:00', '2014-03-29 12:34:38', '85.26.164.255\0\0\0', '2014-12-05 13:58:52', 0, '', 'bbca5484eab6f926c094adcec51af96b.jpg'),
(9, 2, '92ec2e4bb6b311d90abd400134d52f0d', 'test7@mail.ru', 'test7', 'c4ca4238a0b923820dcc509a6f75849b', '+04:00', '2014-03-31 16:45:01', '192.168.95.100\0\0', '2014-12-05 14:15:06', 0, '', 'b9dddf1a55f7c608fe3408f1ae82110a.jpg'),
(10, 2, '1072771cde958afcc7d07753b713f5d3', 'test8@mail.ru', 'test8', 'c4ca4238a0b923820dcc509a6f75849b', '', '2014-04-07 14:19:46', '0.0.0.0\0\0\0\0\0\0\0\0\0', '0000-00-00 00:00:00', 3, '', NULL),
(11, 2, 'c9853c0a618ce542a5a9d24b7a327dd0', 'test9@mail.ru', 'test9', 'c4ca4238a0b923820dcc509a6f75849b', '+04:00', '2014-04-07 14:21:55', '31.172.138.37\0\0\0', '2014-12-05 16:56:56', 0, '', '247a58b17fb6ab57dd5c999f6ada48d4.jpg'),
(12, 2, '211e4e226e7f9eeb219e54aaad0f02a7', 'test10@mail.ru', 'test10', 'c4ca4238a0b923820dcc509a6f75849b', '', '2014-04-07 14:23:16', '0.0.0.0\0\0\0\0\0\0\0\0\0', '0000-00-00 00:00:00', 0, '', NULL),
(13, 2, 'a147864d0e9e95b5296a3c2554020e8b', 'serg@mail.ru', 'serg', 'e10adc3949ba59abbe56e057f20f883e', '', '2014-04-14 10:59:59', '194.84.234.27\0\0\0', '2014-04-14 14:38:36', 0, '', NULL),
(14, 2, '1893b170e5085b53b4fd8d02b1071722', 'deepvarvar@mail.ru', 'deep', 'e10adc3949ba59abbe56e057f20f883e', '+04:00', '2014-12-05 13:24:59', '194.226.176.200\0', '2014-12-08 11:32:14', 0, '', '40aa405fd1bc20d61ae8364bf4da8f44.jpg'),
(15, 2, '529bd57d2460022195767acbc1e7b467', 'bibi@pornlab.com', 'XyeIIJIeT', 'e10adc3949ba59abbe56e057f20f883e', '', '2014-12-05 13:32:12', '0.0.0.0\0\0\0\0\0\0\0\0\0', '0000-00-00 00:00:00', 3, '', NULL),
(16, 2, 'a4d3d3d8765e26438b4521fe309e7cd1', 'bibi_jones@mailinator.com', 'XyeIIJIeTzz', 'e10adc3949ba59abbe56e057f20f883e', '+04:00', '2014-12-05 13:34:00', '31.172.138.37\0\0\0', '2014-12-05 14:26:34', 0, '', 'ae0bc38fe6c9f2d647988743570b7491.jpg'),
(17, 2, '9c24fa8836422be0ea7bd98c1041c313', 'melkij2004@yandex.ru', 'Мелкий', '62ad9a428daab3d8e0ab3fbac303378e', '', '2014-12-06 14:15:24', '188.134.75.4\0\0\0\0', '2014-12-07 14:18:31', 0, '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `permissions`
--


-- --------------------------------------------------------

--
-- Table structure for table `session_data`
--

DROP TABLE IF EXISTS `session_data`;
CREATE TABLE IF NOT EXISTS `session_data` (
  `id` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `data` blob NOT NULL,
  `updated_by` datetime NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `session_data`
--

INSERT INTO `session_data` (`id`, `data`, `updated_by`) VALUES
('0lfrc5glcb2d3osdtqo6alcn76', '', '2015-06-29 02:46:41'),
('2vtho35s4aes9to0l7qcolsp37', '', '2015-04-11 08:15:55'),
('39ue2dmf00ok126l5d5ga0m1o6', '', '2015-06-23 19:22:07'),
('6jjebt63kgll86bnu25kesgk52', '', '2015-06-29 02:46:41'),
('6k0aviolp6q7kjrrt5ngmtqd85', '', '2015-06-29 02:46:42'),
('an3qr7qg2qpq0h7msqg3egvja1', '', '2015-07-01 15:48:47'),
('cf9sc56udt544lcnj6h0k753h0', '', '2015-06-29 02:46:42'),
('d4b0is2tpjm2m7gf5v2lbni7m7', '', '2015-06-23 22:12:05'),
('e5ohllv4ri3ho4eivkldc48dm7', '', '2015-06-29 02:46:41'),
('fsciq31vuc86tutmfk56oagvp3', '', '2015-07-01 15:48:48'),
('h5jtcmeiube84r5k330ves3jq1', '', '2015-06-30 17:01:56'),
('hvd91ida1jms90rkpf6d6dic82', '', '2015-06-29 02:46:41'),
('irt28tp2es2v4rd0ad819bo2r1', '', '2015-07-06 23:10:00'),
('kn7c99uq5gmo655u3jp7ave0l2', '', '2015-07-06 23:10:00'),
('m10jls5glf1umbrum048u0q4c3', '', '2015-07-06 23:09:59'),
('md7ai48uqs2v8h7r3ioeg6bp61', '', '2015-07-06 23:09:59'),
('nggiv7btrc29s22u5cosnkodu0', 0x72554d5f39477876713361315333532d41344d6d4f4334654e493965584943493072584b3236355a6d446b4759507a45365256525864673950424838486e3333726953504970546e334f44436f33374b522d524a77672e2e, '2015-04-11 08:15:56'),
('ov31mrsoduheidhu8e2qgaavk6', '', '2015-07-06 23:09:59'),
('pca0h19s0plm2ub9fmrrtuojp2', '', '2015-07-06 23:10:00'),
('pv5j5rih8lkna7q4s97ilhk9n1', '', '2015-07-06 23:10:00'),
('qsg73oiqbotd57i9jujni8b1n4', '', '2015-06-29 02:46:41'),
('s2785bua65519j0sjpi0orb666', '', '2015-07-01 15:48:46'),
('s2nit68g8meeb2p2d11rs203q5', '', '2015-06-29 02:46:42'),
('s3ps7404d6f7jbi8fr5j5uego0', 0x587a547671336f77635f4b354b786a454b686e4659774f51736b444f6831624a383747727441364249564f314d446554414c66507250526b4c32507731616f2d696e7a5a656b54535033347033514e4b5f4d746942376e3449545441317065344a755748664379466978673666535a5942513373765157344c716d424d4d5a41335f68447433646b717175304775385a754c517357672e2e, '2015-04-15 00:44:42'),
('svapiikott0qmqo5ot7hg8a4m3', '', '2015-06-30 17:02:19'),
('t7hdq1fc8jrnpqknq5o719j731', '', '2015-06-30 17:01:56'),
('tlmgl2jomsqru8kht2gtgf8vg0', '', '2015-07-06 23:09:59'),
('u638o4ukl4535htqqavfd7fgi0', 0x44633437684d426f6143453076544348504847616974307278664634716c356c674e68383943686a67766c446870587850764459764757783568354c666955435a44675030327252624b34426c4570664a3941756a4762426439532d6d6e4766437236616c69714a5f5939644370357078454354774e53353931534f7046424d, '2015-07-08 02:42:26'),
('ud40k8d252980vfrluhdabk0b5', '', '2015-06-23 10:23:06'),
('umao67l9j35uisvv311ici4jc2', '', '2015-07-06 23:10:00'),
('utq7gm4l0d866jgmfen9u2ecs6', 0x4654457a433856504941426b36715056515476544b6733785937505470494d36744d5371697965596d486330745852534a52697359786562475072654449395158774c49596d694b736679304272765f3776536761672e2e, '2015-04-24 04:32:41'),
('v5c2aslfq4chbm0maebk7olhq2', 0x724c374142335665506b68684a39315f7036477948554364776b362d6342456b2d476f594a376c57456d6d7a3476646a6f3554614d4f685a43333147696d6c7a5f454b3061643275763174536977476a624741666850547236796d4b6c714f4347375149756d7643555a6c67554f6b305536483449556555724168324d576a4f, '2015-06-10 22:53:09'),
('v8ic9g3m4d2osggjg45r9j43o7', '', '2015-06-17 01:57:16'),
('vao5f72n2d4he1he5idjq5jti1', '', '2015-06-30 17:01:55');
COMMIT;