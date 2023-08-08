<?php
    if (empty($_POST['inputYear']))
    {
        echo "No year selected";
        return 0;
    }
    include_once "include.php";

    $selectedYear = $_POST['inputYear'];
    $query2 = "
    SELECT
        date_format(o.RequiredDate, '%b %Y') AS date,
        round(SUM(od.UnitPrice * od.Quantity)) AS Sales,
        round(SUM(od.Quantity)) AS Quantity
    FROM
        orders o
    INNER JOIN
        orderdetails od ON o.OrderID = od.OrderID
    INNER JOIN
        products p ON od.ProductID = p.ProductID
    WHERE
        YEAR(o.RequiredDate) = $selectedYear
    GROUP BY
        YEAR(o.RequiredDate),
        MONTH(o.RequiredDate)
    ORDER BY
        YEAR(o.RequiredDate),
        MONTH(o.RequiredDate);
";

    $result = mysqli_query($connection, $query2);

    if (!$result) {
        die('Error executing the query: ' . mysqli_error($connection));
    }

    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = [
            'date' => $row['date'],
            'Sales' => (int)$row['Sales'],
            'Quantity' => (int)$row['Quantity'],
        ];
    }

    $jsonData = json_encode($data);

?>
