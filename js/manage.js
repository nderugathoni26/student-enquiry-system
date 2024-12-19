function toggleResponseForm(enquiryId) {
    const form = document.getElementById(`response-form-${enquiryId}`);
    form.classList.toggle('d-none');
}

function toggleRejectForm(enquiryId) {
    const form = document.getElementById(`reject-form-${enquiryId}`);
    form.classList.toggle('d-none');
}