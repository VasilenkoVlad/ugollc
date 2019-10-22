<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ModelVendProduct extends Model {

    private $oldProductdate = '2018-05-01 23:59:00'; // already added products date
    private $oldProductid = '904'; // already added products date

    //Log content if fails any operation

    public function logContent($content) {
        $content = gmdate("Y-m-d\TH:i:s", time()) . ' - ' . $content;
        $filename = DIR_LOGS . 'webhook_' . date("Ymd") . '_log.txt';
        $logFile = fopen($filename, 'a');
        fwrite($logFile, $content . "\n");
        fclose($logFile);
    }

    public function updateProductInventoryOnVend($vend_product_id,$quantity){
         $this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = '" . (int) $quantity . "',date_modified = NOW() WHERE vend_product_id = '" . $vend_product_id . "'");
         
    }

    public function insertWebhookProdcut($webhookData) {
        if (isset($webhookData['id']) && $webhookData['id'] != '') {
            $this->db->query("INSERT INTO oc_vend_product_webhook SET vend_product_id = '" . $webhookData['id'] . "',status = 'pending', created_date = '" . date('Y-m-d H:i:s') . "'");
        }
    }

    public function getPendingWebhookProdcuts() {
        $query = $this->db->query("SELECT id,vend_product_id FROM oc_vend_product_webhook WHERE status='pending' group by vend_product_id");
        return $query->rows;
    }

    public function checkProductPresence($vend_product_id) {
        //check product already added or not      
        $checkProductResultArray = $this->getProduct($vend_product_id);
        $checkProductResult = array();
        if (count($checkProductResultArray) === 0) {
            $checkProductResult['queryOperartion'] = 'insert';
        } else {
            if (isset($checkProductResultArray[0]['date_added']) && $checkProductResultArray[0]['date_added']!='') {
                $checkProductResult = $checkProductResultArray[0];
            } else {
                $checkProductResult = $checkProductResultArray;
            }            
            //echo $checkProductResult['date_added']."<br>".$this->oldProductid;exit;
            if ($checkProductResult['product_id'] < $this->oldProductid) {
                $checkProductResult['queryOperartion'] = 'update_inventory';
            }else{
                $checkProductResult['queryOperartion'] = 'update'; 
            }            
        }
        return $checkProductResult;
    }

    public function getProduct($vendProductId) {
        $query = $this->db->query("SELECT product_id,sku,date_added,vend_product_id FROM " . DB_PREFIX . "product WHERE vend_product_id='" . $vendProductId . "'");
        $queryResult = $query->row;
        return $queryResult;
    }

    public function updateWebhookStatus($vendProductId, $message, $status) {
        //echo "UPDATE oc_vend_product_webhook SET status = '" . $status . "',message='" . $message . "' WHERE vend_product_id='" . $vendProductId . "'";
        $query = $this->db->query("UPDATE oc_vend_product_webhook SET status = '" . $status . "',message='" . $message . "' WHERE vend_product_id='" . $vendProductId . "' AND status='pending'");
    }
    
    public function updateProductInventory($product_id,$data){
         $this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = '" . (int) $data['quantity'] . "',date_modified = NOW() WHERE product_id = '" . (int) $product_id . "'");
         
    }

    public function checkCategory($categoryName) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_description WHERE name='" . $categoryName . "'");
        $queryResult = $query->row;
        if (count($queryResult) > 0) {
            return $queryResult['category_id'];
        } else {
            return 0;
        }
    }

    public function addUpdatemanufacturer($name) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer WHERE name='" . $this->db->escape($name) . "'");
        $queryResult = $query->row;
        if (count($queryResult) > 0) {
            return $queryResult['manufacturer_id'];
        } else {
            $this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer SET name = '" . $this->db->escape($name) . "', sort_order = '" . (int) 0 . "'");
            $manufacturer_id = $this->db->getLastId();
            return $manufacturer_id;
        }
    }

