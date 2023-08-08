<?php
include_once "include.php";
include_once "load_info_lines.php";
include_once "load_info_columns.php";

$yearoptions = "SELECT DISTINCT(YEAR(RequiredDate)) AS years FROM orders";
$result = mysqli_query($connection, $yearoptions);
if (!$result) {
    die('Error executing the query: ' . mysqli_error($connection));
}

$options = '';
$selectedYear = isset($_POST['inputYear']) ? $_POST['inputYear'] : '';

while ($row = mysqli_fetch_assoc($result)) {
    $isSelected = ($selectedYear === $row['years']) ? 'selected' : '';
    $options .= "<option $isSelected>" . $row['years'] . "</option>";
}

mysqli_free_result($result);

mysqli_close($connection);

?>
<html>
<head>
    <title>JavaScript example</title>
    <meta charSet="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <style media="only screen">
        html, body {
            height: 100%;
            width: 100%;
            margin: 0;
            box-sizing: border-box;
            -webkit-overflow-scrolling: touch;
        }

        html {
            position: absolute;
            top: 0;
            left: 0;
            padding: 0;
            overflow: auto;
        }

        body {
            padding: 1rem;
            overflow: auto;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/ag-charts-community/dist/ag-charts-community.min.js"></script>
</head>

<body>

    <div id="myChart1" style="height: 48%"></div>

    <div style="align-items: center; justify-content: center; display: flex;">
        <form id="YearForm" method="POST">
            <select id="inputYear" name="inputYear">
                <option value="">Choose year</option>
                <?= $options;?>
            </select>
            <button>Draw</button>
        </form>
    </div>

    <div id="myChart2" style="height: 48%"></div>

<script src="https://cdn.jsdelivr.net/npm/ag-charts-community@8.0.6/dist/ag-charts-community.min.js"></script>
<script>
    const options1 = {
        container: document.getElementById('myChart1'),
        autoSize: true,
        title: {
            text: 'Top 10 products sold by month of the year 1998',
        },
        data: <?= $jsonData1 ?>,

        series:
            <?= json_encode($series);?>
    };
    agCharts.AgChart.create(options1);
</script>
<script>
    const data = <?= json_encode($data,JSON_HEX_TAG); ?>;
    const options = {
        container: document.getElementById('myChart2'),
        data: data,
        title: {
            text: 'Total Sales'
        },
        subtitle: {
            text: 'per month'
        },
        footnote: {
            text: 'Based by all sales for a certain year'
        },
        padding: {
            top: 40,
            right: 40,
            bottom: 40,
            left: 40
        },
        series: [
            { type: 'column', xKey: 'date', yKey: 'Sales', stacked: false },
            { type: 'column', xKey: 'date', yKey: 'Quantity', stacked: false }
        ],
        legend: {
            spacing: 40
        },
    };
    agCharts.AgChart.create(options);
</script>
</body>
</html>
