<?php
// Исходные данные
$key = 'a7549ea16658ae45d098dd4e1f637108';
$city = 'Moscow';
// Проходим по всем файлам с расширением .json
foreach (glob("*.json") as $file) {
  // Если файл был создан менее часа назад (имя файла - дата создания)
  // то обрабатываем данные из него
  if (intval(basename($file, ".json"))-(60*60) <= time()){
    $json = file_get_contents($file);
    $message =  "Data from file";
  }
  // Иначе удаляем файл с устаревшими данными
  else {unlink($file);}
}
// Если в $json ничего нет, значит актуальных данных нет
// Тогда получаем данные api
if (is_null($json)) {
    $request = trim('http://api.openweathermap.org/data/2.5/weather?q='.$city.'&appid='.$key);
    $json = file_get_contents($request);

    // Сохраняем json
    file_put_contents(time().'.json',$json);
    $message = "Data from OpenWeather";
}

// Обрабатываем json и сохраняем необходимые данные
$json = json_decode($json, true);
$temp = round(($json["main"]["temp"] - 273.15),1);
$status = $json["weather"]["0"]["main"];
$wind = $json["wind"];
$pressure = $json["main"]["pressure"];
 ?>
 <!--Шаблон для вывода полученных данных-->
 <html>
   <head>
     <meta charset="utf-8">
     <title>Forecast for today!</title>
     <style>
       body {
        background-image: url(images/background.jpg);
        background-repeat:no-repeat;
        background-size:auto;
        background-position:center;
        color: #FFF;
       }
       div{
         text-align: center;
         font-family: sans-serif;
         font-size: 130%;
       }
       p{
         margin: 5px;
       }
       .background{
         background-color: #000;
         opacity: 0.5;
         margin-left: 10%;
         margin-right: 10%;
       }
       .content{
         margin: 0 auto;
         width: 75%;
         text-align: left;
         font-size: 125%;
       }
     </style>
   </head>
   <body>
     <div class="background">
       <div style="opacity:1;">
         <h1 style="margin-bottom: -20; margin-top:0;">Greetings, Human!</h1>
         <h2>Weather for today in <?echo $city?>:</h2>
         <div class="content">
         <center>
             <?
             // Выводим картинку в зависимости от текущей погоды
             $source = "images/weather/".strtolower($status).".png";
             echo "<img src=$source align=middle>";
            ?>
         </center>
         <!--Выводим полученные данные-->
         <p>Current temperature: <?echo "$temp";?>°C</p>
         <p>Outside: <?echo "$status";?></p>
         <p>Wind speed: <?echo $wind["speed"];?> m/s</p>
         <p>Pressure: <?echo $pressure;?> </p>
       </div>
       <p style="font-size: 75%"><?echo "$message";?></p>
     </div>
   </body>
 </html>
