<?php

namespace Fbt\App\Controller;

class MainController
{

    /**
     * Adds a custom tab to the WooCommerce product data tabs.
     *
     * @param array $tabs Existing product data tabs.
     * @return array Modified product data tabs with the custom 'Frequently Bought Together' tab.
     */
    public static function addProductTab($tabs)
    {
        $tabs['fbt'] = ['label'    => __('Frequently Bought Together', 'fbtPlugin'),
            'target'   => 'fbt_product_data',
            'class'    => ['show_if_simple', 'show_if_variable'],
            'priority' => 60,];
        return $tabs;
    }

    /**
     * TDisplays the content of the 'Frequently Bought Together' custom tab.
     *
     * This method loads the view file for the 'Frequently Bought Together' tab in the product edit screen.
     * @return void
     *
     */

    public static function addProductTabView()
    {
        $template_path = 'products-view.php';
        if (file_exists(FBT_PATH . 'App/View/' . $template_path)) {
            wc_get_template($template_path, [], '', FBT_PATH . 'App/View/');
        }
    }

    /**
     * Enqueues the necessary scripts and styles for the plugin.
     * This method enqueues a CSS file and a JavaScript file for handling AJAX requests and localizes script data for use in JavaScript.
     *
     * @return void
     */

    public static function enqueueScripts()
    {
        wp_enqueue_style('fbt-style-css', FBT_URL . 'Assets/css/style.css');
        wp_enqueue_script('fbt-ajax-script', FBT_URL . 'Assets/js/script.js', ['jquery'], '1.0.1', true);
        wp_localize_script('fbt-ajax-script', 'fbt_ajax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('fbt_add_to_cart_nonce'),
            'selectProductMessage' => esc_html__('Please select at least one product.', 'fbtPlugin'),
        ]);
    }

    /**
     * Saves the selected 'Frequently Bought Together' products for a product.
     *
     * This function is called when a WooCommerce product is saved. It saves the selected frequently bought together products in the database as post meta.
     * @param int $post_id The ID of the current product being saved.
     * @return void
     *
     */

    public static function saveProducts($post_id)
    {
        if (isset($_POST['fbt_products'])) {
            $fbt_products = array_map('sanitize_text_field', $_POST['fbt_products']);
            update_post_meta($post_id, '_fbt_products', implode(',', $fbt_products));
        } else {
            delete_post_meta($post_id, '_fbt_products');
        }
    }

    /**
     * Displays the 'Frequently Bought Together' products on the single product page.
     *
     *  This method loads the view file that shows the selected frequently bought together products.
     *
     * @return void
     */
    public static function displayProducts()
    {
        $template_path = 'fbt-view.php';
        if (file_exists(FBT_PATH . 'App/View/' . $template_path)) {
            wc_get_template($template_path, [], '', FBT_PATH . 'App/View/');
        }
    }

    /**
     * Handles the AJAX request to add selected products to the cart.
     *
     *  This function verifies the nonce, processes the product IDs sent via the AJAX request,
     *  and adds each selected product to the WooCommerce cart.
     *
     * @return void
     * @throws Exception If nonce verification fails.
     */

    public static function addToCart()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'fbt_add_to_cart_nonce')) {
            wp_send_json_error(['message' => esc_html__('Nonce verification failed', 'frequently-bought-together')]);
            return;
        }

        if (isset($_POST['product_ids']) && is_array($_POST['product_ids'])) {
            foreach ($_POST['product_ids'] as $product_id) {
                WC()->cart->add_to_cart($product_id);
            }
            wp_send_json_success(['message' => esc_html__('Product added to the cart', 'frequently-bought-together')]);
        } else {
            wp_send_json_error(['message' => esc_html__('No products selected', 'frequently-bought-together')]);
        }
    }
}