CREATE TABLE `article` (
    `id` int(11) NOT NULL,
    `title` varchar(255) NOT NULL,
    `slug` varchar(255) NOT NULL,
    `chapo` varchar(255) NOT NULL,
    `content` text NOT NULL,
    `createdAt` datetime NOT NULL,
    `updatedAt` datetime NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `article` (`id`, `title`, `slug`, `chapo`, `content`, `createdAt`) VALUES
    (1, 'Voici mon premier article', 'mon-premier-article', 'Chapo article un', 'Mon super blog est en construction.', '2023-09-27 22:36:17'),
    (2, 'Un deuxième article', 'mon-deuxieme-article', 'Chapo article deux', 'Je continue à ajouter du contenu sur mon blog.', '2023-09-27 23:04:26'),
    (3, 'Mon troisième article', 'mon-troisieme-article', 'Chapo article trois', 'Mon blog est génial !!!', '2023-09-27 23:17:02');

ALTER TABLE `article`
    ADD PRIMARY KEY (`id`);

ALTER TABLE `article`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
