<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/manage.css">
    <title>Manage Quiz</title>
</head>
<?php
        include 'adminheader.php'
    ?>
<body>
    <div class="title-section">
        <h1>Manage quiz</h1>
    </div>
    <button class="back" onclick="window.location.href='viewquiz.php'">Back</button>
    <div class="quiz-container">
        <div class="quiz-topic">
            <div class="topic-image"></div>
            <div class="topic-info">
                <h3>Topic</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.</p>
            </div>
        </div>
    </div>

    <div class="bottom-buttons">
        <button id="add-question">Add Question</button>
    </div>

    <script src="js/manage.js"></script>
    <?php
    include 'adminfooter.php'
    ?>
</body>
</html>

