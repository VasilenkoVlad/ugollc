<?php

class ControllerCommonCountryBaseAPI extends ApiController {

	public function index($args = array()) {
		$id = isset($args['id']) ? $args['id'] : null;

		if($this->request->isGetRequest()) {
			$this->get($id);
		}else if ($this->request->isPostRequest()) {
			$this->getState();
		}
		else {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_METHOD_NOT_FOUND, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_METHOD_NOT_FOUND));
		}
		
	}

	/**
	 * Resource methods
	 */
	
	public function get($id = NULL) {
		if($id == NULL) {
			$countries = array('countries' => $this->getCountries());
			$this->response->setOutput($countries);
		}
		else {
			$country = array('country' => $this->getCountry($id));
			$this->response->setOutput($country);
		}
	}

	/**
	 * Helper methods
	 */
	
	protected function getCountry($id) {
		$this->load->model('localisation/country');
		$this->load->model('localisation/zone');

		$country = $this->model_localisation_country->getCountry($id);

		if(empty($country)) {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_COUNTRY_NOT_FOUND, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_COUNTRY_NOT_FOUND));
		}
		
		$country = $this->processCountry($country);

		$zones = $this->model_localisation_zone->getZonesByCountryId($id);
		$zones = $this->processZones($zones);

		$country['zones'] = $zones;

		return $country;
	}
	
	protected function getCountries() {
		$this->load->model('localisation/country');

		$countries = $this->model_localisation_country->getCountries();

		return $this->processCountries($countries);
	}

	protected function processCountries($countries) {
		foreach($countries as &$country) {
			$country = $this->processCountry($country);
		}

		return $countries;
	}

	protected function processCountry($country) {
		$country['country_id'] = (int)$country['country_id'];
		$country['postcode_required'] = $country['postcode_required'] == '1' ? true : false;
		unset($country['status']);

		return $country;
	}

	protected function processZones($zones) {
		foreach($zones as &$zone) {
			$zone = $this->processZone($zone);
		}

		return $zones;
	}

	protected function processZone($zone) {
		$zone['zone_id'] = (int)$zone['zone_id'];

		unset($zone['country_id']);
		unset($zone['status']);

		return $zone;
	}

	public function getState() {
		$json = array();

		$this->load->model('localisation/country');		
		$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);		
		if ($country_info) {
			$this->load->model('localisation/zone');

			$json = array(
				'country_id'        => $country_info['country_id'],
				'name'              => $country_info['name'],
				'iso_code_2'        => $country_info['iso_code_2'],
				'iso_code_3'        => $country_info['iso_code_3'],
				'address_format'    => $country_info['address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->post['country_id']),
				'status'            => $country_info['status']
			);
		}		
		
		echo json_encode($json);
	}
}

?>
