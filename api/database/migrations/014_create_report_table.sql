CREATE TABLE `user_reports`(
    `id` INTEGER PRIMARY KEY AUTO_INCREMENT,
    `user_id` INTEGER,
    `reported_id` INTEGER,
    `raison` VARCHAR(255) NOT NULL ,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`reported_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
)