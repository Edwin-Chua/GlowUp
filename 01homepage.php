<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link rel="stylesheet" href="css/01home.css">  
</head>
<body>

    <?php
        include 'userheader.php'
    ?>

        <section class="title-section">
            <h1>Health & Wellness</h1>
            <p>Explore a range of topics and resources dedicated to improving your overall well-being. From healthy eating 
                habits and fitness routines to mental health strategies and self-care practices, discover how you can lead 
                a healthier, more balanced life.</p>
        </section>
    
        <section class="card-section">
            <a href="02nutrition.php" class="card">
                <div class="card-icon">
                    <img src="https://jaimedicalsystems.com/wp-content/uploads/2024/03/shutterstock_590825882-scaled.jpg" alt="Nutrition Icon">
                </div>
                <h2>Nutrition</h2>
                <p>Discover how a balanced diet can fuel your body with the right nutrients for optimal performance.</p>
            </a>
            
            <a href="02fitness.php" class="card">
                <div class="card-icon">
                    <img src="https://blog.nasm.org/hubfs/fitness-trends.jpg" alt="Fitness Icon">
                </div>
                <h2>Fitness</h2>
                <p>Learn how to improve your fitness level, increase strength, and boost energy through effective exercise plans.</p>
            </a>
            
            <a href="02health.php" class="card">
                <div class="card-icon">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRHucOyaiANIntNFOL1mkVWBiLPSMmrAwzyag&s" alt="Mental Health Icon">
                </div>
                <h2>Mental Health</h2>
                <p>Take care of your mental well-being with practices that promote mindfulness, reduce stress, and improve 
                    emotional health.</p>
            </a>
            
            <a href="02selfcare.php" class="card">
                <div class="card-icon">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSfHvvXE8UIdw4qmHS_1Vw_gumEJb2NOWuHPg&s" alt="Self-Care Icon">
                </div>
                <h2>Self-Care</h2>
                <p>Learn how simple habits and practices can improve your overall health and well-being, helping you relax and recharge.</p>
            </a>
        </section>

    <?php
    include 'userfooter.php'
    ?>
</body>
</html>



