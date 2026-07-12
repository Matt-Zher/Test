
; /* Start:"a:4:{s:4:"full";s:79:"/local/components/library/books.list/templates/.default/script.js?1783284005991";s:6:"source";s:65:"/local/components/library/books.list/templates/.default/script.js";s:3:"min";s:0:"";s:3:"map";s:0:"";}"*/
document.querySelectorAll('.book-row').forEach(row => {

    row.addEventListener('click', function () {

        document.getElementById('book-id').value = this.dataset.id;

        document.getElementById('book-name').value = this.dataset.name;

        document.getElementById('book-author').value = this.dataset.author;

        document.getElementById('book-year').value = this.dataset.year;

        document.getElementById('book-description').value = this.dataset.description;

        const button = document.getElementById('submit-btn');

        button.value = 'update';
        button.textContent = 'Сохранить изменения';

    });

});

document.getElementById('cancel-edit').addEventListener('click', function () {

    document.querySelector('form').reset();

    document.getElementById('book-id').value = '';

    const button = document.getElementById('submit-btn');

    button.value = 'create';
    button.textContent = 'Добавить книгу';

});
/* End */
;; /* /local/components/library/books.list/templates/.default/script.js?1783284005991*/