//    public function addNewProduct($data) {
//        $this->db->query("INSERT INTO " . DB_PREFIX . "product SET vend_product_id = '" . $data['id'] . "',vend_processing = 'inprogress', date_added = NOW()");
//        return $this->db->getLastId();
//    }

    public function updateVendProcessing($vendProcessing, $productId) {
        $query = $this->db->query("UPDATE " . DB_PREFIX . "product SET vend_processing = '" . $vendProcessing . "' WHERE product_id='" . $productId . "'");
    }

    public function updateProductStatus($data) {
        if (count($data) > 0) {
            $this->db->query("UPDATE " . DB_PREFIX . "product SET vend_product_id = '" . $this->db->escape($data['vend_product_id']) . "', status = '" . $this->db->escape($data['status']) . "' WHERE product_id = '" . (int) $data['product_id'] . "'");
        }
    }

    public function addCategory($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "category SET parent_id = '" . (int) $data['parent_id'] . "', `top` = '" . (isset($data['top']) ? (int) $data['top'] : 0) . "', `column` = '" . (int) $data['column'] . "', sort_order = '" . (int) $data['sort_order'] . "', status = '" . (int) $data['status'] . "', date_modified = NOW(), date_added = NOW()");

        $category_id = $this->db->getLastId();

        if (isset($data['image'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "category SET image = '" . $this->db->escape($data['image']) . "' WHERE category_id = '" . (int) $category_id . "'");
        }

        foreach ($data['category_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "category_description SET category_id = '" . (int) $category_id . "', language_id = '" . (int) $language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
        }

        // MySQL Hierarchical Data Closure Table Pattern
        $level = 0;

        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int) $data['parent_id'] . "' ORDER BY `level` ASC");

        foreach ($query->rows as $result) {
            $this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int) $category_id . "', `path_id` = '" . (int) $result['path_id'] . "', `level` = '" . (int) $level . "'");

            $level++;
        }

        $this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int) $category_id . "', `path_id` = '" . (int) $category_id . "', `level` = '" . (int) $level . "'");

        if (isset($data['category_filter'])) {
            foreach ($data['category_filter'] as $filter_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "category_filter SET category_id = '" . (int) $category_id . "', filter_id = '" . (int) $filter_id . "'");
            }
        }

        if (isset($data['category_store'])) {
            foreach ($data['category_store'] as $store_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "category_to_store SET category_id = '" . (int) $category_id . "', store_id = '" . (int) $store_id . "'");
            }
        }

        // Set which layout to use with this category
        if (isset($data['category_layout'])) {
            foreach ($data['category_layout'] as $store_id => $layout_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "category_to_layout SET category_id = '" . (int) $category_id . "', store_id = '" . (int) $store_id . "', layout_id = '" . (int) $layout_id . "'");
            }
        }

        if (isset($data['keyword'])) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'category_id=" . (int) $category_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
        }

        $this->cache->delete('category');

        return $category_id;
    }

    public function addProduct($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "product SET model = '" . $this->db->escape($data['model']) . "', sku = '" . $this->db->escape($data['sku']) . "', upc = '" . $this->db->escape($data['upc']) . "', ean = '" . $this->db->escape($data['ean']) . "', jan = '" . $this->db->escape($data['jan']) . "', isbn = '" . $this->db->escape($data['isbn']) . "', mpn = '" . $this->db->escape($data['mpn']) . "', location = '" . $this->db->escape($data['location']) . "', quantity = '" . (int) $data['quantity'] . "', minimum = '" . (int) $data['minimum'] . "', subtract = '" . (int) $data['subtract'] . "', stock_status_id = '" . (int) $data['stock_status_id'] . "', date_available = '" . $this->db->escape($data['date_available']) . "', manufacturer_id = '" . (int) $data['manufacturer_id'] . "', shipping = '" . (int) $data['shipping'] . "', price = '" . (float) $data['price'] . "', points = '" . (int) $data['points'] . "', weight = '" . (float) $data['weight'] . "', weight_class_id = '" . (int) $data['weight_class_id'] . "', length = '" . (float) $data['length'] . "', width = '" . (float) $data['width'] . "', height = '" . (float) $data['height'] . "', length_class_id = '" . (int) $data['length_class_id'] . "', status = '" . (int) $data['status'] . "', tax_class_id = '" . (int) $data['tax_class_id'] . "', sort_order = '" . (int) $data['sort_order'] . "', date_added = NOW()");

        $product_id = $this->db->getLastId();

        if (isset($data['image']) && $data['image'] != '') {
            $this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape($data['image']) . "' WHERE product_id = '" . (int) $product_id . "'");
        }

        foreach ($data['product_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int) $product_id . "', language_id = '" . (int) $language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', tag = '" . $this->db->escape($value['tag']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
        }

        if (isset($data['product_store']) && count($data['product_store'] > 0)) {
            foreach ($data['product_store'] as $store_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int) $product_id . "', store_id = '" . (int) $store_id . "'");
            }
        }

        if (isset($data['product_attribute']) && count($data['product_attribute'] > 0)) {
            foreach ($data['product_attribute'] as $product_attribute) {
                if ($product_attribute['attribute_id']) {
                    // Removes duplicates
                    $this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int) $product_id . "' AND attribute_id = '" . (int) $product_attribute['attribute_id'] . "'");

                    foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {
                        $this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int) $product_id . "' AND attribute_id = '" . (int) $product_attribute['attribute_id'] . "' AND language_id = '" . (int) $language_id . "'");

                        $this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int) $product_id . "', attribute_id = '" . (int) $product_attribute['attribute_id'] . "', language_id = '" . (int) $language_id . "', text = '" . $this->db->escape($product_attribute_description['text']) . "'");
                    }
                }
            }
        }

        if (isset($data['product_option']) && count($data['product_option'] > 0)) {
            foreach ($data['product_option'] as $product_option) {
                if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
                    if (isset($product_option['product_option_value'])) {
                        $this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int) $product_id . "', option_id = '" . (int) $product_option['option_id'] . "', required = '" . (int) $product_option['required'] . "'");

                        $product_option_id = $this->db->getLastId();

                        foreach ($product_option['product_option_value'] as $product_option_value) {
                            $this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET product_option_id = '" . (int) $product_option_id . "', product_id = '" . (int) $product_id . "', option_id = '" . (int) $product_option['option_id'] . "', option_value_id = '" . (int) $product_option_value['option_value_id'] . "', quantity = '" . (int) $product_option_value['quantity'] . "', subtract = '" . (int) $product_option_value['subtract'] . "', price = '" . (float) $product_option_value['price'] . "', price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) . "', points = '" . (int) $product_option_value['points'] . "', points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "', weight = '" . (float) $product_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "'");
                        }
                    }
                } else {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int) $product_id . "', option_id = '" . (int) $product_option['option_id'] . "', value = '" . $this->db->escape($product_option['value']) . "', required = '" . (int) $product_option['required'] . "'");
                }
            }
        }

        if (isset($data['product_discount']) && count($data['product_discount'] > 0)) {
            foreach ($data['product_discount'] as $product_discount) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int) $product_id . "', customer_group_id = '" . (int) $product_discount['customer_group_id'] . "', quantity = '" . (int) $product_discount['quantity'] . "', priority = '" . (int) $product_discount['priority'] . "', price = '" . (float) $product_discount['price'] . "', date_start = '" . $this->db->escape($product_discount['date_start']) . "', date_end = '" . $this->db->escape($product_discount['date_end']) . "'");
            }
        }

        if (isset($data['product_special']) && count($data['product_special'] > 0)) {
            foreach ($data['product_special'] as $product_special) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int) $product_id . "', customer_group_id = '" . (int) $product_special['customer_group_id'] . "', priority = '" . (int) $product_special['priority'] . "', price = '" . (float) $product_special['price'] . "', date_start = '" . $this->db->escape($product_special['date_start']) . "', date_end = '" . $this->db->escape($product_special['date_end']) . "'");
            }
        }

        if (isset($data['product_image']) && count($data['product_image'] > 0)) {
            foreach ($data['product_image'] as $product_image) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int) $product_id . "', image = '" . $this->db->escape($product_image['image']) . "', sort_order = '" . (int) $product_image['sort_order'] . "'");
            }
        }

        if (isset($data['product_download']) && count($data['product_download'] > 0)) {
            foreach ($data['product_download'] as $download_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_download SET product_id = '" . (int) $product_id . "', download_id = '" . (int) $download_id . "'");
            }
        }

        if (isset($data['product_category']) && count($data['product_category'] > 0)) {
            foreach ($data['product_category'] as $category_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int) $product_id . "', category_id = '" . (int) $category_id . "'");
            }
        }

        if (isset($data['product_filter']) && count($data['product_filter'] > 0)) {
            foreach ($data['product_filter'] as $filter_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_filter SET product_id = '" . (int) $product_id . "', filter_id = '" . (int) $filter_id . "'");
            }
        }

        if (isset($data['product_related']) && count($data['product_related']) > 0) {
            foreach ($data['product_related'] as $related_id) {
                $this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int) $product_id . "' AND related_id = '" . (int) $related_id . "'");
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int) $product_id . "', related_id = '" . (int) $related_id . "'");
                $this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int) $related_id . "' AND related_id = '" . (int) $product_id . "'");
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int) $related_id . "', related_id = '" . (int) $product_id . "'");
            }
        }

        if (isset($data['product_reward']) && count($data['product_reward'] > 0)) {
            foreach ($data['product_reward'] as $customer_group_id => $product_reward) {
                if ((int) $product_reward['points'] > 0) {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "product_reward SET product_id = '" . (int) $product_id . "', customer_group_id = '" . (int) $customer_group_id . "', points = '" . (int) $product_reward['points'] . "'");
                }
            }
        }

        if (isset($data['product_layout']) && count($data['product_layout'] > 0)) {
            foreach ($data['product_layout'] as $store_id => $layout_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_layout SET product_id = '" . (int) $product_id . "', store_id = '" . (int) $store_id . "', layout_id = '" . (int) $layout_id . "'");
            }
        }

        if ($data['keyword']) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int) $product_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
        }

        if (isset($data['product_recurring']) && count($data['product_recurring'] > 0)) {
            foreach ($data['product_recurring'] as $recurring) {
                $this->db->query("INSERT INTO `" . DB_PREFIX . "product_recurring` SET `product_id` = " . (int) $product_id . ", customer_group_id = " . (int) $recurring['customer_group_id'] . ", `recurring_id` = " . (int) $recurring['recurring_id']);
            }
        }

        $this->cache->delete('product');

        return $product_id;
    }

    public function editProduct($product_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "product SET model = '" . $this->db->escape($data['model']) . "',quantity = '" . (int) $data['quantity'] . "',manufacturer_id = '" . (int) $data['manufacturer_id'] . "',price = '" . (float) $data['price'] . "',date_modified = NOW() WHERE product_id = '" . (int) $product_id . "'");

        if (isset($data['image']) && count($data['image']) > 0) {
            $this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape($data['image']) . "' WHERE product_id = '" . (int) $product_id . "'");
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int) $product_id . "'");

        foreach ($data['product_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int) $product_id . "', language_id = '" . (int) $language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', tag = '" . $this->db->escape($value['tag']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
        }

//        $this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int) $product_id . "'");
//
//        if (isset($data['product_store']) && count($data['product_store']) > 0) {
//            foreach ($data['product_store'] as $store_id) {
//                $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int) $product_id . "', store_id = '" . (int) $store_id . "'");
//            }
//        }

//        $this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int) $product_id . "'");
//
//        if (!empty($data['product_attribute']) && count($data['product_attribute']) > 0) {
//            foreach ($data['product_attribute'] as $product_attribute) {
//                if ($product_attribute['attribute_id']) {
//                    // Removes duplicates
//                    $this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int) $product_id . "' AND attribute_id = '" . (int) $product_attribute['attribute_id'] . "'");
//
//                    foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {
//                        $this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int) $product_id . "', attribute_id = '" . (int) $product_attribute['attribute_id'] . "', language_id = '" . (int) $language_id . "', text = '" . $this->db->escape($product_attribute_description['text']) . "'");
//                    }
//                }
//            }
//        }

//        $this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int) $product_id . "'");
//        $this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int) $product_id . "'");

//        if (isset($data['product_option']) && count($data['product_option']) > 0) {
//            foreach ($data['product_option'] as $product_option) {
//                if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
//                    if (isset($product_option['product_option_value'])) {
//                        $this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_option_id = '" . (int) $product_option['product_option_id'] . "', product_id = '" . (int) $product_id . "', option_id = '" . (int) $product_option['option_id'] . "', required = '" . (int) $product_option['required'] . "'");
//
//                        $product_option_id = $this->db->getLastId();
//
//                        foreach ($product_option['product_option_value'] as $product_option_value) {
//                            $this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET product_option_value_id = '" . (int) $product_option_value['product_option_value_id'] . "', product_option_id = '" . (int) $product_option_id . "', product_id = '" . (int) $product_id . "', option_id = '" . (int) $product_option['option_id'] . "', option_value_id = '" . (int) $product_option_value['option_value_id'] . "', quantity = '" . (int) $product_option_value['quantity'] . "', subtract = '" . (int) $product_option_value['subtract'] . "', price = '" . (float) $product_option_value['price'] . "', price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) . "', points = '" . (int) $product_option_value['points'] . "', points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "', weight = '" . (float) $product_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "'");
//                        }
//                    }
//                } else {
//                    $this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_option_id = '" . (int) $product_option['product_option_id'] . "', product_id = '" . (int) $product_id . "', option_id = '" . (int) $product_option['option_id'] . "', value = '" . $this->db->escape($product_option['value']) . "', required = '" . (int) $product_option['required'] . "'");
//                }
//            }
//        }

//        $this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int) $product_id . "'");
//
//        if (isset($data['product_discount']) && count($data['product_discount'])) {
//            foreach ($data['product_discount'] as $product_discount) {
//                $this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int) $product_id . "', customer_group_id = '" . (int) $product_discount['customer_group_id'] . "', quantity = '" . (int) $product_discount['quantity'] . "', priority = '" . (int) $product_discount['priority'] . "', price = '" . (float) $product_discount['price'] . "', date_start = '" . $this->db->escape($product_discount['date_start']) . "', date_end = '" . $this->db->escape($product_discount['date_end']) . "'");
//            }
//        }
//        $this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int) $product_id . "'");
//
//        if (isset($data['product_special']) && count($data['product_special']) > 0) {
//            foreach ($data['product_special'] as $product_special) {
//                $this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int) $product_id . "', customer_group_id = '" . (int) $product_special['customer_group_id'] . "', priority = '" . (int) $product_special['priority'] . "', price = '" . (float) $product_special['price'] . "', date_start = '" . $this->db->escape($product_special['date_start']) . "', date_end = '" . $this->db->escape($product_special['date_end']) . "'");
//            }
//        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int) $product_id . "'");

        if (isset($data['product_image']) && $data['product_image'] > 0) {
            foreach ($data['product_image'] as $product_image) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int) $product_id . "', image = '" . $this->db->escape($product_image['image']) . "', sort_order = '" . (int) $product_image['sort_order'] . "'");
            }
        }

