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


async function toggleUserStatus(e, userId) {
    e.preventDefault(); // prevents the default behavior of the event
    e.stopPropagation();  // prevents the event from clicking on the parent element

    const target = e.target;
    const isAdmin = target.innerText == 'Remove admin';

    try {
        const formData = new FormData();
        formData.append('user-id', userId);
        formData.append('action', isAdmin ? 'removeAdmin' : 'addAdmin');

        const response = await fetch('/toggleUserStatus', {
            method: 'POST',
            body: formData,
        });

        const data = await response.json();
        if (data.isAdmin) {
            target.innerText = 'Remove admin';
        } else {
            target.innerText = 'Add admin';
        }
    } catch (error) {
        alert(error.message);
    }
}

async function toggleBookStatus(e, bookId, action) {
    e.preventDefault(); // prevents the default behavior of the event
    e.stopPropagation();  // prevents the event from clicking on the parent element


    try {
        const formData = new FormData();
        formData.append('book-id', bookId);
        formData.append('action', action);

        await fetch('/toggleBookStatus', {
            method: 'POST',
            body: formData,
        });

        const bookCard = document.querySelector("#book-card-" + bookId);
        const header = document.querySelector("#book-empty-header");
        const booksList = document.querySelectorAll("#booksList .card");
        console.log(booksList);
        booksList.length == 1 ? header.style.display = "initial" :

            header.style.display = "none";
        bookCard.parentNode.removeChild(bookCard);
    } catch (error) {
        alert(error.message);
    }
}
async function toggleReviewStatus(e, reviewId, action) {
    e.preventDefault(); // prevents the default behavior of the event
    e.stopPropagation();  // prevents the event from clicking on the parent element


    try {
        const formData = new FormData();
        formData.append('review-id', reviewId);
        formData.append('action', action);

        await fetch('/toggleReviewStatus', {
            method: 'POST',
            body: formData,
        });

        const reviewCard = document.querySelector("#review-card-" + reviewId);
        const header = document.querySelector("#review-empty-header");
        const reviewsList = document.querySelectorAll("#reviewsList .card");
        console.log(reviewsList);
        reviewsList.length == 1 ? header.style.display = "initial" :

            header.style.display = "none";
        reviewCard.parentNode.removeChild(reviewCard);
    } catch (error) {
        alert(error.message);
    }
}

async function removeUser(e, userId, isCurrent) {
    e.preventDefault(); // prevents the default behavior of the event
    e.stopPropagation();  // prevents the event from clicking on the parent element

    const target = document.getElementById('user-card-' + userId);

    try {
        const formData = new FormData();
        formData.append('user-id', userId);
        formData.append('action', 'removeUser');

        await fetch('/toggleUserStatus', {
            method: 'POST',
            body: formData,
        });

        target.parentNode.removeChild(target);

        if (isCurrent) {
            window.location.href = '/logout';
        }
    } catch (error) {
        alert(error.message);
    }
}