<?php

namespace Fbt\App;

use Fbt\App\Controller\MainController;

class Router
{
    public static function init()
    {

        // Adds a new tab in the WooCommerce product data section for 'Frequently Bought Together' products.
        add_filter('woocommerce_product_data_tabs', [MainController::class, 'addProductTab']);

        // Displays the content of the 'Frequently Bought Together' tab on the product edit screen.
        add_action('woocommerce_product_data_panels', [MainController::class, 'addProductTabView']);

        // Saves the selected 'Frequently Bought Together' products when a product is saved.
        add_action('woocommerce_process_product_meta', [MainController::class, 'saveProducts']);

        // Enqueues admin-specific scripts and styles needed for the plugin functionality.
        add_action('admin_enqueue_scripts', [MainController::class, 'enqueueScripts']);

        // Enqueues frontend scripts and styles needed for displaying 'Frequently Bought Together' products.
        add_action('wp_enqueue_scripts', [MainController::class, 'enqueueScripts']);

        // Displays the 'Frequently Bought Together' products on the single product page.
        add_action('woocommerce_after_single_product', [MainController::class, 'displayProducts']);

        // Checks if the request is an AJAX call and registers AJAX actions for adding products to the cart.
        if (wp_doing_ajax()) {
            // Handles AJAX request for logged-in users to add 'Frequently Bought Together' products to the cart.
            add_action('wp_ajax_fbt_add_to_cart', [MainController::class, 'addToCart']);

            // Handles AJAX request for guest users to add 'Frequently Bought Together' products to the cart.
            add_action('wp_ajax_nopriv_fbt_add_to_cart', [MainController::class, 'addToCart']);
        }
    }
}