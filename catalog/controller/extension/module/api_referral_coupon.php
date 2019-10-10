<?php
class ControllerModuleApiReferralCoupon extends Controller {
    public function index() {
        $data['sending_reward'] =   $this->config->get('referral_coupon_referrer_sending_reward');
        $data['coupon_redeemed_reward'] =   $this->config->get('referral_coupon_referrer_reward_for_coupon_used');
        $data['reward_type'] = ($this->config->get('referral_coupon_reward_type') == 'credit' ? 'Store Credit' : 'Reward Point');
        $data['coupon_discount'] =   ($this->config->get('referral_coupon_type') == 'P' ? $this->config->get('referral_coupon_discount') . '%' : $this->currency->format($this->config->get('referral_coupon_discount'), $this->session->data['currency']));
        $data['order_total'] = $this->currency->format($this->config->get('referral_coupon_total'), $this->session->data['currency']);
        $data['customer_login'] =   ($this->config->get('referral_coupon_logged') ? $this->language->get('text_yes') : $this->language->get('text_no'));
        $data['expire'] =   $this->config->get('referral_coupon_expire');
        $data['uses_total']=  $this->config->get('referral_coupon_uses_total');
        $data['uses_customer'] =  $this->config->get('referral_coupon_uses_customer');

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput($data);
    } 
  
  
    public function sendReferral() {
        $this->load->language('module/referral_coupon');
        if (isset($this->request->post['referee_email']) && isset($this->request->post['referee_email'])) {
            $referrer_message = $this->request->post['referrer_message'];
            $referee_name = trim($this->request->post['referee_name']);
            $json['referee']['name'] = $this->request->post['referee_name'];

            $referee_email = trim($this->request->post['referee_email']);
            $json['referee']['email'] = $referee_email;

            $sending_limit = $this->getSendingLimit();
            $email_existed = $this->db->query("SELECT email FROM " . DB_PREFIX . "customer_referral_coupon WHERE email = '" . $this->db->escape($referee_email) . "' UNION SELECT email FROM " . DB_PREFIX . "customer WHERE email='" . $this->db->escape($referee_email) . "'")->num_rows;
            $json['referee']['error']['email_existed'] = $email_existed ? $this->language->get('error_email_existed') : '';
            if ($sending_limit['remain'] > 0 && !$email_existed) {
                
                while (empty($code)) {
                    $code = substr(md5(mt_rand()), 0, 5);
                    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "coupon WHERE code = '" . $this->db->escape($code) . "'");
                    if ($query->num_rows) $code = '';
                }

                $coupon_name = str_replace('{referee_name}', $referee_name, $this->language->get('coupon_name'));

                $date_end = ($this->config->get('referral_coupon_expire')) ? date('Y-m-d', strtotime('today') + ($this->config->get('referral_coupon_expire') * 86400)) : '0000-00-00';

                $this->db->query("INSERT INTO " . DB_PREFIX . "coupon SET name = '" . $this->db->escape($coupon_name) . "', code = '" . $this->db->escape($code) . "', type = '" . $this->db->escape($this->config->get('referral_coupon_type')) . "', discount = '" . (float)$this->config->get('referral_coupon_discount') . "', total = '" . (float)$this->config->get('referral_coupon_total') . "', logged = '" . (int)$this->config->get('referral_coupon_logged') . "', shipping = '" . (int)$this->config->get('referral_coupon_shipping') . "', date_start = NOW(), date_end = '" . $this->db->escape($date_end) . "', uses_total = '" . (int)$this->config->get('referral_coupon_uses_total') . "', uses_customer = '" . (int)$this->config->get('referral_coupon_uses_customer') . "', status = '1', date_added = NOW()");

                $coupon_id = $this->db->getLastId();
                $customer_email = $this->request->post['customer_email']; 
                $customer_info = $this->getCustomer($customer_email);
                $this->db->query("INSERT INTO " . DB_PREFIX . "customer_referral_coupon SET referrer_id = '" . (int)$customer_info['customer_id'] . "', coupon_id = '" . (int)$coupon_id . "', date_added = NOW(), name = '" . $this->db->escape($referee_name) . "', email = '" . $this->db->escape($referee_email) . "'");
                if ($this->config->get('referral_coupon_referrer_sending_reward')) {
                    $description = str_replace('{referee_name}', $referee_name, $this->language->get('text_sending_referral_reward_desc'));
                   
                    $this->addReward($customer_info['customer_id'], 0, $this->config->get('referral_coupon_referrer_sending_reward'), $description);
                }
                if ($this->config->get('referral_coupon_from_email') == 'custom') {
                  $from['email'] = $this->config->get('referral_coupon_from_custom_email');
                  $from['name'] = $this->config->get('referral_coupon_from_custom_name');
                } elseif ($this->config->get('referral_coupon_from_email') == 'referrer') {
                  $from['email'] = $customer_info['email'];
                  $from['name'] = $customer_info['firstname'] . ' ' . $customer_info['lastname'];
                } else {
                  $from['email'] = $this->config->get('config_email');
                  $from['name'] = $this->config->get('config_name');
                }
                
                $to['email'] = $referee_email;
                $to['name'] = $referee_name;
                $subject = str_replace('{referrer_name}', $this->customer->getFirstName() . ' ' . $this->customer->getLastName(), $this->language->get('email_subject'));

                $body = $this->getEmailHTML($code, $referee_name, $referrer_message,$customer_info['firstname'],$customer_info['lastname']);
                
                //$this->email($from, $to, $subject, $body);
                //Send referral text instead of mail implemaentation
                $sms_query = "SELECT value FROM oc_setting where `key` = 'config_send_sms_status' and `code` = 'config'";
                $sms = $this->db->query($sms_query);
                if($sms->row['value'] == 1){
                     $this->load->library('clicksend_lib/clicksend');
                     $obj_clicksend = Clicksend::get_instance($this->registry);
                     $country_code = '+1';
                     $result = $obj_clicksend->send_referral_sms($to['email'],$country_code,$body);	
                 }
                $json['referee']['success'] = $this->language->get('text_sent_success');
                 
            }
            $json['referee']['sending_limit'] = $this->getSendingLimit($customer_info['customer_id']);
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput($json);
    } 
  
  
    public function getSendingLimit($customer_id) {
        $this->load->language('module/referral_coupon');

        $data['text'] = '';
        $data['remain'] = 1;

        if ($this->config->get('referral_coupon_limit') && $this->config->get('referral_coupon_period')) {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_referral_coupon WHERE referrer_id = '" . (int)$customer_id . "' AND date_added > DATE_SUB(NOW(), INTERVAL " . (int)$this->config->get('referral_coupon_period') . " HOUR) ORDER BY date_added ASC");

            $data['remain'] = $this->config->get('referral_coupon_limit') - $query->num_rows;
            if ($data['remain'] <= 0) {
              $time = date('H:i:s', strtotime($query->row['date_added']) + (3600 * (int)$this->config->get('referral_coupon_period')));
              $data['text'] = str_replace('{time}', $time, $this->language->get('error_sending_limit_reached'));
            } else {
              $find = array(
                '{referrals}',
                '{hours}',
                '{remain}'
              );

              $replace = array(
                $this->config->get('referral_coupon_limit'),
                $this->config->get('referral_coupon_period'),
                $data['remain']
              );

              $data['text'] = str_replace($find, $replace, $this->language->get('text_sending_limit'));
            }
        }
        return $data;
    } 
  
  
    public function addReward($customer_id = 0, $order_id = 0, $reward = 0, $description = '') {
        if (!$customer_id || !$reward) return;

        $this->load->language('module/referral_coupon');

        if ($this->config->get('referral_coupon_reward_type') == "point") {
          $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$customer_id . "' AND order_id = '" . (int)$order_id . "' AND order_id != '0'");
          if ($query->num_rows) return;

          $this->db->query("INSERT INTO " . DB_PREFIX . "customer_reward SET customer_id = '" . (int)$customer_id . "', order_id = '" . (int)$order_id . "', description = '" . $this->db->escape($description) . "', points = '" . (int)$reward . "', date_added = NOW(), referral_reward = '1'");
        } else {
          $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$customer_id . "' AND order_id = '" . (int)$order_id . "' AND order_id != '0'");
          if ($query->num_rows) return;

