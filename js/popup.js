function openPopup() {
  document.getElementById("popup").style.display = "flex";
}

function closePopup() {
  document.getElementById("popup").style.display = "none";
}

function startQuiz() {
  window.location.href = "quiz.php?new_quiz=1"; // Redirects to quiz.php
}
