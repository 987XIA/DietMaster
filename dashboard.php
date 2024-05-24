<?php
session_start();

include("php/config.php");
if (!isset($_SESSION['valid'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js"></script>
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>


<body>
    <!-- header -->
    <header class="header">
        <div class="logo">
            <a href="dashboard.php"><i class="fa-solid fa-plate-wheat"></i>MealMapper</a>
        </div>
        <div class="date">
            <p id="currentDateTime">Loading...</p>
        </div>

        <div class="right-links">
            <a href="php/logout.php"> <button class="btn">Log Out</button> </a>
        </div>

    </header>
    <!-- header -->

    <!--content-->

    <!--content-->

    <!--sidebar-->
    <div class="sidebar">
        <a href="#" id="chart" class="sidebar-item"><i class="fa-solid fa-chart-pie"></i> Chart</a>
        <a href="#" id="today" class="sidebar-item"><i class="fa-solid fa-calendar-day"></i> Today</a>
        <a href="#" id="food-list" class="sidebar-item"><i class="fa-solid fa-utensils"></i> Food List</a>
    </div>
    <!--sidebar-->

    <!-- Main Content -->
    <div class="main-content">
        <div id="content">
        </div>
    </div>

    <!-- Main Content -->

    <!--footer-->
    <footer>
        <p>Copyright &#169; 2024 Xia. All Right Reserved.</p>
    </footer>
    <!--footer-->

</body>

</html>

<?php
$con->close();
?>