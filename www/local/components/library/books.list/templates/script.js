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