ALTER TABLE `users`
ADD `email_verified` BOOLEAN DEFAULT FALSE,
ADD `temporary_email_token` varchar(255);
