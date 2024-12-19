function showLectures(category) {
    document.querySelectorAll('section').forEach(section => {
        section.classList.add('d-none');
    });
    document.getElementById(category).classList.remove('d-none');
}

function backToCategories() {
    document.querySelectorAll('section').forEach(section => {
        section.classList.add('d-none');
    });
    document.getElementById('view-lectures').classList.remove('d-none');
}