// JavaScript functions to show notes/exam sections
function showLectures(type) {
    document.getElementById('view-resources').classList.add('d-none');
    document.getElementById('notes').classList.add('d-none');
    document.getElementById('Exam').classList.add('d-none');
    if (type === 'notes') {
        document.getElementById('notes').classList.remove('d-none');
    } else if (type === 'Exam') {
        document.getElementById('Exam').classList.remove('d-none');
    }
}

// Search and filter notes
function searchNotes() {
    const searchTerm = document.getElementById('searchNotes').value.toLowerCase();
    const noteItems = document.querySelectorAll('.note-item');
    noteItems.forEach(item => {
        const text = item.textContent.toLowerCase();
        item.style.display = text.includes(searchTerm) ? '' : 'none';
    });
}

function filterNotes() {
    const filterValue = document.getElementById('filterWeek').value.toLowerCase();
    const noteItems = document.querySelectorAll('.note-item');
    noteItems.forEach(item => {
        const week = item.getAttribute('data-week').toLowerCase();
        item.style.display = (filterValue === 'all' || week === filterValue) ? '' : 'none';
    });
}

// Search and filter exams
function searchExams() {
    const searchTerm = document.getElementById('searchExams').value.toLowerCase();
    const examItems = document.querySelectorAll('.exam-item');
    examItems.forEach(item => {
        const text = item.textContent.toLowerCase();
        item.style.display = text.includes(searchTerm) ? '' : 'none';
    });
}

function filterExams() {
    const filterValue = document.getElementById('filterYear').value;
    const examItems = document.querySelectorAll('.exam-item');
    examItems.forEach(item => {
        const year = item.getAttribute('data-year');
        item.style.display = (filterValue === 'all' || year === filterValue) ? '' : 'none';
    });
}

// Back to resource segments
function backToSegments() {
    document.getElementById('notes').classList.add('d-none');
    document.getElementById('Exam').classList.add('d-none');
    document.getElementById('view-resources').classList.remove('d-none');
}