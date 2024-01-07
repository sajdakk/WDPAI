function previewImage() {
    const imageUpload = document.querySelector('#imageUpload');
    const imagePreview = document.querySelector('#imagePreview');
    const avatarForm = document.querySelector('#avatarForm');

    if (imageUpload.files.length > 0) {
        const selectedImage = URL.createObjectURL(imageUpload.files[0]);
        imagePreview.innerHTML = `<img src="${selectedImage}" alt="Selected Image">`;

        // Trigger form submission when an image is selected
        avatarForm.submit();
    } else {
        imagePreview.innerHTML = `<div class="placeholder-image"></div>`;
    }
}

function toggleMenuItem(clickedItem, listType) {
    // Get all menu items
    const menuItems = document.querySelectorAll('.profile-menu-item');

    // Remove "selected" class from all items
    menuItems.forEach(item => {
        item.classList.remove('selected-profile-menu-item');
        item.classList.add('not-selected-profile-menu-item');
    });

    // Add "selected" class to the clicked item
    clickedItem.classList.add('selected-profile-menu-item');
    clickedItem.classList.remove('not-selected-profile-menu-item');

    // Show/hide the corresponding list based on the selected menu item
    const reviewsList = document.querySelector('#reviewsList');
    const booksList = document.querySelector('#booksList');

    if (listType === 'reviews') {
        reviewsList.style.display = 'flex';
        booksList.style.display = 'none';
    } else {
        reviewsList.style.display = 'none';
        booksList.style.display = 'flex';
    }
}