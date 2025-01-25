CREATE TABLE `messages`(
    `id` integer PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `sender_id` integer,
    `receiver_id` integer,
    `content` TEXT NOT NULL,
    `view` BOOLEAN DEFAULT 0, 
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`sender_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`receiver_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
)