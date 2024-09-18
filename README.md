# WordPress Plugins by swami1302

Welcome to the repository of custom WordPress plugins developed by **swami1302**. These plugins aim to extend the functionality of WordPress and WooCommerce with features such as auto-applying coupons, buy-one-get-one promotions, mail integration, and more.

## Plugins

### 1. **Auto Apply Coupon**
- **Name**: Auto Apply Coupon
- **Description**: Automatically apply a pre-configured coupon code to the cart when conditions are met.
- **Branch**: `Auto-apply-coupon`
- **Features**:
  - Automatically applies coupon codes without manual user input.
  - Supports custom rules for when the coupon is applied (e.g., cart total, product type).
  - Uses WooCommerce hooks and AJAX for real-time updates.

---

### 2. **Buy X Get X (BXGX)**
- **Name**: Buy X Get X
- **Description**: Offer a "Buy One, Get One Free" deal for specific products in WooCommerce.
- **Branch**: `buy-x-get-x`
- **Features**:
  - Automatically add a free product to the cart when the customer purchases a qualifying item.
  - Store owner can configure the offer directly on the product creation page.
  - Uses the MVC pattern with a router file for scalability.

---

### 3. **Delete Draft Posts Plugin**
- **Name**: Delete Draft Posts Plugin
- **Description**: Automatically delete old draft posts to improve WordPress site performance.
- **Branch**: `delete-draft-post-v01`
- **Features**:
  - Automatically delete 10 draft posts every day at 10 AM.
  - Admin can view deleted drafts in a custom admin menu page.
  - Designed for performance optimization of WordPress sites with a large number of drafts.

---

### 4. **Frequently Bought Together (FBT Plugin)**
- **Name**: Frequently Bought Together Plugin
- **Description**: Add "Frequently Bought Together" functionality to WooCommerce product pages, similar to Amazon.
- **Branch**: `frequently-bought-together`
- **Features**:
  - Allows users to select and display related products that are often purchased together.
  - A single-click option to add all FBT products to the cart.
  - Uses an AJAX-based searchable dropdown to select products in the WooCommerce admin panel.

---

### 5. **SendGrid Mailer**
- **Name**: SendGrid Mailer
- **Description**: Integrates SendGrid for sending transactional and marketing emails via WordPress.
- **Branch**: `sendGrid-mailer`
- **Features**:
  - Allows easy configuration of SendGrid API settings within WordPress.
  - Send transactional emails (e.g., order confirmations) via SendGrid.
  - Supports SendGrid's templating and marketing features for email campaigns.

---

## How to Use

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/swami1302/wordpress-plugins.git
