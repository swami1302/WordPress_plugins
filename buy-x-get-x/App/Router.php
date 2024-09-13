<?php

namespace BXGX\App;

use BXGX\App\controller\Controller;

// Define the Router class
class Router {

    // Initialize the router
    public function init() {
        // Check if the current user is an admin
        if (is_admin()) {
            // Add a menu item to the admin dashboard
            add_action('admin_menu', [Controller::class, 'addMenu']);
            // Enqueue scripts and styles for the admin area
            add_action('admin_enqueue_scripts', [Controller::class, 'enqueueScripts']);
        } else {
            // Frontend hooks for non-admin users

            // Display a custom message on the single product summary
            add_action('woocommerce_single_product_summary', [Controller::class, 'displayMessage']);
            // Add a free product to the cart when a qualifying product is added
            add_action('woocommerce_add_to_cart', [Controller::class, 'addToCart'], 10, 6);
            // Set the price of the free product to zero before totals are calculated
            add_action('woocommerce_before_calculate_totals', [Controller::class, 'setProductPrice'], 10, 1);
            // Update the quantity of the free product when the main product quantity changes
            add_action('woocommerce_after_cart_item_quantity_update', [Controller::class, 'updateProductQuantity'], 10, 4);
            // Remove the free product when the main product is removed from the cart
            add_action('woocommerce_cart_item_removed', [Controller::class, 'removeFreeProductWhenMainProductRemoved'], 10, 2);
            add_filter('woocommerce_cart_item_quantity', [Controller::class, 'disableFreeProductQuantityField'], 10, 3);
            add_filter('woocommerce_cart_item_remove_link', [Controller::class, 'disableRemoveButtonForFreeProducts'], 10, 5);
            add_action('woocommerce_cart_loaded_from_session', [Controller::class, 'bxgxCheckCartForRemovedProducts'], 20, 1);

        }

        // Check if the request is an AJAX call
        if (wp_doing_ajax()) {
            // Handle AJAX request for searching products
            add_action('wp_ajax_bxgx_search_products', [Controller::class, 'searchProducts']);
            // Handle AJAX request for saving selected products
            add_action('wp_ajax_bxgx_save_selected_products', [Controller::class, 'saveSelectedProducts']);
        }
    }
}
