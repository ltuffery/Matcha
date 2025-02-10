CREATE TABLE `preferences`
(
    `id` INTEGER PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `user_id`          INTEGER,
    `age_minimum`      INTEGER,
    `age_maximum`      INTEGER,
    `distance_maximum` INTEGER DEFAULT 10,
    `by_tags`          BOOLEAN DEFAULT 1,
    `sexual_preferences` ENUM('A', 'M', 'F', 'O') DEFAULT 'A',

    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
);