CREATE TABLE `user_blocked`
(
    `user_id`    INTEGER,
    `blocked_id`   INTEGER,
    `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`user_id`, `blocked_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`blocked_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
);