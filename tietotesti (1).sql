-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 14.05.2025 klo 12:37
-- Palvelimen versio: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tietotesti`
--

-- --------------------------------------------------------

--
-- Rakenne taululle `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `teacher_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vedos taulusta `categories`
--

INSERT INTO `categories` (`id`, `name`, `teacher_id`) VALUES
(1, 'Historia', 1),
(2, 'Maantiede', 1),
(3, 'Urheilu', 1),
(4, 'Tiede', 1),
(5, 'Taide ja viihde', 1);

-- --------------------------------------------------------

--
-- Rakenne taululle `highscores`
--

CREATE TABLE `highscores` (
  `id` int(11) NOT NULL,
  `player_name` varchar(255) NOT NULL,
  `score` int(11) NOT NULL,
  `total_questions` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vedos taulusta `highscores`
--

INSERT INTO `highscores` (`id`, `player_name`, `score`, `total_questions`, `teacher_id`, `category_id`, `created_at`) VALUES
(65, 'Veeti Myllymäki', 3, 5, 8, 2, '2025-05-14 09:01:50'),
(66, 'Eetu Sanssi', 3, 5, 8, 4, '2025-05-14 10:18:32'),
(67, 'Eetu Sanssi', 1, 5, 8, 3, '2025-05-14 10:26:45'),
(68, 'Hakan Seppä', 5, 10, 6, 1, '2025-05-14 10:28:48'),
(69, 'Aatu Pöntiö', 2, 5, 8, 3, '2025-05-14 10:30:13'),
(70, 'Jasu', 3, 5, 8, 25, '2025-05-14 10:34:28');

-- --------------------------------------------------------

--
-- Rakenne taululle `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `question` text NOT NULL,
  `option_a` varchar(255) NOT NULL,
  `option_b` varchar(255) NOT NULL,
  `option_c` varchar(255) NOT NULL,
  `option_d` varchar(255) NOT NULL,
  `correct_option` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vedos taulusta `questions`
--

