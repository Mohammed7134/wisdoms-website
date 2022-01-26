<?php
include("v1/templates/categories.php");
$url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$var = parse_url($url, PHP_URL_PATH);
$var = explode('/', $url);
$var = explode("=", $var[1]);
$title = $categories[$var[1]];
include("v1/templates/head.php");
headerTemplate("فقه الحياة | " . ($title ? $title : "مفاهيم وتوجيهات تتعلق بالحياة"), false);
include("v1/templates/main.php");
include("v1/templates/foot.php");
// include __DIR__ . "/v1/php/getAllWisdoms.php";
// echo $response;
