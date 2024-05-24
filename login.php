<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="container">
        <div class="box form-box">

            <?php
            include("php/config.php");
            if (isset($_POST['submit'])) {
                $username = mysqli_real_escape_string($con, $_POST['username']);
                $password = mysqli_real_escape_string($con, $_POST['password']);

                //verify the username and password matched

                $result = mysqli_query($con, "SELECT * FROM users WHERE Username='$username' AND Password='$password'") or die("Select Error");
                $row = mysqli_fetch_assoc($result);

                if (is_array($row) && !empty($row)) {
                    $_SESSION['valid'] = $row['Username'];
                    $_SESSION['username'] = $row['Username'];
                    $_SESSION['password'] = $row['Password'];
                    $_SESSION['id'] = $row['Id'];

                    // if login successful, then goes to dashboard page
                    header("Location: dashboard.php");
                    exit;
                    // ensure stops after redirection

                } else {
                    echo "<div class='message'>
                            <p>Wrong Username/Password</p>
                            </div> <br>";
                    echo "<a href='login.php'><button class='btn'>Login</button>";
                }
            } else {

            ?>

                <div class="login">
                    <h1>Login</h1>
                    <p>Please fill in your credentials to login.</p>
                </div>
                <form action="" method="post">
                    <div class="field input">
                        <label for="username">Username:</label><br>
                        <input type="text" name="username" id="username" autocomplete="off" required><br>
                    </div>
                    <div class="field input">
                        <label for="password">Password:</label><br>
                        <input type="password" name="password" id="password" autocomplete="off" required><br>
                    </div>
                    <div class="field">
                        <input type="submit" class="btn" name="submit" value="Login">
                    </div>
                    <div class="links">
                        Don't have an account?
                        <a href="signup.php">Sign up now.</a>
                    </div>

                </form>

        </div>
    <?php } ?>
    </div>

</body>

</html>