INSERT INTO `questions` (`id`, `category_id`, `teacher_id`, `question`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_option`) VALUES
(1, 1, 1, 'Kuka oli ensimmäinen Yhdysvaltain presidentti?', 'George Washington', 'Thomas Jefferson', 'Abraham Lincoln', 'John Adams', 'a'),
(2, 1, 1, 'Missä vuonna Titanic upposi Atlantilla?', '1905', '1912', '1921', '1930', 'b'),
(3, 1, 1, 'Kuka hallitsi Ranskaa suuren osan 1800-lukua?', 'Ludvig XIV', 'Napoleon Bonaparte', 'Henrik V', 'Charles de Gaulle', 'b'),
(4, 1, 1, 'Kuka oli Neuvostoliiton ensimmäinen johtaja?', 'Vladimir Lenin', 'Josif Stalin', 'Leonid Brežnev', 'Nikita Hruštšov', 'a'),
(5, 1, 1, 'Missä vuonna Berliinin muuri murtui?', '1987', '1989', '1991', '1993', 'b'),
(6, 1, 1, 'Kuka oli Englannin kuningatar, joka hallitsi viktoriaanisella ajalla?', 'Kuningatar Viktoria', 'Kuningatar Elisabet I', 'Kuningatar Mary', 'Kuningatar Anne', 'a'),
(7, 1, 1, 'Missä sodassa käytiin kuuluisa Normandian maihinnousu?', 'Ensimmäinen maailmansota', 'Toinen maailmansota', 'Korean sota', 'Vietnamin sota', 'b'),
(8, 1, 1, 'Kuka oli viimeinen farao muinaisessa Egyptissä?', 'Tutankhamon', 'Ramses II', 'Cleopatra', 'Akhenaten', 'c'),
(9, 1, 1, 'Mikä oli ensimmäisen maailmansodan pääasiallinen syy?', 'Saksan ja Ranskan välinen konflikti', 'Assassinaatio Sarajevossa', 'Britannian ja Saksan kilpailu', 'Venäjän vallankumous', 'b'),
(10, 1, 1, 'Missä maassa alkoi teollinen vallankumous?', 'Yhdysvallat', 'Ranska', 'Britannia', 'Saksa', 'c'),
(11, 2, 1, 'Mikä on maailman suurin valtameri?', 'Atlantin valtameri', 'Tyynimeri', 'Intian valtameri', 'Jäämeri', 'b'),
(12, 2, 1, 'Mikä on maailman suurin aavikko?', 'Sahara', 'Arabian aavikko', 'Gobi', 'Kalahari', 'a'),
(13, 2, 1, 'Missä maassa sijaitsee Amazon-joki?', 'Kolumbia', 'Peru', 'Brasilia', 'Venezuela', 'c'),
(14, 2, 1, 'Mikä on maailman pienin maa pinta-alaltaan?', 'Vatikaanivaltio', 'Monaco', 'San Marino', 'Liechtenstein', 'a'),
(15, 2, 1, 'Mikä on Australian suurin kaupunki?', 'Sydney', 'Melbourne', 'Brisbane', 'Canberra', 'a'),
(16, 2, 1, 'Missä maassa Machu Picchu sijaitsee?', 'Meksiko', 'Peru', 'Chile', 'Kolumbia', 'b'),
(17, 2, 1, 'Mikä valtio omistaa Grönlannin?', 'Islanti', 'Norja', 'Tanska', 'Ruotsi', 'c'),
(18, 2, 1, 'Mikä on Yhdysvaltain pisin joki?', 'Mississippi', 'Missouri', 'Colorado', 'Ohio', 'b'),
(19, 2, 1, 'Mikä on Etelä-Afrikan pääkaupunki?', 'Pretoria', 'Cape Town', 'Johannesburg', 'Durban', 'a'),
(20, 2, 1, 'Missä maassa Eiffel-torni sijaitsee?', 'Espanja', 'Italia', 'Ranska', 'Saksa', 'c'),
(21, 3, 1, 'Kuka on voittanut eniten Grand Slam -turnauksia tenniksessä (miehet)?', 'Roger Federer', 'Rafael Nadal', 'Novak Djokovic', 'Pete Sampras', 'c'),
(22, 3, 1, 'Missä maassa pidettiin ensimmäiset modernit olympialaiset vuonna 1896?', 'Ranska', 'Kreikka', 'Iso-Britannia', 'Italia', 'b'),
(23, 3, 1, 'Mikä maa voitti jalkapallon maailmanmestaruuden vuonna 2018?', 'Brasilia', 'Saksa', 'Argentiina', 'Ranska', 'd'),
(24, 3, 1, 'Kuinka monta pelaajaa on koripallojoukkueessa kentällä kerrallaan?', '5', '6', '7', '8', 'a'),
(25, 3, 1, 'Kuka on tunnettu \"Jäämies\" F1-kuljettajana?', 'Michael Schumacher', 'Lewis Hamilton', 'Kimi Räikkönen', 'Sebastian Vettel', 'c'),
(26, 3, 1, 'Kuinka monta kultamitalia Michael Phelps voitti Pekingin olympialaisissa 2008?', '6', '7', '8', '9', 'c'),
(27, 3, 1, 'Kuka voitti ensimmäisen Tour de Francen?', 'Maurice Garin', 'Eddy Merckx', 'Bernard Hinault', 'Lance Armstrong', 'a'),
(28, 3, 1, 'Missä maassa järjestettiin vuoden 2010 jalkapallon MM-kisat?', 'Saksa', 'Etelä-Afrikka', 'Brasilia', 'Venäjä', 'b'),
(29, 3, 1, 'Kuka koripalloilija tunnetaan lempinimellä \"King James\"?', 'Michael Jordan', 'LeBron James', 'Kobe Bryant', 'Shaquille O\'Neal', 'b'),
(30, 3, 1, 'Mikä maa on voittanut eniten kriketin maailmanmestaruuksia?', 'Australia', 'Englanti', 'Intia', 'Pakistan', 'a'),
(31, 4, 1, 'Mikä alkuaine on kemialliselta merkiltään H?', 'Happi', 'Helium', 'Vety', 'Kloori', 'c'),
(32, 4, 1, 'Kuka kehitti yleisen suhteellisuusteorian?', 'Albert Einstein', 'Isaac Newton', 'Niels Bohr', 'Galileo Galilei', 'a'),
(33, 4, 1, 'Mikä on ihmiskehon suurin elin?', 'Aivot', 'Iho', 'Maksa', 'Sydän', 'b'),
(34, 4, 1, 'Mikä planeetta tunnetaan punaisena planeettana?', 'Venus', 'Mars', 'Jupiter', 'Saturnus', 'b'),
(35, 4, 1, 'Mikä on veden kemiallinen kaava?', 'H2', 'O2', 'CO2', 'H2O', 'd'),
(36, 4, 1, 'Mikä yksikkö on voiman SI-yksikkö?', 'Joule', 'Watti', 'Newton', 'Ohmi', 'c'),
(37, 4, 1, 'Kuka on DNA:n kaksoiskierremallin löytäjä yhdessä James Watsonin kanssa?', 'Rosalind Franklin', 'Francis Crick', 'Erwin Schrödinger', 'Linus Pauling', 'b'),
(38, 4, 1, 'Mikä eläin on nisäkästen joukossa ainutlaatuinen munimisensa vuoksi?', 'Nokkonokkaeläin', 'Piikkisika', 'Vyötiäinen', 'Koala', 'a'),
(39, 4, 1, 'Mikä on valon nopeus tyhjiössä?', '300,000 km/s', '150,000 km/s', '500,000 km/s', '1,000,000 km/s', 'a'),
(40, 4, 1, 'Kuka tunnetaan evoluutioteorian isänä?', 'Charles Darwin', 'Gregor Mendel', 'James Watson', 'Louis Pasteur', 'a'),
(41, 5, 1, 'Kuka ohjasi elokuvan \"Titanic\"?', 'Steven Spielberg', 'Martin Scorsese', 'James Cameron', 'George Lucas', 'c'),
(42, 5, 1, 'Mikä yhtye levytti kappaleen \"Hey Jude\"?', 'The Beatles', 'The Rolling Stones', 'The Who', 'Led Zeppelin', 'a'),
(43, 5, 1, 'Kuka näytteli pääroolin elokuvassa \"Forrest Gump\"?', 'Tom Hanks', 'Leonardo DiCaprio', 'Matt Damon', 'Brad Pitt', 'a'),
(44, 5, 1, 'Kuka sävelsi teoksen \"Eine kleine Nachtmusik\"?', 'Ludwig van Beethoven', 'Wolfgang Amadeus Mozart', 'Johann Sebastian Bach', 'Franz Schubert', 'b'),
(45, 5, 1, 'Mikä maalaus tunnetaan nimellä \"Mona Lisa\"?', 'The Scream', 'Starry Night', 'The Persistence of Memory', 'Mona Lisa', 'd'),
(46, 5, 1, 'Kuka on Harry Potter -kirjasarjan kirjoittaja?', 'J.K. Rowling', 'J.R.R. Tolkien', 'C.S. Lewis', 'George R.R. Martin', 'a'),
(47, 5, 1, 'Kuka tunnetaan maalaustaiteen \"Kuutamosonaatti\" säveltäjänä?', 'Frédéric Chopin', 'Johann Sebastian Bach', 'Ludwig van Beethoven', 'Wolfgang Amadeus Mozart', 'c'),
(48, 5, 1, 'Mikä elokuva voitti parhaan elokuvan Oscarin vuonna 2020?', '1917', 'Joker', 'Parasite', 'Once Upon a Time in Hollywood', 'c'),
(49, 5, 1, 'Kuka näytteli Iron Mania Marvelin elokuvasarjassa?', 'Robert Downey Jr.', 'Chris Evans', 'Chris Hemsworth', 'Mark Ruffalo', 'a'),
(50, 5, 1, 'Kuka on laulanut kappaleen \"Thriller\"?', 'Michael Jackson', 'Prince', 'Elton John', 'Stevie Wonder', 'a'),
(51, 6, 2, 'Missä kaupungissa Julius Caesar murhattiin?', 'Ateena', 'Rooma', 'Kartago', 'Aleksandria', 'b'),
(52, 6, 2, 'Kuka oli Ranskan viimeinen kuningas ennen Ranskan vallankumousta?', 'Ludvig XIV', 'Ludvig XV', 'Ludvig XVI', 'Napoleon', 'c'),
(53, 6, 2, 'Kuka oli tunnettu kuningatar Englannissa 1500-luvulla?', 'Elisabet I', 'Maria I', 'Viktoria', 'Anna', 'a'),
(54, 6, 2, 'Kuka johti Neuvostoliittoa toisen maailmansodan aikana?', 'Lenin', 'Trotski', 'Stalin', 'Hruštšov', 'c'),
(55, 6, 2, 'Mikä valtio hyökkäsi Puolaan vuonna 1939 aloittaen toisen maailmansodan?', 'Italia', 'Japani', 'Saksa', 'Neuvostoliitto', 'c'),
(56, 6, 2, 'Missä maassa tapahtui Teekutsujen kapina vuonna 1773?', 'Kanada', 'Yhdysvallat', 'Iso-Britannia', 'Ranska', 'b'),
(57, 6, 2, 'Kuka oli kuuluisa Kreikan sodan jumala?', 'Zeus', 'Apollo', 'Ares', 'Hermes', 'c'),
(58, 6, 2, 'Kuka oli kuuluisin farao muinaisessa Egyptissä?', 'Cleopatra', 'Tutankhamon', 'Ramses II', 'Akhenaten', 'b'),
(59, 6, 2, 'Kuka perusti Mongolivaltakunnan?', 'Kublai-kaani', 'Tamerlane', 'Genghis-kaani', 'Attila', 'c'),
(60, 6, 2, 'Missä vuosisadalla Yhdysvallat itsenäistyi?', '1600-luku', '1700-luku', '1800-luku', '1900-luku', 'b'),
(61, 6, 2, 'Kuka oli ensimmäinen Britannian kuningatar, joka hallitsi omissa nimissään?', 'Mary I', 'Elizabeth I', 'Victoria', 'Anne', 'a'),
(62, 6, 2, 'Missä kaupungissa perustettiin Yhdistyneet kansakunnat?', 'Washington D.C.', 'New York', 'San Francisco', 'Lontoo', 'c'),
(63, 6, 2, 'Mikä valtakunta tunnettiin nimellä \"Rooman vihollinen\"?', 'Egypti', 'Persia', 'Karthago', 'Kreikka', 'c'),
(64, 6, 2, 'Missä vuonna Bastilji valloitettiin Ranskan vallankumouksessa?', '1789', '1792', '1804', '1815', 'a'),
(65, 6, 2, 'Kuka oli kuuluisa matemaatikko ja fyysikko, joka keksi painovoiman?', 'Albert Einstein', 'Isaac Newton', 'Galileo Galilei', 'Nikola Tesla', 'b'),
(66, 6, 2, 'Missä vuonna Napoleon hävisi Waterloon taistelun?', '1805', '1812', '1815', '1821', 'c'),
(67, 6, 2, 'Mikä maa oli osa Kolmivaltaliittoa toisen maailmansodan aikana?', 'Ranska', 'Italia', 'Yhdysvallat', 'Kiina', 'b'),
(68, 6, 2, 'Missä vuonna avaruusajan katsotaan alkaneen Sputnikin laukaisulla?', '1953', '1957', '1961', '1969', 'b'),
(69, 6, 2, 'Mikä Egyptin faarao tunnetaan parhaiten hautakammionsa aarteista?', 'Cleopatra', 'Tutankhamon', 'Ramses II', 'Akhenaten', 'b'),
(70, 6, 2, 'Kuka oli Rooman valtakunnan ensimmäinen keisari?', 'Julius Caesar', 'Augustus', 'Nero', 'Caligula', 'b'),
(113, 3, 4, 'Mikä on jalkapallon maalin korkeus?', '2,44 m', '2,55 m', '2,30 m', '2,60 m', 'a'),
(114, 3, 4, 'Kuinka monta pelaajaa on jääkiekkojoukkueessa?', '6', '5', '7', '8', 'a'),
(115, 3, 4, 'Mikä on olympialaisten motto?', 'Citius, Altius, Fortius', 'Faster, Higher, Stronger', 'One World, One Dream', 'The Spirit Within', 'a');

-- --------------------------------------------------------

--
-- Rakenne taululle `teachers`
--

CREATE TABLE `teachers` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vedos taulusta `teachers`
--

INSERT INTO `teachers` (`id`, `username`, `password_hash`) VALUES
(6, 'Eetu Sanssi', '$2y$10$RYK9kjMmn.kDweWB4moc3eORla44MUtcrJTmYDns8V7qgH2mtOf0q'),
(8, 'Veeti Myllymäki', '$2y$10$RYK9kjMmn.kDweWB4moc3eORla44MUtcrJTmYDns8V7qgH2mtOf0q');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `highscores`
--
ALTER TABLE `highscores`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `highscores`
--
ALTER TABLE `highscores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
