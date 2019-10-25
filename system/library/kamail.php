<?php
/*
	Project: Ka Extensions
	Author : karapuz <support@ka-station.com>

	Version: 3 ($Revision: 52 $)

*/
class KaMail {
	public $mail;
	public $sender;
	public $data;
	public $images;
	public $registry;

	function __construct($registry, $store_id = 0) {
	
		$this->language = $registry->get('language');
		$this->global_config = $registry->get('config');
		$this->db       = $registry->get('db');
		$this->request  = $registry->get('request');
		$this->session  = $registry->get('session');
		$this->log      = $registry->get('log');
		$this->load     = $registry->get('load');
	
		$this->mail = new Mail(); 
		
		$protocol = $this->global_config->get('config_mail_protocol');

		if (!empty($protocol) && $protocol != 'mail') {
			$this->mail->protocol = $protocol;
			$this->mail->parameter = $this->global_config->get('config_mail_parameter');
			$this->mail->smtp_hostname = $this->global_config->get('config_mail_smtp_hostname');
			$this->mail->smtp_username = $this->global_config->get('config_mail_smtp_username');
			$this->mail->smtp_password = html_entity_decode($this->global_config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$this->mail->smtp_port = $this->global_config->get('config_mail_smtp_port');
			$this->mail->smtp_timeout = $this->global_config->get('config_mail_smtp_timeout');
		}
		
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "store WHERE store_id = '" . (int)$store_id . "'");
		
		if (!empty($query->row)) {
			$this->data['store_name'] = $query->row['name'];
			$this->data['sender']     = $query->row['name'];
			$this->data['store_url']  = $query->row['url'] . 'index.php?route=account/login';

			$query =  $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "setting WHERE 
				store_id = '" . (int)$store_id . "' AND `group` = 'config'"
			);
			$this->config = new Config();
			foreach ($query->rows as $setting) {
				if (!$setting['serialized']) {
					$this->config->set($setting['key'], $setting['value']);
				} else {
					$this->config->set($setting['key'], unserialize($setting['value']));
				}
			}
			
		} else {
			$this->config = &$this->global_config;
			$this->data['store_name'] = $this->config->get('config_name');
			$url = (defined('HTTP_CATALOG')) ? HTTP_CATALOG : HTTP_SERVER;
			$this->data['store_url']  = $url . 'index.php?route=account/login';
		}
		
		$this->data['config'] = &$this->config;
	}


/*
	$tpl may contain the template name with and without '.tpl' extension
*/		
	public function send($from, $to, $subject, $tpl, $extra = array()) {

		if (empty($from)) {
			$from = $this->config->get('config_email');
		}

		if (empty($this->data['sender'])) {
			$sender = html_entity_decode($this->config->get('config_name'));
		} else {
			$sender = html_entity_decode($this->data['sender']);
		}

		// HTML Mail
		$this->data['subject'] = $subject;
		$this->images['logo']  = $this->config->get('config_logo');

		$html = $text = '';

 		$tpl_path = KaGlobal::getTemplateDir();
 		
 		if (substr($tpl, -4) == '.tpl') {
 			$tpl = substr($tpl, 0, -4);
 		}

 		$file_path = "mail/text/" . $tpl . '.tpl';
		if (file_exists(DIR_TEMPLATE . $tpl_path . $file_path)) {
			$text = $this->load->view("mail/text/" . $tpl, $this->data);
		}

		if (!empty($this->images)) {
			foreach ($this->images as $ik => $iv) {
		      	if (file_exists(DIR_IMAGE . $iv)) {
		      		$filename = DIR_IMAGE . $iv;
		      		$this->data[$ik] = $this->data['_images'][$ik] = 'cid:' . urlencode(basename($filename));
					$this->mail->addAttachment($filename);
			  	}
		  	}
		}

		$file_path = "mail/" . $tpl . '.tpl';
		if (file_exists(DIR_TEMPLATE . $tpl_path . $file_path)) {
			$html = $this->load->view("mail/" . $tpl, $this->data);
		}

		if (empty($html) && empty($text)) {
			$this->log->write("WARNING: None of email templates is found: " . DIR_TEMPLATE . ' - ' . $tpl_path . ' - ' . $file_path);
			return false;
		}
		
		$this->mail->setTo($to);
		$this->mail->setFrom($from);
		$this->mail->setSender($sender);
		$this->mail->setSubject($subject);
		$this->mail->setText($text);
		$this->mail->setHtml($html);

		$this->mail->send();

		return true;
	}
}
?>