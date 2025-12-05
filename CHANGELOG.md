# CHANGELOG - Glassify CI Project

## Version 1.1.0 - Wishlist Feature Implementation
**Date:** December 5, 2025  
**Branch:** customer-branch

---

## 📋 Summary of Changes

This update introduces a complete **Wishlist System** allowing customers to save products (with or without customizations) for later purchase.

---

## 🆕 New Features

### 1. Wishlist Functionality
- **Add to Wishlist**: Users can save products to their wishlist from the 2D customization page
- **Save Customizations**: When adding to wishlist, all glass customizations (shape, type, thickness, edge, frame, engraving, and design image) are saved
- **Move to Cart**: Items can be moved from wishlist directly to cart with one click
- **Remove Items**: Individual items can be removed from wishlist
- **Clear Wishlist**: Users can clear all items at once
- **Design Preview**: Custom design images can be viewed in a modal with download option

---

## 📁 New Files Created

### Controllers
| File | Description |
|------|-------------|
| `application/controllers/WishlistCon.php` | Handles all wishlist AJAX operations (add, remove, move to cart, clear, check status) |

### Models
| File | Description |
|------|-------------|
| `application/models/Wishlist_model.php` | Database operations for wishlist table and customization handling |

### JavaScript
| File | Description |
|------|-------------|
| `assets/js/wishlist.js` | Wishlist page interactions (remove, move to cart, clear) |
| `assets/js/2d-functions/addtowishlist.js` | Add to wishlist button functionality with animations |

### Views
| File | Description |
|------|-------------|
| `application/views/shop/wishlist.php` | Wishlist page view with table and design modal |

### CSS
| File | Description |
|------|-------------|
| `assets/css/general-customer/shop/wishlist_style.css` | Styles for wishlist page |

---

## 📝 Modified Files

### Routes (`application/config/routes.php`)
Added the following routes:
```php
$route['wishlist'] = 'WishlistCon/index';
$route['wishlist/add/(:num)'] = 'WishlistCon/add/$1';
$route['wishlist/add-ajax'] = 'WishlistCon/add_ajax';
$route['wishlist/remove-ajax'] = 'WishlistCon/remove_ajax';
$route['wishlist/clear-ajax'] = 'WishlistCon/clear_ajax';
$route['wishlist/move-to-cart'] = 'WishlistCon/move_to_cart_ajax';
$route['wishlist/count'] = 'WishlistCon/get_count_ajax';
$route['wishlist/check'] = 'WishlistCon/check_ajax';
```

### Header (`application/views/includes/header.php`)
- Added wishlist heart icon in navigation
- Links to wishlist page (redirects to login if not logged in)

### 2D Modeling Page (`application/views/shop/2DModeling.php`)
- Added "Add to Wishlist" button with heart icon
- Button appears alongside the "Add to Cart" button

---

## 🗄️ Database Changes

### New Table: `wishlist`
**SQL File:** `database/wishlist_table.sql`

```sql
CREATE TABLE IF NOT EXISTS `wishlist` (
    `Wishlist_ID` INT(11) NOT NULL AUTO_INCREMENT,
    `Customer_ID` INT(11) NOT NULL,
    `Product_ID` INT(11) NOT NULL,
    `CustomizationID` INT(11) DEFAULT NULL,
    `DateAdded` DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`Wishlist_ID`),
    CONSTRAINT `fk_wishlist_customer` FOREIGN KEY (`Customer_ID`) 
        REFERENCES `customer` (`Customer_ID`) ON DELETE CASCADE,
    CONSTRAINT `fk_wishlist_product` FOREIGN KEY (`Product_ID`) 
        REFERENCES `product` (`Product_ID`) ON DELETE CASCADE,
    CONSTRAINT `fk_wishlist_customization` FOREIGN KEY (`CustomizationID`) 
        REFERENCES `customization` (`CustomizationID`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### Modified Table: `customization`
**SQL File:** `database/customization_update.sql`

Added the following columns:

| Column | Type | Description |
|--------|------|-------------|
| `DesignRef` | VARCHAR(255) | Path to saved design image from Konva.js canvas |
| `PriceBreakdown` | TEXT | JSON string containing price breakdown details |
| `CreatedAt` | TIMESTAMP | Record creation timestamp |
| `UpdatedAt` | TIMESTAMP | Auto-updated on record modification |

Added indexes:
- `idx_customer_id` on `Customer_ID`
- `idx_product_id` on `Product_ID`

### How to Apply Database Changes
1. Navigate to phpMyAdmin or MySQL command line
2. Select your Glassify database
3. Run the SQL files in this order:
   - `database/customization_update.sql` (updates existing table)
   - `database/wishlist_table.sql` (creates new table)

---

## 🔧 Technical Implementation

### Database Tables Used
- `wishlist` - Stores wishlist items with Customer_ID, Product_ID, CustomizationID
- `customization` - Stores glass customization details (shape, dimensions, type, etc.)
- `product` - Product information
- `cart` - Shopping cart for move-to-cart functionality

### Key Features

#### 1. Customization Preservation
When adding a customized product to wishlist:
- All customization options are saved to the `customization` table
- Design canvas image (from Konva) is saved as PNG to `uploads/designs/`
- Estimated price is calculated and stored

#### 2. Move to Cart
When moving from wishlist to cart:
- Creates a **duplicate** customization entry (keeps original for wishlist)
- Removes item from wishlist after successful cart addition
- Updates both cart and wishlist counters

#### 3. Duplicate Prevention
- System checks if product (with same customization) already exists in wishlist
- Shows "Already in Wishlist" notification if duplicate attempt

#### 4. UI/UX Enhancements
- Animated heart button with pop and particle effects
- Smooth slide-out animations when removing items
- Toast notifications for success/error states
- Design preview modal with download capability

---

## 🎨 User Interface

### Wishlist Page Features
- Table layout showing: Image, Product Name, Customization Details, Price, Actions
- Custom design thumbnails with click-to-enlarge modal
- "Add to Cart" button for each item
- "Remove" button (X) for each item
- "Clear Wishlist" link to remove all items
- Empty state with browse products link

### 2D Modeling Page
- Heart icon button next to "Add to Cart"
- Visual feedback when item is added (heart fills red)
- Button disables after adding to prevent duplicates

---

## 🔐 Security

- All wishlist operations require user authentication
- Session validation on every AJAX request
- Customer can only access their own wishlist items
- CSRF protection on form submissions

---

## 📌 Notes

- Wishlist items with customizations store the design image in `uploads/designs/`
- When clearing wishlist, associated customization records are also deleted
- The wishlist count can be displayed in header using `#wishlist-count` element

---

## 🧪 Testing Checklist

- [ ] Add product without customization to wishlist
- [ ] Add product with full customization to wishlist
- [ ] Check duplicate prevention works
- [ ] Remove single item from wishlist
- [ ] Clear entire wishlist
- [ ] Move item to cart
- [ ] View design preview modal
- [ ] Download design image
- [ ] Test with logged out user (should redirect to login)
- [ ] Test wishlist persistence after logout/login

---

*End of Changelog*
