<?php
class ModelExtensionTotalDistanceFee extends Model {
	public function getTotal($total) {
		if (isset($this->session->data['distance_fee']['title']) && isset($this->session->data['distance_fee']['cost'])) {
			$total['totals'][] = array(
				'code'       => 'distance_fee',
				'title'      => $this->session->data['distance_fee']['title'],
				'value'      => $this->session->data['distance_fee']['cost'],
				'sort_order' => $this->config->get('distance_fee_sort_order')
			);

			if ($this->session->data['distance_fee']['tax_class_id']) {
				$tax_rates = $this->tax->getRates($this->session->data['distance_fee']['cost'], $this->session->data['distance_fee']['tax_class_id']);
                                
                                if(!isset($this->request->get['api_call']) || $this->request->get['api_call'] != 1) {
                                    foreach ($tax_rates as $tax_rate) {
                                            if (!isset($total['taxes'][$tax_rate['tax_rate_id']])) {
                                                    $total['taxes'][$tax_rate['tax_rate_id']] = $tax_rate['amount'];
                                            } else {
                                                    $total['taxes'][$tax_rate['tax_rate_id']] += $tax_rate['amount'];
                                            }
                                    }
                                }    
			}
                        
                         if(!isset($this->request->get['api_call']) || $this->request->get['api_call'] != 1) {
                            $total['total'] += $this->session->data['distance_fee']['cost'];
                         }
                           
                }
	}
}