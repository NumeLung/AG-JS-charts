<?php
include_once "include.php";

if (empty($_POST['inputYear']))
    {
        echo "No year selected";
        return 0;
    }
    $selectedYear = $_POST['inputYear'];
    $query1 = "
SELECT
    YEAR(o.OrderDate) AS year,
    MONTH(o.OrderDate) AS month,
    sub.Top10 AS name,
    SUM(od.Quantity) AS cnt
FROM
    (
        SELECT
            p.ProductName AS Top10,
            p.ProductID,
            SUM(od.Quantity) AS TotalQuantitySold
        FROM
            products p
        JOIN
            orderdetails od ON p.ProductID = od.ProductID
        GROUP BY
            p.ProductID, p.ProductName
        ORDER BY
            TotalQuantitySold DESC
        LIMIT 10
    ) AS sub
JOIN
    orderdetails od ON sub.ProductID = od.ProductID
JOIN
    orders o ON od.OrderID = o.OrderID
WHERE
    YEAR(o.OrderDate) = $selectedYear
GROUP BY
    year, month, sub.Top10
ORDER BY
    year, month, cnt DESC;
";
    $result = mysqli_query($connection, $query1);

    $aData = [];
    $series = [];

    foreach ($result as $row) {
        $month = (int)$row['month'];
        $product = $row['name'];
        $cnt = (int)$row['cnt'];

        if (!isset($aData[$month])) {
            $aData[$month] = ['month' => $month];
        }

        $aData[$month][$product] = $cnt;

        $series['month' . '-' . $row['name']] = [
            'xKey' => 'month',
            'yKey' => $row['name']
        ];
    }

    $series = array_values($series);
    $output = array_values($aData);

    if (!$result) {
        die('Error executing the query: ' . mysqli_error($connection));
    }

    $jsonData1 = json_encode(array_values($output));

?>

