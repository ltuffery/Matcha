CREATE TABLE `users` (
  `id` integer PRIMARY KEY,
  `username` varchar(255),
  `email` varchar(255),
  `email_verified` bool,
  `password` varchar(255),
  `first_name` varchar(255),
  `last_name` varchar(255),
  `age` integer,
  `gender` ENUM ('M', 'F', 'O'),
  `sexual_preferences` ENUM ('A', 'M', 'F', 'O'),
  `biography` text,
  `localisation` text,
  `fame_rating` float,
  `ip` varchar(255),
  `premium` bool,
  `profile_verified` bool,
  `created_at` timestamp
);