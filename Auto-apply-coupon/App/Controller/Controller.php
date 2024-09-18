<?php
namespace AAC\App\Controller;

class Controller {

    // Function to generate and store a coupon code in the WooCommerce session
    public static function generateCouponCode() {
        // Retrieve the coupon code stored in the WooCommerce session
        $coupon_code = WC()->session->get('acc_coupon_code');

        // Check if the coupon code does not exist or if the stored coupon is not applied in the cart
        if (!$coupon_code || !in_array($coupon_code, WC()->cart->get_applied_coupons())) {
            $length = 10;
            $characters = 'abcdefghijklmnopqrstuvwxyz1234567890';

            // Shuffle the characters and extract a substring to generate a coupon of the defined length
            $coupon = substr(str_shuffle($characters), 0, $length);

            // Ensure that the generated coupon is not already in the list of applied coupons in the cart
            if (!in_array($coupon, WC()->cart->applied_coupons)) {
                // Add the newly generated coupon to the cart's applied coupons list
                WC()->cart->applied_coupons[] = $coupon;

                // Store the generated coupon code in the WooCommerce session for future reference
                WC()->session->set("acc_coupon_code", $coupon);
            }
        }
    }

    // Function to verify if a coupon code matches the one in the session and return the discount details
    public static function setCoupon($response, $code) {
        // Retrieve the stored coupon code from the WooCommerce session
        error_log($response);
        error_log($code);
        $coupon_code = WC()->session->get('acc_coupon_code');

        // Check if the provided coupon code matches the stored session coupon
        if ($code === $coupon_code) {
            // Return the discount details for the matching coupon
            return [
                'id' => 0,                 // The ID can be used as an identifier (could be dynamic or set to 0)
                'amount' => 80,            // Discount amount (in this case, an 80% discount)
                'discount_type' => 'percent', // Discount type is set to 'percent' (percentage discount)
            ];
        }

        // If the coupon code doesn't match, return the original response (no discount applied)
        return $response;
    }
}
