const timeElement = document.getElementById("minutes");

//set the time in seconds
let timeElapsed = 0;
let maxTime = 180; //limit time

//fucntion to start countdown
function startTimer(){
    const intervalID = setInterval(function(){

        //calculate minutes and seconds
        const minutes = Math.floor(timeElapsed/60);
        const seconds = timeElapsed %60;

        //format the time with leading zeros if necessary
        timeElement.textContent = `${formatTime(minutes)}:${formatTime(seconds)}`;

        timeElapsed++;

        if(timeElapsed > maxTime){
            clearInterval(intervalID);
            timeElement.textContent = "Time's Up"
        }
    }, 1000);
}

//function to format the time, add leading zeros if single digit
function formatTime(time){
    return time < 10?`0${time}`:time;
}

//start the timer when the page loads
window.onload = startTimer;
