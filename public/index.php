<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use Tariff\CompareTariff\Controller\CompareTariffController;

$compareTariff = new CompareTariffController();
$request = parse_url($_SERVER['REQUEST_URI']);
//print_r($request);die();

switch ($request["path"]) {
	case '/tariff/calculate':
		$compareTariff->calculateTariff();
		break;
	default:
		echo "Incorrect route";
		die();
}

die();

