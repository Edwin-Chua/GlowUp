document.addEventListener("DOMContentLoaded", function () {
    const quizContainer = document.querySelector(".quiz-container");
    const addQuestionBtn = document.getElementById("add-question");

    // Create a container for questions
    const questionsContainer = document.createElement("div");
    questionsContainer.classList.add("questions-container");
    quizContainer.appendChild(questionsContainer);

    // Create a container for the buttons
    const buttonContainer = document.createElement("div");
    buttonContainer.style.display = "flex";
    buttonContainer.style.gap = "10px";
    buttonContainer.style.justifyContent = "center";
    buttonContainer.appendChild(addQuestionBtn);

    const saveChangesBtn = document.createElement("button");
    saveChangesBtn.id = "save-quiz";
    saveChangesBtn.textContent = "Save Changes";
    saveChangesBtn.classList.add("save-btn");
    saveChangesBtn.style.display = "none";
    
    buttonContainer.appendChild(saveChangesBtn);
    quizContainer.appendChild(buttonContainer);

    loadQuizFromDatabase();

    addQuestionBtn.addEventListener("click", function () {
        const newQuestion = createQuestionElement();
        questionsContainer.appendChild(newQuestion);
        saveChangesBtn.style.display = "block";
    });

    saveChangesBtn.addEventListener("click", function () {
        saveQuiz();
    });

    function createQuestionElement(questionData = null) {
        const newQuestion = document.createElement("div");
        newQuestion.classList.add("quiz-question");
        
        // Don't set quiz_id for new questions, let database auto-increment handle it
        if (questionData && questionData.quiz_id) {
            newQuestion.dataset.quizId = questionData.quiz_id;
        }

        const correctAnswer = questionData?.correctAnswer || null;

        newQuestion.innerHTML = `
            <div class="question-header">
                <div class="question-image"></div>
                <p contenteditable="true"><strong>${questionData?.question || "New Question?"}</strong></p>
                <select>
                    <option>5 points</option>
                </select>
                <button class="delete-question">Delete</button>
            </div>
            <ul class="answers">
                ${createAnswerOptions(questionData?.answers || ["Option A", "Option B", "Option C", "Option D"], correctAnswer)}
            </ul>
            <button class="set-correct">Set as Correct</button>
        `;

        // Add event listeners for radio buttons
        const radioButtons = newQuestion.querySelectorAll('.correct-answer');
        radioButtons.forEach(radio => {
            radio.addEventListener('change', () => {
                setCorrectAnswer(newQuestion);
            });
        });

        // If there's an initial correct answer, highlight it
        if (correctAnswer) {
            setTimeout(() => {
                setCorrectAnswer(newQuestion);
            }, 0);
        }

        newQuestion.querySelector(".set-correct").addEventListener("click", function () {
            setCorrectAnswer(newQuestion);
        });

        attachDeleteFunctionality(newQuestion);
        return newQuestion;
    }

    function createAnswerOptions(answers, correctAnswer) {
        const labels = ["A", "B", "C", "D"];
        let optionsHtml = "";

        for (let i = 0; i < answers.length; i++) {
            let checked = correctAnswer === labels[i] ? "checked" : "";
            optionsHtml += `
                <li>
                    <label>
                        <input type="radio" name="question-group" class="correct-answer" value="${labels[i]}" ${checked}>
                        ${labels[i]}. <span contenteditable="true">${answers[i]}</span>
                    </label>
                </li>`;
        }

        return optionsHtml;
    }

    function setCorrectAnswer(questionElement) {
        const answers = questionElement.querySelectorAll(".correct-answer");
        const labels = questionElement.querySelectorAll("label");

        // Remove previous highlights from all labels
        labels.forEach(label => {
            label.classList.remove("correct");
        });

        // Find the selected correct answer
        const selectedAnswer = questionElement.querySelector(".correct-answer:checked");
        if (selectedAnswer) {
            selectedAnswer.closest("label").classList.add("correct");
            
            // Add a visual indicator
            const correctLabel = selectedAnswer.closest("label");
            correctLabel.style.backgroundColor = "#d4edda";
            correctLabel.style.color = "#155724";
            correctLabel.style.borderRadius = "4px";
            correctLabel.style.padding = "5px";
            correctLabel.style.transition = "all 0.3s ease";
        }
    }
    function attachDeleteFunctionality(questionElement) {
        const deleteButton = questionElement.querySelector(".delete-question");
        deleteButton.addEventListener("click", function () {
            questionElement.style.transition = "opacity 0.3s ease-out";
            questionElement.style.opacity = "0";
            setTimeout(() => {
                questionElement.remove();
            }, 300);
        });
    }

    function loadQuizFromDatabase() {
        fetch("managefile.php", {
            method: "GET",
            headers: { "Content-Type": "application/json" }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.quizData.length > 0) {
                // Clear existing questions
                questionsContainer.innerHTML = '';
                
                // Add each question from the database
                data.quizData.forEach(questionData => {
                    const questionElement = createQuestionElement({
                        quiz_id: questionData.quiz_id,
                        question: questionData.question,
                        answers: questionData.answers,
                        correctAnswer: questionData.correctAnswer
                    });
                    questionsContainer.appendChild(questionElement);
                });
                
                // Show save button if there are questions
                saveChangesBtn.style.display = "block";
            }
        })
        .catch(error => console.error("Error:", error));
    }

    function saveQuiz() {
        const questions = questionsContainer.querySelectorAll(".quiz-question");
        const quizData = [];

        questions.forEach(question => {
            const quizId = question.dataset.quizId || null;
            const questionText = question.querySelector("p").textContent;
            const answers = [...question.querySelectorAll(".answers li span")].map(span => span.textContent);
            const correctAnswer = question.querySelector(".correct-answer:checked")?.value || null;

            quizData.push({
                quiz_id: quizId,
                question: questionText,
                A: answers[0],
                B: answers[1],
                C: answers[2],
                D: answers[3],
                correctAnswer,
                points: 5
            });
        });

        fetch("managefile.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ quizData })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update question IDs for new questions
                data.questions.forEach((updatedQuestion, index) => {
                    if (updatedQuestion.status === "inserted") {
                        const questionElements = questionsContainer.querySelectorAll(".quiz-question:not([data-quiz-id])");
                        if (questionElements.length > 0) {
                            questionElements[0].dataset.quizId = updatedQuestion.question_id;
                        }
                    }
                });
                alert("Quiz saved successfully!");
            } else {
                alert("Failed to save quiz: " + data.message);
            }
        })
        .catch(error => {
            console.error("Error saving quiz:", error);
            alert("Error saving quiz. Please try again.");
        });
    }
});
