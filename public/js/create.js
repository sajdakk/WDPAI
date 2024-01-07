function addAuthor() {
    var authorName = document.querySelector('#authorName').value.trim();
    var authorSurname = document.querySelector('#authorSurname').value.trim();
    if (authorName !== '' && authorSurname !== '') {
        var checkboxContainer = document.querySelectorAll('.checkboxContainer')[0];
        var newCheckbox = document.createElement('div');
        newCheckbox.className = 'checkbox-input';

        newCheckbox.innerHTML = '<input type="checkbox" checked name="authors[]" value="' + authorName + ' ' + authorSurname + '">' + authorName + ' ' + authorSurname;
        checkboxContainer.appendChild(newCheckbox);
        document.querySelector('#authorName').value = '';
        document.querySelector('#authorSurname').value = '';

    }
}

function filterCheckboxes() {
    // Pobierz wartość z pola wyszukiwania
    var searchText = document.querySelector('#searchInput').value.trim().toLowerCase();

    // Pobierz kontener z checkboxami
    var checkboxContainer = document.querySelectorAll('.checkboxContainer');

    // Pobierz wszystkie checkboxy w kontenerze
    var checkboxes = checkboxContainer[0].querySelectorAll('.checkbox-input');

    // Iteruj przez checkboxy i ukryj/ pokaż w zależności od tekstu wyszukiwania
    for (var i = 0; i < checkboxes.length; i++) {
        var checkboxLabel = checkboxes[i].children[0].nextSibling.nodeValue.trim().toLowerCase();
        console.log(checkboxes[i]);

        if (checkboxLabel.includes(searchText)) {
            checkboxes[i].style.display = 'block';
        } else {
            checkboxes[i].style.display = 'none';
        }
    }
}

function previewImage() {
    const imageUpload = document.querySelector('#imageUpload');
    const imagePreview = document.querySelector('#imagePreview');

    if (imageUpload.files && imageUpload.files[0]) {
        const reader = new FileReader();

        reader.onload = function (e) {
            const preview = document.createElement('img');
            preview.src = e.target.result;
            imagePreview.innerHTML = '';
            imagePreview.appendChild(preview);
        };

        reader.readAsDataURL(imageUpload.files[0]);
    } else {
        imagePreview.innerHTML = 'No image selected';
    }
}