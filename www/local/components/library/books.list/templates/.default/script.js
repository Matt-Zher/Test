document.addEventListener('DOMContentLoaded', () => {

    const form = document.querySelector('form');
    const submitButton = document.getElementById('submit-btn');
    const cancelButton = document.getElementById('cancel-edit');
    const cancelFilter = document.getElementById('cancel-filter');

    const id = document.getElementById('book-id');
    const name = document.getElementById('book-name');
    const author = document.getElementById('book-author');
    const year = document.getElementById('book-year');
    const description = document.getElementById('book-description');

    const name_filter = document.getElementById('book-name-filter');
    const author_filter = document.getElementById('book-author-filter');
    const year_filter = document.getElementById('book-year-filter');
    const description_filter = document.getElementById('book-description-filter');

    cancelButton.addEventListener('click', () => {
        id.value = '';
        name.value = '';
        author.value = '';
        year.value = '';
        description.value = '';
        submitButton.value = 'create';
        submitButton.textContent = 'Добавить книгу';

    });
    cancelFilter.addEventListener('click', () => {
        name_filter.value = '';
        author_filter.value = '';
        year_filter.value = '';
    });
    document.querySelectorAll('.book-row').forEach(row => {

        row.addEventListener('click', () => {

            id.value = row.dataset.id;
            name.value = row.dataset.name;
            author.value = row.dataset.author;
            year.value = row.dataset.year;
            description.value = row.dataset.description;

            submitButton.value = 'update';
            submitButton.textContent = 'Сохранить изменения';

        });

    });

});