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

</head>


<body>
    <div class="nav">
        <div class="logo">
            <p><a href="dashboard.php">Logo</a></p>
        </div>
        <div class="date">
            <p>curent date and time on right</p>
        </div>

        <div class="right-links">
            <!--
            php
            ---$id = $_SESSION['valid'];
            $query = mysqli_query($con, " SELECT * FROM users WHERE Id=$id ");

            while ($result = mysqli_fetch_assoc($query)) {
                $res_Uname = $result['Username'];
                $res_id = $result['Id'];
            }

            echo "<a href='edit.php?Id=$res_id'>Change Profile</a>";
            
            -->
            <a href="php/logout.php"> <button class="btn">Log Out</button> </a>

        </div>

    </div>

    <main>
        <div class="main-box top">
            <div class="top">
                <div class="box">
                    <p>Welcome, <b><?php echo $_SESSION['username']; ?></b></p>
                </div>

                <div class="box">
                    <p>How is the day today</p>
                </div>
            </div>

            <div class="buttom">
                <div class="box">
                    <p>Later for chart</p>
                </div>
            </div>

        </div>
    </main>
</body>

</html>