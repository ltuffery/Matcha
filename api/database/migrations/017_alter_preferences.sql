ALTER TABLE `preferences`
    ADD COLUMN `fame_gap` INTEGER DEFAULT 0,
    DROP COLUMN `is_custom_loc`;
