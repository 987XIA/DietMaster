<?php
session_start();

include("php/config.php");
if (!isset($_SESSION['valid'])) {
    header("Location: login.php");
    exit;
}

// Handle add food item
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"]) && $_POST["action"] == "add") {
    if (isset($_POST["name"]) && isset($_POST["fat"]) && isset($_POST["protein"]) && isset($_POST["carb"])) {
        $name = $_POST["name"];
        $fat = $_POST["fat"];
        $protein = $_POST["protein"];
        $carb = $_POST["carb"];

        $sql = "INSERT INTO food (name, fat, protein, carb) VALUES ('$name', '$fat', '$protein', '$carb')";

        if ($con->query($sql) === TRUE) {
            echo "Successfully Created";
        } else {
            echo "Error: " . $sql . "<br>" . $con->error;
        }
    }
}

// Handle edit food item
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"]) && $_POST["action"] == "edit") {
    if (isset($_POST["id"]) && isset($_POST["name"]) && isset($_POST["fat"]) && isset($_POST["protein"]) && isset($_POST["carb"])) {
        $id = $_POST["id"];
        $name = $_POST["name"];
        $fat = $_POST["fat"];
        $protein = $_POST["protein"];
        $carb = $_POST["carb"];

        $sql = "UPDATE food SET name='$name', fat='$fat', protein='$protein', carb='$carb' WHERE id='$id'";

        if ($con->query($sql) === TRUE) {
            echo "Record updated successfully";
        } else {
            echo "Error updating record: " . $con->error;
        }
    }
}

// Handle delete food item
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"]) && $_POST["action"] == "delete") {
    if (isset($_POST["id"])) {
        $id = $_POST["id"];

        $sql = "DELETE FROM food WHERE id='$id'";

        if ($con->query($sql) === TRUE) {
            echo "Record deleted successfully";
        } else {
            echo "Error deleting record: " . $con->error;
        }
    }
}

// Handle add to today's list
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"]) && $_POST["action"] == "add_to_today") {
    if (isset($_POST["selected_items"])) {
        $selectedItems = $_POST["selected_items"];
        $today = date("Y-m-d");
        foreach ($selectedItems as $itemId) {
            $sql = "INSERT INTO todays_list (food_id, date) VALUES ('$itemId', '$today')";
            if ($con->query($sql) !== TRUE) {
                echo "Error: " . $sql . "<br>" . $con->error;
            }
        }
        echo "Successfully Added.";
    }
}

// Handle remove from today's list
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"]) && $_POST["action"] == "remove_from_today") {
    if (isset($_POST["selected_items"])) {
        $selectedItems = $_POST["selected_items"];
        foreach ($selectedItems as $itemId) {
            $sql = "DELETE FROM todays_list WHERE id='$itemId'";
            if ($con->query($sql) !== TRUE) {
                echo "Error: " . $sql . "<br>" . $con->error;
            }
        }
        echo "Successfully Removed.";
    }
}

// Fetch food items from database
$sql = "SELECT id, name, fat, protein, carb FROM food";
$result = $con->query($sql);

// Fetch specific food item for editing if needed
$editItem = null;
if (isset($_GET['edit_id'])) {
    $editId = $_GET['edit_id'];
    $editSql = "SELECT id, name, fat, protein, carb FROM food WHERE id='$editId'";
    $editResult = $con->query($editSql);
    if ($editResult->num_rows > 0) {
        $editItem = $editResult->fetch_assoc();
    }
}

// Fetch today's food items from database
$todaysSql = "SELECT t.id, f.name, f.fat, f.protein, f.carb 
              FROM todays_list t 
              JOIN food f ON t.food_id = f.id 
              WHERE t.date = CURRENT_DATE()";
$todaysResult = $con->query($todaysSql);

