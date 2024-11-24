ALTER TABLE `users`
ADD `first_name` varchar(255),
ADD `last_name` varchar(255),
ADD `age` integer,
ADD `gender` ENUM('M', 'F', 'O'),
ADD `sexual_preferences` ENUM('A', 'M', 'F', 'O'),
ADD `biography` text;
