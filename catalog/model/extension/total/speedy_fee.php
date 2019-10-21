<?php
class ModelExtensionTotalSpeedyFee extends Model {
	public function getTotal($total) {
		if (isset($this->session->data['speedy_fee']['title']) && isset($this->session->data['speedy_fee']['cost'])) {
			$total['totals'][] = array(
				'code'       => 'speedy_fee',
				'title'      => $this->session->data['speedy_fee']['title'],
				'value'      => $this->session->data['speedy_fee']['cost'],
				'sort_order' => $this->config->get('speedy_fee_sort_order')
			);

			if ($this->session->data['speedy_fee']['tax_class_id']) {
				$tax_rates = $this->tax->getRates($this->session->data['speedy_fee']['cost'], $this->session->data['speedy_fee']['tax_class_id']);

				foreach ($tax_rates as $tax_rate) {
					if (!isset($total['taxes'][$tax_rate['tax_rate_id']])) {
						$total['taxes'][$tax_rate['tax_rate_id']] = $tax_rate['amount'];
					} else {
						$total['taxes'][$tax_rate['tax_rate_id']] += $tax_rate['amount'];
					}
				}
			}

			$total['total'] += $this->session->data['speedy_fee']['cost'];
		}
	}
}