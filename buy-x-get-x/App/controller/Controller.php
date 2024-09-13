<?php

namespace BXGX\App\controller;
use BXGX\utils\Helper;
use BXGX\App\model\Model;

// Define the Controller class
class Controller {

    // Add a menu item to the WordPress admin dashboard
    public static function addMenu() {
        add_menu_page(
            'Buy-x Get-x',                    // Page title
            'Buy-x Get-x',                    // Menu title
            'manage_options',                 // Capability required to access this menu
            'buyx-getx',                      // Menu slug
            [self::class, 'bxgxViewPage'],    // Callback function to display the page content
            'dashicons-cart',                 // Menu icon
            6                                 // Position in the menu
        );
    }

    // Display the custom admin page
    public static function bxgxViewPage() {
        if(Helper::isCurrentPage('buyx-getx')){
            $template_path = 'bxgx-view.php'; // Template file name

            // Check if the template file exists
            if (file_exists(BXGX_PATH . 'App/view/' . $template_path)) {
                // Load the template file
                wc_get_template($template_path, [], '', BXGX_PATH . 'App/view/');
            }
        }

    }

    // Enqueue scripts and styles in the admin area
    public static function enqueueScripts() {
        // Enqueue custom JavaScript file with jQuery as a dependency
        wp_enqueue_script('script', BXGX_URL . 'assets/js/script.js', ['jquery'], '1.0.4', true);
        // Enqueue custom CSS file
        wp_enqueue_style('style', BXGX_URL . 'assets/css/style.css', [], '1.0', 'all');
        // Localize script to pass AJAX URL to JavaScript
        wp_localize_script('script', 'bxgxAjax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('bxgx_nonce'),
        ]);
    }

    // Handle AJAX request for searching products
    public static function searchProducts() {
        // Sanitize the search query
        $search_query = sanitize_text_field($_POST['query']);
        $response = [];

        // Proceed if the search query is at least 3 characters
        if (strlen($search_query) >= 3) {
            // Query arguments to search for products
            $args = [
                'post_type'      => 'product',
                'posts_per_page' => 20,
                'post_status'    => 'publish',
                's'              => $search_query,
                'fields'         => 'ids'
            ];

            // Get products matching the search query
            $products = get_posts($args);

            // Prepare the response data
            foreach ($products as $product_id) {
                $response[] = [
                    'id'   => $product_id,
                    'name' => get_the_title($product_id)
                ];
            }
        }

        // Send the response in JSON format
        wp_send_json($response);
    }

    // Handle AJAX request for saving selected products
    public static function saveSelectedProducts() {
        // Check if products are provided in the request
        if (isset($_POST['products']) || isset($_POST['remove_products'])) {
            // Retrieve previously selected products

            // Sanitize and prepare the list of newly selected products
            $products_selected = isset($_POST['products']) ? array_map('intval', array_map('sanitize_text_field', $_POST['products'])) : [];

            $all_selected_products = Model::getSelectedProducts();
            // Remove meta for products that need to be removed
            if (!empty($all_selected_products)) {
                foreach ($all_selected_products as $product_id) {
                    error_log("Product to unselect: ID = $product_id, Name = " . get_the_title($product_id));
                    delete_post_meta($product_id, '_bxgx_products');
                }
            }

            // Update meta for products that need to be selected
            if (!empty($products_selected)) {
                foreach ($products_selected as $product_id) {
                    update_post_meta($product_id, '_bxgx_products', 'yes'); // Mark product as selected
                }
            }

            // Send success response
            wp_send_json_success(['message' => 'Products updated successfully!']);
        } else {
            // Send error response if no products are selected
            wp_send_json_error(['message' => 'No products selected.']);
        }
    }


    // Add a free product to the cart when a qualifying product is added
    public static function addToCart($cart_item_key, $product_id, $quantity) {
        $cart = WC()->cart;

        // Check if the product is part of the BXGX offer
        if (get_post_meta($product_id, '_bxgx_products', true) === 'yes') {
            $free_product_id = $product_id;
            $found = false;

            // Check if the free product is already in the cart
            foreach ($cart->get_cart() as $key => $values) {
                if ($values['product_id'] == $free_product_id && isset($values['is_free'])) {
                    $cart->set_quantity($key, $quantity); // Update quantity if already in the cart
                    $found = true;
                    break;
                }
            }

            // Add the free product to the cart if not already added
            if (!$found) {
                $cart->add_to_cart($free_product_id, $quantity, 0, [], ['is_free' => true, 'main_product_key' => $cart_item_key]);
            }

        }
    }


    // Display a custom message on the single product page
    public static function displayMessage() {
        $template_path = BXGX_PATH . 'App/view/'; // Path to the template directory
        // Load the frontend template file
        wc_get_template('frontend.php', [], '', $template_path);
    }

    // Set the price of the free product to zero before calculating totals
    public static function setProductPrice($cart) {
        // Loop through each cart item
        foreach ($cart->get_cart() as $cart_item) {
            // Check if the cart item is a free product
            if (isset($cart_item['is_free'])) {
                // Set the product price to zero
                $cart_item['data']->set_price(0);
            }
        }
    }

    // Update the quantity of the free product when the main product quantity changes
    public static function updateProductQuantity($cart_item_key, $quantity, $old_quantity, $cart) {
        // Loop through each cart item
        foreach ($cart->get_cart() as $key => $cart_item) {
            // Check if the cart item is a free product associated with the main product
            if (isset($cart_item['is_free']) && $cart_item['main_product_key'] == $cart_item_key) {
                // Update the quantity of the free product
                WC()->cart->set_quantity($key, $quantity);
            }
        }
    }

    // Remove the free product when the main product is removed from the cart
    public static function removeFreeProductWhenMainProductRemoved($cart_item_key, $cart) {
        // Get the cart item for the removed product
        $removed_item = WC()->cart->get_cart_item($cart_item_key);

        // Check if the removed item is a main product (not free)
        if (!isset($removed_item['is_free']) || $removed_item['is_free'] === false) {
            $main_product_key = $cart_item_key;  // The removed item is the main product

            // Loop through all items in the cart
            foreach ($cart->get_cart() as $key => $cart_item) {
                // Check if the item is a free product associated with the main product
                if (isset($cart_item['is_free']) && $cart_item['is_free'] === true && $cart_item['main_product_key'] === $main_product_key) {
                    // Remove the free product from the cart
                    WC()->cart->remove_cart_item($key);
                }
            }
        }
    }



    public static function disableFreeProductQuantityField($quantity, $cart_item_key, $cart_item) {
        // Check if the cart item is a free product
        if (isset($cart_item['is_free']) && $cart_item['is_free']) {
            // Return the current quantity but without the input field (disable manual changes)
            return $cart_item['quantity'];
        }

        // If it's not a free product, return the default quantity input field
        return $quantity;
    }

    public static function disableRemoveButtonForFreeProducts($remove_link, $cart_item_key) {
        $cart_item = WC()->cart->get_cart_item($cart_item_key);
        if (isset($cart_item['is_free']) && $cart_item['is_free'] == true) {
            return '<span class="disabled-remove-icon"><i class="dashicons dashicons-trash" style="display: none"></i></span>';
        }
        return $remove_link;
    }

    public static function bxgxCheckCartForRemovedProducts($cart) {
        foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
            // Check if the cart item is a free product
            if (isset($cart_item['is_free']) && $cart_item['is_free'] === true) {
                $main_product_key = $cart_item['main_product_key'];  // Get the main product key

                // Ensure the main product key is valid and get its corresponding main product
                if (WC()->cart->get_cart_item($main_product_key) !== null) {
                    $main_product_id = WC()->cart->get_cart_item($main_product_key)['product_id'];

                    // Check if the main product is still part of the BXGX offer by c   hecking the meta
                    $bxgx_status = get_post_meta($main_product_id, '_bxgx_products', true);

                    // If the main product is no longer part of the BXGX offer, remove the free product
                    if ($bxgx_status !== 'yes') {
                        WC()->cart->remove_cart_item($cart_item_key);
                    }
                }
            }
        }
    }

}