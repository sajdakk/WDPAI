function showToast(event) {
    // Use your preferred method to show a toast message
    // Example using a simple alert:
    alert("You have to log in");

    // Stop the event propagation to prevent the parent form's onclick from being triggered
    event.stopPropagation();
}