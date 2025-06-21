function openLogoutPopup() {
    document.getElementById("logoutPopup").style.display = "flex";
}

function closePopup() {
    document.getElementById("logoutPopup").style.display = "none";
}

// Function to handle the logout
function logoutUser() {
    // Optional: Clear session or any necessary logout actions
    window.location.href = "login.php"; // Redirects to login page after logout
}
