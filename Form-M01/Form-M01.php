<?php
include '../DB/connection.php';
//try
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['formType']) && $_POST['formType'] === 'enquiry') {
    // Extract the form data
    $date = $_POST['date'];
    $enquiryRef = $_POST['enquiryRef']; // Assuming you've added this field to your form
    $channel = $_POST['channel'];
    $platform = $_POST['platform'];
    $status = $_POST['status'];
    $approachProspect = isset($_POST['approach_prospect']) ? $_POST['approach_prospect'] : '';
    $checkedBy = $_POST['by'];
    $note = $_POST['note'];
    $remark = $_POST['remark'];

    // Insert the data into the enquiry table
    $query = "INSERT INTO form_m01 (date, enquiry_ref, channel_id, platform_id, status_id, approach_prospect_id, checkedBy, note, remark) VALUES (?, ?,
    (SELECT id FROM data_m01 WHERE value = ? AND type = 'channel'),
    (SELECT id FROM data_m01 WHERE value = ? AND type = 'platform'),
    (SELECT id FROM data_m01 WHERE value = ? AND type = 'status'),
    (SELECT id FROM data_m01 WHERE value = ? AND type = 'approach_prospect'),
    (SELECT id FROM data_m01 WHERE value = ? AND type = 'checked_by'),
    ?, ?)";

    $stmt = mysqli_prepare($conn, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssssssss", $date, $enquiryRef, $channel, $platform, $status, $approachProspect, $checkedBy, $note, $remark);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            echo '<script>alert("Enquiry submitted successfully!");</script>';
            // Redirect or perform other success operations
        } else {
            echo "Error inserting data: " . mysqli_error($conn);
        }
    } else {
        echo "Error preparing statement: " . mysqli_error($conn);
    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Entry Form</title>
    <link rel="stylesheet" href="styles.css">
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
        
        h2 {
            font-size: 24px;
            font-weight: 600;
            color: #333;
            text-align: center;
        }
        
        label {
            display: block;
            margin-top: 15px;
            font-weight: 500;
        }
        
        input[type="text"],
        input[type="date"],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }
        
        input[type="text"]:hover,
        input[type="date"]:hover,
        select:hover,
        textarea:hover,
        input[type="text"]:focus,
        input[type="date"]:focus,
        select:focus,
        textarea:focus {
            border-color: #88a4bc;
        }
        
        button {
            width: 100%;
            padding: 10px;
            background-color: #F3A462;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        button:hover {
            background-color: #FA913B;
        }
        
        .form-container {
            max-width: 600px;
            margin: 30px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        /* Radio buttons and labels inline */
        .radio-group {
            display: flex;
            align-items: center;
            justify-content: flex-start;
        }
        
        .radio-group label {
            margin-right: 15px; /* Space after the label */
        }
        
        /* Additional styling for radio buttons for a cleaner look */
        /* Hide the default radio button */
        input[type='radio'] {
            visibility: hidden; /* Hide the input */
            position: absolute; /* Remove it from the document flow */
        }
        
        /* Create a custom radio button */
        .radio-group label:before {
            content: '';
            display: inline-block;
            margin-right: 10px;
            width: 15px; /* Custom width */
            height: 15px; /* Custom height */
            border-radius: 50%;
            border: 2px solid #9b9b9b;
            background-color: #fff;
            vertical-align: middle;
        }
        
        /* Style when the radio button is checked */
        input[type='radio']:checked + label:before {
            background-color: #F3A462;
            border-color: black;
        }
        
        /* Optional: Add an inner circle to mimic a radio button */
        input[type='radio']:checked + label:after {
            content: '';
            display: inline-block;
            position: absolute;
            width: 10px; /* Width of the inner circle */
            height: 10px; /* Height of the inner circle */
            background-color: #fff;
            border-radius: 50%;
            left: 5px; /* Position to the center */
            top: 5px; /* Position to the center */
        }

        /* Responsive design */
        @media (max-width: 600px) {
            .form-container {
                width: 90%;
            }
        }
        

    </style>
</head>

<body>

    <div class="navbar">
        <a href="Form-M01.php">Enter Data</a>
        <a href="../Form-M01-Admin/Form-M01-data-display.php" target="_blank">View Records</a>
        <a href="../Form-M01-Admin/Form-M01-manage-data-02.php" target="_blank">Manage Data (02)</a>
    </div>

    <!-- Enquiry Form -->
    <div class="form-container active" id="enquiryForm">
        <h2>Data Entry Form</h2>
        <form class="enquiry-form" method="POST" action="">
            <input type="hidden" name="formType" value="enquiry">

            <label for="date">Date:</label>
            <input type="date" id="date" name="date" required>

            <label for="channel">Channel:</label>
            <select id="channel" name="channel">
                <?php
                // Fetch data from Channel table
                $query_channel = "SELECT * FROM data_m01 WHERE type='channel'";
                $result_channel = mysqli_query($conn, $query_channel);

                if ($result_channel) {
                    while ($row = mysqli_fetch_assoc($result_channel)) {
                        echo "<option value='{$row['value']}'>{$row['value']}</option>";
                    }
                } else {
                    echo "Error fetching channels: " . mysqli_error($conn);
                }
                ?>
            </select>

            <label for="enquiryRef">Enquiry Reference:</label>
            <input type="text" id="enquiryRef" name="enquiryRef" required>

            <label for="platform">Platform:</label>
            <select id="platform" name="platform">
                <?php
                // Fetch data from Channel table
                $query_platform = "SELECT * FROM data_m01 WHERE type='platform'";
                $result_platform = mysqli_query($conn, $query_platform);
                
                if ($result_platform) {
                    while ($row = mysqli_fetch_assoc($result_platform)) {
                        echo "<option value='{$row['value']}'>{$row['value']}</option>";
                    }
                } else {
                    echo "Error fetching platform: " . mysqli_error($conn);
                }
                ?>
            </select>

            <label for="status">Status:</label>
            <select id="status" name="status">
                <?php
                // Fetch data from Status table
                $query_status = "SELECT * FROM data_m01 WHERE type='status'";
                $result_status = mysqli_query($conn, $query_status);
                if ($result_status) {
                    while ($row = mysqli_fetch_assoc($result_status)) {
                        echo "<option value='{$row['value']}'>{$row['value']}</option>";
                    }
                } else {
                    echo "Error fetching status: " . mysqli_error($conn);
                }
                ?>
            </select>

            <label for="approach_prospect">Approach Prospect:</label>
            <div class="radio-group">
                <input type="radio" id="approach_prospect_yes" name="approach_prospect" value="yes">
                <label for="approach_prospect_yes">Yes</label>
                <input type="radio" id="approach_prospect_no" name="approach_prospect" value="no">
                <label for="approach_prospect_no">No</label>
            </div>
            
            <br>

            <label for="by">Checked By:</label>
            <select id="by" name="by">
                <?php
                // Fetch data from Users table
                $query_by = "SELECT * FROM data_m01 WHERE type='checked_by'";
                $result_by = mysqli_query($conn, $query_by);
                if ($result_by) {
                    while ($row = mysqli_fetch_assoc($result_by)) {
                        echo "<option value='{$row['value']}'>{$row['value']}</option>";
                    }
                } else {
                    echo "Error fetching user: " . mysqli_error($conn);
                }
                ?>
            </select>

            <label for="note">Note:</label>
            <input type="text" id="note" name="note">

            <label for="remark">Remark:</label>
            <input type="text" id="remark" name="remark">

            <button type="submit">Submit Enquiry</button>
        </form>
    </div>

</body>

</html>