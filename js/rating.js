document.addEventListener("DOMContentLoaded", () => {
    const stars = document.querySelectorAll(".star");

    stars.forEach((star) => {
        star.addEventListener("click", () => {
            const ratingValue = star.getAttribute("data-value");
            const topic = document.querySelector(".rating-section").getAttribute("data-topic"); // Get the topic dynamically

            updateStars(ratingValue);
            sendRatingToServer(topic, ratingValue);
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

    function sendRatingToServer(topic, rating) {
        fetch("update_rating.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `topic=${encodeURIComponent(topic)}&rating=${encodeURIComponent(rating)}`
        })
        // topic=topic&rating=5
        .then(response => response.text())
        .then(data => {
            if (data === "success") {
                alert("Rating submitted successfully!");
            } else {
                alert("Failed to submit rating.");
            }
        })
        .catch(error => console.error("Error:", error));
    }
});
