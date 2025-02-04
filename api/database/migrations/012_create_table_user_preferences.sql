CREATE TABLE `preferences`
(
    `user_id`          INTEGER PRIMARY KEY,
    `age_minimum`      INTEGER,
    `age_maximum`      INTEGER,
    `distance_maximum` INTEGER DEFAULT 10,
    `by_tags`          BOOLEAN DEFAULT 1,
    `sexual_preferences` ENUM('A', 'M', 'F', 'O') DEFAULT 'A'
);