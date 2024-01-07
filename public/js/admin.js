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
    const usersList = document.querySelector('#usersList');

    if (listType === 'reviews') {
        reviewsList.style.display = 'flex';
        booksList.style.display = 'none';
        usersList.style.display = 'none';
    } else if (listType === 'books') {
        reviewsList.style.display = 'none';
        booksList.style.display = 'flex';
        usersList.style.display = 'none';
    }
    else if (listType === 'users') {
        reviewsList.style.display = 'none';
        booksList.style.display = 'none';
        usersList.style.display = 'flex';
    }
}