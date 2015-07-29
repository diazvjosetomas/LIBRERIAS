<?php
ini_set("soap.wsdl_cache_enabled", 0);
$url = "http://www.webservicex.com/globalweather.asmx?wsdl";
$options["connection_timeout"] = 1000;
$options = array();
$options['user_agent'] = "";
$options["location"] = $url;
$options['trace'] = 1;

$client = new SoapClient($url,$options);

echo '<pre>';
var_dump($client->__getFunctions());
echo '</pre>';

$resultado_suma = $client->GetWeather(array("CountryName"=>"Venezuela","CityName"=>"Maracay"));
var_dump($resultado_suma);

//echo $resultado_suma->GetCitiesByCountryResult;
?>