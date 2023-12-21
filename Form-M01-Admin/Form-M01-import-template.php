<?php
// Define the headers for the CSV file
$headers = ['type', 'value'];

// Create a temporary file to store the CSV data
$tempFilename = tempnam(sys_get_temp_dir(), 'csv_template');

// Open the temporary file in write mode
$file = fopen($tempFilename, 'w');

// Check if the file was opened successfully
if ($file === false) {
    die('Unable to create the CSV file.');
}

// Insert the headers into the CSV file
fputcsv($file, $headers);

// Close the CSV file
fclose($file);

// Set headers to trigger the download
header('Content-Description: File Transfer');
header('Content-Type: application/csv');
header('Content-Disposition: attachment; filename="csv_template.csv"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($tempFilename));

// Clear the output buffer
ob_clean();
flush();

// Read the temporary file for download
readfile($tempFilename);

// Delete the temporary file after download
unlink($tempFilename);
?>
