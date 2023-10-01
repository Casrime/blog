CREATE TABLE `comment` (
   `id` int(11) NOT NULL,
   `comment` text NOT NULL,
   `article_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `comment` (`id`, `comment`, `article_id`) VALUES
    (1, 'Génial, hâte de voir ce que ça donne !', 1),
    (2, 'Trop cool ! depuis le temps', 1);

ALTER TABLE `comment`
    ADD PRIMARY KEY (`id`),
  ADD KEY `fk_article_id` (`article_id`);

ALTER TABLE `comment`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `comment`
    ADD CONSTRAINT `fk_article_id` FOREIGN KEY (`article_id`) REFERENCES `article` (`id`);
