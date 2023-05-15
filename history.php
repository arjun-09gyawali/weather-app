<?php

header("Content-type: application/json");
header("Access-Control-Allow-Origin: *");


if (!isset($_GET["place"])){
    echo json_encode(["error"=>"no place provided"]);
    exit;
}
$place = $_GET["place"];


$connection = new mysqli("localhost","root","","weather");

$queryString = 'SELECT * FROM weather_data WHERE Name = "'.$place.'";';
$res = $connection->query($queryString);
if($res && $res->num_rows>0){
    $data = $res->fetch_all(MYSQLI_ASSOC);
    $send = array();

    foreach($data as $row){
        $condi = $row['Description'];
        $temp = $row['Temprature'];
        $country = $row['Name'];
        $wind = $row['Wind'];
        $humi = $row['Humidity'];
        $Date = $row['Moment'];
        $icon = $row['icon'];
        $Dt = $row['dt'];

        $s = array(
            'condi' => $condi,
            'temp' => $temp,
            'country' => $country,
            'wind' => $wind,
            'humi' => $humi,
            'Date' => $Date,
            'icon' => $icon,
            'dt' => $Dt
        );

        array_push($send,$s);

    }
    echo json_encode($send);
}else{
    echo json_encode(array());
}

?>