<?php
/*
Plugin Name: SendGrid Mailer
Description: Overrides the default mail sending functionality to use SendGrid.
Version: 1.0.0
Author: Swami
Text Domain: sendgrid-mailer
*/

if (!defined('ABSPATH')) die('You are not allowed to call this page directly.');


define('MY_PLUGIN_PATH', plugin_dir_path(__FILE__));


if (!file_exists(MY_PLUGIN_PATH . 'vendor/autoload.php')) {
    error_log('Vendor autoload file not found');
    return;
}
require_once MY_PLUGIN_PATH . 'vendor/autoload.php';
//get_gmt_from_date()

// Add admin menu
add_action('admin_menu', 'addSendGridMailMenu');
function addSendGridMailMenu() {
    add_menu_page(
        __('SendGrid','sendgrid-mailer'),
        __('SendGrid','sendgrid-mailer'),
        'manage_options',
        'sendgrid-mail',
        'mailPage',
        'dashicons-email-alt',
        10
    );
}

function mailPage() {
    if (!file_exists(MY_PLUGIN_PATH . '/page.php')) {
        error_log('FrontPage not found');
        return;
    }
    include_once MY_PLUGIN_PATH . '/page.php';
}

// Enqueue scripts
add_action('admin_enqueue_scripts', 'enqueueSendGridMailerScripts');
function enqueueSendGridMailerScripts($slug) {
    if ($slug !== 'toplevel_page_sendgrid-mail') {
        return;
    }

    wp_enqueue_script('sendgrid-mailer', plugin_dir_url(__FILE__) . 'js/sendgridmailer.js', 'jquery', '1.0', true);
    wp_localize_script('sendgrid-mailer', 'sendgridMailer', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('sendgrid_mailer_nonce'),
    ]);
}

// Handle AJAX form submission
add_action('wp_ajax_sendgrid_send_mail', 'handleSendGridSendMail');
function handleSendGridSendMail() {
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'sendgrid_mailer_nonce')) {
        wp_send_json_error(['message' => esc_html__('Nonce verification failed', 'sendgrid-mailer')]);
        return;
    }
    // Sanitize and validate input
    $to = sanitize_email($_POST['to']);
    $cc = sanitize_email($_POST['cc']);
    $subject = sanitize_text_field($_POST['subject']);
    $message = wp_kses_post($_POST['message']);

    // Validate required fields
    $errors = [];
    $required_fields = [
        'to' => esc_html__('TO','sendgrid-mailer'),
        'subject' => esc_html__('SUBJECT','sendgrid-mailer'),
        'message' => esc_html__('MESSAGE','sendgrid-mailer'),
    ];
    foreach ($required_fields as $field => $label) {
        if (empty($_POST[$field])) {
            /* translators: %s is the name of the empty field */
            $errors[] = sprintf(esc_html__('The "%s" field is empty','sendgrid-mailer'),$label);
        }
    }
    if (!empty($errors)) {
        wp_send_json_error(['field_errors' => $errors]);
    }
    // Send email using SendGrid
    $send_grid = new \SendGrid('API_KEY_TEXT');
    $email = new \SendGrid\Mail\Mail();
    $email->setFrom('swamii1413@gmail.com', 'Swami'); // Replace with your email
    $email->setSubject($subject);
    $email->addTo($to);
    if (!empty($cc)) {
        $email->addCc($cc);
    }
    $email->addContent("text/plain", $message);

    try {
        $response = $send_grid->send($email);
        $statusCode=$response->statusCode();
        if ($statusCode == 202) {
            wp_send_json_success(['message' => esc_html__('Email sent successfully!','sendgrid-mailer')]);
            return;
        }
        wp_send_json_error(['message' => esc_html__('Failed to send email. Status Code: ' . $response->statusCode(),'sendgrid-mailer')]);

    } catch (Exception $e) {
        wp_send_json_error(['message' => esc_html__('Caught exception: ' . $e->getMessage(),'sendgrid-mailer')]);
    }

}