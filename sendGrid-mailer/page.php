<?php
if (!defined('ABSPATH')) {
    die(esc_html__("You cannot be here", 'sendgrid-mailer'));
}
?>
<div class="wrap">
    <h1><?php echo esc_html__('Send Grid Mailer', 'sendgrid-mailer'); ?></h1>
    <form id="sendgrid_mail_form" method="post">
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><label for="to"><?php echo esc_html__('To', 'sendgrid-mailer'); ?> *</label></th>
                <td><input type="email" name="to" id="to" class="regular-text" style="width: 400px;" placeholder="<?php echo esc_attr__('Enter recipient\'s email', 'sendgrid-mailer'); ?>" required></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="cc"><?php echo esc_html__('CC', 'sendgrid-mailer'); ?></label></th>
                <td><input type="email" name="cc" id="cc" class="regular-text" style="width: 400px;" placeholder="<?php echo esc_attr__('Enter CC email', 'sendgrid-mailer'); ?>"></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="subject"><?php echo esc_html__('Subject', 'sendgrid-mailer'); ?> *</label></th>
                <td><input type="text" name="subject" id="subject" class="regular-text" style="width: 400px;" placeholder="<?php echo esc_attr__('Enter subject', 'sendgrid-mailer'); ?>" required></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="message"><?php echo esc_html__('Message', 'sendgrid-mailer'); ?> *</label></th>
                <td><textarea name="message" id="message" class="large-text" style="width: 100%; height: 200px;" placeholder="<?php echo esc_attr__('Enter your message', 'sendgrid-mailer'); ?>" required></textarea></td>
            </tr>
        </table>
        <p class="submit"><input type="submit" name="sendgrid_send_mail" id="sendgrid_send_mail" class="button button-primary" value="<?php echo esc_attr__('Send Email', 'sendgrid-mailer'); ?>"></p>
    </form>
</div>
