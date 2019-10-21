<?php

class ControllerVendProduct extends Controller {

    private $productTaxClassid = '9'; // tax class id
    private $newestProductCategory = '89'; // Default category for adding new products:Newest Arrivals    

    public function __constract() {        
        $this->load->model('vend/product');
        $this->load->model('catalog/category');
        $this->vendposwebhook = new Vendposwebhook;
    }

    public function prodcutInventoryWebhook() {  
        $this->load->model('vend/product');
        $productWebhooksResponse = $_REQUEST; //Received webhooks post response
       // $this->model_vend_product->logContent("webhook inventory!");
       // $this->model_vend_product->logContent(print_r($productWebhooksResponse,true));
        if ($productWebhooksResponse != '') {
            $productJsonData = $productWebhooksResponse['payload'];
            $productDetails = json_decode($productJsonData, true);
            $this->model_vend_product->logContent(print_r($productDetails,true));
            if (count($productDetails) > 0) {
                $vend_product_id = $productDetails['product_id'];
                $quantity = $productDetails['count'];
                if ($vend_product_id != '') {
                    $this->model_vend_product->logContent($vend_product_id."::::: ".$quantity);
                    $this->model_vend_product->updateProductInventoryOnVend($vend_product_id,$quantity);
                    $this->model_vend_product->logContent($vend_product_id." :stock control inventory(".$quantity.") updated in OC!");
                }else{
                     $this->model_vend_product->logContent("Stock control webhook executed but don't get vend product id!");
                }
                header("HTTP/1.1 200 OK");
            } else {
                //blank request
                header("HTTP/1.1 204 OK");
                exit;
            }
        } else {
            //blank request
            header("HTTP/1.1 204 OK");
            exit;
        }
        exit;
    }

    /**
     * This webhook call from vend a
     */
    public function prodcutUpdateWebhook() {
        $this->load->model('vend/product');
        $productWebhooksResponse = $_REQUEST; //Received webhooks post response
        if ($productWebhooksResponse != '') {
            $productJsonData = $productWebhooksResponse['payload'];
            $productDetails = json_decode($productJsonData, true);

            if (count($productDetails) > 0) {
                $checkProductPresence = $this->model_vend_product->insertWebhookProdcut($productDetails);
                header("HTTP/1.1 200 OK");
            } else {
                //blank request
                header("HTTP/1.1 204 OK");
                exit;
            }
        } else {
            //blank request
            header("HTTP/1.1 204 OK");
            exit;
        }
        exit;
    }

