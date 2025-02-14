ALTER TABLE `preferences`
    ADD `lat` FLOAT DEFAULT 0,
    ADD `lon` FLOAT DEFAULT 0,
    ADD `is_custom_loc` BOOLEAN NOT NULL DEFAULT FALSE;