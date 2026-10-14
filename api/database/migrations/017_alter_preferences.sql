ALTER TABLE `preferences`
    ADD `fame_gap` INTEGER DEFAULT 0,
    DROP COLUMN `is_custom_loc`;
