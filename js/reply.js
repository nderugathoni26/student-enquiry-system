    // jQuery to show/hide reply form on button click
    $(document).ready(function() {
        $('.reply-btn').click(function() {
            var messageId = $(this).data('message-id');
            $('#reply-form-' + messageId).toggle();  // Toggle the visibility of the reply form
        });
    });