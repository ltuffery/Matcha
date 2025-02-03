CREATE TABLE `preferences`
(
    `user_id`          INTEGER PRIMARY KEY,
    `age_minimum`      INTEGER,
    `age_maximum`      INTEGER,
    `distance_maximum` INTEGER,
    `by_tags`          BOOLEAN
);