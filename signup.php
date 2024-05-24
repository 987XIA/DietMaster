<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script src="js/script.js"></script>
</head>

<body>
    <div class="container">
        <div class="box form-box">
            <?php
            include("php/config.php");
            if (isset($_POST['submit'])) {
                $username = $_POST['username'];
                $password = $_POST['password'];
                $cpassword = $_POST['cpassword'];

                //check if pwd matches cpwd
                if ($password != $cpassword) {
                    echo "<div class='message'>
                         <p>Passwords do not match. Please try again.</p>
                         </div> <br>";
                    echo "<a href='javascript:self.history.back()'><button class='btn'>Go Back</button>";
                } else {
                    //verify the unique username
                    $verify_query = mysqli_query($con, "SELECT Username FROM users WHERE Username='$username'");
                    if (mysqli_num_rows($verify_query) != 0) {
                        echo "<div class='message'>
                            <p>Oops! Somebody have used this name.</p>
                        </div> <br>";
                        echo "<a href='javascript:self.history.back()'><button class='btn'>Go Back</button>";
                    } else {
                        mysqli_query($con, "INSERT INTO users(Username,Password) VALUES('$username','$password')") or die("Error");

                        echo "<div class='message'>
                                <p>Registration Successful</p>
                                </div> <br>";
                        echo "<a href='login.php'><button class='btn'>Login</button>";
                    }
                }
            } else {

            ?>


                <div class="signup">
                    <h1>Sign Up</h1>
                    <p>Please fill this form to create an account.</p>
                </div>
                <form action="" method="post" id="signupForm">
                    <div class="field input">
                        <label for="username">Username:</label><br>
                        <input type="text" name="username" id="username" autocomplete="off" required><br>
                    </div>
                    <div class="field input">
                        <label for="password">Password:</label><br>
                        <input type="password" name="password" id="password" autocomplete="off" required><br>
                    </div>
                    <div class="field input">
                        <label for="password">Confirm Password:</label><br>
                        <input type="password" name="cpassword" id="cpassword" autocomplete="off" required><br>
                    </div>
                    <div class="field">
                        <input type="submit" class="btn" name="submit" value="Submit">
                    </div>
                    <div class="field">
                        <input type="submit" id="reset" class="btn" name="reset" value="Reset" onclick="resetForm()">
                    </div>
                    <div class="links">
                        Already have an account?
                        <a href="login.php">Login here.</a>
                    </div>

                </form>

        </div>
    <?php } ?>
    </div>

</body>

</html>
