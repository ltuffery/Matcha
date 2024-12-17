CREATE TABLE `likes` (
    `user_id` INTEGER,
    `liked_id` INTEGER,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`user_id`, `liked_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`liked_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
);