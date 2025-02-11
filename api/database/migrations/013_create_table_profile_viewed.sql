CREATE TABLE `profile_viewed` (
    `user_id` INTEGER,
    `viewed_id` INTEGER,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`user_id`, `viewed_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`viewed_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
);