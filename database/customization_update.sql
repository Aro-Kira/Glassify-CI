-- Update customization table to support design images and price breakdown
-- Run this SQL to update your existing customization table

-- Add DesignRef column if it doesn't exist
ALTER TABLE `customization` 
ADD COLUMN IF NOT EXISTS `DesignRef` VARCHAR(255) NULL DEFAULT NULL 
COMMENT 'Path to the saved design image from Konva.js' AFTER `Engraving`;

-- Add PriceBreakdown column if it doesn't exist  
ALTER TABLE `customization`
ADD COLUMN IF NOT EXISTS `PriceBreakdown` TEXT NULL DEFAULT NULL
COMMENT 'JSON string containing price breakdown details' AFTER `EstimatePrice`;

-- Add CreatedAt timestamp if it doesn't exist
ALTER TABLE `customization`
ADD COLUMN IF NOT EXISTS `CreatedAt` TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER `PriceBreakdown`;

-- Add UpdatedAt timestamp if it doesn't exist
ALTER TABLE `customization`
ADD COLUMN IF NOT EXISTS `UpdatedAt` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `CreatedAt`;

-- Create index for faster lookups
ALTER TABLE `customization` ADD INDEX IF NOT EXISTS `idx_customer_id` (`Customer_ID`);
ALTER TABLE `customization` ADD INDEX IF NOT EXISTS `idx_product_id` (`Product_ID`);

-- If your MySQL version doesn't support "IF NOT EXISTS" for columns, use these instead:
-- Check if column exists before adding:
/*
-- For older MySQL versions, check manually first:
SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'your_database_name' 
AND TABLE_NAME = 'customization' 
AND COLUMN_NAME = 'DesignRef';

-- If column doesn't exist, run:
ALTER TABLE `customization` ADD COLUMN `DesignRef` VARCHAR(255) NULL DEFAULT NULL;
ALTER TABLE `customization` ADD COLUMN `PriceBreakdown` TEXT NULL DEFAULT NULL;
ALTER TABLE `customization` ADD COLUMN `CreatedAt` TIMESTAMP DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `customization` ADD COLUMN `UpdatedAt` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
*/
