<?php

namespace Tariff\CompareTariff\Controller;

use Tariff\CompareTariff\Helper\CalculateHelper;
use Exception;

class CompareTariffController 
{
	private CalculateHelper $helper;

	public function __construct() {
		$this->helper = new CalculateHelper();
	}

	/**
	 * @return string $jsonString The returned string contains JSON
	 */
	public function calculateTariff(): string {
		try {
			$consumption = $this->helper->validate($_GET);
			$tariffProviderData = json_decode(file_get_contents(__DIR__ . "/../../tariff.json"), true);
			$result = $this->helper->generateTariffPerConsumption($consumption, $tariffProviderData);
			$this->helper->jsonResponse($result, 200);
		} catch (Exception $e) {
			$this->helper->jsonResponse([$validatedData], 402);
		}
	}
}