-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th4 09, 2018 lúc 12:41 PM
-- Phiên bản máy phục vụ: 10.1.31-MariaDB
-- Phiên bản PHP: 7.2.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `zend`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `album`
--

DROP TABLE IF EXISTS `album`;
CREATE TABLE `album` (
  `id` int(11) NOT NULL,
  `artist` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `album`
--

INSERT INTO `album` (`id`, `artist`, `title`) VALUES
(1, 'The Military Wives', 'In My Dreams'),
(2, 'Adele', '21'),
(3, 'Bruce Springsteen', 'Wrecking Ball (Deluxe)'),
(4, 'Lana Del Rey', 'Born To Die'),
(5, 'Gotye', 'Making Mirrors'),
(8, 'David Bowie', 'The Next Day (Deluxe Version)'),
(9, 'Bastille', 'Bad Blood'),
(10, 'Bruno Mars', 'Unorthodox Jukebox'),
(11, 'Emeli Sandé', 'Our Version of Events (Special Edition)'),
(12, 'Bon Jovi', 'What About Now (Deluxe Version)'),
(13, 'Justin Timberlake', 'The 20/20 Experience (Deluxe Version)'),
(14, 'Bastille', 'Bad Blood (The Extended Cut)'),
(15, 'P!nk', 'The Truth About Love'),
(16, 'Sound City - Real to Reel', 'Sound City - Real to Reel'),
(17, 'Jake Bugg', 'Jake Bugg'),
(18, 'Various Artists', 'The Trevor Nelson Collection'),
(19, 'David Bowie', 'The Next Day'),
(20, 'Mumford & Sons', 'Babel'),
(21, 'The Lumineers', 'The Lumineers'),
(22, 'Various Artists', 'Get Ur Freak On - R&B Anthems'),
(23, 'The 1975', 'Music For Cars EP'),
(24, 'Various Artists', 'Saturday Night Club Classics - Ministry of Sound'),
(25, 'Hurts', 'Exile (Deluxe)'),
(26, 'Various Artists', 'Mixmag - The Greatest Dance Tracks of All Time'),
(27, 'Ben Howard', 'Every Kingdom'),
(28, 'Stereophonics', 'Graffiti On the Train'),
(29, 'The Script', '#3'),
(30, 'Stornoway', 'Tales from Terra Firma'),
(31, 'David Bowie', 'Hunky Dory (Remastered)'),
(32, 'Worship Central', 'Let It Be Known (Live)'),
(33, 'Ellie Goulding', 'Halcyon'),
(34, 'Various Artists', 'Dermot O\'Leary Presents the Saturday Sessions 2013'),
(35, 'Stereophonics', 'Graffiti On the Train (Deluxe Version)'),
(36, 'Dido', 'Girl Who Got Away (Deluxe)'),
(37, 'Hurts', 'Exile'),
(38, 'Bruno Mars', 'Doo-Wops & Hooligans'),
(39, 'Calvin Harris', '18 Months'),
(40, 'Olly Murs', 'Right Place Right Time'),
(41, 'Alt-J (?)', 'An Awesome Wave'),
(42, 'One Direction', 'Take Me Home'),
(43, 'Various Artists', 'Pop Stars'),
(44, 'Various Artists', 'Now That\'s What I Call Music! 83'),
(45, 'John Grant', 'Pale Green Ghosts'),
(46, 'Paloma Faith', 'Fall to Grace'),
(47, 'Laura Mvula', 'Sing To the Moon (Deluxe)'),
(48, 'Duke Dumont', 'Need U (100%) [feat. A*M*E] - EP'),
(49, 'Watsky', 'Cardboard Castles'),
(50, 'Blondie', 'Blondie: Greatest Hits'),
(51, 'Foals', 'Holy Fire'),
(52, 'Maroon 5', 'Overexposed'),
(53, 'Bastille', 'Pompeii (Remixes) - EP'),
(54, 'Imagine Dragons', 'Hear Me - EP'),
(55, 'Various Artists', '100 Hits: 80s Classics'),
(56, 'Various Artists', 'Les Misérables (Highlights From the Motion Picture Soundtrack)'),
(57, 'Mumford & Sons', 'Sigh No More'),
(58, 'Frank Ocean', 'Channel ORANGE'),
(59, 'Bon Jovi', 'What About Now'),
(60, 'Various Artists', 'BRIT Awards 2013'),
(61, 'Taylor Swift', 'Red'),
(62, 'Fleetwood Mac', 'Fleetwood Mac: Greatest Hits'),
(63, 'David Guetta', 'Nothing But the Beat Ultimate'),
(64, 'Various Artists', 'Clubbers Guide 2013 (Mixed By Danny Howard) - Ministry of Sound'),
(65, 'David Bowie', 'Best of Bowie'),
(66, 'Laura Mvula', 'Sing To the Moon'),
(67, 'ADELE', '21'),
(68, 'Of Monsters and Men', 'My Head Is an Animal'),
(69, 'Rihanna', 'Unapologetic'),
(70, 'Various Artists', 'BBC Radio 1\'s Live Lounge - 2012'),
(71, 'Avicii & Nicky Romero', 'I Could Be the One (Avicii vs. Nicky Romero)'),
(72, 'The Streets', 'A Grand Don\'t Come for Free'),
(73, 'Tim McGraw', 'Two Lanes of Freedom'),
(74, 'Foo Fighters', 'Foo Fighters: Greatest Hits'),
(75, 'Various Artists', 'Now That\'s What I Call Running!'),
(76, 'Swedish House Mafia', 'Until Now'),
(77, 'The xx', 'Coexist'),
(78, 'Five', 'Five: Greatest Hits'),
(79, 'Jimi Hendrix', 'People, Hell & Angels'),
(80, 'Biffy Clyro', 'Opposites (Deluxe)'),
(81, 'The Smiths', 'The Sound of the Smiths'),
(82, 'The Saturdays', 'What About Us - EP'),
(83, 'Fleetwood Mac', 'Rumours'),
(84, 'Various Artists', 'The Big Reunion'),
(85, 'Various Artists', 'Anthems 90s - Ministry of Sound'),
(86, 'The Vaccines', 'Come of Age'),
(87, 'Nicole Scherzinger', 'Boomerang (Remixes) - EP'),
(88, 'Bob Marley', 'Legend (Bonus Track Version)'),
(89, 'Josh Groban', 'All That Echoes'),
(90, 'Blue', 'Best of Blue'),
(91, 'Ed Sheeran', '+'),
(92, 'Olly Murs', 'In Case You Didn\'t Know (Deluxe Edition)'),
(93, 'Macklemore & Ryan Lewis', 'The Heist (Deluxe Edition)'),
(94, 'Various Artists', 'Defected Presents Most Rated Miami 2013'),
(95, 'Gorgon City', 'Real EP'),
(96, 'Mumford & Sons', 'Babel (Deluxe Version)'),
(97, 'Various Artists', 'The Music of Nashville: Season 1, Vol. 1 (Original Soundtrack)'),
(98, 'Various Artists', 'The Twilight Saga: Breaking Dawn, Pt. 2 (Original Motion Picture Soundtrack)'),
(99, 'Various Artists', 'Mum - The Ultimate Mothers Day Collection'),
(100, 'One Direction', 'Up All Night'),
(101, 'Bon Jovi', 'Bon Jovi Greatest Hits'),
(102, 'Agnetha Fältskog', 'A'),
(103, 'Fun.', 'Some Nights'),
(104, 'Justin Bieber', 'Believe Acoustic'),
(105, 'Atoms for Peace', 'Amok'),
(106, 'Justin Timberlake', 'Justified'),
(107, 'Passenger', 'All the Little Lights'),
(108, 'Kodaline', 'The High Hopes EP'),
(109, 'Lana Del Rey', 'Born to Die'),
(110, 'JAY Z & Kanye West', 'Watch the Throne (Deluxe Version)'),
(111, 'Biffy Clyro', 'Opposites'),
(112, 'Various Artists', 'Return of the 90s'),
(113, 'Gabrielle Aplin', 'Please Don\'t Say You Love Me - EP'),
(114, 'Various Artists', '100 Hits - Driving Rock'),
(115, 'Jimi Hendrix', 'Experience Hendrix - The Best of Jimi Hendrix'),
(116, 'Various Artists', 'The Workout Mix 2013'),
(117, 'The 1975', 'Sex'),
(118, 'Chase & Status', 'No More Idols'),
(119, 'Rihanna', 'Unapologetic (Deluxe Version)'),
(120, 'The Killers', 'Battle Born'),
(121, 'Olly Murs', 'Right Place Right Time (Deluxe Edition)'),
(122, 'A$AP Rocky', 'LONG.LIVE.A$AP (Deluxe Version)'),
(123, 'Various Artists', 'Cooking Songs'),
(124, 'Haim', 'Forever - EP'),
(125, 'Lianne La Havas', 'Is Your Love Big Enough?'),
(126, 'Michael Bublé', 'To Be Loved'),
(127, 'Daughter', 'If You Leave'),
(128, 'The xx', 'xx'),
(129, 'Eminem', 'Curtain Call'),
(130, 'Kendrick Lamar', 'good kid, m.A.A.d city (Deluxe)'),
(131, 'Disclosure', 'The Face - EP'),
(132, 'Palma Violets', '180'),
(133, 'Cody Simpson', 'Paradise'),
(134, 'Ed Sheeran', '+ (Deluxe Version)'),
(135, 'Michael Bublé', 'Crazy Love (Hollywood Edition)'),
(136, 'Bon Jovi', 'Bon Jovi Greatest Hits - The Ultimate Collection'),
(137, 'Rita Ora', 'Ora'),
(138, 'g33k', 'Spabby'),
(139, 'Various Artists', 'Annie Mac Presents 2012'),
(140, 'David Bowie', 'The Platinum Collection'),
(141, 'Bridgit Mendler', 'Ready or Not (Remixes) - EP'),
(142, 'Dido', 'Girl Who Got Away'),
(143, 'Various Artists', 'Now That\'s What I Call Disney'),
(144, 'The 1975', 'Facedown - EP'),
(145, 'Kodaline', 'The Kodaline - EP'),
(146, 'Various Artists', '100 Hits: Super 70s'),
(147, 'Fred V & Grafix', 'Goggles - EP'),
(148, 'Biffy Clyro', 'Only Revolutions (Deluxe Version)'),
(149, 'Train', 'California 37'),
(150, 'Ben Howard', 'Every Kingdom (Deluxe Edition)'),
(151, 'Various Artists', 'Motown Anthems'),
(152, 'Courteeners', 'ANNA'),
(153, 'Johnny Marr', 'The Messenger'),
(154, 'Rodriguez', 'Searching for Sugar Man'),
(155, 'Jessie Ware', 'Devotion'),
(156, 'Bruno Mars', 'Unorthodox Jukebox'),
(157, 'Various Artists', 'Call the Midwife (Music From the TV Series)');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
  `id` int(10) NOT NULL,
  `post_id` int(10) NOT NULL,
  `text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `posts`
--

INSERT INTO `posts` (`id`, `title`, `text`) VALUES
(1, 'Blog #1', 'Welcome to my first blog post 1'),
(2, 'Blog #2', 'Welcome to my second blog post'),
(3, 'Blog #3', 'Welcome to my third blog post'),
(4, 'Blog #4', 'Welcome to my fourth blog post'),
(5, 'Blog #5', 'Welcome to my fifth blog post'),
(6, 'BAC', 'Bakery');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `post_tag`
--

DROP TABLE IF EXISTS `post_tag`;
CREATE TABLE `post_tag` (
  `post_id` int(10) NOT NULL,
  `tag_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tags`
--

DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags` (
  `id` int(10) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `album`
--
ALTER TABLE `album`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `post_tag`
--
ALTER TABLE `post_tag`
  ADD PRIMARY KEY (`tag_id`,`post_id`);

--
-- Chỉ mục cho bảng `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `album`
--
ALTER TABLE `album`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=158;

--
-- AUTO_INCREMENT cho bảng `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
