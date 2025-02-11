CREATE TABLE `notifications` (
    `id` integer PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `user_id` integer NOT NULL,
    `sender_id` integer,
    `type` varchar(255) NOT NULL,
    `content` TEXT NOT NULL,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    `view` bool DEFAULT 0,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`sender_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
);