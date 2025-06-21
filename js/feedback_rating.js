document.addEventListener("DOMContentLoaded", () => {
    const stars = document.querySelectorAll(".star");

    stars.forEach((star) => {
        star.addEventListener("click", () => {
            const ratingValue = star.getAttribute("data-value");
            updateStars(ratingValue);
            document.getElementById("rating").value = ratingValue; // Updates the hidden input
            console.log("Rating selected: " + ratingValue); // Log the rating value
        });
    });

    function updateStars(rating) {
        stars.forEach((star) => {
            if (star.getAttribute("data-value") <= rating) {
                star.setAttribute("src", "icons/icon-starcolor.svg"); // Filled star
            } else {
                star.setAttribute("src", "icons/icon-starhalf.svg"); // Unfilled star
            }
        });
    }

    // Handle the form submission
    const feedbackForm = document.getElementById("feedbackForm"); // assuming you have a form with this id
    feedbackForm.addEventListener("submit", function(event) {
        event.preventDefault();

        // Simulating a successful feedback submission (can be replaced with actual submission logic)
        alert("Feedback sent successfully!");

        // Redirect to feedbackConfirm.php after success
        window.location.href = "feedbackConfirm.php";
    });
});
