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
            echo "New record created successfully";
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
?>

<!DOCTYPE html>
<html>

<head>
    <title>Food List</title>
</head>

<body>
    <div class="content">
        <div class="foodlist">
            <table id="foodListTable">
                <thead>
                    <tr>
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
                                    <td>" . $row["id"] . "</td>
                                    <td>" . $row["name"] . "</td>
                                    <td>" . $row["fat"] . "</td>
                                    <td>" . $row["protein"] . "</td>
                                    <td>" . $row["carb"] . "</td>
                                    <td>
                                        <form action='food.php' method='get' style='display:inline-block;'>
                                            <input type='hidden' name='edit_id' value='" . $row["id"] . "'>
                                            <input type='submit' value='Edit'>
                                        </form>
                                        <form action='food.php' method='post' style='display:inline-block;'>
                                            <input type='hidden' name='id' value='" . $row["id"] . "'>
                                            <input type='hidden' name='action' value='delete'>
                                            <input type='submit' value='Delete'>
                                        </form>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No food items found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            <br>
            <form action="food.php" method="post">
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
                    <input type="submit" value="Update Food Item">
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
                    <input type="submit" value="Add Food Item">
                <?php endif; ?>
            </form>
        </div>
    </div>
</body>

</html>

<?php
$con->close();
?>