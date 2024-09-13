jQuery(document).ready(function($) {
    $('#sendgrid_mail_form').on('submit', function(event) {
        event.preventDefault();

        var formData = {
            action: 'sendgrid_send_mail',
            nonce: sendgridMailer.nonce,
            to: $('#to').val(),
            cc: $('#cc').val(),
            subject: $('#subject').val(),
            message: $('#message').val(),

        };

        $.post(sendgridMailer.ajaxUrl, formData, function(response) {
            if (response.success) {

                alert(`${response.data.message}`);
                $('#sendgrid_mail_form')[0].reset();

            } else {
                alert(`Error ${response.data.message}`);
                $('#sendgrid_mail_form')[0].reset();
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error("AJAX request failed: " + textStatus + ", " + errorThrown);
            alert("An error occurred while sending the message. Please try again.");
        });
    });
});
