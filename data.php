<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "rdmd";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$rice_data = [];
$symptoms_data = [];
$disease_type_data = [];
$detection_result_data = [];

// Handling form submissions for insert, update, and delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['insert_rice'])) {
        $variety = $_POST['variety'];
        $growth_stage = $_POST['growth_stage'];
        $description = $_POST['description'];
        $severity_level = $_POST['severity_level'];
        $location_on_plant = $_POST['location_on_plant'];
        $name = $_POST['name'];
        $disease_description = $_POST['disease_description'];
        $confidence_level = $_POST['confidence_level'];
        $timestamp = date('Y-m-d H:i:s');

        $conn->begin_transaction();
        try {
            $sql_insert_rice = "INSERT INTO Rice (Variety, GrowthStage) VALUES ('$variety', '$growth_stage')";
            $conn->query($sql_insert_rice);
            $last_id = $conn->insert_id;

            $sql_insert_symptoms = "INSERT INTO Symptoms (ID, Description, SeverityLevel, LocationOnPlant) VALUES ('$last_id', '$description', '$severity_level', '$location_on_plant')";
            $conn->query($sql_insert_symptoms);

            $sql_insert_disease_type = "INSERT INTO DiseaseType (ID, Name, Description) VALUES ('$last_id', '$name', '$disease_description')";
            $conn->query($sql_insert_disease_type);

            $sql_insert_detection_result = "INSERT INTO DetectionResult (RiceID, SymptomID, DiseaseTypeID, ConfidenceLevel, Timestamp) VALUES ('$last_id', '$last_id', '$last_id', '$confidence_level', '$timestamp')";
            $conn->query($sql_insert_detection_result);

            $conn->commit();
        } catch (Exception $e) {
            $conn->rollback();
            echo "Failed to insert data: " . $e->getMessage();
        }
        header("Location: " . $_SERVER['PHP_SELF']);
    }

    if (isset($_POST['edit_rice'])) {
        $rice_id = $_POST['rice_id'];
        $variety = $_POST['variety'];
        $growth_stage = $_POST['growth_stage'];
        $sql_update_rice = "UPDATE Rice SET Variety='$variety', GrowthStage='$growth_stage' WHERE ID='$rice_id'";
        $conn->query($sql_update_rice);
        header("Location: " . $_SERVER['PHP_SELF']);
    }

    if (isset($_POST['delete_rice'])) {
        $rice_id = $_POST['rice_id'];
        $sql_delete_rice = "DELETE FROM Rice WHERE ID='$rice_id'";
        $conn->query($sql_delete_rice);
        header("Location: " . $_SERVER['PHP_SELF']);
    }

    if (isset($_POST['insert_symptoms'])) {
        $description = $_POST['description'];
        $severity_level = $_POST['severity_level'];
        $location_on_plant = $_POST['location_on_plant'];
        $sql_insert_symptoms = "INSERT INTO Symptoms (Description, SeverityLevel, LocationOnPlant) VALUES ('$description', '$severity_level', '$location_on_plant')";
        $conn->query($sql_insert_symptoms);
        header("Location: " . $_SERVER['PHP_SELF']);
    }

    if (isset($_POST['edit_symptoms'])) {
        $symptom_id = $_POST['symptom_id'];
        $description = $_POST['description'];
        $severity_level = $_POST['severity_level'];
        $location_on_plant = $_POST['location_on_plant'];
        $sql_update_symptoms = "UPDATE Symptoms SET Description='$description', SeverityLevel='$severity_level', LocationOnPlant='$location_on_plant' WHERE ID='$symptom_id'";
        $conn->query($sql_update_symptoms);
        header("Location: " . $_SERVER['PHP_SELF']);
    }

    if (isset($_POST['delete_symptoms'])) {
        $symptom_id = $_POST['symptom_id'];
        $sql_delete_symptoms = "DELETE FROM Symptoms WHERE ID='$symptom_id'";
        $conn->query($sql_delete_symptoms);
        header("Location: " . $_SERVER['PHP_SELF']);
    }

    if (isset($_POST['insert_disease_type'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $sql_insert_disease_type = "INSERT INTO DiseaseType (Name, Description) VALUES ('$name', '$description')";
        $conn->query($sql_insert_disease_type);
        header("Location: " . $_SERVER['PHP_SELF']);
    }

    if (isset($_POST['edit_disease_type'])) {
        $disease_type_id = $_POST['disease_type_id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $sql_update_disease_type = "UPDATE DiseaseType SET Name='$name', Description='$description' WHERE ID='$disease_type_id'";
        $conn->query($sql_update_disease_type);
        header("Location: " . $_SERVER['PHP_SELF']);
    }

    if (isset($_POST['delete_disease_type'])) {
        $disease_type_id = $_POST['disease_type_id'];
        $sql_delete_disease_type = "DELETE FROM DiseaseType WHERE ID='$disease_type_id'";
        $conn->query($sql_delete_disease_type);
        header("Location: " . $_SERVER['PHP_SELF']);
    }

    if (isset($_POST['insert_detection_result'])) {
        $rice_id = $_POST['rice_id'];
        $symptom_id = $_POST['symptom_id'];
        $disease_type_id = $_POST['disease_type_id'];
        $confidence_level = $_POST['confidence_level'];
        $timestamp = date('Y-m-d H:i:s');
        $sql_insert_detection_result = "INSERT INTO DetectionResult (RiceID, SymptomID, DiseaseTypeID, ConfidenceLevel, Timestamp) VALUES ('$rice_id', '$symptom_id', '$disease_type_id', '$confidence_level', '$timestamp')";
        $conn->query($sql_insert_detection_result);
        header("Location: " . $_SERVER['PHP_SELF']);
    }

    if (isset($_POST['edit_detection_result'])) {
        $detection_id = $_POST['detection_id'];
        $rice_id = $_POST['rice_id'];
        $symptom_id = $_POST['symptom_id'];
        $disease_type_id = $_POST['disease_type_id'];
        $confidence_level = $_POST['confidence_level'];
        $timestamp = $_POST['timestamp'];
        $sql_update_detection_result = "UPDATE DetectionResult SET RiceID='$rice_id', SymptomID='$symptom_id', DiseaseTypeID='$disease_type_id', ConfidenceLevel='$confidence_level', Timestamp='$timestamp' WHERE DetectionID='$detection_id'";
        $conn->query($sql_update_detection_result);
        header("Location: " . $_SERVER['PHP_SELF']);
    }

    if (isset($_POST['delete_detection_result'])) {
        $detection_id = $_POST['detection_id'];
        $sql_delete_detection_result = "DELETE FROM DetectionResult WHERE DetectionID='$detection_id'";
        $conn->query($sql_delete_detection_result);
        header("Location: " . $_SERVER['PHP_SELF']);
    }
}

// Fetch data after handling POST requests
$sql_rice = "SELECT * FROM Rice";
$result_rice = $conn->query($sql_rice);
if ($result_rice->num_rows > 0) {
    $rice_data = $result_rice->fetch_all(MYSQLI_ASSOC);
}

$sql_symptoms = "SELECT * FROM Symptoms";
$result_symptoms = $conn->query($sql_symptoms);
if ($result_symptoms->num_rows > 0) {
    $symptoms_data = $result_symptoms->fetch_all(MYSQLI_ASSOC);
}

$sql_disease_type = "SELECT * FROM DiseaseType";
$result_disease_type = $conn->query($sql_disease_type);
if ($result_disease_type->num_rows > 0) {
    $disease_type_data = $result_disease_type->fetch_all(MYSQLI_ASSOC);
}

$sql_detection_result = "SELECT * FROM DetectionResult";
$result_detection_result = $conn->query($sql_detection_result);
if ($result_detection_result->num_rows > 0) {
    $detection_result_data = $result_detection_result->fetch_all(MYSQLI_ASSOC);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RDMD</title>
    <link rel="stylesheet" href="style.css">
</head>
<style>
    body {
        font-family: 'Oswald', sans-serif;
        background-color: #f0f0f0;
    }

    .sub-header {
        background-color: #808080;
        color: #fff;
        padding: 20px 0;
    }

    nav a {
        color: #fff;
        text-decoration: none;
        margin: 0 15px;
    }

    nav a:hover {
        text-decoration: underline;
    }

    main {
        padding: 20px;
        text-align: center;
    }

    table {
        width: 80%;
        margin: 20px auto;
        border-collapse: collapse;
    }

    table, th, td {
        border: 1px solid #808080;
        padding: 8px;
    }

    th {
        background-color: #808080;
        color: white;
    }

    footer {
        background-color: #808080;
        color: white;
        padding: 10px 0;
        position: fixed;
        bottom: 0;
        width: 100%;
        text-align: center;
    }

    form {
        width: 80%;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        border: 1px solid #808080;
        border-radius: 5px;
    }

    form label {
        display: block;
        margin: 10px 0 5px;
    }

    form input[type="text"],
    form input[type="submit"] {
        width: 100%;
        padding: 8px;
        margin: 5px 0 20px;
    }

    form input[type="submit"] {
        background-color: #808080;
        color: white;
        border: none;
        cursor: pointer;
    }

    form input[type="submit"]:hover {
        background-color: #606060;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
    }

    .edit-form, .delete-form {
        display: inline-block;
    }
</style>
<body>
<section class="sub-header">
    <nav>
        <a href="index.html"><img src="RD.png" alt="logo" class="logo"></a>
        <div class="nav-links">
            <ul>
                <li><a href="index.html">HOME</a></li>
                <li><a href="about.html">ABOUT</a></li>
                <li><a href="contact.html">CONTACT</a></li>
            </ul>
        </div>
    </nav>
</section>

<main>
    <div class="table-container">
        <h1>Rice Table</h1>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Variety</th>
                    <th>Growth Stage</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rice_data as $row): ?>
                <tr>
                    <form class="edit-form" action="" method="POST">
                        <td><?= $row['ID'] ?></td>
                        <td><input type="text" name="variety" value="<?= $row['Variety'] ?>"></td>
                        <td><input type="text" name="growth_stage" value="<?= $row['GrowthStage'] ?>"></td>
                        <td class="action-buttons">
                            <input type="hidden" name="rice_id" value="<?= $row['ID'] ?>">
                            <input type="submit" name="edit_rice" value="Edit">
                        </form>
                        <form class="delete-form" action="" method="POST">
                            <input type="hidden" name="rice_id" value="<?= $row['ID'] ?>">
                            <input type="submit" name="delete_rice" value="Delete" onclick="return confirm('Are you sure you want to delete this item?')">
                        </form>
                        </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <form action="" method="POST">
            <h3>Insert New Rice Record</h3>
            <label>Variety:</label>
            <input type="text" name="variety" required>
            <label>Growth Stage:</label>
            <input type="text" name="growth_stage" required>
            <input type="submit" name="insert_rice" value="Insert">
        </form>

        <h1>Symptoms Table</h1>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Description</th>
                    <th>Severity Level</th>
                    <th>Location on Plant</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($symptoms_data as $row): ?>
                <tr>
                    <form class="edit-form" action="" method="POST">
                        <td><?= $row['ID'] ?></td>
                        <td><input type="text" name="description" value="<?= $row['Description'] ?>"></td>
                        <td><input type="text" name="severity_level" value="<?= $row['SeverityLevel'] ?>"></td>
                        <td><input type="text" name="location_on_plant" value="<?= $row['LocationOnPlant'] ?>"></td>
                        <td class="action-buttons">
                            <input type="hidden" name="symptom_id" value="<?= $row['ID'] ?>">
                            <input type="submit" name="edit_symptoms" value="Edit">
                        </form>
                        <form class="delete-form" action="" method="POST">
                            <input type="hidden" name="symptom_id" value="<?= $row['ID'] ?>">
                            <input type="submit" name="delete_symptoms" value="Delete" onclick="return confirm('Are you sure you want to delete this item?')">
                        </form>
                        </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <form action="" method="POST">
            <h3>Insert New Symptom Record</h3>
            <label>Description:</label>
            <input type="text" name="description" required>
            <label>Severity Level:</label>
            <input type="text" name="severity_level" required>
            <label>Location on Plant:</label>
            <input type="text" name="location_on_plant" required>
            <input type="submit" name="insert_symptoms" value="Insert">
        </form>

        <h1>DiseaseType Table</h1>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($disease_type_data as $row): ?>
                <tr>
                    <form class="edit-form" action="" method="POST">
                        <td><?= $row['ID'] ?></td>
                        <td><input type="text" name="name" value="<?= $row['Name'] ?>"></td>
                        <td><input type="text" name="description" value="<?= $row['Description'] ?>"></td>
                        <td class="action-buttons">
                            <input type="hidden" name="disease_type_id" value="<?= $row['ID'] ?>">
                            <input type="submit" name="edit_disease_type" value="Edit">
                        </form>
                        <form class="delete-form" action="" method="POST">
                            <input type="hidden" name="disease_type_id" value="<?= $row['ID'] ?>">
                            <input type="submit" name="delete_disease_type" value="Delete" onclick="return confirm('Are you sure you want to delete this item?')">
                        </form>
                        </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <form action="" method="POST">
            <h3>Insert New Disease Type Record</h3>
            <label>Name:</label>
            <input type="text" name="name" required>
            <label>Description:</label>
            <input type="text" name="description" required>
            <input type="submit" name="insert_disease_type" value="Insert">
        </form>

        <h1>DetectionResult Table</h1>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>DetectionID</th>
                    <th>RiceID</th>
                    <th>SymptomID</th>
                    <th>DiseaseTypeID</th>
                    <th>Confidence Level</th>
                    <th>Timestamp</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($detection_result_data as $row): ?>
                <tr>
                    <form class="edit-form" action="" method="POST">
                        <td><?= $row['DetectionID'] ?></td>
                        <td><input type="text" name="rice_id" value="<?= $row['RiceID'] ?>"></td>
                        <td><input type="text" name="symptom_id" value="<?= $row['SymptomID'] ?>"></td>
                        <td><input type="text" name="disease_type_id" value="<?= $row['DiseaseTypeID'] ?>"></td>
                        <td><input type="text" name="confidence_level" value="<?= $row['ConfidenceLevel'] ?>"></td>
                        <td><input type="text" name="timestamp" value="<?= $row['Timestamp'] ?>"></td>
                        <td class="action-buttons">
                            <input type="hidden" name="detection_id" value="<?= $row['DetectionID'] ?>">
                            <input type="submit" name="edit_detection_result" value="Edit">
                        </form>
                        <form class="delete-form" action="" method="POST">
                            <input type="hidden" name="detection_id" value="<?= $row['DetectionID'] ?>">
                            <input type="submit" name="delete_detection_result" value="Delete" onclick="return confirm('Are you sure you want to delete this item?')">
                        </form>
                        </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <form action="" method="POST">
            <h3>Insert New Detection Result Record</h3>
            <label>RiceID:</label>
            <input type="text" name="rice_id" required>
            <label>SymptomID:</label>
            <input type="text" name="symptom_id" required>
            <label>DiseaseTypeID:</label>
            <input type="text" name="disease_type_id" required>
            <label>Confidence Level:</label>
            <input type="text" name="confidence_level" required>
            <input type="submit" name="insert_detection_result" value="Insert">
        </form>
    </div>
</main>

<footer>
    <p>&copy; 2024 CCC151</p>
</footer>
</body>
</html>