// Calculate the sum of macronutrients for today's list
$totalFat = 0;
$totalProtein = 0;
$totalCarb = 0;
if ($todaysResult->num_rows > 0) {
    while ($row = $todaysResult->fetch_assoc()) {
        $totalFat += $row["fat"];
        $totalProtein += $row["protein"];
        $totalCarb += $row["carb"];
    }
    // Reset pointer to fetch data again for display
    $todaysResult->data_seek(0);
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

    <!-- Main Content -->
    <div class="main-content">
        <div id="content">
            <!-- Food List -->
            <div id="food-list-content">
                <form id="foodForm" action="dashboard.php" method="post">
                    <table id="foodListTable">
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Fat (g)</th>
                                <th>Protein (g)</th>
                                <th>Carbs (g)</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                            <td><input type='checkbox' name='selected_items[]' value='" . $row["id"] . "'></td>
                                            <td>" . $row["id"] . "</td>
                                            <td>" . $row["name"] . "</td>
                                            <td>" . $row["fat"] . "</td>
                                            <td>" . $row["protein"] . "</td>
                                            <td>" . $row["carb"] . "</td>
                                            <td>
                                                <button type='button' class='edit-btn' data-id='" . $row["id"] . "'><i class='fa-solid fa-pen-to-square'></i></button>
                                                <form action='dashboard.php' method='post' style='display:inline-block;'>
                                                    <input type='hidden' name='id' value='" . $row["id"] . "'>
                                                    <input type='hidden' name='action' value='delete'>
                                                    <button type='submit' class='delete-btn'><i class='fa-solid fa-trash-can'></i></button>
                                                </form>
                                            </td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7'>No food items found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                    <br>
                    <input type="hidden" name="action" value="add_to_today">
                    <input type="submit" value="+ Today's List">
                </form>
                <br>
                <form id="foodItemForm" action="dashboard.php" method="post">
                    <?php if ($editItem) : ?>
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id" value="<?php echo $editItem['id']; ?>">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" value="<?php echo $editItem['name']; ?>" required><br>
                        <label for="fat">Fat (g):</label>
                        <input type="text" id="fat" name="fat" value="<?php echo $editItem['fat']; ?>" required><br>
                        <label for="protein">Protein (g):</label>
                        <input type="text" id="protein" name="protein" value="<?php echo $editItem['protein']; ?>" required><br>
                        <label for="carb">Carbs (g):</label>
                        <input type="text" id="carb" name="carb" value="<?php echo $editItem['carb']; ?>" required><br>
                        <input type="submit" value="Update">
                    <?php else : ?>
                        <input type="hidden" name="action" value="add">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" required><br>
                        <label for="fat">Fat (g):</label>
                        <input type="text" id="fat" name="fat" required><br>
                        <label for="protein">Protein (g):</label>
                        <input type="text" id="protein" name="protein" required><br>
                        <label for="carb">Carbs (g):</label>
                        <input type="text" id="carb" name="carb" required><br>
                        <input type="submit" value="+ Food">
                    <?php endif; ?>
                </form>
            </div>

            <!-- Today's List -->
            <div id="todays-list-content" style="display: none;">
                <form id="todaysForm" action="dashboard.php" method="post">
                    <table id="todaysListTable">
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>Name</th>
                                <th>Fat (g)</th>
                                <th>Protein (g)</th>
                                <th>Carbs (g)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($todaysResult->num_rows > 0) {
                                while ($row = $todaysResult->fetch_assoc()) {
                                    echo "<tr>
                                            <td><input type='checkbox' name='selected_items[]' value='" . $row["id"] . "'></td>
                                            <td>" . $row["name"] . "</td>
                                            <td>" . $row["fat"] . "</td>
                                            <td>" . $row["protein"] . "</td>
                                            <td>" . $row["carb"] . "</td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5'>No food items found</td></tr>";
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2">Total</td>
                                <td><?php echo $totalFat; ?></td>
                                <td><?php echo $totalProtein; ?></td>
                                <td><?php echo $totalCarb; ?></td>
                            </tr>
                        </tfoot>
                    </table>
                    <br>
                    <input type="hidden" name="action" value="remove_from_today">
                    <input type="submit" value="- Today's List">
                </form>
            </div>
        </div>
    </div>
    <!-- Main Content -->

    <!--sidebar-->
    <div class="sidebar">
        <a href="#" id="chart" class="sidebar-item"><i class="fa-solid fa-chart-pie"></i> Chart</a>
        <a href="#" id="today" class="sidebar-item"><i class="fa-solid fa-calendar-day"></i> Today</a>
        <a href="#" id="food-list" class="sidebar-item"><i class="fa-solid fa-utensils"></i> Food List</a>
    </div>
    <!--sidebar-->

    <!--footer-->
    <footer>
        <p>Copyright &#169; 2024 Xia. All Right Reserved.</p>
    </footer>
    <!--footer-->
    
    <script>
        document.getElementById('food-list').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('food-list-content').style.display = 'block';
            document.getElementById('todays-list-content').style.display = 'none';
        });

        document.getElementById('today').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('food-list-content').style.display = 'none';
            document.getElementById('todays-list-content').style.display = 'block';
        });

        document.addEventListener('submit', function(e) {
            if (e.target.matches('form')) {
                e.preventDefault();
                const form = e.target;
                const formData = new FormData(form);

                fetch('dashboard.php', {
                        method: form.method,
                        body: formData
                    })
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById('content').innerHTML = new DOMParser().parseFromString(data, 'text/html').getElementById('content').innerHTML;
                    })
                    .catch(error => console.error('Error submitting form:', error));
            }
        });

        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                fetch(`dashboard.php?edit_id=${id}`)
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById('content').innerHTML = new DOMParser().parseFromString(data, 'text/html').getElementById('content').innerHTML;
                    })
                    .catch(error => console.error('Error fetching edit form:', error));
            });
        });
    </script>
    
</body>

</html>

<?php
$con->close();
?>
