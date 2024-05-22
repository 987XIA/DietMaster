<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Profile</title>
    <link rel="stylesheet" href="/css/style.css">
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
            <a href="#">Change Profile</a>
            <a href="logout.php"><button class="btn" >logout</button></a>
        </div>
    </div>

    <div class="container">
        <div class="box form-box">
            <div class="header">
                <header>Change Profile</header>
                <p>Update your profile setting.</p>
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
                    <input type="submit" class="btn" name="submit" value="Update">
                </div>
                <div class="links">
                    Don't have an account?
                    <a href="signup.html">Sign up now.</a>
                </div>

            </form>

        </div>

    </div>
</body>
</html>