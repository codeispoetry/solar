<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solar</title>
</head>
<body>
    <canvas id="chart" height="300"></canvas>

<?php
$db = new SQLite3('data/solar.db');
$sql = "
	SELECT 
		AVG(P_Load) AS p_load, 
		AVG(P_PV) AS p_pv, 

		strftime('%H:%M', timestamp) AS label 
	FROM solar GROUP BY strftime('%Y%m%d%H0', timestamp) + strftime('%M', timestamp)/15
	ORDER BY timestamp;";
$results = $db->query($sql);

$p_load = [];
$p_pv = [];
$labels = [];
while ($row = $results->fetchArray()) {
    $p_load[] = $row['p_load'];
    $p_pv[] = $row['p_pv'];
    $labels[] = $row['label'];
}
$p_load = join(',', $p_load);
$p_pv = join(',', $p_pv);

$labels = "'" . join("','", $labels) . "'";
?>
    <script src="/node_modules/chart.js/dist/chart.min.js"></script>

    <script>
  
        const data = {
            labels: [<?php echo $labels; ?>],
            datasets: [{
                backgroundColor: 'rgb(255, 99, 132)',
                borderColor: 'rgb(255, 0, 0)',
                data: [<?php echo $p_load; ?>],
            },
            {
                backgroundColor: 'rgb(0, 255, 0)',
                borderColor: 'rgb(0, 255, 0)',
                data: [<?php echo $p_pv; ?>],
            }]
        };

        const config = {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins:{
                legend: {
                    display: false
                    },
                },
            }
        };

        const myChart = new Chart(
            document.getElementById('chart'),
            config
        );
</script>
</body>
</html>