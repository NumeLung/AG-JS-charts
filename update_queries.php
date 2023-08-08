<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedYear = json_decode(file_get_contents('php://input'))->year;

    // Update your queries with the selected year
    $query1 = str_replace('1998', $selectedYear, $query1);
    $query2 = str_replace('1998', $selectedYear, $query2);

    // You can store the updated queries back in separate files if needed
    file_put_contents('updated_query1.sql', $query1);
    file_put_contents('updated_query2.sql', $query2);

    // You can also return a response if necessary
    echo json_encode(['success' => true]);
}