    public function processPendingWebhooks() {       
      
        $this->load->model('vend/product');
        $pendingProduct = $this->model_vend_product->getPendingWebhookProdcuts();
       // echo "<pre>";print_r($pendingProduct);exit; 
        if (count($pendingProduct) > 0) {
            foreach ($pendingProduct as $whProduct) {
                $stopCurrentProcessing = FALSE;
                //check product present or not in OC DB
                $vendProductId = $whProduct['vend_product_id'];
                //fetch vend product details using API            
                $vendProductResponse = $this->vendposwebhook->getProductDetails($vendProductId);
                //echo "<pre>";print_r($vendProductResponse);exit;
                if (isset($vendProductResponse['data']['id']) && $vendProductResponse['data']['id'] != '') { // get product details in vend
                    $vendProductDetails = $vendProductResponse['data'];
                    $checkProductPresence = $this->model_vend_product->checkProductPresence($vendProductId);
                    //if (count($vendProductDetails['images']) > 0 || $checkProductPresence['queryOperartion'] == 'update_inventory') {
                        
                        $vendProductInventory = $this->vendposwebhook->getProductInventory($vendProductId);
//                        echo "<pre>";print_r($vendProductInventory);exit;
                        if ($checkProductPresence['queryOperartion'] == 'insert') {
                            $categoryId = $this->newestProductCategory; //default category for new products
                            //preapre data as per opencart database
                            $ocPostData = array();
                            $vendProductName = $vendProductDetails['name'];
                            $vendProductNameArray = explode('{', $vendProductDetails['name']);
                            if (count($vendProductNameArray) > 1) {
                                $vendProductDetails['name'] = $vendProductNameArray[0];
                                $model = str_replace("}", '', $vendProductNameArray[1]);
                            } else {
                                $model = $vendProductDetails['handle'];
                            }

                            if (isset($vendProductDetails['variant_name']) && $vendProductDetails['variant_name'] != '') {
                                $variant_name_array = explode('/', $vendProductDetails['variant_name']);
                                if (count($variant_name_array) > 1) {
                                    $vendProductDetails['name'] = $variant_name_array[1];
                                }
                            }
                            $ocPostData['product_description']['1']['name'] = $vendProductDetails['name'];
                            $ocPostData['product_description']['1']['description'] = $vendProductDetails['description'];
                            $ocPostData['product_description']['1']['meta_title'] = $vendProductDetails['name'];
                            $ocPostData['product_description']['1']['meta_description'] = '';
                            $ocPostData['product_description']['1']['meta_keyword'] = '';
                            if (isset($vendProductDetails['categories']) && count($vendProductDetails['categories']) > 0) {
                                $listCategories = '';
                                foreach ($vendProductDetails['categories'] as $pcat) {
                                    $listCategories.=$pcat['name'] . ',';
                                }
                                $listCategories = substr($listCategories, 0, -1);
                                $ocPostData['product_description']['1']['tag'] = $listCategories;
                            } else {
                                $ocPostData['product_description']['1']['tag'] = '';
                            }
                            $ocPostData['model'] = $model;
                            $ocPostData['sku'] = $vendProductDetails['sku'];
                            $ocPostData['upc'] = '';
                            $ocPostData['ean'] = '';
                            $ocPostData['jan'] = '';
                            $ocPostData['isbn'] = '';
                            $ocPostData['mpn'] = '';
                            $ocPostData['location'] = '';
                            $ocPostData['price'] = $vendProductDetails['price_excluding_tax'];
                            $ocPostData['tax_class_id'] = $this->productTaxClassid; //every product is taxable
                            $ocPostData['quantity'] = ((isset($vendProductInventory['data'][0]['inventory_level']) && $vendProductInventory['data'][0]['inventory_level'] > 0) ? $vendProductInventory['data'][0]['inventory_level'] : 0);
                            $ocPostData['minimum'] = '1';
                            $ocPostData['subtract'] = '1';
                            $ocPostData['stock_status_id'] = '5'; //need research on it
                            $ocPostData['shipping'] = '1';
                            $ocPostData['keyword'] = '';
                            $ocPostData['date_available'] = '';
                            $ocPostData['length'] = '';
                            $ocPostData['width'] = '';
                            $ocPostData['height'] = '';
                            $ocPostData['length_class_id'] = '';
                            $ocPostData['weight'] = ''; //need to discuss
                            $ocPostData['weight_class_id'] = ''; //need to discuss
                            $ocPostData['status'] = '0';
                            $ocPostData['sort_order'] = '';
                            $ocPostData['manufacturer'] = ((isset($vendProductDetails['brand']['name']) && $vendProductDetails['brand']['name'] != '') ? $vendProductDetails['brand']['name'] : '');
                            //get manufacturer

                            if ($ocPostData['manufacturer'] != '') {
                                $manufacturer_id = $this->model_vend_product->addUpdatemanufacturer($ocPostData['manufacturer']);
                                $ocPostData['manufacturer_id'] = $manufacturer_id;
                            } else {
                                $ocPostData['manufacturer_id'] = '';
                            }
                            $ocPostData['category'] = '';
                            if ($categoryId > 0) {
                                $ocPostData['product_category'][0] = $categoryId;
                            }
                            $ocPostData['filter'] = '';
                            $ocPostData['product_store'][0] = 0;
                            $ocPostData['download'] = '';
                            $ocPostData['related'] = '';
                            $ocPostData['product_related'] = array();
                            $ocPostData['points'] = '';
                            $ocPostData['product_reward'][1]['points'] = '';
                            $ocPostData['product_layout'][0] = '';
//                            echo "<pre>";print_r($ocPostData);exit; 
                            //product images

                            if (isset($vendProductDetails['image_url']) && $vendProductDetails['image_url'] != '') {
                                $mainProductImage = $this->downloadImages($vendProductDetails['image_url']);
                                $ocPostData['image'] = $mainProductImage;
                            } else {
                                $ocPostData['image'] = '';
                            }

                            $addtionalImages = array();
                            if (isset($vendProductDetails['images']) && count($vendProductDetails['images']) > 1) {
                                array_shift($vendProductDetails['images']);
                                foreach ($vendProductDetails['images'] as $imgkey => $imgval) {
                                    $addtionalImages[$imgkey]['image'] = $this->downloadImages($imgval['url']);
                                    $addtionalImages[$imgkey]['sort_order'] = $imgkey;
                                }
                            }

                            $ocPostData['product_image'] = $addtionalImages;
                            
//                            if (isset($vendProductDetails['variant_options'][0]['name']) && $vendProductDetails['variant_options'][0]['name'] != '') {
//                                $productOptionsArray = array();
//                                foreach ($vendProductDetails['variant_options'] as $op) {
//                                    $productOptionsArray[$op['name']][] = $op['value'];
//                                }
//
//
//                                $product_option = array();
////                                $opcnt = 0;
////                                echo "<pre>";
////                                print_r($productOptionsArray);
////                                exit;
////                                foreach ($productOptionsArray as $opKey => $op) {
////                                    //check option present or not in OC
////                                    if ($opKey != '') {
////                                        $option_id = $this->addUpdateOption($opKey);
////                                    } else {
////                                        $option_id = '';
////                                    }
////                                    if ($option_id > 0) {
////                                        $product_option[$opcnt]['product_option_id'] = '';
////
////                                        $product_option[$opcnt]['name'] = $opKey;
////                                        //addupdate option in oc
////                                        $product_option[$opcnt]['option_id'] = $option_id;
////                                        $product_option[$opcnt]['type'] = 'select';
////                                        $product_option[$opcnt]['required'] = '1';
////                                        foreach ($op as $okey => $opval) {
////                                            //addupdate option value
////                                            $optionValueId = '';
////                                            if ($opval != '') {
////                                                $optionValueId = $this->addUpdateOptionValue($opval, $option_id);
////                                            } else {
////                                                $optionValueId = '';
////                                            }
////                                            $product_option[$opcnt]['product_option_value'][$okey]['option_value_id'] = $optionValueId;
////                                            $product_option[$opcnt]['product_option_value'][$okey]['product_option_value_id'] = '';
////                                            $product_option[$opcnt]['product_option_value'][$okey]['quantity'] = '';
////                                            $product_option[$opcnt]['product_option_value'][$okey]['subtract'] = '1';
////                                            $product_option[$opcnt]['product_option_value'][$okey]['price_prefix'] = '+';
////                                            $product_option[$opcnt]['product_option_value'][$okey]['price'] = '';
////                                            $product_option[$opcnt]['product_option_value'][$okey]['points_prefix'] = '+';
////                                            $product_option[$opcnt]['product_option_value'][$okey]['points'] = '';
////                                            $product_option[$opcnt]['product_option_value'][$okey]['weight_prefix'] = '+';
////                                            $product_option[$opcnt]['product_option_value'][$okey]['weight'] = '';
////                                        }
////                                    }
////                                    $opcnt++;
////                                }
//                                $ocPostData['product_option'] = $product_option;
//                            } else {
//                                $ocPostData['option'] = '';
//                            }
                            $ocPostData['option'] = '';
                            $product_id = $this->model_vend_product->addProduct($ocPostData);
                            if ($product_id > 0) {
                                echo $message = $vendProductId . ':new product added in OC';
                                $updProductData = array();
                                $updProductData['product_id'] = $product_id;
                                $updProductData['vend_product_id'] = $vendProductId;
                                $updProductData['status'] = 1;
                                $this->model_vend_product->updateProductStatus($updProductData);
                                $this->model_vend_product->logContent($message);
                                $this->model_vend_product->updateWebhookStatus($vendProductId, $message, 'processed');
                            } else {
                               echo $message = $vendProductId . ':new product not added in OC!';
                                $this->model_vend_product->logContent($message);
                                $this->model_vend_product->updateWebhookStatus($vendProductId, $message, 'not_processed');
                            }
                        } elseif ($checkProductPresence['queryOperartion'] == 'update') {
                            $ocPostData = array();
                            $vendProductName = $vendProductDetails['name'];
                            $vendProductNameArray = explode('{', $vendProductDetails['name']);
                            if (count($vendProductNameArray) > 1) {
                                $vendProductDetails['name'] = $vendProductNameArray[0];
                                $model = str_replace("}", '', $vendProductNameArray[1]);
                            } else {
                                $model = $vendProductDetails['handle'];
                            }

                            if (isset($vendProductDetails['variant_name']) && $vendProductDetails['variant_name'] != '') {
                                $variant_name_array = explode('/', $vendProductDetails['variant_name']);
                                if (count($variant_name_array) > 1) {
                                    $vendProductDetails['name'] = $variant_name_array[1];
                                }
                            }
                            $ocPostData['product_description']['1']['name'] = $vendProductDetails['name'];
                            $ocPostData['product_description']['1']['meta_title'] = $vendProductDetails['name'];
                            $ocPostData['product_description']['1']['meta_description'] = '';
                            $ocPostData['product_description']['1']['meta_keyword'] = '';
                            $ocPostData['product_description']['1']['description'] = $vendProductDetails['description'];
                            if (isset($vendProductDetails['categories']) && count($vendProductDetails['categories']) > 0) {
                                $listCategories = '';
                                foreach ($vendProductDetails['categories'] as $pcat) {
                                    $listCategories.=$pcat['name'] . ',';
                                }
                                $listCategories = substr($listCategories, 0, -1);
                                $ocPostData['product_description']['1']['tag'] = $listCategories;
                            } else {
                                $ocPostData['product_description']['1']['tag'] = '';
                            }
                            $ocPostData['model'] = $model;
//                            $ocPostData['sku'] = $vendProductDetails['sku'];

                            $ocPostData['price'] = $vendProductDetails['price_excluding_tax'];
//                            $ocPostData['tax_class_id'] = $this->productTaxClassid; //every product is taxable
                            $ocPostData['quantity'] = ((isset($vendProductInventory['data'][0]['inventory_level']) && $vendProductInventory['data'][0]['inventory_level'] > 0) ? $vendProductInventory['data'][0]['inventory_level'] : 0);

                            $ocPostData['manufacturer'] = ((isset($vendProductDetails['brand']['name']) && $vendProductDetails['brand']['name'] != '') ? $vendProductDetails['brand']['name'] : '');
                            //get manufacturer

                            if ($ocPostData['manufacturer'] != '') {
                                $manufacturer_id = $this->model_vend_product->addUpdatemanufacturer($ocPostData['manufacturer']);
                                $ocPostData['manufacturer_id'] = $manufacturer_id;
                            } else {
                                $ocPostData['manufacturer_id'] = '';
                            }


                            //echo "<pre>";print_r($ocPostData);exit;
                            //product images

                            if (isset($vendProductDetails['image_url']) && $vendProductDetails['image_url'] != '') {
                                $mainProductImage = $this->downloadImages($vendProductDetails['image_url']);
                                $ocPostData['image'] = $mainProductImage;
                            } else {
                                $ocPostData['image'] = '';
                            }

                            $addtionalImages = array();
                            if (isset($vendProductDetails['images']) && count($vendProductDetails['images']) > 1) {
                                array_shift($vendProductDetails['images']);
                                foreach ($vendProductDetails['images'] as $imgkey => $imgval) {
                                    $addtionalImages[$imgkey]['image'] = $this->downloadImages($imgval['url']);
                                    $addtionalImages[$imgkey]['sort_order'] = $imgkey;
                                }
                            }

                            $ocPostData['product_image'] = $addtionalImages;
//                            $ocPostData['status'] = '1';
//                            echo "<pre>";print_r($ocPostData);exit;
//                                echo "<pre>";print_r($ocPostData);exit;
                            $this->model_vend_product->editProduct($checkProductPresence['product_id'], $ocPostData);
                            echo $message = $vendProductId . ': product updated in OC';
                            $this->model_vend_product->logContent($message);
                            $this->model_vend_product->updateWebhookStatus($vendProductId, $message, 'processed');
                        } else { //update invnetory only
                            $ocPostData = array();
                            $ocPostData['quantity'] = ((isset($vendProductInventory['data'][0]['inventory_level']) && $vendProductInventory['data'][0]['inventory_level'] > 0) ? $vendProductInventory['data'][0]['inventory_level'] : 0);
                            //echo "<pre>";print_r($ocPostData);exit;
                            $this->model_vend_product->updateProductInventory($checkProductPresence['product_id'], $ocPostData);
                            echo $message = $vendProductId . ': product inventory updated in OC';
                            $this->model_vend_product->logContent($message);
                            $this->model_vend_product->updateWebhookStatus($vendProductId, $message, 'processed');
                        }

                   /** } else {
                        $message = $vendProductId . ':product created for generate sales reciept. No need to add it in opencart site!';
                        $this->model_vend_product->logContent($message);
                        $this->model_vend_product->updateWebhookStatus($vendProductId, $message, 'deleted');
                      } * */
                } else {
                    $message = $vendProductId . ':not recieved product details in vend API call!';
                    $this->model_vend_product->logContent($message);
                    $this->model_vend_product->updateWebhookStatus($vendProductId, $message, 'not_processed');
                }
            }
        } else {
            $this->model_vend_product->logContent('Pending webhooks products not present in OC database!');
        }
    }

