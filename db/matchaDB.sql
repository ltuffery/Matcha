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

CREATE TABLE `tags` (
  `id` integer PRIMARY KEY,
  `name` varchar(255)
);

CREATE TABLE `tag_user` (
  `id` integer PRIMARY KEY,
  `tag_id` integer,
  `user_id` integer
);

CREATE TABLE `pictures` (
  `id` integer PRIMARY KEY,
  `base64` text,
  `user_id` integer
);

CREATE TABLE `messages` (
  `id` integer PRIMARY KEY,
  `sender_id` integer,
  `recever_id` integer,
  `content` text,
  `created_at` timestamp
);

CREATE TABLE `notifications` (
  `id` integer PRIMARY KEY,
  `user_id` integer,
  `type` ENUM ('LIKE', 'UNLIKE', 'LIKE_BACK', 'VIEW', 'MESSAGE'),
  `viewed` bool,
  `created_at` timestamp
);

CREATE TABLE `likes` (
  `id` integer PRIMARY KEY,
  `user_id` integer,
  `target_id` integer
);

ALTER TABLE `tags` ADD FOREIGN KEY (`id`) REFERENCES `tag_user` (`tag_id`);

ALTER TABLE `users` ADD FOREIGN KEY (`id`) REFERENCES `tag_user` (`user_id`);

ALTER TABLE `users` ADD FOREIGN KEY (`id`) REFERENCES `pictures` (`user_id`);

ALTER TABLE `users` ADD FOREIGN KEY (`id`) REFERENCES `messages` (`sender_id`);

ALTER TABLE `users` ADD FOREIGN KEY (`id`) REFERENCES `messages` (`recever_id`);

ALTER TABLE `users` ADD FOREIGN KEY (`id`) REFERENCES `notifications` (`user_id`);

ALTER TABLE `users` ADD FOREIGN KEY (`id`) REFERENCES `likes` (`user_id`);

ALTER TABLE `users` ADD FOREIGN KEY (`id`) REFERENCES `likes` (`target_id`);
