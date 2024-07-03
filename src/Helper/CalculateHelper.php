<?php

namespace Tariff\CompareTariff\Helper;
use Exception;

class CalculateHelper 
{
	public function validate(array $request): float|Exception
	{
		if(is_array($request)) {
			if (isset($request["Consumption"]) && is_numeric($request["Consumption"]) ) {
				return (float)$request["Consumption"];
			}
		}
		throw new Exception("Invalid request data");
	}

	public function jsonResponse(array $data = [], $statusCode = 200): void
	{
		header("Content-Type: application/json");
		header('Status: '. $statusCode);
		echo json_encode([
			"status" => $statusCode,
			"data" => $data
		]);
		exit();
	}

	public function generateTariffPerConsumption(float $consumption, array $tariffData): array 
	{
		$result = [];
		foreach ($tariffData as $index => $data) {
			if ($data["type"] === 1) {
				$result[] = $data;
				$result[$index]["annualCost"] = self::calculateTypeOneTariff($consumption, $data);
			} else if ($data["type"] === 2) {
				$result[] = $data;
				$result[$index]["annualCost"] = self::calculateTypeTwoTariff($consumption, $data);
			} else {
				$result[] = [];
			}
		}
		$annualCost = array_column($result, 'annualCost');
		array_multisort($annualCost, SORT_DESC, $result);
		return $result;
	}

	protected static function calculateTypeOneTariff(float $consumption, array $tariffData): float
	{
		$additionalCostInEuro = $tariffData["additionalKwhCost"] / 100;
		$baseCost = ($tariffData["baseCost"] * 12);
		return $baseCost + ($consumption * $additionalCostInEuro);
	}

	protected static function calculateTypeTwoTariff(float $consumption, array $tariffData): float
	{
		if ($consumption <= $tariffData["includedKwh"]) {
			return $tariffData["baseCost"];
		} else {
			$additionalConsumption = $consumption - $tariffData["includedKwh"];
			$additionalCostInEuro = $tariffData["additionalKwhCost"] / 100;
			return $tariffData["baseCost"] + ($additionalConsumption * $additionalCostInEuro);
		}
	}
}