//        $this->db->query("DELETE FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int) $product_id . "'");
//
//        if (isset($data['product_download']) && $data['product_download'] > 0) {
//            foreach ($data['product_download'] as $download_id) {
//                $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_download SET product_id = '" . (int) $product_id . "', download_id = '" . (int) $download_id . "'");
//            }
//        }
        //$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int) $product_id . "'");
//        if (isset($data['product_category']) && $data['product_category'] > 0) {
//            foreach ($data['product_category'] as $category_id) {
//                $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int) $product_id . "', category_id = '" . (int) $category_id . "'");
//            }
//        }
//
//        $this->db->query("DELETE FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int) $product_id . "'");
//
//        if (isset($data['product_filter']) && $data['product_filter'] > 0) {
//            foreach ($data['product_filter'] as $filter_id) {
//                $this->db->query("INSERT INTO " . DB_PREFIX . "product_filter SET product_id = '" . (int) $product_id . "', filter_id = '" . (int) $filter_id . "'");
//            }
//        }
//        $this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int) $product_id . "'");
//        $this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE related_id = '" . (int) $product_id . "'");
//
//        if (isset($data['product_related']) && $data['product_related'] > 0) {
//            foreach ($data['product_related'] as $related_id) {
//                $this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int) $product_id . "' AND related_id = '" . (int) $related_id . "'");
//                $this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int) $product_id . "', related_id = '" . (int) $related_id . "'");
//                $this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int) $related_id . "' AND related_id = '" . (int) $product_id . "'");
//                $this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int) $related_id . "', related_id = '" . (int) $product_id . "'");
//            }
//        }
//
//        $this->db->query("DELETE FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int) $product_id . "'");
//
//        if (isset($data['product_reward']) && $data['product_reward'] > 0) {
//            foreach ($data['product_reward'] as $customer_group_id => $value) {
//                if ((int) $value['points'] > 0) {
//                    $this->db->query("INSERT INTO " . DB_PREFIX . "product_reward SET product_id = '" . (int) $product_id . "', customer_group_id = '" . (int) $customer_group_id . "', points = '" . (int) $value['points'] . "'");
//                }
//            }
//        }
//        $this->db->query("DELETE FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int) $product_id . "'");
//        if (isset($data['product_layout']) && $data['product_layout'] > 0) {
//            foreach ($data['product_layout'] as $store_id => $layout_id) {
//                $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_layout SET product_id = '" . (int) $product_id . "', store_id = '" . (int) $store_id . "', layout_id = '" . (int) $layout_id . "'");
//            }
//        }
//        $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int) $product_id . "'");
//
//        if ($data['keyword']) {
//            $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int) $product_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
//        }
//
//        $this->db->query("DELETE FROM `" . DB_PREFIX . "product_recurring` WHERE product_id = " . (int) $product_id);
//
//        if (isset($data['product_recurring']) && $data['product_recurring'] > 0) {
//            foreach ($data['product_recurring'] as $product_recurring) {
//                $this->db->query("INSERT INTO `" . DB_PREFIX . "product_recurring` SET `product_id` = " . (int) $product_id . ", customer_group_id = " . (int) $product_recurring['customer_group_id'] . ", `recurring_id` = " . (int) $product_recurring['recurring_id']);
//            }
//        }

        $this->cache->delete('product');
    }

    public function checkOption($option) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "option_description WHERE name='" . $option . "'");
        $queryResult = $query->row;
        if (count($queryResult) > 0) {
            return $queryResult['option_id'];
        } else {
            return 0;
        }
    }

    public function addOption($option) {
        $this->db->query("INSERT INTO `" . DB_PREFIX . "option` SET type = 'select', sort_order = ''");
        $option_id = $this->db->getLastId();
        $this->db->query("INSERT INTO " . DB_PREFIX . "option_description SET option_id = '" . (int) $option_id . "', language_id = '1', name = '" . $this->db->escape($option) . "'");
        return $option_id;
    }

    public function checkOptionValue($option, $option_id) {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "option_value` o LEFT JOIN " . DB_PREFIX . "option_value_description od ON (o.option_id = od.option_id) WHERE od.name = '" . $option . "' AND od.option_id='" . $option_id . "'");
        //$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "option_value_description WHERE name='" . $option . "'");
        $queryResult = $query->row;

        //echo "<pre>test here:";print_r($queryResult);exit;
        if (count($queryResult) > 0) {
            return $queryResult['option_value_id'];
        } else {
            return 0;
        }
    }

    public function addOptionValue($option, $option_id) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "option_value SET option_id = '" . (int) $option_id . "'");

        $option_value_id = $this->db->getLastId();

        $this->db->query("INSERT INTO " . DB_PREFIX . "option_value_description SET option_value_id = '" . (int) $option_value_id . "', language_id = '" . (int) 1 . "', option_id = '" . (int) $option_id . "', name = '" . $this->db->escape($option) . "'");


        return $option_value_id;
    }

}

?>