<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require 'vendor/autoload.php';
use Dompdf\Dompdf;
class Po extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		auth_check();
		$this->load->model('vendor/vendor_model', 'vendor_model');
		$this->load->model('admin/user_model', 'user_model');
		$this->load->model('po/po_model', 'po_model');
		$this->load->model('configuration/configuration_model', 'configuration_model');
		$this->load->model('shipping/shipping_model', 'shipping_model');
		$this->load->helper('log');
		$this->load->model('log/log_model', 'log_model');
	}

	/*
	 * Get all po list
	 */
	public function list()
	{
		$chechPermission = get_controller_and_action();
		if($chechPermission == FALSE)
		{
			redirect(base_url('access'));
		}
		$data['title'] = 'Po List';
		$this->load->view('admin/includes/_header', $data);
		$this->load->view('po/po_list');
		$this->load->view('admin/includes/_footer');	
	}
	/*
	 * Use in Po List for datatable
	 */
	public function datatable_json()
	{
		$edit_link = '';
		$action_arr['controller'] = 'po';
        $action_arr['action']     = 'list';
        $check_permission = get_permission_by_action($action_arr);
		$list = $this->po_model->get_datatables();
        $data = array();
        $no = 0;
        /* Check access permission
         * Restricted direct the url
         */
        if($check_permission == FALSE)
        	redirect(base_url('access'));

        $price_permission = $edit_permission = '';
		$action_arr['controller'] = 'po';
		$action_arr['action']     = 'prices';
		$price_permission = get_permission_by_action($action_arr);

		$edit_action_arr['controller'] = 'po';
        $edit_action_arr['action']     = 'edit';
        $edit_permission = get_permission_by_action($edit_action_arr);

        foreach ($list as $po_list)
        {
        	$inv_status = '';
            $row = array();
            $row[] = $po_list->created_at;
            $row[] = $po_list->po_number;
            $row[] = $po_list->vendor_name;
            $row[] = $po_list->tracking_number;
            if($price_permission == TRUE)
            	$row[] = '$'.$po_list->po_amount;
            else
            	$row[] = '-';
            $paid_status = 'No';
            if($po_list->is_paid == 1)
            {
            	$row[] = 'Yes';
            	$po_status = "<img src=".base_url()."assets/img/checkbox.png class='invoice-grid-checkboxes'>";
            }
            else
            {
            	$row[] = $paid_status;
            	$po_status = "<img src=".base_url()."assets/img/uncheckbox.png class='invoice-grid-checkboxes'>";
            }

            if($po_list->is_posted == 1)
            	$row[] = "<img src=".base_url()."assets/img/checkbox.png class='invoice-grid-checkboxes'>";
            else
            	$row[] = "<img src=".base_url()."assets/img/uncheckbox.png class='invoice-grid-checkboxes'>";          

            if($po_list->is_landed == 1)
            	$row[] = "<img src=".base_url()."assets/img/checkbox.png class='invoice-grid-checkboxes'>";
            else
            	$row[] = "<img src=".base_url()."assets/img/uncheckbox.png class='invoice-grid-checkboxes'>";      	

            if($po_list->is_received == 1)
            	$row[] = "<img src=".base_url()."assets/img/checkbox.png class='invoice-grid-checkboxes'>";
            else
            	$row[] = "<img src=".base_url()."assets/img/uncheckbox.png class='invoice-grid-checkboxes'>";

            if($po_list->is_discrepancy == 1)
            	$row[] = "<img src=".base_url()."assets/img/checkbox.png class='invoice-grid-checkboxes'>";
            else
            	$row[] = "<img src=".base_url()."assets/img/uncheckbox.png class='invoice-grid-checkboxes'>";
 
            if($po_list->is_closed == 1)
            	$row[] = "<img src=".base_url()."assets/img/checkbox.png class='invoice-grid-checkboxes'>";
            else
            	$row[] = "<img src=".base_url()."assets/img/uncheckbox.png class='invoice-grid-checkboxes'>";

            if($edit_permission == TRUE)
            	$link = '<a title="Edit" class="update btn btn-sm btn-warning" href="'.base_url('po/edit/'.$po_list->id).'"> <i class="fa fa-pencil-square-o"></i></a>';
            elseif($check_permission == TRUE)
            	$link = '<a title="View" class="update btn btn-sm btn-warning" href="'.base_url('po/view/'.$po_list->id).'"> <i class="fa fa-pencil-square-o"></i></a>';
            else
            	$link = '-';
            $row[] = $link;
            $data[] = $row;
        }
        $output = array(
                    "recordsTotal" => $this->po_model->count_all(),
                    "recordsFiltered" => $this->po_model->count_filtered(),
                    "data" => $data,
                );
        echo json_encode($output);
        exit;
	}

	/**
	 *  create po
	 */
	public function create()
	{
		$action_arr['controller'] = 'po';
        $action_arr['action']     = 'create';
        $check_permission = get_permission_by_action($action_arr);
        /* Check access permission
         * Restricted direct the url
         */
        if($check_permission == FALSE)
        {
        	redirect(base_url('access'));
        }
        else
        {
        	$singleArray = []; 
			$shippingConfigAddress = get_configuration_group_data(2);
			if(!empty($shippingConfigAddress)){
			foreach ($shippingConfigAddress as $key => $childArray) 
			{ 
				$singleArray[$childArray['code']] = $childArray['value'];
			}
		}
		$current_user = $this->session->userdata('id');
		$data['defaultDeliveryAddress'] = $singleArray;
		$data['title'] = 'Create PO';
		$this->load->view('admin/includes/_header', $data);
		$vendorList = $this->vendor_model->get_all_vendor_list();
		$state_list = $this->user_model->get_state_list();
		$data['state_list'] = $state_list;
		$data['vendorList'] = $vendorList;
		$shipping_method_list = $this->shipping_model->get_methods($company = '');
		$data['shipping_method_list'] = $shipping_method_list;
		
			if($this->input->post('save_for_later') || $this->input->post('save_and_post') || $this->input->post('save_post_email') || $this->input->post('save_pdf'))
			{
				$this->form_validation->set_rules('email', 'Email', 'trim|required');
				$this->form_validation->set_rules('vendor', 'Vendor', 'trim|required');
				$this->form_validation->set_rules('billing_street_address_1', 'Address Street ', 'trim|required');
				$this->form_validation->set_rules('billing_city', 'Address City', 'trim|required');
				$this->form_validation->set_rules('billing_zipcode', 'Address Zip Code', 'trim|required');
				$this->form_validation->set_rules('shipping_street_address_1', 'Delivery Street', 'trim|required');
				$this->form_validation->set_rules('shipping_city', 'Delivery City', 'trim|required');
				$this->form_validation->set_rules('shipping_country', 'Delivery Country ', 'trim|required');
				$this->form_validation->set_rules('shipping_zipcode', 'Delivery Zip Code', 'trim|required');
				$this->form_validation->set_rules('shipping_company', 'Shipping Company', 'trim|required');

				$billing_country = $this->input->post('billing_country');
				if(strtolower($billing_country) == strtolower('US'))
					$this->form_validation->set_rules('billing_state', 'Address State', 'trim|required');
				else
					$this->form_validation->set_rules('billing_state_input', 'Address State', 'trim|required');

				$shipping_country = $this->input->post('shipping_country');
				if(strtolower($shipping_country) == strtolower('US'))
					$this->form_validation->set_rules('shipping_state', 'Delivery State', 'trim|required');
				else
					$this->form_validation->set_rules('shipping_state_input', 'Delivery State', 'trim|required');

				//document  pdf start
				$po_attachment = array();
				$po_file_name = '';
				$upload_doc_arr = array();
				$other_document = $this->input->post('other_document');
				$file_count = 0;
				$other_file_upload_error = '';

				if(isset($_FILES['other_document']['name'][0]) && empty($_FILES['other_document']['name'][0]))
					$other_file_upload_error = TRUE;
				if(isset($_FILES['other_document']['name']) && !empty($_FILES['other_document']['name']))
				{
					$config['upload_path']   = FCPATH.$this->config->item('po_path');
				    $config['allowed_types'] = $this->config->item('po_allowed_types');
				    // $config['allowed_types'] = '*';
				    $config['max_size'] = $this->config->item('po_max_size');
				    //$config['encrypt_name'] = TRUE;
					if(!is_dir($config['upload_path']))
					  mkdir($config['upload_path'], 0777, true);

					for ($file_count = 0; $file_count < count($_FILES['other_document']['name']); $file_count++)
					{
						if(isset($_FILES['other_document']['name'][$file_count]) && !empty($_FILES['other_document']['name'][$file_count]))
						{
							$_FILES['file']['name']     = time().'_'.$_FILES['other_document']['name'][$file_count];
				            $_FILES['file']['type']     = $_FILES['other_document']['type'][$file_count];
				            $_FILES['file']['tmp_name'] = $_FILES['other_document']['tmp_name'][$file_count];
				            $_FILES['file']['error']    = $_FILES['other_document']['error'][$file_count];
				            $_FILES['file']['size']     = $_FILES['other_document']['size'][$file_count];
							$other_doc_file = array();
						    /* Call file upload helper function */
							$other_doc_file = file_upload('file', $config);
							$invoice_file_name = '';
							if(!empty($other_doc_file))
							{
								if(isset($other_doc_file['error']))
								{
									$other_file_upload_error = FALSE;
									$this->session->set_flashdata('error', $other_doc_file['error']);
									break;
								}										
								if(isset($other_doc_file['file_name']))
								{
									$other_file_upload_error = TRUE;
									$po_attachment[$file_count]['po_attachment'] = time().'_'.$other_doc_file['file_name'];
									$po_attachment[$file_count]['document_type'] = 4;
								}
							}
						}
					}
				}
				$document_label = array();
				$document_label_count = 0;
				$document_label = $this->input->post('documentlable');
				foreach($document_label as $lable){
					if(!empty($lable))
						$document_label_count++;
				}
				$po_attachment_count = 0;
				if(!empty($po_attachment))
					$po_attachment_count = count($po_attachment);

				if($this->form_validation->run() == FALSE || (isset($other_file_upload_error) && $other_file_upload_error == FALSE) || ($document_label_count != $po_attachment_count) )
				{
					$error_arr = array('errors' => validation_errors());
					$this->session->set_flashdata('errors', $error_arr['errors']);
					$this->load->view('po/po_create', $data);
				}
				else
				{					
					$vendor_id = $email = $tracking_number = $payment_id = $billing_street_address_1 = $billing_street_address_2 = $billing_city = $billing_state_input = $billing_state = $billing_zipcode = $billing_contact = $billing_fax = $payment_term = $other_terms = $shipping_company = $shipping_method = $shipping_account_number = $internal_notes = $is_paid =  $is_posted = $is_landed = $is_received = $is_discrepancy = $is_closed = $created_by = $internal_notes = $shipping_street_address_1 = $shipping_street_address_2 = $shipping_city = $shipping_state_input = $shipping_state = $shipping_zipcode = $shipping_contact = $shipping_fax = '';
					
					//randam generated po number
					$po_number = '';
					$lastPoNumber = $this->po_model->getLastPoNumber();
					if($lastPoNumber->po_number == 0 || !$lastPoNumber)
						$po_number = 100000;
					else
						$po_number = $lastPoNumber->po_number+1;

					$created_by = $this->session->userdata('id');
					$vendor_id =  $this->input->post('vendor');
					$email = $this->input->post('email');
					$tracking_number = $this->input->post('tracking_number');
					
					$billing_street_address_1 = $this->input->post('billing_street_address_1');
					$billing_street_address_2 = $this->input->post('billing_street_address_2');
					$billing_city = $this->input->post('billing_city');
					
					$billing_state_input = $this->input->post('billing_state_input');
					$billing_state = $this->input->post('billing_state');
					$billing_zipcode = $this->input->post('billing_zipcode');
					$billing_contact = $this->input->post('billing_contact');
					$billing_fax = $this->input->post('billing_fax');
					$payment_term = $this->input->post('payment_term');
					if(trim(strtolower($payment_term)) == trim(strtolower('Other')))
						$other_terms = $this->input->post('other_terms');
					
					$shipping_company = $this->input->post('shipping_company');
					if(trim(strtolower($shipping_company)) == trim(strtolower('Other')))
						$shipping_method = $this->input->post('other_shipping_method');
					else
						$shipping_method = $this->input->post('shipping_method');

					$shipping_account_number = $this->input->post('shipping_account_number');
					$internal_notes = $this->input->post('internal_notes');
					$shipping_street_address_1 = $this->input->post('shipping_street_address_1');
					$shipping_street_address_2 = $this->input->post('shipping_street_address_2');
					$shipping_city = $this->input->post('shipping_city');
					$shipping_state_input = $this->input->post('shipping_state_input');
					$shipping_state = $this->input->post('shipping_state');
					$shipping_zipcode = $this->input->post('shipping_zipcode');
					$shipping_contact = $this->input->post('shipping_contact');
					$shipping_fax = $this->input->post('shipping_fax');
					$shipping_acc_no  = $this->input->post('shipping_account_number');
					if($this->input->post('payment_id'))
						$payment_id = $this->input->post('payment_id');
					
					if(!empty($payment_id))
						$is_paid = 1;
					
					$is_landed = $is_discrepancy = $is_received = $is_posted = $is_closed = 0;
					//add item code start
					$sku = $this->input->post('sku');
					$qty = $this->input->post('qty');
					$description = $this->input->post('description');
					$price_per_unit = $this->input->post('price_per_unit');
					$items_arr = array();
					$subtotal = 0;
					if(count($sku) > 0 && count($qty) > 0 && count($price_per_unit))
					{
						$items_arr['sku'] = $sku;
						$items_arr['qty'] = $qty;
						$items_arr['price'] = $price_per_unit;
						$items_arr['description'] = $description;
						for($i = 0; $i < count($sku); $i++)
						{
							if(!empty($sku[$i]) && !empty($qty[$i]) && $price_per_unit[$i])
								$subtotal = $subtotal + ($qty[$i] * number_format((float)$price_per_unit[$i], 2, '.', ''));
						}
					}

					if($this->input->post('save_and_post') || $this->input->post('save_post_email'))
						$is_posted = 1;

					//add item code end
					$po_master_data = array(
						'vendor_id' => $vendor_id,
						'po_number' => $po_number,
						'email'=> $email,
						'shipping_company' => $shipping_company,
						'shipping_method' => $shipping_method,
						'internal_notes' => trim($internal_notes),
						'payment_trans_id' => $payment_id,
						'payment_term'=> $payment_term,
						'other_payment_term' => $other_terms,
						'tracking_number' => trim($tracking_number),
						'shipping_account_number' => $shipping_acc_no,
						'po_amount' => $subtotal,
						'is_posted' => $is_posted,
						'is_landed' => $is_landed,
						'is_received' => $is_received,
						'is_discrepancy' => $is_discrepancy,
						'is_closed' => $is_closed,
						'is_paid' => $is_paid,
						'created_by' => $created_by,
						'created_at' => CURRENT_DATE_TIME,
						'updated_at' => CURRENT_DATE_TIME,
						'received_at'=> CURRENT_DATE_TIME
					);					
					//Address code Start
					$shippingState = '';
					$billingState = '';
					if(trim(strtolower($shipping_country))==trim(strtolower('US')))
						$shippingState = $shipping_state;
					else
						$shippingState = $shipping_state_input;

					if(trim(strtolower($billing_country))==trim(strtolower('US')))
						$billingState = $billing_state;
					else
						$billingState = $billing_state_input;
					
					$m_po_address = $log_arr = array();
					$m_po_address_billing = array(
						'address_type' => 1,
						'vendor_id' => $vendor_id,
						'street_address_1' => $billing_street_address_1,
						'street_address_2' => $billing_street_address_2,
						'city' => $billing_city,
						'zipcode' => $billing_zipcode,
						'telephone' => $billing_contact,
						'fax' => $billing_fax,
						'state' => $billingState,
						'country' => $billing_country
						);
					$m_po_address[] = $m_po_address_billing; 
					$m_po_address_shipping = array(
						'address_type' => 2,
						'vendor_id' => $vendor_id,
						'street_address_1' => $shipping_street_address_1,
						'street_address_2' => $shipping_street_address_2,
						'city' => $shipping_city,
						'zipcode' => $shipping_zipcode,
						'telephone' => $shipping_contact,
						'fax' => $shipping_fax,
						'state' => $shippingState,
						'country' => $shipping_country
						);
					$m_po_address[] = $m_po_address_shipping;
					//Address code end
					$result = $this->po_model->save_po_data($po_master_data,$items_arr,$m_po_address,$po_attachment,$document_label);

					if(isset($result['po_id']) && !empty($result['po_id']))
					{
						$log_index = 0;
						$log_arr[$log_index]['log_text'] = "Purchase order save successfully";
						$log_arr[$log_index]['po_id'] = $result['po_id'];
						$log_arr[$log_index]['updated_by'] = $current_user;
						$log_arr[$log_index]['created_at'] = date('Y-m-d H:i:s');
						$log_index++;
						if($this->input->post('save_post_email'))
						{
							$log_msg = 'Purchase order save and sent email to vendor successfully';	
							$this->create_po_pdf($email, $result['po_id'], 'F');
						}
						else
						{
							$log_msg = 'Purchase order save successfully';
						}
						$this->session->set_flashdata('success', $log_msg);

						/*START Use to create PO in QuickBooks desktop application*/
						if($is_posted == 1)
						{
							$qb_log_res = '';
							$this->load->helper('quickbook');
							$qb_result = add_po($result['po_id'], $po_master_data, $items_arr, $m_po_address);
							if(isset($qb_result['qb_po_id']) && !empty($qb_result['qb_po_id']))
							{
								$update_po = array('qb_po_id' => $qb_result['qb_po_id']);
								$this->po_model->update_po($result['po_id'], $update_po);
								$qb_log_res = "PO sync to QB application";
							}
							if(isset($qb_result['message']))
							{
								$qb_log_res = 'QB Error: '. $qb_result['message'];
								$this->session->set_flashdata('error', $qb_log_res);
							}
							if($qb_log_res)
							{
								$log_arr[$log_index]['log_text'] = $qb_log_res;
								$log_arr[$log_index]['po_id'] = $result['po_id'];
								$log_arr[$log_index]['updated_by'] = $current_user;
								$log_arr[$log_index]['created_at'] = date('Y-m-d H:i:s');
								$log_index++;
							}
						}
						
						if(!empty($log_arr))
							$log_result = $this->log_model->insert_log($log_arr);
						/*END Use to create PO in QuickBooks desktop application*/
						redirect(base_url('po/edit/'.$result['po_id']));
					}
					else
					{
						$log_msg = 'Unable to save Purchase order';
						$this->load->view('po/po_create', $data);
					}
				}
			}
			else{
				$this->load->view('po/po_create', $data);
			}
        }
		$this->load->view('admin/includes/_footer');
    }

	/**
	 *	po edit
	 */
	public function edit($po_id = '')
	{
		$action_arr['controller'] = 'po';
        $action_arr['action']     = 'edit';
        $check_permission = get_permission_by_action($action_arr);
        /* Check access permission
         * Restricted direct the url
         */
        if($check_permission == FALSE)
        	redirect(base_url('access'));

		if(!empty($po_id))
		{
			$po_detail = $this->po_model->get_po_master_data($po_id);
			if(empty($po_detail) || (isset($po_detail[0])) && empty($po_detail[0]))
			{
				$this->session->set_flashdata('errors', 'PO no longer exist');
				redirect(base_url('po/list'));
			}
			$po_detail = $po_detail[0];
			$exist_qb_id = (isset($po_detail['qb_po_id'])) ? $po_detail['qb_po_id'] : '';
			$singleArray = [];
			$shippingConfigAddress = get_configuration_group_data(2);
			if(!empty($shippingConfigAddress))
			{
				foreach ($shippingConfigAddress as $key => $childArray) 
				{ 
					$singleArray[$childArray['code']] = $childArray['value'];
				}
			}
			$vendorList = $this->vendor_model->get_all_vendor_list();
			$state_list = $this->user_model->get_state_list();
			$data['state_list'] = $state_list;
			$data['vendorList'] = $vendorList;
			$shipping_method_list = $this->shipping_model->get_methods($company = '');
			$data['shipping_method_list'] = $shipping_method_list;
			$m_po_address = $po_items = $po_attachment = $vendorInfo = $vendorAddress = '';
			
			$m_po_address = $this->po_model->get_po_address_data($po_id);
			$po_items = $this->po_model->get_po_items_data($po_id);
			$po_attachment = $this->po_model->get_po_attachment_data($po_id);
			$vendorId = $po_detail['vendor_id'];
			$vendorInfo = $this->vendor_model->get_vendor_by_id($vendorId);
			$data['vendorInfo'] = $vendorInfo;

			$data['po_master_data'] = $po_detail;
			$data['m_po_address'] = $m_po_address;
			$data['po_items'] = $po_items;
			$data['po_attachment'] = $po_attachment;
			$this->load->view('admin/includes/_header', $data);

			$data['defaultDeliveryAddress'] = $singleArray;
			$data['title'] = 'edit PO';

			if($this->input->post('save_for_later') || $this->input->post('save_and_post') || $this->input->post('save_post_email') || $this->input->post('download_pdf'))
			{
				$this->form_validation->set_rules('email', 'Email', 'trim|required');
				$this->form_validation->set_rules('vendor', 'Vendor', 'trim|required');

				$this->form_validation->set_rules('billing_street_address_1', 'Address Street ', 'trim|required');
				$this->form_validation->set_rules('billing_city', 'Address City', 'trim|required');
				$this->form_validation->set_rules('billing_country', 'Address Country', 'trim|required');

				$billing_country = $this->input->post('billing_country');
				if(strtolower($billing_country) == strtolower('US'))
					$this->form_validation->set_rules('billing_state', 'Address State', 'trim|required');
				else
					$this->form_validation->set_rules('billing_state_input', 'Address State', 'trim|required');

				$shipping_country = $this->input->post('shipping_country');

				if(strtolower($shipping_country) == strtolower('US'))
					$this->form_validation->set_rules('shipping_state', 'Delivery State', 'trim|required');
				else
					$this->form_validation->set_rules('shipping_state_input', 'Delivery State', 'trim|required');

				$this->form_validation->set_rules('billing_zipcode', 'Address Zip Code', 'trim|required');
				$this->form_validation->set_rules('shipping_street_address_1', 'Delivery Street', 'trim|required');
				$this->form_validation->set_rules('shipping_city', 'Delivery City', 'trim|required');
				$this->form_validation->set_rules('shipping_country', 'Delivery Country ', 'trim|required');
				$this->form_validation->set_rules('shipping_zipcode', 'Delivery Zip Code', 'trim|required');
				$this->form_validation->set_rules('shipping_company', 'Shipping Company', 'trim|required');

				$current_user = $this->session->userdata('id');
				//document  pdf start
				$log_arr = $upload_doc_arr = $update_label_arr = $add_attachment_arr = array();
				$po_file_name = '';
				$log_index = $file_count = $item_index = 0;
				$other_file_upload_error = TRUE;
				$document_label = $this->input->post('document_label');
				// document lable
				if($this->input->post('document_label'))
				{
					$document_label = $this->input->post('document_label');
					foreach($document_label as $document_labelKey => $document_labelVal)
					{
						$exist_document_label = $new_label = '';
						$new_label = $document_label[$file_count];
						
						if($new_label != '')
						{
							if(isset($po_attachment[$document_labelKey]))
							{
								$exist_document_label = $po_attachment[$document_labelKey]['document_label'];
								if(trim(strtolower($exist_document_label)) != trim(strtolower($new_label)))
								{
									$log_txt = '';
									$update_label_arr[$file_count]['id'] = $po_attachment[$document_labelKey]['id'];
									$update_label_arr[$file_count]['document_label'] = $new_label;
									if(!$exist_document_label)
										$log_txt = 'Added Documnent label '.$new_label;
									else
										$log_txt = 'Changed documnent label from '.$exist_document_label.' to '.$new_label;

									$log_arr[$item_index]['log_text'] = $log_txt;
									$log_arr[$item_index]['po_id'] = $po_id;
									$log_arr[$item_index]['updated_by'] = $current_user;
									$log_arr[$item_index]['created_at'] = CURRENT_DATE_TIME;
									$item_index++;
								}
							}
							else
							{
								$file_count_index = $file_count;
								if($file_count != 0)
									$file_count_index = $file_count-count($po_attachment);

								if(isset($_FILES['other_document']['name'][$file_count_index]) && !empty($_FILES['other_document']['name'][$file_count_index]) && $document_labelVal != '')
								{
									$config['upload_path']   = FCPATH.$this->config->item('po_path');
								    $config['allowed_types'] = $this->config->item('po_allowed_types');
								    // $config['allowed_types'] = '*';
								    $config['max_size'] = $this->config->item('po_max_size');
									if(!is_dir($config['upload_path']))
									  mkdir($config['upload_path'], 0777, true);

									$_FILES['file']['name']     = time().'_'.$_FILES['other_document']['name'][$file_count_index];
						            $_FILES['file']['type']     = $_FILES['other_document']['type'][$file_count_index];
						            $_FILES['file']['tmp_name'] = $_FILES['other_document']['tmp_name'][$file_count_index];
						            $_FILES['file']['error']    = $_FILES['other_document']['error'][$file_count_index];
						            $_FILES['file']['size']     = $_FILES['other_document']['size'][$file_count_index];
									$other_doc_file = array();
								    /* Call file upload helper function */
									$other_doc_file = file_upload('file', $config);
									$invoice_file_name = '';
									if(!empty($other_doc_file))
									{
										if(isset($other_doc_file['error']))
										{
											$other_file_upload_error = FALSE;
											$this->session->set_flashdata('error', $other_doc_file['error']);
											break;
										}

										if(isset($other_doc_file['file_name']))
										{
											$log_text = "Uploaded documents ".$other_doc_file['file_name'];
											$log_arr[$log_index+$file_count]['log_text'] = $log_text;
											$log_arr[$log_index+$file_count]['po_id'] = $po_id;
											$log_arr[$log_index+$file_count]['created_at'] = CURRENT_DATE_TIME;
											$log_arr[$log_index+$file_count]['updated_by'] = $current_user;
											$log_index++;

											$log_text = "Added label ".$new_label;
											$log_arr[$log_index+$file_count]['log_text'] = $log_text;
											$log_arr[$log_index+$file_count]['po_id'] = $po_id;
											$log_arr[$log_index+$file_count]['created_at'] = CURRENT_DATE_TIME;
											$log_arr[$log_index+$file_count]['updated_by'] = $current_user;
											$log_index++;

											$add_attachment_arr[$file_count]['po_attachment'] = $other_doc_file['file_name'];
											$add_attachment_arr[$file_count]['document_type'] = 4;
											$add_attachment_arr[$file_count]['document_label'] = $new_label;
											$add_attachment_arr[$file_count]['po_id'] = $po_id;
										}
									}
								}
							}
						}
						$file_count++;
					}
				}

				if(isset($_FILES['other_document']['name'][0]) && empty($_FILES['other_document']['name'][0]))
					$other_file_upload_error = TRUE;

				if($this->form_validation->run() == FALSE || $other_file_upload_error == FALSE)
				{
					$error_arr = array('errors' => validation_errors());
					$this->session->set_flashdata('errors', $error_arr['errors']);
					redirect('po/edit/'.$po_id);
				}
				else
				{
					$item_index = 0;			
					$changedInfoAr = edit_po_logs($data, $po_id, $log_arr, $state_list, $vendorList);

					if(!empty($log_arr))
						$item_index = count($log_arr);
					
					$update_lable_arr = $new_doc_lable_arr = array();
					$result = '';
					if($this->input->post('save_and_post') || $this->input->post('save_post_email'))
					{
						$changedInfoAr['po_master']['is_posted'] = 1;
					}

					if(!empty($changedInfoAr) || !empty($update_label_arr) || !empty($add_attachment_arr))
					{
						$update_po_master = $update_po_address = $update_po_items = $add_po_attachment = $update_po_attachment = $add_po_items = '';
						if(isset($changedInfoAr['po_master']) && !empty($changedInfoAr['po_master']))
							$update_po_master = $this->po_model->update_po($po_id, $changedInfoAr['po_master']);

						if(isset($changedInfoAr['po_address']) && !empty($changedInfoAr['po_address']))
							$update_po_address = $this->po_model->update_po_address($changedInfoAr['po_address']);
						
						if(isset($changedInfoAr['updated_itemAr']) && !empty($changedInfoAr['updated_itemAr']))
							$update_po_items = $this->po_model->update_po_items($changedInfoAr['updated_itemAr']);
						
						if(isset($changedInfoAr['new_itemAr']) && !empty($changedInfoAr['new_itemAr']))
							$add_po_items = $this->po_model->save_po_items($changedInfoAr['new_itemAr'], '');

						if(!empty($add_attachment_arr))
							$add_po_attachment = $this->po_model->insert_po_attachment($add_attachment_arr);

						if(!empty($update_label_arr))
							$update_po_attachment = $this->po_model->update_attachment_label($update_label_arr);
						
						if($update_po_master || $update_po_address || $update_po_items || $update_po_attachment || $add_po_attachment || $add_po_items)
						{
							if(isset($changedInfoAr['log_arr']) && !empty($changedInfoAr['log_arr']))
								$this->log_model->insert_log($changedInfoAr['log_arr']);
							
							$update_msg = 'Purchase order updated successfully';
						}
						else
							$update_msg = 'Nothing to update';
						$this->session->set_flashdata('success', $update_msg);
					}
					else
					{
						$update_msg = 'Nothing to update';
						$this->session->set_flashdata('success', $update_msg);
					}
					if($this->input->post('save_post_email'))
					{
						$this->session->set_flashdata('success', '');
						$this->session->set_flashdata('success', 'Purchase order save and sent email to vendor successfully');
						$email = trim($this->input->post('email'));
						$this->create_po_pdf($email, $po_id, 'F');
					}
					/*Use to download the PDF*/
					if($this->input->post('download_pdf'))
					{
						$price_permission = '';
						$action_arr['controller'] = 'po';
						$action_arr['action']     = 'prices';
						$price_permission = get_permission_by_action($action_arr);
						if($price_permission == FALSE)
							redirect(base_url('access'));
						
						$this->create_po_pdf($email = '', $po_id, 'D');
					}
					/*START Use to create PO in QuickBooks desktop application*/
					if($this->input->post('save_and_post') || $this->input->post('save_post_email'))
					{
						$po_master_data = $this->po_model->get_po_master_data($po_id);
						$po_master_data = $po_master_data[0];
						if($exist_qb_id == '' || $exist_qb_id == NUll)
						{
							$qb_po_id = '';
							$qb_items = $items_arr = array();
							$qb_po_address = $this->po_model->get_po_address_data($po_id);
							$qb_items = $this->po_model->get_po_items_data($po_id);
							if(!empty($qb_items))
							{
								foreach ($qb_items as $qb_key => $qb_value)
								{
									$items_arr['sku'][] = $qb_value['sku'];
									$items_arr['qty'][] = $qb_value['qty'];
									$items_arr['price'][] = $qb_value['price'];
									$items_arr['description'][] = $qb_value['description'];
								}
							}
							$this->load->helper('quickbook');
							$qb_result = add_po($po_id, $po_master_data, $items_arr, $qb_po_address);
							$qb_log_res = '';
							if(isset($qb_result['qb_po_id']) && !empty($qb_result['qb_po_id']))
							{
								$update_po = array('qb_po_id' => $qb_result['qb_po_id'], 'updated_at' => date('Y-m-d H:i:s'));
								$this->po_model->update_po($po_id, $update_po);
								$qb_log_res = "PO sync to QB application";
							}
							if(isset($qb_result['message']) && !empty($qb_result['message']))
							{
								$qb_log_res = 'QB Error: '. $qb_result['message'];
								$this->session->set_flashdata('error', $qb_log_res);
							}
							if($qb_log_res)
							{
								$qb_log = 0;
								$log_arr_qb[$qb_log]['log_text'] = $qb_log_res;
								$log_arr_qb[$qb_log]['po_id'] = $po_id;
								$log_arr_qb[$qb_log]['updated_by'] = $current_user;
								$log_arr_qb[$qb_log]['created_at'] = date('Y-m-d H:i:s');
								$log_result = $this->log_model->insert_log($log_arr_qb);
							}
						}
						else
						{
							/*Update PO on QB*/
							$this->load->helper('quickbook');
							update_po($po_id, $po_master_data, $exist_qb_id);
						}
					}
					/*END Use to create PO in QuickBooks desktop application*/				
					redirect('po/edit/'.$po_id);
				}
			}
			else
			{
				$this->load->view('po/po_edit', $data);
				$this->load->view('admin/includes/_footer');
			}
		}
	}

	public function view($po_id = '')
	{
		$action_arr['controller'] = 'po';
        $action_arr['action']     = 'list';
        $check_permission = get_permission_by_action($action_arr);
        /* Check access permission
         * Restricted direct the url
         */
        if($check_permission == FALSE)
        	redirect(base_url('access'));

		if(!empty($po_id))
		{
			$po_detail = $this->po_model->get_po_master_data($po_id);
			if(empty($po_detail) || (isset($po_detail[0])) && empty($po_detail[0]))
			{
				$this->session->set_flashdata('errors', 'PO no longer exist');
				redirect(base_url('po/list'));
			}
			$po_detail = $po_detail[0];
			$singleArray = [];
			$shippingConfigAddress = get_configuration_group_data(2);
			if(!empty($shippingConfigAddress))
			{
				foreach ($shippingConfigAddress as $key => $childArray) 
				{ 
					$singleArray[$childArray['code']] = $childArray['value'];
				}
			}
			$vendorList = $this->vendor_model->get_all_vendor_list();
			$state_list = $this->user_model->get_state_list();
			$data['state_list'] = $state_list;
			$data['vendorList'] = $vendorList;
			$shipping_method_list = $this->shipping_model->get_methods($company = '');
			$data['shipping_method_list'] = $shipping_method_list;
			$po_master_data = $m_po_address = $po_items = $po_attachment = $vendorInfo = $vendorAddress = '';
			$po_master_data = $this->po_model->get_po_master_data($po_id);
			$m_po_address = $this->po_model->get_po_address_data($po_id);
			$po_items = $this->po_model->get_po_items_data($po_id);
			$po_attachment = $this->po_model->get_po_attachment_data($po_id);
			$vendorId = $po_detail['vendor_id'];
			$vendorInfo = $this->vendor_model->get_vendor_by_id($vendorId);
			$data['vendorInfo'] = $vendorInfo;
			$data['po_master_data'] = $po_detail;
			$data['m_po_address'] = $m_po_address;
			$data['po_items'] = $po_items;
			$data['po_attachment'] = $po_attachment;
			$this->load->view('admin/includes/_header', $data);
			$data['defaultDeliveryAddress'] = $singleArray;
			$data['title'] = 'View PO';
		}
		$this->load->view('po/po_view', $data);
		$this->load->view('admin/includes/_footer');
	}
	/**
	*  load vendor data 
	*/
	public function load_vendor(){
		$vendorId = $this->input->post('vendorId');
		if(!$vendorId)
		{
		$this->session->set_flashdata('error', "Vendor no longer exist");
		$result['vendor_not_exist'] = 'vendor_not_exist';
		}
		else
		{
			$vendorDetail = $this->vendor_model->get_vendor_by_id($vendorId);
			$vendorDetail = json_decode(json_encode($vendorDetail), true);
			$result['vendorInfo'] = $vendorDetail;
			$vendorAddressDetail = '';
			if(isset($vendorDetail['default_address_id']))
			{
				if($vendorDetail['default_address_id'] !=0){
					$vendorAddressDetail = $this->vendor_model->get_vendor_default_address($vendorDetail['default_address_id']);
				}
				else{
					$vendorAddressDetail = $this->vendor_model->get_vendor_address($vendorDetail['id']);
				}
				$result['vendorAddressDetail'] = $vendorAddressDetail;
			}			echo json_encode($result);
        	exit;
		}
	}
	/**
	* remove document
	*/
	public function remove_doc()
	{
		$current_user = $this->session->userdata('id');
		$result = array();
		$action_arr['controller'] = 'po';
        $action_arr['action']     = 'edit';
        $check_permission = get_permission_by_action($action_arr);
        /* Check access permission
         * Restricted direct the url
         */
        if($check_permission == FALSE)
        {
        	$response['access_denied'] = 'access_denied';
        	echo json_encode($result);
        	exit;
        }
		$doc_id = $po_id = $exist_id = $file_name = '';
		$doc_id = $this->input->post('doc_id');
		$po_id = $this->input->post('po_id');
		if($doc_id)
		{
			$po_attachment = $this->po_model->get_po_attachment($po_id);
			$exist_id = array_search($doc_id, array_column($po_attachment, 'id'));
			if(is_numeric($exist_id))
			{
				$po_attachment_arr = $po_attachment[$exist_id];
				$file_name = $po_attachment_arr['po_attachment'];
				if($file_name)
				{
					$result = $this->po_model->remove_doc($doc_id);
					if($result)
					{
						$path = FCPATH.'uploads/po_attachment/'.$file_name;
						unlink($path);
						$response['success'] = "'".$file_name."' ".' document has been removed successfully.';
					}
					else
						$response['error'] = 'Something went wrong, unable to remove file.';
				}
			}
			else
			{
				$response['error'] = "File doesn't not exist";
			}
		}
		else
		{
			$response['error'] = 'Something went wrong, unable to remove file.';
		}
		echo json_encode($response);
		exit;
	}

	/**
	*  function is used to generate pdf by po number
	*/
	public function create_po_pdf($vendorEmail, $po_id = '', $download)
	{
		// echo $po_pdf_dir = APPPATH.'logs/paypal/';
		$dompdf = new DOMPDF();
		$this->load->helper('pdf_helper');
		$po_master_data = $this->po_model->get_po_master_data($po_id);
		$po_address = $this->po_model->get_po_address_data($po_id);
		$po_items = $this->po_model->get_po_items_data($po_id);
		$state = $this->user_model->get_state_list();
		$vendorId = $po_master_data[0]['vendor_id'];

		$vendorData = $this->vendor_model->get_vendor_by_id($vendorId);
		//$vendorEmail = $vendorData->email;
		$country = get_country_list();
		$billingAddress = array();
		$shipppingAddress = array();
		
		$countryList=  array_column($country,'country_name','country_code');
		$state_list=  array_column($state,'name','state_code');
		$billingStr = '';
		$shippingStr = '';

		if(!empty($po_address)){
			foreach ($po_address as $key => $addVal) {
				if($addVal['address_type'] == 1)
				{
					if(isset($vendorData->vendor_name) && !empty($vendorData->vendor_name))
						$billingStr .= $vendorData->vendor_name.'<br>';

					$billingStr .= $addVal['street_address_1'];
					$billingAddress['street_address_2'] = $addVal['street_address_2'];
					if(!empty($addVal['street_address_2']))
						$billingStr  .= ", ".$addVal['street_address_2'];
					
					if(!empty($addVal['city']))
						$billingStr .="<br>".$addVal['city'];
					
					$billingAddress['telephone'] = $addVal['telephone'];
					$billingAddress['fax'] = $addVal['fax'];
					if(!empty($addVal['state']))
					{
						$stateVal = $addVal['state'];
						if(array_key_exists($stateVal,$state_list))
							$billingStr .= ", ".$state_list[$stateVal];
						else
							$billingStr .= ", ".$addVal['state'];
					}
					if(!empty($addVal['zipcode']))
						$billingStr .= ", ".$addVal['zipcode'];
					
					if(!empty($addVal['country']))
					{
						$countryVal = $addVal['country'];
						if (array_key_exists($countryVal,$countryList))
							$billingStr .= "<br>".$countryList[$countryVal];
						else
							$billingStr .= "<br>".$addVal['country'];
					}
				}
				else if($addVal['address_type']==2){
					$shippingStr .= $addVal['street_address_1'];
					if(!empty($addVal['street_address_2'])){
						$shippingStr .= "<br>".$addVal['street_address_2'];
					}
					if(!empty($addVal['city'])){
						$shippingStr .= "<br>".$addVal['city'];
					}

					$shipppingAddress['telephone'] = $addVal['telephone'];
					$shipppingAddress['fax'] = $addVal['fax'];
					if(!empty($addVal['state'])){
						$stateVal = $addVal['state'];
						if (array_key_exists($stateVal,$state_list))
						{
							$shippingStr .= ", ".$state_list[$stateVal];
						}
						else{
							$shippingStr .= ", ".$addVal['state'];
						}
					}
					if(!empty($addVal['zipcode'])){
						$shippingStr .= ", ".$addVal['zipcode'];
					}
					if(!empty($addVal['country'])){
						$countryVal = $addVal['country'];
						if (array_key_exists($countryVal,$countryList))
						{
							$shippingStr .= "<br>".$countryList[$countryVal];
						}
						else{
							$shippingStr .= "<br>". $addVal['country'];
						}
					}
				}
			}
		}
		$headerAddress = '';
		$singleArray = [];
		$shippingConfigAddress = get_configuration_group_data(2);
		$company_name = 'MET International';
		if(!empty($shippingConfigAddress)){
			foreach ($shippingConfigAddress as $key => $childArray) 
			{ 
				$singleArray[$childArray['code']] = $childArray['value'];
			}
		}

		if(!empty($singleArray)){
			if(isset($singleArray['company_name']) && !empty($singleArray['company_name']))
				$company_name = trim($singleArray['company_name']);

			if(isset($singleArray['street_address_1']) && !empty($singleArray['street_address_1']))
				$headerAddress .= trim($singleArray['street_address_1']);

			if(isset($singleArray['street_address_2']) && !empty($singleArray['street_address_2']))
				$headerAddress .= ", ".trim($singleArray['street_address_2']);

			if($singleArray['shipping_city'])
				$headerAddress .= "<br>".trim($singleArray['shipping_city']);

			if($singleArray['shipping_state'])
				$headerAddress .= ", ".trim($singleArray['shipping_state']);

			if($singleArray['shipping_zipcode'])
				$headerAddress .= " ".trim($singleArray['shipping_zipcode']);
		}
		$shippingStr = $company_name."<br>".$shippingStr;
        $logo = '';
		$logo = get_config_value('logo');
		$header = "<table border='0' cellpadding='0' cellspacing='0' width='100%' style='width: 100%;font-family: arial;'>";
		$header .= "<tbody>";
		$header .= "<tr>";
		$header .= "<td align='center' valign='top' width='200' style='width: 200px;'>";
		$header .= "<img src='".$logo."' width='180' alt='logo' style='width: 180px;'>";
		$header .= "<p style='font-size: 14px;color: #000;font-weight: 600;margin: 0;text-align: center;padding-top:3px;'>".$headerAddress."</p>";
		$header .= "</td>";
		$header .= "<td width='30' style='width: 30px;'>&nbsp;</td>";
		$header .= "<td align='right' valign='top' width='200' style='width: 200px;'>";
		$header .= "<h2 style='margin: 0;'><strong>Purchase Order ".$po_master_data[0]['po_number']."</strong></h2>";
		$header .= "<p style='margin: 0;font-size: 20px;color: #000;padding: 10px 0;'>".date("m/d/Y", strtotime($po_master_data[0]['created_at']))."</p>";
		$header .= "</td>";
		$header .= "</tr>";
		$header .= "</tbody>";
		$header .= "</table>";

		$header .= "<br>";
		$header .= "<br>";

		$header .= "<table border='0' cellpadding='0' cellspacing='0' width='100%' style='width: 100%;font-family: arial;height:150px;'>";
		$header .= "<tbody>";
		$header .= "<tr>";
		$header .= "<td align='left' valign='top' width='200' style='width: 200px;'>";
		$header .= "<table align='left' border='0' cellpadding='0' cellspacing='0' valign='top' width='100%' style='border:1px solid #ddd;'>";
		$header .= "<tbody>";
		$header .= "<tr>";
		$header .= "<td colspan='2' align='left' valign='top' style='font-size: 20px;color: #000;font-weight: 600;margin: 0;text-align: left;background: #e6e6e6;border-bottom:1px solid #ddd;padding: 6px 10px;'><strong>Vendor Information</strong></td>";
		$header .= "</tr>";
		$header .= "<tr>";
		$header .= "<td colspan='2' style='padding: 10px;'>";
		$header .= "<p style='font-size: 14px;color: #000;margin: 0;text-align: left;line-height: 22px;'>".$billingStr."</p>";
		$header .= "</td>";
		$header .= "</tr>";
		$header .= "<tr>";
		$header .= "<td width='60%' style='border-top: 1px solid #ddd;border-right: 1px solid #ddd;text-align: left;font-size: 12px;color: #000;padding: 5px;'>Phone # ".$billingAddress['telephone']."</td>";
		$header .= "<td width='40%' style='border-top: 1px solid #ddd;text-align: left;font-size: 12px;color: #000;padding: 5px;'>Fax # ".$billingAddress['fax']."</td>";
		$header .= "</tr>";
		$header .= "</tbody>";
		$header .= "</table>";
		$header .= "</td>";
		$header .= "<td width='30' style='width: 30px;'>&nbsp;</td>";
		$header .= "<td align='left' valign='top' width='190' style='width: 190px;'>";
		$header .= "<table align='left' border='0' cellpadding='0' cellspacing='0' valign='top' width='100%' style='border:1px solid #ddd;'>";
		$header .= "<tbody>";
		$header .= "<tr>";
		$header .= "<td colspan='2' align='left' valign='top' style='font-size: 20px;color: #000;font-weight: 600;margin: 0;text-align: left;background: #e6e6e6;border-bottom:1px solid #ddd;padding: 6px 10px;'><strong>Ship To</strong></td>";
		$header .= "</tr>";
		$header .= "<tr>";
		$header .= "<td colspan='2' style='padding: 10px;'>";
		$header .= "<p style='font-size: 14px;color: #000;margin: 0;text-align: left;line-height: 22px;'>".$shippingStr."</p>";
		$header .= "</td>";
		$header .= "</tr>";
		$header .= "<tr>";
		$header .= "<td colspan='2' style='text-align: left;font-size: 14px;color: #000;padding: 5px;'>&nbsp;</td>";
		$header .= "</tr>";
		$header .= "</tbody>";
		$header .= "</table>";
		$header .= "</td>";
		$header .= "</tr>";
		$header .= "</tbody>";
		$header .= "</table>";

		$header .= "<br>";
		$header .= "<br>";

		$header .= "<div style='background: #f0f0f0;padding: 15px;border: 1px solid #000;border-radius: 10px;'>";
		$header .= "<table border='0' cellpadding='0' cellspacing='0' width='100%' style='width: 100%;font-family: arial;'>";
		$header .= "<tbody>";
		$header .= "<tr>";
		$header .= "<td style='font-size: 16px;font-weight: bold;font-style: italic;color: #000;text-align: center;    text-transform: uppercase;'>Zero insurance value. bill shipping charges to the account number provided below and reference our purchase order number on the airbill. any shipping charges resulting from failure to follow these instructions will be deducted from the payment towards this purchase order.</td>";
		$header .= "</tr>";
		$header .= "</tbody>";
		$header .= "</table>";
		$header .= "</div>";

		$header .= "<table border='0' cellpadding='0' cellspacing='0' width='100%' style='width: 100%;font-family: arial;'>";
		$header .= "<tbody>";
		$header .= "<tr>";
		$header .= "<td height='5'>&nbsp;</td>";
		$header .= "</tr>";
		$header .= "</tbody>";
		$header .= "</table>";

		$header .= "<div style='border: 1px solid #000;border-radius: 10px;width:90%;margin:0 auto;'>";
		$header .= "<table align='center' class='ups-table' border='0' cellpadding='0' cellspacing='0' width='100%' style='width: 100%;font-family: arial;'>";
		$header .= "<tbody>";
		$header .= "<tr>";
		$header .= "<td style='padding: 8px;font-size: 16px;color: #000;font-weight: bold;font-style: italic;text-align: center;'>Ship Via:</td>";
		$header .= "<td style='padding: 8px;font-size: 16px;color: #000;font-weight: bold;font-style: italic;text-align: center;'>".$po_master_data[0]['shipping_method']."</td>";
		$header .= "<td style='padding: 8px;font-size: 16px;color: #000;font-weight: bold;font-style: italic;text-align: center;'>Account #</td>";
		$header .= "<td style='padding: 8px;font-size: 16px;color: #000;font-weight: bold;font-style: italic;text-align: center;'>".$po_master_data[0]['shipping_account_number']."</td>";
		$header .= "</tr>";
		$header .= "</tbody>";
		$header .= "</table>";
		$header .= "</div>";

		$header .= "<table border='0' cellpadding='0' cellspacing='0' width='100%' style='width: 100%;font-family: arial;'>";
		$header .= "<tbody>";
		$header .= "<tr>";
		$header .= "<td height='80' style='height:80px;'>&nbsp;</td>";
		$header .= "</tr>";
		$header .= "</tbody>";
		$header .= "</table>";
		
		
		$main = '';
		$main .= "<table class='invoice-table' border='0' cellpadding='0' cellspacing='0' width='100%' style='width: 100%;font-family: arial;border: 2px solid #000;table-layout: fixed;'>";
		$main .= "<thead>";
		$main .= "<tr>";
		$main .= "<td style='border-top: 2px solid #000;border-right: 2px solid #000;border-bottom: 2px solid #000;text-align: center;font-size: 16px;color: #000;padding: 5px;background: #e6e6e6;'>Terms:</td>";
		$main .= "<td style='border-top: 2px solid #000;border-right: 2px solid #000;border-bottom: 2px solid #000;text-align: center;font-size: 16px;color: #000;padding: 5px;background: #fff;'>".$po_master_data[0]['payment_term']."</td>";
		$main .= "<td colspan='2' style='border-top: 2px solid #000;border-right: 2px solid #000;border-bottom: 2px solid #000;text-align: center;font-size: 16px;color: #000;padding: 5px;background: #e6e6e6;'>Tracking Number:</td>";
		$main .= "<td style='border-top: 2px solid #000;border-bottom: 2px solid #000;;text-align: center;font-size: 16px;color: #000;padding: 5px;background: #fff;'>".$po_master_data[0]['tracking_number']."</td>";
		$main .= "</tr>";
		$main .= "</thead>";
		$main .= "<tbody>";
		

		$main .= "<tr>";
		$main .= "<td style='border-right: 2px solid #000;border-bottom: 2px solid #000;text-align: center;font-size: 14px;color: #000;padding: 5px;background: #e6e6e6;'>Item</td>";
		$main .= "<td style='border-right: 2px solid #000;border-bottom: 2px solid #000;text-align: center;font-size: 14px;color: #000;padding: 5px;background: #e6e6e6;width: 40%;'>Description</td>";
		$main .= "<td style='border-right: 2px solid #000;border-bottom: 2px solid #000;text-align: center;font-size: 14px;color: #000;padding: 5px;background: #e6e6e6;'>Qty</td>";
		$main .= "<td style='border-right: 2px solid #000;border-bottom:2px solid #000;text-align: center;font-size: 14px;color: #000;padding: 5px;background: #e6e6e6;'>Rate</td>";
		$main .= "<td style='border-bottom: 2px solid #000;text-align: center;font-size: 14px;color: #000;padding: 5px;background: #e6e6e6;'>Amount</td>";
		$main .= "</tr>";
		//row start
		$count = 1;
		$total = 0;
		foreach ($po_items as $items_value){
			$sku_length = $sku = $first_part = $last_part = '';
			$sku_length = strlen($items_value['sku']);
			$limit = 12;
			if($sku_length > $limit)
			{
				$first_part = substr($items_value['sku'], 0, $limit);
				$last_part = substr($items_value['sku'], $limit, $sku_length);
				$sku = $first_part."\n".$last_part;
			}
			else
			{
				$sku = $items_value['sku'];
			}
			$price_qty = 0.00;
			$price_qty = $items_value['price'] * $items_value['qty'];
			$rowTotal  = number_format((float)$price_qty, 2, '.', '');
			$total = $total + $rowTotal;
			$even_odd = ($count % 2 == 0) ? 'even_class' : 'odd_class';
			$main .= "<tr class='".$even_odd."'>";
			$main .= "<td style='border-bottom: 2px solid #000;border-right: 2px solid #000;text-align: center;font-size: 14px;color: #000;padding: 5px;'>".nl2br($sku)."</td>";
			$main .= "<td style='border-bottom: 2px solid #000;border-right: 2px solid #000;text-align: center;font-size: 14px;color: #000;padding: 5px;'>".nl2br($items_value['description'])."</td>";
			$main .= "<td style='border-bottom: 2px solid #000;border-right: 2px solid #000;text-align: center;font-size: 14px;color: #000;padding: 5px;'>".$items_value['qty']."</td>";
			$main .= "<td style='border-bottom: 2px solid #000;border-right: 2px solid #000;text-align: center;font-size: 14px;color: #000;padding: 5px;'>$".number_format((float)$items_value['price'], 2, '.', '')."</td>";
			$main .= "<td style='border-bottom: 2px solid #000;text-align: center;font-size: 14px;color: #000;padding: 5px;'>$".$rowTotal."</td>";
			$main .= "</tr>";
            $count++;
        }
			$main .= "<tr>";
			$main .= "<td colspan='3' style='border-bottom: 2px solid #000;border-right: 2px solid #000;text-align: center;font-size: 14px;color: #000;padding: 5px;'>
			    <p style='font-size: 16px;font-weight: 600;color: #000;text-align: left;line-height: 20px;margin: 0;font-family: arial;'>&nbsp;</p></td>";
			$main .= "<td colspan='1' style='border-bottom: 2px solid #000;text-align: left;font-size: 14px;color: #000;padding: 5px;'><strong>Total</strong></td>";
			$main .= "<td colspan='1' style='border-bottom: 0px solid #000;text-align: right;font-size: 14px;color: #000;padding: 5px;'>$".number_format((float)$total, 2, '.', '')."</td>";
			$main .= "</tr>";
		//row end
		$main .= "</tbody>";
		$main .= "</table>";

		$main .= "<div style='background:#fff;'>";
		$main .= "<table border='0' cellpadding='0' cellspacing='0' width='100%' style='width: 100%;font-family: arial;padding-top: 15px;'>";
		$main .= "<tbody>";
		$main .= "<tr>";
		$main .= "<td align='center'>";
		$main .= "<p style='font-size: 14px;color: #000;margin: 0;'>Please Do NOT Insure</p>";
		$main .= "<p style='font-size: 14px;color: #000;margin: 0;'>Please Email Tracking to <a href='#'>accounting@metint.com </a></p>";
		$main .= "</td>";
		$main .= "</tr>";
		$main .= "</tbody>";
		$main .= "</table>";
		$main .= "</div>";
		$html = '<html>
		    <head>
		        <style>
		            @page {
					  size: 210.0mm 297.0mm;
					  margin-top: 10.0mm;
					  margin-right: 10.0mm;
					  margin-bottom: 10.0mm;
					  margin-left: 10.0mm;
					}
                    body {padding-top: 50px;font-family: arial;padding-bottom: 10px;}
		            header {
		            	position: fixed;
			            top: 0px;
			            left: 0px;
			            right: 0px;
			            text-align: center;
			            bottom: 0;
		                width: 100%;
						margin: 0px auto;
		            }
		            main {
		            	position: relative;
			            top: 470px;
			            left: 0px;
			            right: 0px;
						width: 100%;
					    margin: 0 auto 0 auto;
						}
					tr.even_class {background: #e5e5e5;}
                    tr.odd_class {background: #fff;}
                    .ups-table{
                    	-webkit-border-radius:10px;
						-moz-border-radius:10px;
						border-radius:10px;
                    }
		        </style>
		    </head>
		    <body>
		        <!-- Define header and footer blocks before your content -->
		        <header>'.$header.'</header>
		        <footer></footer>
		        <!-- Wrap the content of your PDF inside a main tag -->
		        <main>'.$main.'</main>
		    </body>
		</html>';
		/* Write log in text file*/
		// $po_pdf_dir = APPPATH.'logs/paypal/';
		// if(!is_dir($po_pdf_dir))
		// {
		// 	mkdir($po_pdf_dir, 0777, true);
		// }
		$downloadpdf = FCPATH . "uploads/po_pdf/";
		$todayDate = date('d-m-Y');
		$dompdf = new Dompdf();
		$dompdf->loadHtml($html);
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();
		if($download == 'D')
			$dompdf->stream($po_master_data[0]['po_number'].'pdf',array("Attachment" => 1));
		else
			$output = $dompdf->output();
        $filename = $po_master_data[0]['po_number'].'_'.time().'.pdf';
        file_put_contents($downloadpdf.$filename, $output);
        if($download == 'F')
        {

	        $config['protocol'] = 'sendmail';
			$config['mailpath'] = '/usr/sbin/sendmail';
			$config['charset'] = 'iso-8859-1';
			$config['wordwrap'] = TRUE;
			$email_cc = '';
			$email_cc = get_config_value($code = 'email_cc');
			$message = "<div>Please see attached for a copy of Purchase Order #".$po_master_data[0]['po_number']." from MET International.</div><div>&nbsp;</div><div>&nbsp;</div><div>&nbsp;</div><div>&nbsp;</div>";
			$message .= "<div>Thank you for your business! \n";
			$message .= "<div>Regards,\n</div>";
			$message .= "<div>MET International \n</div>";
			$message .= "<div>(972) 478-5641 \n</div>";
			$message .= "<div>Accounting@metint.com\n</div>";
			$from_email = get_config_value($code = 'email');
			$this->email->initialize($config);
			$this->email->from($from_email);
	        $this->email->to($vendorEmail);
	        if($email_cc)
	        	$this->email->cc($email_cc);
	        $this->email->subject('PO #'.$po_master_data[0]['po_number'].' from MET International');
	        $this->email->set_mailtype("html");
	        $this->email->message($message);
	        $this->email->attach($downloadpdf.$filename);
	        $success = $this->email->send();
	        if($success) {
			    echo "Send";
			    //unlink($filename.'.pdf'); //for delete generated pdf file. 
			}
        }
	}

}?>