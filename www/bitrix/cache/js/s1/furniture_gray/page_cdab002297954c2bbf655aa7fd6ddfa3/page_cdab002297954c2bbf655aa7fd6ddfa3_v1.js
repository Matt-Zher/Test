
; /* Start:"a:4:{s:4:"full";s:80:"/local/components/library/books.list/templates/.default/script.js?17838846401309";s:6:"source";s:65:"/local/components/library/books.list/templates/.default/script.js";s:3:"min";s:0:"";s:3:"map";s:0:"";}"*/
document.addEventListener('DOMContentLoaded', () => {

    const form = document.querySelector('form');
    const submitButton = document.getElementById('submit-btn');
    const cancelButton = document.getElementById('cancel-edit');

    const id = document.getElementById('book-id');
    const name = document.getElementById('book-name');
    const author = document.getElementById('book-author');
    const year = document.getElementById('book-year');
    const description = document.getElementById('book-description');

    cancelButton.addEventListener('click', () => {
        id.value = '';
        name.value = '';
        author.value = '';
        year.value = '';
        description.value = '';
        submitButton.value = 'create';
        submitButton.textContent = 'Добавить книгу';

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
/* End */
;; /* /local/components/library/books.list/templates/.default/script.js?17838846401309*/
