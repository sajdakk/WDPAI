function selectStar(value) {
    const stars = document.querySelectorAll('.review-stars > i');

    stars.forEach(star => star.textContent = 'star_border');
    for (let i = 0; i < value; i++) {
        stars[i].textContent = 'star';
    }

    const reviewRate = document.querySelector('#review-rate');
    reviewRate.value = value;
}

function logoutDetails() {
    // Get the current base URL
    const baseURL = window.location.origin;

    const newURL = `${baseURL}/logout`;

    // Change the form action attribute
    document.querySelector('#logoutForm').action = newURL;

    // Submit the form
    document.querySelector('#logoutForm').submit();
}

function changeFormAction() {
    // Get the current base URL
    const baseURL = window.location.origin;

    // Replace everything after the base URL with '/addReview'
    const newURL = `${baseURL}/addReview`;

    // Change the form action attribute
    document.querySelector('#reviewForm').action = newURL;

    // Submit the form
    document.querySelector('#reviewForm').submit();
}