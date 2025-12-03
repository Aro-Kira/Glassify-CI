-- ============================================================
-- Fix Foreign Key Constraint for Guest Submissions
-- ============================================================
-- This allows guests to submit issues without requiring
-- a Customer_ID that exists in the customer table
-- ============================================================

-- Step 1: Drop existing foreign key constraints
ALTER TABLE `issuereport` 
DROP FOREIGN KEY `issuereport_ibfk_1`,
DROP FOREIGN KEY `issuereport_ibfk_2`;

-- Step 2: Make Customer_ID nullable (allows NULL for guests)
ALTER TABLE `issuereport` 
MODIFY COLUMN `Customer_ID` int(11) NULL;

-- Step 3: Make Order_ID nullable (allows NULL for guests without orders)
ALTER TABLE `issuereport` 
MODIFY COLUMN `Order_ID` int(11) NULL;

-- Step 4: Re-add foreign key constraints with ON DELETE SET NULL
-- This allows the foreign key but sets NULL if customer/order is deleted
ALTER TABLE `issuereport`
ADD CONSTRAINT `issuereport_ibfk_1` 
FOREIGN KEY (`Customer_ID`) 
REFERENCES `customer` (`Customer_ID`) 
ON DELETE SET NULL 
ON UPDATE CASCADE;

ALTER TABLE `issuereport`
ADD CONSTRAINT `issuereport_ibfk_2` 
FOREIGN KEY (`Order_ID`) 
REFERENCES `order` (`OrderID`) 
ON DELETE SET NULL 
ON UPDATE CASCADE;

-- ============================================================
-- Verification
-- ============================================================
-- Check that columns are now nullable:
-- DESCRIBE issuereport;
-- You should see "YES" in the "Null" column for Customer_ID and Order_ID

