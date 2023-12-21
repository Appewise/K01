<?php

include ('../DB/connection.php');

// Start the session
session_start();

// Fetch all records from the database
$result = $conn->query("SELECT * FROM form_DataView");

// Check for errors
if (!$result) {
    die("Error: " . $conn->error);
}

function exportToCSV($result) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="export.csv"');

    $output = fopen("php://output", "w");

    // Output the column headings
    fputcsv($output, array('Date', 'Channel', 'Enquiry Reference', 'Platform', 'Status', 'Approach Prospect', 'Checked By', 'Note', 'Remark'));

    // Output the data from each row
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit();
}

// Check if export button has been submitted
if (isset($_GET['export'])) {
    exportToCSV($result);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Records</title>
</head>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        padding: 20px;
    }

    .navbar a {
        text-decoration: none;
        margin-right: 15px;
        color: #333;
        font-weight: bold;
    }

    .navbar a:hover {
        text-decoration: underline;
    }

    button {
        background-color: #F3A462;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        margin-bottom: 20px;
    }

    button:hover {
        background-color: #FA913B;
    }

    table {
        margin-top: 20px;
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #F3A462;
        color: white;
    }

    tr{
        background-color: white;
    }

    tr:hover {
        background-color: #ddd;
    }
</style>

<body>
    <div class="navbar">
        <a href="../Form-M01/Form-M01.php" target="_blank">Enter Data</a>
        <a href="Form-M01-data-display.php">View Records</a>
        <a href="Form-M01-manage-data-02.php" target="_blank">Manage Data (02)</a>
    </div>
    
    <!-- Data Display Section -->
    <table id="enquiryTable">
        <thead>
            <tr>
                <th>Date</th>
                <th>Channel</th>
                <th>Enquiry Reference</th>
                <th>Platform</th>
                <th>Status</th>
                <th>Approach Prospect</th>
                <th>Checked By</th>
                <th>Note</th>
                <th>Remark</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td>
                    <?php echo htmlspecialchars($row['Date']); ?>
                </td>
                <td>
                    <?php echo htmlspecialchars($row['Channel']); ?>
                </td>
                <td>
                    <?php echo htmlspecialchars($row['EnquiryReference']); ?>
                </td>
                <td>
                    <?php echo htmlspecialchars($row['Platform']); ?>
                </td>
                <td>
                    <?php echo htmlspecialchars($row['Status']); ?>
                </td>
                <td>
                    <?php echo htmlspecialchars($row['ApproachProspect']); ?>
                </td>
                <td>
                    <?php echo htmlspecialchars($row['CheckedBy']); ?>
                </td>
                <td>
                    <?php echo htmlspecialchars($row['Note']); ?>
                </td>
                <td>
                    <?php echo htmlspecialchars($row['Remark']); ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
    </table>
    <button onclick="window.location.href='Form-M01-data-display.php?export=true'">Export to CSV</button>
</body>

</html>
