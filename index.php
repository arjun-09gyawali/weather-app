<?php
header("Content-type: application/json");
header("Access-Control-Allow-Origin: *");


if (!isset($_GET["place"])){
    echo json_encode(["error"=>"no place provided"]);
    exit;
}
$place = $_GET["place"];

$connection = new mysqli("localhost","root","","weather");

function fetch($connection){
    $file = file_get_contents("https://api.openweathermap.org/data/2.5/weather?q=".$_GET["place"]."&appid=ddf141f591dbee5512c13c652b3ec35b&units=metric");
    
    $data = json_decode($file, true);
    $condi = $data['weather'][0]['description'];
    $temp = $data['main']['temp'];
    $country = $data['name'];
    $wind = $data['wind']['speed'];
    $humi = $data['main']['humidity'];
    $Date = date("y-m-d");
    $icon = $data['weather'][0]['icon'];
    $Dt = $data['dt'];
    
    $connection->query("INSERT INTO weather_data VALUES('$country','$condi',$temp,$humi,$wind,'$Date','$icon', $Dt)");
            
    
    $send = array(
        'condi' => $condi,
        'temp' => $temp,
        'country' => $country,
        'wind' => $wind,
        'humi' => $humi,
        'Date' => $Date,
        'icon' => $icon,
        'dt' => $Dt
    );
    return $send;
}
if (isset($_GET["all_data"])){
    $queryString = 'SELECT * FROM weather_data WHERE Name = "'.$place.'"  ORDER BY dt DESC LIMIT 1;';
}

$res = $connection->query($queryString);
if($res && $res->num_rows>0){
    $data = $res->fetch_assoc();

    $weather_time = $data["dt"];
    $current_time = time();
    if ($current_time-$weather_time>86400){
        $send = fetch($connection);
        echo json_encode($send);
        exit;
    } else{
        $condi = $data['Description'];
        $temp = $data['Temprature'];
        $country = $data['Name'];
        $wind = $data['Wind'];
        $humi = $data['Humidity'];
        $Date = $data['Moment'];
        $icon = $data['icon'];
        $Dt = $data['dt'];

        $send = array(
            'condi' => $condi,
            'temp' => $temp,
            'country' => $country,
            'wind' => $wind,
            'humi' => $humi,
            'Date' => $Date,
            'icon' => $icon,
            'dt' => $Dt
        );

        echo json_encode($send);
    }
}else{
    $send = fetch($connection);
    echo json_encode($send);
}

?>