          $this->db->query("INSERT INTO " . DB_PREFIX . "customer_transaction SET customer_id = '" . (int)$customer_id . "', order_id = '" . (int)$order_id . "', description = '" . $this->db->escape($description) . "', amount = '" . (int)$reward . "', date_added = NOW(), referral_reward = '1'");
        }
        if ($this->config->get('referral_coupon_notify')) {
          $this->notifyReward($customer_id, $reward);
        }
    } 
  
    public function getCustomer($email){
        if($email != ""){
          $customer = $this->db->query("SELECT * FROM " .DB_PREFIX."customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "' AND status = '1'");
        }
        return $customer->row;
    }
    
    
    public function notifyReward($customer_id = 0, $reward = 0) {
        $from['email'] = $this->config->get('config_email');
        $from['name'] = $this->config->get('config_name');
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");
        if ($query->num_rows) {
            $to['email'] = $query->row['telephone'];
            $to['name'] = $query->row['firstname'] . ' ' . $query->row['lastname'];

            if ($this->config->get('referral_coupon_reward_type') == 'credit') {
              $reward_type = $this->language->get('text_store_credit');
              $reward_total = $this->db->query("SELECT SUM(amount) AS total FROM " . DB_PREFIX . "customer_transaction WHERE customer_id = '" . (int)$customer_id . "'")->row['total'];
            } else {
              $reward_type = $this->language->get('text_reward_point');
              $reward_total = $this->db->query("SELECT SUM(points) AS total FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$customer_id . "'")->row['total'];
            }

            $subject = str_replace(
              array('{reward}', '{reward_type}', '{store_name}'),
              array($reward, $reward_type, $this->config->get('config_name')),
              $this->language->get('email_subject_reward_notification')
            );

            $body = str_replace(
              array('{reward}', '{reward_type}', '{reward_total}', '{store_name}', '{customer}'),
              array($reward, $reward_type, $reward_total, $this->config->get('config_name'), $to['name']),
              $this->language->get('email_body_reward_notification')
            );
            //$this->email($from, $to, $subject, $body);
            //Send referral text instead of mail implemaentation
              $sms_query = "SELECT value FROM oc_setting where `key` = 'config_send_sms_status' and `code` = 'config'";
              $sms = $this->db->query($sms_query);
              if($sms->row['value'] == 1){
                   $this->load->library('clicksend_lib/clicksend');
                   $obj_clicksend = Clicksend::get_instance($this->registry);
                   $country_code = '+1';
                   $result = $obj_clicksend->send_referral_sms($to['email'],$country_code,$body);	
               }
        }
    } 
    
   public function email($from = array(), $to = array(), $subject = '', $body = '') {
    $mail = new Mail();
        $mail->protocol = $this->config->get('config_mail_protocol');
        $mail->parameter = $this->config->get('config_mail_parameter');
        $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
        $mail->smtp_username = $this->config->get('config_mail_smtp_username');
        $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
        $mail->smtp_port = $this->config->get('config_mail_smtp_port');
        $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

        $mail->setFrom($from['email']);
        $mail->setSender(html_entity_decode($from['name'], ENT_QUOTES, 'UTF-8'));
        $mail->setTo($to['email']);
        $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
        $mail->setHtml($body);
        $mail->send();
    } 
  
  
    public function getEmailHTML($coupon_code = '{coupon_code}', $referee_name = '{referee_name}', $referrer_message = '{referrer_message}',$firstname, $lastname) {
        $this->load->language('module/referral_coupon');
        $reward_type = $this->config->get('referral_coupon_reward_type') == 'credit' ? $this->language->get('text_store_credit') : $this->language->get('text_reward_point');
        $expire_date = ($this->config->get('referral_coupon_expire')) ? date($this->language->get('date_format_short'), strtotime('today') + ($this->config->get('referral_coupon_expire') * 86400)) : '';
        $order_total = $this->currency->format($this->config->get('referral_coupon_total'), $this->session->data['currency']);
        $customer_login = ($this->config->get('referral_coupon_logged') ? $this->language->get('text_yes') : $this->language->get('text_no'));
        $uses_total = $this->config->get('referral_coupon_uses_total');
        $uses_customer = $this->config->get('referral_coupon_uses_customer');
        $referrer_name = $firstname . ' ' . $lastname;
        $store_link = HTTP_SERVER;
        $store_logo = $store_link . 'image/' . $this->config->get('config_logo');
        $store_name = $this->config->get('config_name');
        $coupon_discount = ($this->config->get('referral_coupon_type') == 'P' ? $this->config->get('referral_coupon_discount') . '%' : $this->currency->format($this->config->get('referral_coupon_discount'), $this->session->data['currency']));
        $html = str_replace(
          array('{referee_name}', '{referrer_message}', '{coupon_code}', '{reward_type}', '{expire_date}', '{order_total}', '{customer_login}', '{uses_total}', '{uses_customer}', '{referrer_name}', '{store_link}', '{store_logo}', '{store_name}', '{coupon_discount}'),
          array($referee_name, $referrer_message, $coupon_code, $reward_type, $expire_date, $order_total, $customer_login, $uses_total, $uses_customer, $referrer_name, $store_link, $store_logo, $store_name, $coupon_discount),
          $this->language->get('email_body')
        );
        return $html;
    } 
  
    public function getReferralsHistory() {

        $data['referrals'] = array();

        $customer_email = $this->request->post['customer_email']; 
        $customer_info = $this->getCustomer($customer_email);

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_referral_coupon WHERE referrer_id = '" . (int)$customer_info['customer_id']. "' ORDER BY date_added DESC");
        foreach ($query->rows as $r) {
          $data['referrals'][] = array(
            'name' => $r['name'],
            'email' => $r['email'],
            'date_added' => date($this->language->get('date_format_short'), strtotime($r['date_added']))
          );
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput($data);
    } 
  
  
  
 } 
?>
