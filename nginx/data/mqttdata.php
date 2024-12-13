<?php
header('Content-Type: application/json');
$servername = 'mariadb_web';
$username = 'liva';
$password = 'liva';
$dbname = 'mqttdata';

// Opret forbindelse
$conn = new mysqli($servername, $username, $password, $dbname);

// Tjek forbindelsen
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
/*
CREATE TABLE `mqttdata_received` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`json` JSON NULL,
    'data' varchar(1024) NULL,
	`created` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP(),
	PRIMARY KEY (`id`)
)
;
*/
$sql = "SELECT
            JSON_EXTRACT(data, '$.sensor') AS sensor,
            JSON_ARRAYAGG(JSON_EXTRACT(data, '$.temperature')) AS temp,
            JSON_ARRAYAGG(JSON_EXTRACT(data, '$.humidity')) AS hum,
            JSON_ARRAYAGG(JSON_EXTRACT(data, '$.pressure')) AS press,
            JSON_ARRAYAGG(created) AS lbl
        FROM
            mqttdata_received
        GROUP BY
            sensor";

$result = $conn->query($sql);

$data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $sensorName = $row['sensor'];
        $labels = ConvertLabels(json_decode($row['lbl']));

        $data[] = [
            "sensor" => $sensorName,
            "datasets" => [
                [
                    "label" => "Temperatur",
                    "data" => json_decode($row['temp']),
                    "borderColor" => "rgba(75, 192, 192, 1)",
                    "fill" => false
                ],
                [
                    "label" => "Fugtighed",
                    "data" => json_decode($row['hum']),
                    "borderColor" => "rgba(54, 162, 235, 1)",
                    "fill" => false
                ],
                [
                    "label" => "Lufttryk",
                    "data" => json_decode($row['press']),
                    "borderColor" => "rgba(255, 159, 64, 1)",
                    "yAxisID" => 'y-axis-2',
                    "fill" => false
                ]
            ],
            //"labels" => json_decode($row['lbl'])
            "labels" => $labels //json_decode($row['lbl'])
        ];
    }
}

$conn->close();

echo json_encode($data);


function ConvertLabels($labels) {
    //print_r($labels);
    // Nyt array til at gemme de formaterede datetime strenge
    $formattedArray = array();

    // Gennemløb det originale array og formater hver datetime streng
    foreach ($labels as $datetimeStr) {
        $datetime = new DateTime($datetimeStr);
        $formattedArray[] = $datetime->format('d-m H:i');
    }

// Udskriv det nye array med de formaterede datetime strenge
    return($formattedArray);
}

?>
