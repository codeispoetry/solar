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
		AVG(P_Load) * -1 AS p_load, 
		AVG(P_PV) AS p_pv, 
		AVG(SOC) AS soc, 
		AVG(P_grid) AS p_grid, 


		strftime('%H:%M', timestamp) AS label 
	FROM solar GROUP BY strftime('%Y%m%d%H0', timestamp) + strftime('%M', timestamp)/15
	ORDER BY timestamp
	;";
$results = $db->query($sql);


$labels = [];
$data = [];

$lines = ['p_load','p_pv','p_grid'];

while ($row = $results->fetchArray()) {
 
    array_walk(
        $lines, function ($item) {
            global $data, $row;
            $data[$item] .= max(0, $row[$item]) . ',';
        }
    );

    $labels[] = $row['label'];
}

$labels = "'" . join("','", $labels) . "'";




?>
    <script src="/node_modules/chart.js/dist/chart.min.js"></script>

    <script>
  
        const data = {
            labels: [<?php echo $labels; ?>],
            datasets: [
           
            <?php 
                array_walk(
                    $lines, function ($label) {
                        global $data;
                        static $i=0;
                        
                        $colors = ['red','green','blue','orange','violet'];


                        echo "
							{
								label: '$label',
								backgroundColor: '{$colors[$i]}',
								borderColor: '{$colors[$i]}',
								data: [{$data[$label]}],
							},
						";

                        $i++;

                    } 
                ); 
            
                ?>
            
        ]
        };

        const config = {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins:{
                    legend: {
                        display: true
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