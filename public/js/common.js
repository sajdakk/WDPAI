function routeToLogin() {
    window.location.href = '/login';
}

function routeToRegistration() {
    window.location.href = '/register';
}

function logout() {
    window.location.href = '/logout';
}

function routeToDetails(bookId) {
    window.location.href = '/details/' + bookId;
}

async function toggleFavorite(e, bookId) {
    e.preventDefault(); // prevents the default behavior of the event
    e.stopPropagation();  // prevents the event from clicking on the parent element

    const icon = e.target;

    try {
        const formData = new FormData();
        formData.append('book-id', bookId);

        const response = await fetch('/toggleFavorite', {
            method: 'POST',
            body: formData,
        });

        const data = await response.json();
        if (data.favorite) {
            icon.textContent = 'favorite';
        } else {
            icon.textContent = 'favorite_outline';
        }
    } catch (error) {
        alert(error.message);
    }
}