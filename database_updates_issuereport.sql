-- ============================================================
-- Database Updates for Issue/Support Feature
-- ============================================================
-- Run this SQL script to update the issuereport table
-- with Status, Priority fields and updated Category enum
-- ============================================================

-- Step 1: Add Status field (to track Open/Resolved)
ALTER TABLE `issuereport` 
ADD COLUMN `Status` enum('Open','Resolved') DEFAULT 'Open' AFTER `Report_Date`;

-- Step 2: Add Priority field (for Sales Rep to set priority)
ALTER TABLE `issuereport` 
ADD COLUMN `Priority` enum('Low','Medium','High') DEFAULT 'Low' AFTER `Status`;

-- Step 3: Update Category enum to match form options
-- Note: This will require dropping and recreating the column due to enum limitations
-- First, let's check current data (backup recommended)

-- Update existing Category values to match new enum
UPDATE `issuereport` 
SET `Category` = 'Order Issue' 
WHERE `Category` = 'Order Issue';

UPDATE `issuereport` 
SET `Category` = 'Billing Issue' 
WHERE `Category` = 'Billing Issue';

UPDATE `issuereport` 
SET `Category` = 'Delivery Issue' 
WHERE `Category` = 'Delivery Issue';

-- Modify Category enum to include more options
ALTER TABLE `issuereport` 
MODIFY COLUMN `Category` enum(
    'Order Issue',
    'Payment Issue',
    'Delivery Issue',
    'General Inquiry',
    'Installation Problems',
    'Product Defect/Damage',
    'Measurement/Design Problems',
    'Billing/Payment Questions',
    'Other'
) DEFAULT NULL;

-- Step 4: Allow Customer_ID and Order_ID to be nullable for guest submissions
-- (Optional - if you want to allow guest submissions without requiring login)
-- ALTER TABLE `issuereport` 
-- MODIFY COLUMN `Customer_ID` int(11) NULL,
-- MODIFY COLUMN `Order_ID` int(11) NULL;

-- Step 5: Add index on Status for faster queries
ALTER TABLE `issuereport` 
ADD INDEX `idx_status` (`Status`);

-- Step 6: Add index on Priority for faster queries
ALTER TABLE `issuereport` 
ADD INDEX `idx_priority` (`Priority`);

-- ============================================================
-- Verification Query
-- ============================================================
-- Run this to verify the changes:
-- DESCRIBE `issuereport`;
-- SELECT * FROM `issuereport` LIMIT 1;

