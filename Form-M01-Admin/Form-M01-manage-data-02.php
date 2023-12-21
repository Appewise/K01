<?php
// Include the database connection file
include '../DB/connection.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check for a CSV file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {
    $file = $_FILES['csv_file'];

    // Check for upload errors
    if ($file['error'] === UPLOAD_ERR_OK) {
        // Open the file in read mode
        $handle = fopen($file['tmp_name'], 'r');

        // Skip the header line
        fgetcsv($handle);

        // Begin a transaction
        $conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

        // Read each line of the CSV file
        while (($data = fgetcsv($handle)) !== FALSE) {
            $type = $data[0];
            $value = $data[1];

            // Prepare the MySQL insert statement
            $stmt = $conn->prepare("INSERT INTO data_m01 (type, value) VALUES (?, ?)");
            $stmt->bind_param("ss", $type, $value);
            $stmt->execute();
        }

        // Commit the transaction
        $conn->commit();

        fclose($handle);
        echo '<script>alert("Data imported successfully!");</script>';
        exit;
    } else {
        echo '<script>alert("Error uploading file!");</script>';
    }
}

// Export Data to CSV
if (isset($_POST['export'])) {
    $query = "SELECT * FROM data_m01";

    // Prepare the SQL statement
    $stmt = mysqli_prepare($conn, $query);

    if (!$stmt) {
        die("Query failed: " . mysqli_error($conn));
    }

    // Execute the query
    if (!mysqli_stmt_execute($stmt)) {
        die("Query execution failed: " . mysqli_error($conn));
    }

    // Bind result variables
    mysqli_stmt_bind_result($stmt, $id, $type, $value);

    // Create a CSV file
    $filename = 'export.csv';
    $file = fopen($filename, 'w');

    // Add CSV headers
    $header = array('id', 'type', 'value');
    fputcsv($file, $header);

    // Fetch and add data rows to the CSV file
    while (mysqli_stmt_fetch($stmt)) {
        $row = array($id, $type, $value);
        fputcsv($file, $row);
    }

    // Close the CSV file
    fclose($file);

    // Close the prepared statement
    mysqli_stmt_close($stmt);

    // Provide a download link to the user
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename=' . $filename);
    readfile($filename);
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Import and Export</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        
        header {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 20px 0;
        }

        h1 {
            margin: 0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            font-weight: bold;
        }

        input[type="file"] {
            margin-top:10px;
            padding: 10px;
            border-radius: 5px;
            width: 100%;
        }

        input[type="submit"] {
            background-color: #333;
            color: #fff;
            padding: 10px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[value="Import Data"] {
            margin: 20px 0px 20px 0px;
        }

        input[type="submit"]:hover {
            background-color: #555;
        }

        p {
            margin-top: 20px;
        }

        a {
            color: blue;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header>
        <h1>Data Import and Export</h1>
    </header>
    <div class="container">
        <h2>Import Data</h2>
        <form action="Form-M01-manage-data-02.php" method="post" enctype="multipart/form-data">
            <label for="csv_file">Choose a CSV file to import:</label>
            <input type="file" name="csv_file" id="csv_file" accept=".csv" required>
            <input type="submit" value="Import Data">
        </form>
        <h2>Export Data to CSV</h2>
        <form action="Form-M01-manage-data-02.php" method="post">
            <input type="submit" name="export" value="Export Data">
        </form>
        <br>
        <hr>
        <h2>Download CSV Template</h2>
        <p>Click the link below to download a CSV template:</p>
        <a href="Form-M01-import-template.php">Click To Download Template</a>
    </div>
</body>
</html>
