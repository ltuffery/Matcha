CREATE TABLE `reactions`(
                           `id` integer PRIMARY KEY NOT NULL AUTO_INCREMENT,
                           `user_id` integer,
                           `message_id` integer,
                           `emoji` VARCHAR(10) NOT NULL,
                           `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
                           FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
                           FOREIGN KEY (`message_id`) REFERENCES `messages`(`id`) ON DELETE CASCADE
)