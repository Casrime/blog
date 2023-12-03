CREATE TABLE `user` (
    `id` int(11) NOT NULL,
    `email` varchar(100) NOT NULL,
    `password` varchar(60) NOT NULL,
    `roles` json NOT NULL,
    `createdAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `user` (`id`, `email`, `password`, `roles`, `createdAt`) VALUES
    (1, 'admin@blog.com', '$2y$10$.9flv1yRPQcHUBJd8aFqruH7n2TVNqTqYxOYMYdhjNBN1AChF0NTm', '["ROLE_USER", "ROLE_ADMIN"]', '2023-11-25 10:27:12');

ALTER TABLE `user`
    ADD PRIMARY KEY (`id`);

ALTER TABLE `user`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
