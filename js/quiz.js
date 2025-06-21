document.addEventListener("DOMContentLoaded", function(){
    //DEBUGGING
    let playIdElement = document.getElementById("play_id");

    //  Check if the element exists before accessing textContent
    if (!playIdElement) {
        console.error("play_id element not found in DOM.");
        return;
    }

    let nextBtn =document.getElementById("nextBtn");
    let returnBtn=document.getElementById("returnBtn")
    let choices = document.querySelectorAll(".choice-container");

    //check if a new play_id is assigned
    let playId = sessionStorage.getItem("play_id");
    let currentPlayId = playIdElement.textContent.trim();

    if (playId !==currentPlayId){
        localStorage.removeItem("answeredQuestions");
        sessionStorage.setItem("play_id", currentPlayId);
    }

    //retrieve the correct answer from php
    let correctAnswer = document.getElementById("correctAnswer").textContent.trim();
    let quizId = document.getElementById("quizId").textContent.trim();

    if(!correctAnswer || !quizId){
        console.error("Missing elemtns: correctAnswer ot quizId not found in DOM.")
        return;
    }

    let answerSelected = false; //ensure user selects an answer before procedding
    let selectedOption = null;

    //retrieve answered question from localstorage
    let storedData = localStorage.getItem("answeredQuestions");
    let answeredQuestions = storedData ? JSON.parse(storedData) : {};

    //function to update the result icon after answered quiz
    function updateChoiceDisplay(){
        choices.forEach(choice => {
            let option = choice.getAttribute("data-option");
            let icon = choice.querySelector(".result-icon");

            //ensure the icons exist
            if(!icon){
                icon = document.createElement("img");
                icon.classList.add("result-icon");
                icon.style.display = "none";
                choice.appendChild(icon);
            }

            //if the question has been answered, update the icon display
            if(answeredQuestions[quizId]){
                let previousAnswer = answeredQuestions[quizId].selectedOption;
                choice.classList.remove("selected");

                //show result icons 
                if (option === correctAnswer ){
                    icon.src = "icons/icon-correct.svg";
                } else {
                    icon.src = "icons/icon-wrong.svg";
                }
                icon.style.display = "inline";

                //if the question is answered
                if(option === previousAnswer){
                    choice.classList.add("selected");
                }
            } else {
                choice.classList.remove("selected") 
                icon.style.display = "none"; // hide if not answered yet
            }
        })
    }
    
    // check if question has been answered already
    if (answeredQuestions[quizId]){
        answerSelected = true; //set the to true for answered questions
        selectedOption = answeredQuestions[quizId].selectedOption;
        choices.forEach(choice => choice.style.pointerEvents = "none");
        updateChoiceDisplay();
    } else {
        //if the question hasn't been answered, enable choices
        choices.forEach(choice => choice.style.pointerEvents = "auto");
    }

    choices.forEach(choice =>{
        //DEBUGGING
        console.log("Choice:", choice, "Pointer Events:", choice.style.pointerEvents);
        console.log("Answer Selected:", answerSelected);
        console.log("Answered Questions:", answeredQuestions);
        //----

        choice.addEventListener("click",function(){

            if (answerSelected) return; //prevent multiple selection
            answerSelected = true;

            selectedOption = this.getAttribute("data-option");

            //highlight the selected answer
            choices.forEach(c => c.classList.remove("selected")); //remove previous selection
            this.classList.add("selected");

            //store answered question in localStorage
            answeredQuestions[quizId] = {
                selectedOption: selectedOption,
                isCorrect: selectedOption === correctAnswer
            };
            localStorage.setItem("answeredQuestions", JSON.stringify(answeredQuestions));
           
            //disable further clicks after selecting an answer
            choices.forEach(c => c.style.pointerEvents="none");
            updateChoiceDisplay();
        });
    });

    nextBtn.addEventListener("click", function() {
        if(!answerSelected){
            alert("Please select an answer before proceeding.");
            return;
        }

        // Create a hidden form and submit it
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'quiz.php';

        // Add the answer input
        const answerInput = document.createElement('input');
        answerInput.type = 'hidden';
        answerInput.name = 'answer';
        answerInput.value = selectedOption;
        form.appendChild(answerInput);

        // Add the form to the body and submit it
        document.body.appendChild(form);
        form.submit();
    });

    returnBtn.addEventListener("click", function() {
        if (confirm('Do you want to return to the previous question? Your current answer will be discarded.')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'quiz.php';
            
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = 'previous';
            
            form.appendChild(actionInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
});