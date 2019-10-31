<?php
// Heading
$_['heading_title']         = 'Buy Store Credit';

// Text
$_['text_extension']        = 'Extensions';
$_['text_edit']             = 'Edit Buy Store Credit';
$_['text_success']          = 'Success: You have modified module <strong>Buy Store Credit</strong>!';
$_['text_module']           = 'Modules';
$_['text_default']          = 'Default';
$_['text_image_manager']    = 'Image Manager';
$_['text_browse']           = 'Browse';
$_['text_clear']            = 'Clear';
$_['text_free']             = 'Free';
$_['text_fixed']            = 'Fixed';
$_['text_use_never']        = 'Don\'t use it for all cart';
$_['text_use_cart']         = 'Don\'t use it for store credit';

// Entry
$_['entry_status']          = 'Stauts';
$_['entry_send_email']      = 'Customer Notification';
$_['entry_customer']        = 'Customer Buy Credit';
$_['entry_min']             = 'Minimum Amount';
$_['entry_max']             = 'Maximum Amount';
$_['entry_image']           = 'Image';
$_['entry_default']         = 'Default Amount';
$_['entry_subject']         = 'Buy Credit Email Subject'; 
$_['entry_message']         = 'Buy Credit Email Message';
$_['entry_type']            = 'Type Amount';
$_['entry_fixed']           = 'Fixed Amounts';
$_['entry_order_status']    = 'Order Status';
$_['entry_use_credit']      = 'Use Store Credit';

// Help
$_['help_status']           = 'Enable/Disable the status of module.';
$_['help_send_email']       = 'Enable/Disable send a notification email to customer when credit added to his account.';
$_['help_buy_credit'] = 'Allow customer to buy credit via orders.';
$_['help_min']              = 'Enter the minimum amount to buy credit. Keep it empty if you don\'t want to use the limit.';
$_['help_max']              = 'Enter the maximum amount to buy credit. Keep it empty if you don\'t want to use the limit.';
$_['help_image']            = 'Enter the image to diplay it in the place of images of products.<br/>Keep it empty if you don\'t want to use an image.';
$_['help_default']          = 'Enter the default amount to buy credit.';
$_['help_subject']          = 'Enter the subject of customer notification email. keep it empty if want use default subject'; 
$_['help_message']          = 'Email message will be sent to customer after that the credit added to his account.';
$_['help_message_desc']     = '<br /><br />Use the following codes:<br />{firstname} - customer first name<br />{lastname} - customer last name<br />{email} - customer email<br />{order_id} - customer order ID<br />{amount} - purchase amount<br />{total} - customer balance';
$_['help_type']             = 'Choose the type of amount that you want, free if you want let your customer enter the amount or fixed to let your customers choose select the amount.';
$_['help_fixed']            = 'Enter the fixed amounts and separate then by comma.<br />Example: 5,10,20,50,100';
$_['help_message_desc']     = '<br /><br />Use the following codes:<br />{firstname} - customer first name<br />{lastname} - customer last name<br />{email} - customer email<br />{order_id} - customer order ID<br />{amount} - purchase amount<br />{total} - customer balance';
$_['help_order_status']     = 'Select order status  that will add store credit to customer';
$_['help_use_credit']       = 'Choose if want to deduct store credit or not when cart have buy store credit';

// Message
$_['send_credit_default_msg'] = '<p>Congratulation {firstname} {lastname}!</p>
                               <p>The amount of ({amount}) of your buy store credit (order ID: {order_id}) has been added to your account!</p>
                               <p>Your total of credit is now: {total}</p>
                               <p>Your account credit will be automatically deducted from your next purchase.</p>
                               <p>Thank you.</p>';

//tab
$_['tab_settings']          = 'Settings';
$_['tab_email']             = 'Email Template';

//Button
$_['button_save_stay']      = 'Save & Stay';

// Error
$_['error_permission']      = 'Warning: You do not have permission to modify module <strong>Buy Store Credit</strong>!';
$_['error_default']         = 'Amount must not be empty, please entre an amount!';
$_['error_default_max']     = 'Amount must be greater than or equal to max amount!';
$_['error_default_min']     = 'Amount must be less than or equal to min amount!';
$_['error_min']             = 'Min amount must be greater than max amount!';
$_['error_max']             = 'Max amount must be less than min amount!';
$_['error_fixed']           = 'Enter amounts separate by only comma!';
$_['error_warning']         = 'Warning: Please check the form carefully for errors!';
