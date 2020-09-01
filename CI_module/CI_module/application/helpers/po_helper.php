<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('edit_po_logs')) {
 function edit_po_logs($data_old, $po_id, &$log_arr, $state_list, $vendorList)
 {		
 		$CI =& get_instance();
 		$current_user = $CI->session->userdata('id');
 		$country_list = get_country_list();
 		$update_po_master_data = array();
 		$log_index = 0;
 		$po_maste_data = $data_old['po_master_data'];

 		//po master start
 		$new_vendor_id = $_POST['vendor'];
 		$new_email = $_POST['email'];
 		$new_billing_street_address_2 = $_POST['billing_street_address_2'];
 		$new_billing_city = $_POST['billing_city'];
 		$new_billing_country = $_POST['billing_country'];

 		$new_billing_contact = '';
 		$new_billing_zipcode = $_POST['billing_zipcode'];
 		$new_billing_contact = $_POST['billing_contact'];
 		$new_billing_fax = $_POST['billing_fax'];

 		$vendor_list = array_column($vendorList, 'vendor_name', 'id');
 		if($new_vendor_id != $po_maste_data['vendor_id'])
 		{

 			$log_text = "Changed vendor from ".$vendor_list[$po_maste_data['vendor_id']].' to ' .$vendor_list[$new_vendor_id];
			$log_arr[$log_index]['log_text'] = $log_text;
			$log_arr[$log_index]['po_id'] = $po_id;
			$log_arr[$log_index]['created_at'] = date('Y-m-d H:i:s');
			$log_arr[$log_index]['updated_by'] = $current_user;
			$log_index++;
			$update_po_master_data['vendor_id'] = $new_vendor_id;
 		}

 		if(isset($_POST['is_posted']))
 		{
	 		$old_is_posted = $data_old['po_master_data']['is_posted'];
	 		$new_is_posted = 1;
	 		if(trim(strtolower($old_is_posted)) != trim(strtolower($new_is_posted)))
	 		{	
				$log_text = "Changed Posted status from No to Yes";
				$log_arr[$log_index]['log_text'] = $log_text;
				$log_arr[$log_index]['po_id'] = $po_id;
				$log_arr[$log_index]['created_at'] = date('Y-m-d H:i:s');
				$log_arr[$log_index]['updated_by'] = $current_user;
				$log_index++;
				$update_po_master_data['is_posted'] = $new_is_posted;
	 		}
 		}
 		else
 		{	
 			$old_is_posted = $data_old['po_master_data']['is_posted'];
	 		$new_is_posted = 0; //company
	 		if(trim(strtolower($old_is_posted)) != trim(strtolower($new_is_posted)))
	 		{	
				$log_text = "Changed Posted status from Yes to NO";
				$log_arr[$log_index]['log_text'] = $log_text;
				$log_arr[$log_index]['po_id'] = $po_id;
				$log_arr[$log_index]['created_at'] = date('Y-m-d H:i:s');
				$log_arr[$log_index]['updated_by'] = $current_user;
				$log_index++;
				$update_po_master_data['is_posted'] = $new_is_posted;
	 		}
 		}

 		$action_arr['controller'] = 'po';
      	$action_arr['action']     = 'landed';
      	$check_permission = get_permission_by_action($action_arr);
      	if($check_permission == TRUE){
	      	if(isset($_POST['is_landed'])){
		 		$old_is_landed = $po_maste_data['is_landed'];
		 		$new_is_landed =1; //company
		 		if(trim(strtolower($old_is_landed)) != trim(strtolower($new_is_landed)))
		 		{	
		 			$log_text = "Changed Landed status from No to Yes";
					$log_arr[$log_index]['log_text'] = $log_text;
					$log_arr[$log_index]['po_id'] = $po_id;
					$log_arr[$log_index]['created_at'] = date('Y-m-d H:i:s');
					$log_arr[$log_index]['updated_by'] = $current_user;
					$log_index++;
					$update_po_master_data['is_landed'] = $new_is_landed;
		 		}
	 		}
	 		else{
	 			$old_is_landed = $po_maste_data['is_landed'];
		 		$new_is_landed =0; //company
		 		if(trim(strtolower($old_is_landed)) != trim(strtolower($new_is_landed)))
		 		{	
					$log_text = "Changed Landed status from Yes to No";
					$log_arr[$log_index]['log_text'] = $log_text;
					$log_arr[$log_index]['po_id'] = $po_id;
					$log_arr[$log_index]['created_at'] = date('Y-m-d H:i:s');
					$log_arr[$log_index]['updated_by'] = $current_user;
					$log_index++;
					$update_po_master_data['is_landed'] = $new_is_landed;
		 		}
	 		}
      	}

      	$action_arr['controller'] = 'po';
      	$action_arr['action']     = 'received';
      	$check_permission = get_permission_by_action($action_arr);
      	if($check_permission == TRUE){
	      	if(isset($_POST['is_received'])){
		 		$old_is_received = $po_maste_data['is_received'];
		 		$new_is_received = 1; //company
		 		if(trim(strtolower($old_is_received)) != trim(strtolower($new_is_received)))
		 		{	
					$log_text = "Changed Received status from No to Yes";
					$log_arr[$log_index]['log_text'] = $log_text;
					$log_arr[$log_index]['po_id'] = $po_id;
					$log_arr[$log_index]['created_at'] = date('Y-m-d H:i:s');
					$log_arr[$log_index]['updated_by'] = $current_user;
					$log_index++;
					$update_po_master_data['is_received'] = $new_is_received;
					$update_po_master_data['received_at'] = date('Y-m-d H:i:s');
		 		}
	 		}
	 		else{
	 			$old_is_received = $po_maste_data['is_received'];
		 		$new_is_received = 0; //company
		 		if(trim(strtolower($old_is_received)) != trim(strtolower($new_is_received)))
		 		{	
					$log_text = "Changed Received status from Yes to NO";
					$log_arr[$log_index]['log_text'] = $log_text;
					$log_arr[$log_index]['po_id'] = $po_id;
					$log_arr[$log_index]['created_at'] = date('Y-m-d H:i:s');
					$log_arr[$log_index]['updated_by'] = $current_user;
					$log_index++;
					$update_po_master_data['is_received'] = $new_is_received;
					$update_po_master_data['received_at'] = date('Y-m-d H:i:s');
		 		}
	 		}
      	}
 		
 		if(isset($_POST['is_discrepancy'])){
	 		$old_is_decrepency = $po_maste_data['is_discrepancy'];
	 		$new_is_decrepency = 1; //company
	 		if(trim(strtolower($old_is_decrepency)) != trim(strtolower($new_is_decrepency)))
	 		{	
				$log_text = "Changed Discripency status from No to Yes";
				$log_arr[$log_index]['log_text'] = $log_text;
				$log_arr[$log_index]['po_id'] = $po_id;
				$log_arr[$log_index]['created_at'] = date('Y-m-d H:i:s');
				$log_arr[$log_index]['updated_by'] = $current_user;
				$log_index++;
				$update_po_master_data['is_discrepancy'] = $new_is_decrepency;
	 		}
 		}
 		else{
 			$old_is_decrepency = $po_maste_data['is_discrepancy'];
	 		$new_is_decrepency = 0; //company
	 		if(trim(strtolower($old_is_decrepency)) != trim(strtolower($new_is_decrepency)))
	 		{	
				$log_text = "Changed Discripency from Yes to NO";
				$log_arr[$log_index]['log_text'] = $log_text;
				$log_arr[$log_index]['po_id'] = $po_id;
				$log_arr[$log_index]['created_at'] = date('Y-m-d H:i:s');
				$log_arr[$log_index]['updated_by'] = $current_user;
				$log_index++;
				$update_po_master_data['is_discrepancy'] = $new_is_decrepency;
	 		}
 		}

 		if(isset($_POST['is_closed']))
 		{
	 		$old_is_closed = $po_maste_data['is_closed'];
	 		$new_is_closed = 1; //company
	 		if(trim(strtolower($old_is_closed)) != trim(strtolower($new_is_closed)))
	 		{	
				$log_text = "Changed Closed status from No to Yes";
				$log_arr[$log_index]['log_text'] = $log_text;
				$log_arr[$log_index]['po_id'] = $po_id;
				$log_arr[$log_index]['created_at'] = date('Y-m-d H:i:s');
				$log_arr[$log_index]['updated_by'] = $current_user;
				$log_index++;
				$update_po_master_data['is_closed'] = $new_is_closed;
	 		}
 		}
 		else
 		{
 			$old_is_closed = $po_maste_data['is_closed'];
	 		$new_is_closed = 0; //company
	 		if(trim(strtolower($old_is_closed)) != trim(strtolower($new_is_closed)))
	 		{	
				$log_text = "Changed Closed status from Yes to No";
				$log_arr[$log_index]['log_text'] = $log_text;
				$log_arr[$log_index]['po_id'] = $po_id;
				$log_arr[$log_index]['created_at'] = date('Y-m-d H:i:s');
				$log_arr[$log_index]['updated_by'] = $current_user;
				$log_index++;
				$update_po_master_data['is_closed'] = $new_is_closed;
	 		}
 		}

 		$new_email= $_POST['email'];
 		$old_email = $po_maste_data['email']; 		
 		if(trim(strtolower($old_email)) != trim(strtolower($new_email)))
 		{	
 			if(empty($old_email))
				$log_text = "Added Email ". $new_email;
			else
				$log_text = "Changed Email from ".$old_email.' to ' .$new_email;
			$log_arr[$log_index]['log_text'] = $log_text;
			$log_arr[$log_index]['po_id'] = $po_id;
			$log_arr[$log_index]['created_at'] = date('Y-m-d H:i:s');
			$log_arr[$log_index]['updated_by'] = $current_user;
			$log_index++;
			$update_po_master_data['email'] = $new_email;
 		}

 		$old_shipping_label = $po_maste_data['shipping_company'];
 		$new_shipping_label = $_POST['shipping_company']; //company
 		if(trim(strtolower($old_shipping_label)) != trim(strtolower($new_shipping_label)))
 		{	
 			if(empty($old_shipping_label))
 			{
				$log_text = "Added Shipping company ". $new_shipping_label;
 			}
			else
			{
				$carrier = (isset(SHIPPING_COMPANY[$old_shipping_label])) ? trim(SHIPPING_COMPANY[$old_shipping_label]) : $old_shipping_label;

				$log_carrier = (isset(SHIPPING_COMPANY[$new_shipping_label])) ? trim(SHIPPING_COMPANY[$new_shipping_label]) : $new_shipping_label;

				$log_text = "Changed Shipping company from ".$carrier.' to ' .$log_carrier;
			}
			$log_arr[$log_index]['log_text'] = $log_text;
			$log_arr[$log_index]['po_id'] = $po_id;
			$log_arr[$log_index]['created_at'] = date('Y-m-d H:i:s');
			$log_arr[$log_index]['updated_by'] = $current_user;
			$log_index++;
			$update_po_master_data['shipping_company'] = $new_shipping_label;
 		}

 		if(strtolower($_POST['shipping_company'])!=strtolower('Other'))
 			$new_shipping_method = $_POST['shipping_method'];

 		else
 			$new_shipping_method = $_POST['other_shipping_method'];

 		$old_shipping_method = $po_maste_data['shipping_method'];
 		
 		if(trim(strtolower($old_shipping_method)) != trim(strtolower($new_shipping_method)))
 		{	
 			if(empty($old_shipping_method))
				$log_text = "Added Shipping method ". $new_shipping_method;
			else
				$log_text = "Changed Shipping method from ".$old_shipping_method.' to ' .$new_shipping_method;
			$log_arr[$log_index]['log_text'] = $log_text;
			$log_arr[$log_index]['po_id'] = $po_id;
			$log_arr[$log_index]['created_at'] = date('Y-m-d H:i:s');
			$log_arr[$log_index]['updated_by'] = $current_user;
			$log_index++;
			$update_po_master_data['shipping_method'] = $new_shipping_method;
 		}

 		$new_payment_term= $_POST['payment_term'];
 		$old_payment_term = $po_maste_data['payment_term'];	
 		if(trim(strtolower($old_payment_term)) != trim(strtolower($new_payment_term)))
 		{	
 			if(empty($old_payment_term))
				$log_text = "Added Payment Term". $new_payment_term;
			else
				$log_text = "Changed Payment Term from ".$old_payment_term.' to ' .$new_payment_term;
			$log_arr[$log_index]['log_text'] = $log_text;
			$log_arr[$log_index]['po_id'] = $po_id;
			$log_arr[$log_index]['created_at'] = date('Y-m-d H:i:s');
			$log_arr[$log_index]['updated_by'] = $current_user;
			$log_index++;
			$update_po_master_data['payment_term'] = $new_payment_term;
			$update_po_master_data['other_payment_term'] = '';
			if(trim(strtolower($new_payment_term)) == trim(strtolower('Other')))
	 		{
	 			if(isset($_POST['other_terms']) && !empty($_POST['other_terms']))
	 			{
		 			$other_payment_term = $_POST['other_terms'];
		 			if(isset($po_maste_data['other_payment_term']) && empty($po_maste_data['other_payment_term']))
						$log_text = "Added other payment term ". $other_payment_term;
					else
						$log_text = "Changed other payment term from ".$po_maste_data['other_payment_term'].' to ' .$other_payment_term;
					$log_arr[$log_index]['log_text'] = $log_text;
					$log_arr[$log_index]['po_id'] = $po_id;
					$log_arr[$log_index]['created_at'] = date('Y-m-d H:i:s');
					$log_arr[$log_index]['updated_by'] = $current_user;
					$log_index++;
					$update_po_master_data['other_payment_term'] = $other_payment_term;
	 			}
	 		}
 		}
 		else if(trim(strtolower($new_payment_term)) == trim(strtolower('Other')))
 		{
 			if(isset($_POST['other_terms']) && !empty($_POST['other_terms']))
 			{
	 			$other_payment_term = $_POST['other_terms'];
	 			if(isset($po_maste_data['other_payment_term']) && empty($po_maste_data['other_payment_term']))
					$log_text = "Added other payment term value ". $_POST['other_terms'];
				else
					$log_text = "Changed other payment term value from ".$po_maste_data['other_payment_term'].' to ' .$other_payment_term;
				$log_arr[$log_index]['log_text'] = $log_text;
				$log_arr[$log_index]['po_id'] = $po_id;
				$log_arr[$log_index]['created_at'] = date('Y-m-d H:i:s');
				$log_arr[$log_index]['updated_by'] = $current_user;
				$log_index++;
				$update_po_master_data['other_payment_term'] = $other_payment_term;
 			}
 		}
 		$new_internal_notes= $_POST['internal_notes'];
 		$old_internal_notes = $po_maste_data['internal_notes']; 		
 		if(trim(strtolower($old_internal_notes)) != trim(strtolower($new_internal_notes)))
 		{	
 			if(empty($old_internal_notes))
				$log_text = "Added Internal Notes ". $new_internal_notes;
			else
				$log_text = "Changed Internal Notes from ".$old_internal_notes.' to ' .$new_internal_notes;
			$log_arr[$log_index]['log_text'] = $log_text;
			$log_arr[$log_index]['po_id'] = $po_id;
			$log_arr[$log_index]['created_at'] = date('Y-m-d H:i:s');
			$log_arr[$log_index]['updated_by'] = $current_user;
			$log_index++;
			$update_po_master_data['internal_notes'] = $new_internal_notes;
 		}
 		$new_tracking_number = '';
 		$new_tracking_number = $_POST['tracking_number'];
 		$old_tracking_number = $po_maste_data['tracking_number']; 		
 		if(trim(strtolower($old_tracking_number)) != trim(strtolower($new_tracking_number)))
 		{	
 			if(empty($old_tracking_number)){

				$log_text = "Added Tracking Number ". $new_tracking_number;
 			}
			else
			{
				if($new_tracking_number)
					$log_text = "Changed Tracking Number from ".$old_tracking_number.' to ' .$new_tracking_number;
				else
					$log_text = "Removed Tracking Number ".$old_tracking_number;
			}
			$log_arr[$log_index]['log_text'] = $log_text;
			$log_arr[$log_index]['po_id'] = $po_id;
			$log_arr[$log_index]['created_at'] = date('Y-m-d H:i:s');
			$log_arr[$log_index]['updated_by'] = $current_user;
			$log_index++;
			$update_po_master_data['tracking_number'] = $new_tracking_number;
 		}

 		$new_payment_id = '';
      	$action_arr['controller'] = 'po';
      	$action_arr['action']     = 'payment_id';
      	$check_permission = get_permission_by_action($action_arr);
      	if($check_permission == TRUE){
      		$new_payment_id= $_POST['payment_id'];
	 		$old_payment_id = $po_maste_data['payment_trans_id']; 		
	 		if(trim(strtolower($old_payment_id)) != trim(strtolower($new_payment_id)))
	 		{	
	 			if(empty($old_payment_id))
	 			{
					$log_text = "Added Payment id ". $new_payment_id;
	 			}
				else
				{
					if($new_payment_id)
						$log_text = "Changed Payment id from ".$old_payment_id.' to ' .$new_payment_id;
					else
						$log_text = "Removed Payment id ".$old_payment_id;
				}
				$log_arr[$log_index]['log_text'] = $log_text;
				$log_arr[$log_index]['po_id'] = $po_id;
				$log_arr[$log_index]['created_at'] = date('Y-m-d H:i:s');
				$log_arr[$log_index]['updated_by'] = $current_user;
				$log_index++;
				$update_po_master_data['is_paid'] = 1;
				$update_po_master_data['payment_trans_id'] = $new_payment_id;
	 		}
      	}
	 	
	 	$new_shipping_account_number = '';
	 	$new_shipping_account_number= $_POST['shipping_account_number'];
 		$old_shipping_account_number = $po_maste_data['shipping_account_number']; 		
 		if(trim(strtolower($old_shipping_account_number)) != trim(strtolower($new_shipping_account_number)))
 		{	
 			if(empty($old_shipping_account_number))
 			{
				$log_text = "Added shipping account number ". $new_shipping_account_number;
 			}
			else
			{
				if($new_shipping_account_number)
					$log_text = "Change shipping account number from ".$old_shipping_account_number.' to ' .$new_shipping_account_number;
				else
					$log_text = "Removed shipping account number ".$old_shipping_account_number;
			}
			$log_arr[$log_index]['log_text'] = $log_text;
			$log_arr[$log_index]['po_id'] = $po_id;
			$log_arr[$log_index]['created_at'] = date('Y-m-d H:i:s');
			$log_arr[$log_index]['updated_by'] = $current_user;
			$log_index++;
			$update_po_master_data['shipping_account_number'] = $new_shipping_account_number;
 		}
 		//po master end

 		//address start
 		$addressinfo = array();
 		$deliveryAdd = array();
 		$po_address = $data_old['m_po_address'];
 		foreach($po_address as $addKey => $address){
 			if($address['address_type'] == 1 )
 			{
		 		$new_billing_street1= $_POST['billing_street_address_1'];
		 		$old_billing_street1 = $address['street_address_1']; 		
		 		if(trim(strtolower($old_billing_street1)) != trim(strtolower($new_billing_street1)))
		 		{	
		 			if(empty($old_billing_street1))
						$log_text = "Added billing street address ". $new_billing_street1;
					else
						$log_text = "Changed billing street address from ".$old_billing_street1.' to ' .$new_billing_street1;
					$log_arr[$log_index]['log_text'] = $log_text;
					$log_arr[$log_index]['po_id'] = $po_id;
					$log_arr[$log_index]['created_at'] = date('Y-m-d H:i:s');
					$log_arr[$log_index]['updated_by'] = $current_user;
					$log_index++;
					$addressinfo['street_address_1'] = $new_billing_street1;
					$addressinfo['id'] = $address['id'];
		 		}

		 		$old_billing_street2 = '';
		 		$new_billing_street2= $_POST['billing_street_address_2'];
		 		$old_billing_street2 = $address['street_address_2']; 		
		 		if(trim(strtolower($old_billing_street2)) != trim(strtolower($new_billing_street2)))
		 		{	
		 			if(empty($old_billing_street2))
		 			{
						$log_text = "Added billing street address ". $new_billing_street2;
		 			}
					else
					{
						if($new_billing_street2)
							$log_text = "Changed billing street address 2 from ".$old_billing_street2.' to ' .$new_billing_street2;
						else
							$log_text = "Changed billing street address 2 ".$old_billing_street2;
					}
					$log_arr[$log_index]['log_text'] = $log_text;
					$log_arr[$log_index]['po_id'] = $po_id;
					$log_arr[$log_index]['created_at'] = date('Y-m-d H:i:s');
					$log_arr[$log_index]['updated_by'] = $current_user;
					$log_index++;
					$addressinfo['street_address_2'] = $new_billing_street2;
					$addressinfo['id'] = $address['id'];
		 		}

		 		$bill_country_old = $address['country'];
				$bill_country_new = $_POST['billing_country'];
				if(trim(strtolower($bill_country_old)) != trim(strtolower($bill_country_new)))
				{
					$changed_field = return_change_field($country_list, $bill_country_old, $bill_country_new, 'country_code', 'country_name');
					$log_text = '';
					$log_text = "Changed billing country from ".$changed_field['old_country_name'].' to ' .$changed_field['new_country_name'];
					$log_arr[$log_index]['log_text'] = $log_text;
					$log_arr[$log_index]['po_id'] = $po_id;
					$log_arr[$log_index]['created_at'] = date('Y-m-d H:i:s');
					$log_arr[$log_index]['updated_by'] = $current_user;
					$log_index++;
					$addressinfo['id'] = $address['id'];
					$addressinfo['country'] = $bill_country_new;
				}
				
				$bill_state_new = '';
		 		if(strtolower('US') == strtolower($_POST['billing_country']))
		 			$bill_state_new = $_POST['billing_state'];
		 		else
		 			$bill_state_new = $_POST['billing_state_input'];

		 		$bill_state_old = $address['state'];
		 		if(trim(strtolower($bill_state_old)) != trim(strtolower($bill_state_new)))
				{
					$log_text = $old_field_value = '';
					$state_code_exist = array_search($bill_state_old, array_column($state_list, 'state_code'));
					if(is_numeric($state_code_exist))
					{
				        $state_code_arr = $state_list[$state_code_exist];
				        $log_text = "Changed billing state from ".$state_code_arr['name'].' to ' .$bill_state_new;
					}
					else
					{
						$log_text = "Changed billing state from ".$bill_state_old.' to ' .$bill_state_new;
					}
					$log_arr[$log_index]['log_text'] = $log_text;
					$log_arr[$log_index]['po_id'] = $po_id;
					$log_arr[$log_index]['created_at'] = date('Y-m-d H:i:s');
					$log_arr[$log_index]['updated_by'] = $current_user;
					$log_index++;
					$addressinfo['id'] = $address['id'];
					$addressinfo['state'] = $bill_state_new;
				}

		 		$new_billing_city = $_POST['billing_city'];
		 		$old_billing_city = $address['city']; 		
		 		if(trim(strtolower($old_billing_city)) != trim(strtolower($new_billing_city)))
		 		{	
		 			if(empty($old_billing_city))
						$log_text = "Added billing city  ". $new_billing_city;
					else
						$log_text = "Changed billing city from ".$old_billing_city.' to ' .$new_billing_city;
					$log_arr[$log_index]['log_text'] = $log_text;
					$log_arr[$log_index]['po_id'] = $po_id;
					$log_arr[$log_index]['created_at'] = date('Y-m-d H:i:s');
					$log_arr[$log_index]['updated_by'] = $current_user;
					$log_index++;
					$addressinfo['city'] = $new_billing_city;
					$addressinfo['id'] = $address['id'];
		 		}

		 		$new_billing_zipcode= $_POST['billing_zipcode'];
		 		$old_billing_zipcode = $address['zipcode']; 		
		 		if(trim(strtolower($old_billing_zipcode)) != trim(strtolower($new_billing_zipcode)))
		 		{	
		 			if(empty($old_billing_zipcode))
						$log_text = "Added billing zip code  ". $new_billing_zipcode;
					else
						$log_text = "Changed billing zip code from ".$old_billing_zipcode.' to ' .$new_billing_zipcode;
					$log_arr[$log_index]['log_text'] = $log_text;
					$log_arr[$log_index]['po_id'] = $po_id;
					$log_arr[$log_index]['created_at'] = date('Y-m-d H:i:s');
					$log_arr[$log_index]['updated_by'] = $current_user;
					$log_index++;
					$addressinfo['zipcode'] = $new_billing_zipcode;
					$addressinfo['id'] = $address['id'];
		 		}
		 		$new_billing_telephone = '';
		 		$new_billing_telephone = $_POST['billing_contact'];
		 		$old_billing_telephone = $address['telephone']; 		
		 		if(trim(strtolower($old_billing_telephone)) != trim(strtolower($new_billing_telephone)))
		 		{	
		 			if(empty($old_billing_telephone))
		 			{
						$log_text = "Added billing contact ". $new_billing_telephone;
		 			}
					else
					{
						if($new_billing_telephone)
							$log_text = "Changed billing contact from ".$old_billing_telephone.' to ' .$new_billing_telephone;
						else
							$log_text = "Removed billing contact ".$old_billing_telephone;
					}
					$log_arr[$log_index]['log_text'] = $log_text;
					$log_arr[$log_index]['po_id'] = $po_id;
					$log_arr[$log_index]['created_at'] = date('Y-m-d H:i:s');
					$log_arr[$log_index]['updated_by'] = $current_user;
					$log_index++;
					$addressinfo['telephone'] = $new_billing_telephone;
					$addressinfo['id'] = $address['id'];
		 		}

		 		$new_billing_fax = '';
		 		$new_billing_fax = $_POST['billing_fax'];
		 		$old_billing_fax = $address['fax']; 		
		 		if(trim(strtolower($old_billing_fax)) != trim(strtolower($new_billing_fax)))
		 		{	
		 			if(empty($old_billing_fax))
		 			{
						$log_text = "Added billing fax ". $new_billing_fax;
		 			}
					else
					{
						if($new_billing_fax)
							$log_text = "Changed billing fax from ".$old_billing_fax.' to ' .$new_billing_fax;
						else
							$log_text = "Removed billing fax ".$old_billing_fax;
					}
					$log_arr[$log_index]['log_text'] = $log_text;
					$log_arr[$log_index]['po_id'] = $po_id;
					$log_arr[$log_index]['created_at'] = date('Y-m-d H:i:s');
					$log_arr[$log_index]['updated_by'] = $current_user;
					$log_index++;
					$addressinfo['fax'] = $new_billing_fax;
					$addressinfo['id'] = $address['id'];
		 		}
 			}
 			else if($address['address_type'] == 2)
 			{
 				$old_shipping_street1 = '';
	 			$new_delivery_street1= $_POST['shipping_street_address_1'];
		 		$old_shipping_street1 = $address['street_address_1']; 		
		 		if(trim(strtolower($old_shipping_street1)) != trim(strtolower($new_delivery_street1)))
		 		{	
		 			if(empty($old_shipping_street1))
		 			{
						$log_text = "Added delivery street address ". $new_delivery_street1;
		 			}
					else
					{
						if($old_shipping_street1)
							$log_text = "Changed delivery street addressr from ".$old_shipping_street1.' to ' .$new_delivery_street1;
						else
							$log_text = "Removed delivery street addressr ".$old_shipping_street1;
					}
					$log_arr[$log_index]['log_text'] = $log_text;
					$log_arr[$log_index]['po_id'] = $po_id;
					$log_arr[$log_index]['created_at'] = date('Y-m-d H:i:s');
					$log_arr[$log_index]['updated_by'] = $current_user;
					$log_index++;
					$deliveryAdd['street_address_1'] = $new_delivery_street1;
					$deliveryAdd['id'] = $address['id'];
		 		}
		 		$new_shipping_street2 = '';
		 		$new_shipping_street2 = $_POST['shipping_street_address_2'];
		 		$old_shipping_street2 = $address['street_address_2']; 		
		 		if(trim(strtolower($old_shipping_street2)) != trim(strtolower($new_shipping_street2)))
		 		{	
		 			if(empty($old_shipping_street2))
		 			{
						$log_text = "Added delivery street address ". $new_shipping_street2;
		 			}
					else
					{
						if($new_shipping_street2)
							$log_text = "Changed delivery street address from ".$old_shipping_street2.' to ' .$new_shipping_street2;
						else
							$log_text = "Removed delivery street address 2 ".$old_shipping_street2;
					}
					$log_arr[$log_index]['log_text'] = $log_text;
					$log_arr[$log_index]['po_id'] = $po_id;
					$log_arr[$log_index]['created_at'] = date('Y-m-d H:i:s');
					$log_arr[$log_index]['updated_by'] = $current_user;
					$log_index++;
					$deliveryAdd['street_address_2'] = $new_shipping_street2;
					$deliveryAdd['id'] = $address['id'];
		 		}

		 		$shipping_country_old = $address['country'];
				$shipping_country_new = $_POST['shipping_country'];
				if(trim(strtolower($shipping_country_old)) != trim(strtolower($shipping_country_new)))
				{
					$changed_field = return_change_field($country_list, $shipping_country_old, $shipping_country_new, 'country_code', 'country_name');
					$log_text = '';
					$log_text = "Changed delivery country from ".$changed_field['old_country_name'].' to ' .$changed_field['new_country_name'];
					$log_arr[$log_index]['log_text'] = $log_text;
					$log_arr[$log_index]['po_id'] = $po_id;
					$log_arr[$log_index]['created_at'] = date('Y-m-d H:i:s');
					$log_arr[$log_index]['updated_by'] = $current_user;
					$log_index++;
					$deliveryAdd['id'] = $address['id'];
					$deliveryAdd['country'] = $shipping_country_new;
				}

		 		$new_shipping_city= $_POST['shipping_city'];
		 		$old_shipping_city = $address['city']; 		
		 		if(trim(strtolower($old_shipping_city)) != trim(strtolower($new_shipping_city)))
		 		{	
		 			if(empty($old_shipping_city))
						$log_text = "Added delivery city  ". $new_shipping_city;
					else
						$log_text = "Changed delivery city from ".$old_shipping_city.' to ' .$new_shipping_city;
					$log_arr[$log_index]['log_text'] = $log_text;
					$log_arr[$log_index]['po_id'] = $po_id;
					$log_arr[$log_index]['created_at'] = date('Y-m-d H:i:s');
					$log_arr[$log_index]['updated_by'] = $current_user;
					$log_index++;
					$deliveryAdd['city'] = $new_shipping_city;
					$deliveryAdd['id'] = $address['id'];
		 		}

		 		$new_shipping_zipcode= $_POST['shipping_zipcode'];
		 		$old_shipping_zipcode = $address['zipcode']; 		
		 		if(trim(strtolower($old_shipping_zipcode)) != trim(strtolower($new_shipping_zipcode)))
		 		{	
		 			if(empty($old_shipping_zipcode))
						$log_text = "Added delivery zip code  ". $new_shipping_zipcode;
					else
						$log_text = "Changed delivery zip code from ".$old_shipping_zipcode.' to ' .$new_shipping_zipcode;
					$log_arr[$log_index]['log_text'] = $log_text;
					$log_arr[$log_index]['po_id'] = $po_id;
					$log_arr[$log_index]['created_at'] = date('Y-m-d H:i:s');
					$log_arr[$log_index]['updated_by'] = $current_user;
					$log_index++;
					$deliveryAdd['zipcode'] = $new_shipping_zipcode;
					$deliveryAdd['id'] = $address['id'];
		 		}

		 		$new_shipping_telephone = '';
		 		$new_shipping_telephone = $_POST['shipping_contact'];
		 		$old_shipping_telephone = $address['telephone']; 		
		 		if(trim(strtolower($old_shipping_telephone)) != trim(strtolower($new_shipping_telephone)))
		 		{	
		 			if(empty($old_shipping_telephone))
		 			{
						$log_text = "Added delivery contact ". $new_shipping_telephone;
		 			}
					else
					{
						if($new_shipping_telephone)
							$log_text = "Changed delivery contact from ".$old_shipping_telephone.' to ' .$new_shipping_telephone;
						else
							$log_text = "Removed delivery contact ".$old_shipping_telephone;
					}
					$log_arr[$log_index]['log_text'] = $log_text;
					$log_arr[$log_index]['po_id'] = $po_id;
					$log_arr[$log_index]['created_at'] = date('Y-m-d H:i:s');
					$log_arr[$log_index]['updated_by'] = $current_user;
					$log_index++;
					$deliveryAdd['telephone'] = $new_shipping_telephone;
					$deliveryAdd['id'] = $address['id'];
		 		}
		 		$new_shipping_fax = '';
		 		$new_shipping_fax = $_POST['shipping_fax'];
		 		$old_shipping_fax = $address['fax']; 		
		 		if(trim(strtolower($old_shipping_fax)) != trim(strtolower($new_shipping_fax)))
		 		{	
		 			if(empty($old_shipping_telephone))
		 			{
						$log_text = "Added delivery fax ". $new_shipping_fax;
		 			}
					else
					{
						if($new_shipping_fax)
							$log_text = "Changed delivery fax from ".$old_shipping_fax.' to ' .$new_shipping_fax;
						else
							$log_text = "Removed delivery fax ".$old_shipping_fax;
					}
					$log_arr[$log_index]['log_text'] = $log_text;
					$log_arr[$log_index]['po_id'] = $po_id;
					$log_arr[$log_index]['created_at'] = date('Y-m-d H:i:s');
					$log_arr[$log_index]['updated_by'] = $current_user;
					$log_index++;
					$deliveryAdd['fax'] = $new_shipping_fax;
					$deliveryAdd['id'] = $address['id'];
		 		}

		 		$ship_state_new = '';
		 		if(strtolower('US') == strtolower($_POST['shipping_country']))
		 			$ship_state_new = $_POST['shipping_state'];
		 		else
		 			$ship_state_new = $_POST['shipping_state_input'];

		 		$ship_state_old = $address['state'];
		 		if(trim(strtolower($ship_state_old)) != trim(strtolower($ship_state_new)))
				{
					$log_text = $old_field_value = '';
					$state_code_exist = array_search($ship_state_old, array_column($state_list, 'state_code'));
					if(is_numeric($state_code_exist))
					{
				        $state_code_arr = $state_list[$state_code_exist];
				        $log_text = "Changed delivery state from ".$state_code_arr['name'].' to ' .$ship_state_new;
					}
					else
					{
						$log_text = "Changed delivery state from ".$ship_state_old.' to ' .$ship_state_new;
					}
					$log_arr[$log_index]['log_text'] = $log_text;
					$log_arr[$log_index]['po_id'] = $po_id;
					$log_arr[$log_index]['created_at'] = date('Y-m-d H:i:s');
					$log_arr[$log_index]['updated_by'] = $current_user;
					$log_index++;
					$deliveryAdd['id'] = $address['id'];
					$deliveryAdd['state'] = $ship_state_new;
				}
 			}
 		}//address end
 		$price_permission = $price_per_unit = '';
		$action_arr['controller'] = 'po';
		$action_arr['action']     = 'prices';
		$price_permission = get_permission_by_action($action_arr);
 		//po items
 		$po_itemsAr = $data_old['po_items'];
        $sku = $_POST['sku'];
        $description = $_POST['description'];
        $qty = $_POST['qty'];
        /*Check view price permission*/
        if($price_permission == TRUE)
        {
	        if(isset($_POST['price_per_unit']))
	        {
	        	$price_per_unit = $_POST['price_per_unit'];
	        }
        }
        $items_arr = $update_items_arr = $items_arr_log = array();
        $item_index = $subtotal = 0;
        for ($i = 0; $i < count($sku); $i++)
        {
        	$total_row_price = 0;
            if(!empty($sku[$i]) && !empty($qty[$i]))
            {
                $exist_items = '';
                if(isset($po_itemsAr[$i]))
                {
                	if($price_permission == TRUE)
                		$subtotal = $subtotal + ($qty[$i] * number_format((float)$price_per_unit[$i], 2, '.', ''));
                	else
                		$subtotal = $subtotal + ($qty[$i] * $po_itemsAr[$i]['price']);

                    $exist_items = $po_itemsAr[$i];
                    if(trim(strtolower($sku[$i])) != trim(strtolower($po_itemsAr[$i]['sku'])))
                    {
                        $log_arr[$item_index]['log_text'] = 'Changed sku from '.$po_itemsAr[$i]['sku']. ' to '. $sku[$i];
                        $log_arr[$item_index]['po_id'] = $po_id;
                        $log_arr[$item_index]['updated_by'] = $current_user;
                        $log_arr[$item_index]['created_at'] = date('Y-m-d H:i:s');
                        $update_items_arr[$i]['id'] = $po_itemsAr[$i]['id'];
                        $update_items_arr[$i]['sku'] = $sku[$i];
                        $item_index++;
                    }
                    if(trim($description[$i]) != trim($po_itemsAr[$i]['description']))
                    {
                    	if (empty($po_itemsAr[$i]['description']))
                    	{
                    		$log_arr[$item_index]['log_text'] = 'Added description '.$description[$i];
                    	}
                    	else
                    	{
	                    	if($description[$i])
	                        	$log_arr[$item_index]['log_text'] = 'Changed description from '.$po_itemsAr[$i]['description']. ' to '. $description[$i];
	                        else
	                        	$log_arr[$item_index]['log_text'] = 'Removed description '.$po_itemsAr[$i]['description'];
                    	}
                        $log_arr[$item_index]['po_id'] = $po_id;
                        $log_arr[$item_index]['updated_by'] = $current_user;
                        $log_arr[$item_index]['created_at'] = date('Y-m-d H:i:s');
                        $update_items_arr[$i]['id'] = $po_itemsAr[$i]['id'];
                        $update_items_arr[$i]['description'] = $description[$i];
                        $item_index++;
                    }
                    if(trim($qty[$i]) != trim($po_itemsAr[$i]['qty']))
                    {
                        $log_arr[$item_index]['log_text'] = 'Changed quantity from '.$po_itemsAr[$i]['qty']. ' to '. $qty[$i];
                        $log_arr[$item_index]['po_id'] = $po_id;
                        $log_arr[$item_index]['updated_by'] = $current_user;
                        $log_arr[$item_index]['created_at'] = date('Y-m-d H:i:s');
                        $update_items_arr[$i]['id'] = $po_itemsAr[$i]['id'];
                        $update_items_arr[$i]['qty'] = $qty[$i];
                        $item_index++;
                    }
                    if($price_permission == TRUE)
                    {
	                    if(trim(strtolower($price_per_unit[$i])) != trim(strtolower($po_itemsAr[$i]['price'])))
	                    {
	                        $log_arr[$item_index]['log_text'] = 'Changed price from $'.$po_itemsAr[$i]['price']. ' to $'. number_format((float)$price_per_unit[$i], 2, '.', '');
	                        $log_arr[$item_index]['po_id'] = $po_id;
	                        $log_arr[$item_index]['updated_by'] = $current_user;
	                        $log_arr[$item_index]['created_at'] = date('Y-m-d H:i:s');
	                        $update_items_arr[$i]['id'] = $po_itemsAr[$i]['id'];
	                        $update_items_arr[$i]['price'] = $price_per_unit[$i];
	                        $item_index++;
	                    }
                    }
                }
                else
                {   
                	$subtotal = $subtotal + ($qty[$i] * $price_per_unit[$i]);
                	//Add new row for items
                    $log_arr[$item_index]['log_text'] = 'Added sku '. $sku[$i];
                    $log_arr[$item_index]['po_id'] = $po_id;
                    $log_arr[$item_index]['updated_by'] = $current_user;
                    $log_arr[$item_index]['created_at'] = date('Y-m-d H:i:s');
                    $items_arr[$i]['sku'] = $sku[$i];
                    $item_index++;

                    $log_arr[$item_index]['log_text'] = 'Added quantity '. $qty[$i];
                    $log_arr[$item_index]['po_id'] = $po_id;
                    $log_arr[$item_index]['updated_by'] = $current_user;
                    $log_arr[$item_index]['created_at'] = date('Y-m-d H:i:s');
                    $items_arr[$i]['qty'] = $qty[$i];
                    $item_index++;
                    $items_arr[$i]['po_id'] = $po_id;

                    if(isset($description[$i]) && !empty($description[$i]))
                    {
                        $log_arr[$item_index]['log_text'] = 'Added description '.$description[$i];
                        $log_arr[$item_index]['po_id'] = $po_id;
                        $log_arr[$item_index]['updated_by'] = $current_user;
                        $log_arr[$item_index]['created_at'] = date('Y-m-d H:i:s');
                        $items_arr[$i]['description'] = $description[$i];
                        $item_index++;
                    }
                    if($price_permission == TRUE)
                    {
	                    $log_arr[$item_index]['log_text'] = 'Added price $'.$price_per_unit[$i];
	                    $log_arr[$item_index]['po_id'] = $po_id;
	                    $log_arr[$item_index]['updated_by'] = $current_user;
	                    $log_arr[$item_index]['created_at'] = date('Y-m-d H:i:s');
	                    $items_arr[$i]['price'] = $price_per_unit[$i];
	                    $item_index++;
	                    $items_arr[$i]['created_at'] = date('Y-m-d H:i:s');
	                    $items_arr[$i]['updated_at'] = date('Y-m-d H:i:s');
                    }
                }
            }
        }
        $subtotal = number_format((float)$subtotal, 2, '.', '');
        if($subtotal != $po_maste_data['po_amount'])
        {
        	$log_arr[$item_index]['log_text'] = 'Change total amount from '. $po_maste_data['po_amount'].' to '. $subtotal;
            $log_arr[$item_index]['po_id'] = $po_id;
            $log_arr[$item_index]['updated_by'] = $current_user;
            $log_arr[$item_index]['created_at'] = date('Y-m-d H:i:s');
            $item_index++;
        }
        $update_po_master_data['po_amount'] = $subtotal;
        $finalAr = array();
        if(!empty($update_items_arr))
        	$finalAr['updated_itemAr'] = $update_items_arr;
        if(!empty($items_arr))
        	$finalAr['new_itemAr'] = $items_arr;
        if(!empty($addressinfo))
        	$finalAr['po_address'][] = $addressinfo;
        if(!empty($deliveryAdd))
        	$finalAr['po_address'][] = $deliveryAdd;
        if(!empty($update_po_master_data))
        	$finalAr['po_master'] = $update_po_master_data;
        if(!empty($log_arr))
        	$finalAr['log_arr'] = $log_arr;
        return $finalAr;
    }
    /*
	 * Function to get differnce
	 */
	if (!function_exists('return_change_field')) {
		function return_change_field($customer_list, $exist_customer_id, $customer_id, $column ,$field)
		{
			$log_arr = array();
			$old_field_value = $new_field_value = '';
			$customer_old = array_search($exist_customer_id, array_column($customer_list, $column));
			if(is_numeric($customer_old))
			{
		        $customer_old = $customer_list[$customer_old];
		        $old_field_value = $customer_old[$field];
			}
	        $log_arr['old_'.$field] = $old_field_value;

	        $customer_new = array_search($customer_id, array_column($customer_list, $column));
	        if(is_numeric($customer_new))
	        {
		        $customer_new = $customer_list[$customer_new];
		        $new_field_value = $customer_new[$field];
	        }
	        $log_arr['new_'.$field] = $new_field_value;
	        return $log_arr;
		}
	}
}

?>