    public function addUpdateCategory($categoryName) {
        if ($categoryName != '') {
            //check cateogry present or not
            $categoryid = $this->model_vend_product->checkCategory($categoryName);
            if ($categoryid > 0) {
                return $categoryid;
            } else {
                $insData = array();
                $insData['category_description'][1]['name'] = $categoryName;
                $insData['category_description'][1]['description'] = '';
                $insData['category_description'][1]['meta_title'] = $categoryName;
                $insData['category_description'][1]['meta_description'] = '';
                $insData['category_description'][1]['meta_keyword'] = '';
                $insData['path'] = '';
                $insData['parent_id'] = '0';
                $insData['filter'] = '';
                $insData['category_store'][0] = '0';
                $insData['keyword'] = '';
                $insData['image'] = '';
                $insData['column'] = '1';
                $insData['sort_order'] = '0';
                $insData['status'] = '1';
                $insData['category_layout'][0] = '';
                $this->load->model('catalog/category');
//                echo "<pre>";print_r($insData);exit;
                $this->model_vend_product->addCategory($insData);
                $categoryid = $this->model_vend_product->checkCategory($categoryName);
                return $categoryid;
            }
        }
    }

    public function downloadImages($imgurl) {
        $url_to_image = $imgurl;
        $ch = curl_init($url_to_image);
        $my_save_dir = DIR_IMAGE . 'catalog/';
        $filename = basename($url_to_image);
        $complete_save_loc = $my_save_dir . $filename;
        $fp = fopen($complete_save_loc, 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
        return 'catalog/' . $filename;
    }

    public function index() {
//        $this->load->model('vend/product');
//        $productDetails = array();
//        $productDetails['id'] = '50629930-c7af-325e-f54b-27d27b9f7dae';
//
//        $checkProductPresence = $this->model_vend_product->checkProductPresence($productDetails);
//        if ($checkProductPresence['vend_processing'] == 'inprogress') {
////            echo "<pre>";
////            print_r($checkProductPresence);
//            //fetch vend product details using API            
//            $vendProductResponse = $this->vendposwebhook->getProductDetails($productDetails['id']);
//
//
////            echo "<pre>";
////            print_r($vendProductResponse);
////            exit;
//            if (isset($vendProductResponse['data']['id']) && $vendProductResponse['data']['id'] != '') { // get product details in vend
//                //get product invenotry
//                $vendProductInventory = $this->vendposwebhook->getProductInventory($productDetails['id']);
//                if (isset($vendProductInventory['data'][0]['inventory_level']) && $vendProductInventory['data'][0]['inventory_level'] != '') {
//                    // add category in OC
//                    if (isset($vendProductDetails['type']['name']) && $vendProductDetails['type']['name'] != '') {
//                        $categoryId = $this->addUpdateCategory($vendProductDetails['type']['name']);
//                    } else {
//                        $categoryId = '';
//                    }
//
//
//                    //preapre data as per opencart database
//                    $ocPostData = array();
//                    $vendProductDetails = $vendProductResponse['data'];
//                    $ocPostData['product_description']['1']['name'] = $vendProductDetails['name'];
//                    $ocPostData['product_description']['1']['description'] = $vendProductDetails['description'];
//                    $ocPostData['product_description']['1']['meta_title'] = $vendProductDetails['name'];
//                    $ocPostData['product_description']['1']['meta_description'] = '';
//                    $ocPostData['product_description']['1']['meta_keyword'] = '';
//                    if (isset($vendProductDetails['categories']) && count($vendProductDetails['categories']) > 0) {
//                        $listCategories = '';
//                        foreach ($vendProductDetails['categories'] as $pcat) {
//                            $listCategories.=$pcat['name'] . ',';
//                        }
//                        $listCategories = substr($listCategories, 0, -1);
//                        $ocPostData['product_description']['1']['tag'] = $listCategories;
//                    } else {
//                        $ocPostData['product_description']['1']['tag'] = '';
//                    }
//
//                    $ocPostData['model'] = '';
//                    $ocPostData['sku'] = $vendProductDetails['sku'];
//                    ;
//                    $ocPostData['upc'] = '';
//                    $ocPostData['ean'] = '';
//                    $ocPostData['jan'] = '';
//                    $ocPostData['isbn'] = '';
//                    $ocPostData['mpn'] = '';
//                    $ocPostData['location'] = '';
//                    $ocPostData['price'] = $vendProductDetails['price_excluding_tax'];
//                    $ocPostData['tax_class_id'] = ''; // need research on it
//                    $ocPostData['quantity'] = $vendProductInventory['data'][0]['inventory_level'];
//                    $ocPostData['minimum'] = '';
//                    $ocPostData['subtract'] = '';
//                    $ocPostData['stock_status_id'] = ''; //need research on it
//                    $ocPostData['shipping'] = '';
//                    $ocPostData['keyword'] = '';
//                    $ocPostData['date_available'] = '';
//                    $ocPostData['length'] = '';
//                    $ocPostData['width'] = '';
//                    $ocPostData['height'] = '';
//                    $ocPostData['length_class_id'] = '';
//                    $ocPostData['weight'] = ''; //need to discuss
//                    $ocPostData['weight_class_id'] = ''; //need to discuss
//                    $ocPostData['status'] = '0';
//                    $ocPostData['sort_order'] = '';
//                    $ocPostData['manufacturer'] = '';
//                    $ocPostData['manufacturer_id'] = '';
//                    $ocPostData['category'] = '';
//                    if ($categoryId > 0) {
//                        $ocPostData['product_category'][0] = $categoryId;
//                    }
//                    $ocPostData['filter'] = '';
//                    $ocPostData['product_store'][0] = 0;
//                    $ocPostData['download'] = '';
//                    $ocPostData['related'] = '';
//                    $ocPostData['product_related'] = array();
//                    $ocPostData['points'] = '';
//                    $ocPostData['product_reward'][1]['points'] = '';
//                    $ocPostData['product_layout'][0] = '';
//                    if (isset($vendProductInventory['variant_options'][0]['name']) && $vendProductInventory['variant_options'][0]['name'] != '') {
//                        $productOptionsArray = array();
//                        foreach ($vendProductDetails['variant_options'] as $op) {
//                            $productOptionsArray[$op['name']][] = $op['value'];
//                        }
//                        $product_option = array();
//                        $opcnt = 0;
//                        foreach ($productOptionsArray as $opKey => $op) {
//                            //check option present or not in OC
//                            if ($opKey != '') {
//                                $option_id = $this->addUpdateOption($opKey);
//                            } else {
//                                $option_id = '';
//                            }
//                            if ($option_id > 0) {
//                                $product_option[$opcnt]['product_option_id'] = '';
//
//                                $product_option[$opcnt]['name'] = $opKey;
//                                //addupdate option in oc
//                                $product_option[$opcnt]['option_id'] = $option_id;
//                                $product_option[$opcnt]['type'] = 'select';
//                                $product_option[$opcnt]['required'] = '1';
//                                foreach ($op as $okey => $opval) {
//                                    //addupdate option value
//                                    $optionValueId = '';
//                                    if ($opval != '') {
//                                        $optionValueId = $this->addUpdateOptionValue($opval);
//                                    } else {
//                                        $optionValueId = '';
//                                    }
//                                    $product_option[$opcnt]['product_option_value'][$okey]['option_value_id'] = $optionValueId;
//                                    $product_option[$opcnt]['product_option_value'][$okey] = '';
//                                    $product_option[$opcnt]['product_option_value'][$okey]['quantity'] = '';
//                                    $product_option[$opcnt]['product_option_value'][$okey]['subtract'] = '1';
//                                    $product_option[$opcnt]['product_option_value'][$okey]['price_prefix'] = '+';
//                                    $product_option[$opcnt]['product_option_value'][$okey]['price'] = '';
//                                    $product_option[$opcnt]['product_option_value'][$okey]['points_prefix'] = '+';
//                                    $product_option[$opcnt]['product_option_value'][$okey]['points'] = '';
//                                    $product_option[$opcnt]['product_option_value'][$okey]['weight_prefix'] = '+';
//                                    $product_option[$opcnt]['product_option_value'][$okey]['weight'] = '';
//                                }
//                            }
//                            $opcnt++;
//                        }
//                    } else {
//                        $ocPostData['option'] = '';
//                    }
//
//                    //product images
//                } else {
//                    $this->model_vend_product->logContent('product inventory not received in API call!');
//                }
//            } else { // prodcut details not recived in API call
//                $this->model_vend_product->logContent(isset($vendProductResponse['errors']['global'][0]) && $vendProductResponse['errors']['global'][0] != '' ? $vendProductResponse['errors']['global'][0] : $productDetails['id'] . ' : product details not received in API call!');
//            }
//            echo "<pre>";
//            print_r($vendProductResponse);
//            exit;
//            header("HTTP/1.1 200 OK");
//            exit;
//        } else { //repeted webhook call
//            header("HTTP/1.1 200 OK");
//            exit;
//        }
    }

    public function addUpdateOptionValue($optionValue, $option_id) {
        //echo "<pre>";print_r($optionValue);
        //echo $option_id;exit;
        if ($optionValue != '' && $option_id > 0) {
            //check cateogry present or not
            $optionValueid = $this->model_vend_product->checkOptionValue($optionValue, $option_id);
            if ($optionValueid > 0) {
                return $optionValueid;
            } else {

                return $this->model_vend_product->addOptionValue($optionValue, $option_id);
            }
        }
    }

    public function addUpdateOption($option) {
        if ($option != '') {
            //check cateogry present or not
            $optionid = $this->model_vend_product->checkOption($option);
            if ($optionid > 0) {
                return $optionid;
            } else {
                return $this->model_vend_product->addOption($option);
            }
        }
    }

    public function addUpdateProdcut() {

        $productWebhooksResponse = $_REQUEST; //Received webhooks post response
        if ($productWebhooksResponse != '') {
            $productJsonData = $productWebhooksResponse['payload'];
            $productDetails = json_decode($productJsonData, true);

            if (count($productDetails) > 0) {
                $checkProductPresence = $this->model_vend_product->checkProductPresence($productDetails);
                $vendProductResponse = $this->vendposwebhook->getProductDetails($productDetails['id']);
                echo "<pre>";
                print_r($vendProductResponse);
                exit;
//                $productId = '50629930-c7af-325e-f54b-27d27b9f7dae';
//                $productResponse = $this->vendposwebhook->getProductDetails($productId);
//                echo "test:<pre>";
//                print_r($productResponse);
//                exit;
                header("HTTP/1.1 200 OK");
            } else {
                //blank request
                header("HTTP/1.1 204 OK");
                exit;
            }
        } else {
            //blank request
            header("HTTP/1.1 204 OK");
            exit;
        }




        exit;
    }

}
