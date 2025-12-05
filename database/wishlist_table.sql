-- Wishlist Table for Glassify
-- Run this SQL in your database to create the wishlist table

CREATE TABLE IF NOT EXISTS `wishlist` (
    `Wishlist_ID` INT(11) NOT NULL AUTO_INCREMENT,
    `Customer_ID` INT(11) NOT NULL,
    `Product_ID` INT(11) NOT NULL,
    `CustomizationID` INT(11) DEFAULT NULL,
    `DateAdded` DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`Wishlist_ID`),
    KEY `fk_wishlist_customer` (`Customer_ID`),
    KEY `fk_wishlist_product` (`Product_ID`),
    KEY `fk_wishlist_customization` (`CustomizationID`),
    CONSTRAINT `fk_wishlist_customer` FOREIGN KEY (`Customer_ID`) REFERENCES `customer` (`Customer_ID`) ON DELETE CASCADE,
    CONSTRAINT `fk_wishlist_product` FOREIGN KEY (`Product_ID`) REFERENCES `product` (`Product_ID`) ON DELETE CASCADE,
    CONSTRAINT `fk_wishlist_customization` FOREIGN KEY (`CustomizationID`) REFERENCES `customization` (`CustomizationID`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
