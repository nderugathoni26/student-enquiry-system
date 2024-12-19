function toggleReplyInput(messageId) {
    var replyDiv = document.getElementById('reply-' + messageId);
    if (replyDiv.style.display === 'none' || replyDiv.style.display === '') {
        replyDiv.style.display = 'block';
    } else {
        replyDiv.style.display = 'none';
    }
}