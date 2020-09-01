<?php
class Po_model extends CI_Model
{
	var $table = 'm_po_master';
	var $column_order = array('po_master.created_at',
		'po_master.po_number',
		'po_vendor.vendor_name',
		'po_master.tracking_number'
	);
	var $column_search = array('po_master.po_number', 'po_vendor.vendor_name', 'po_items.description');

	/**
	 * Return last PO number
	 */
	public function getLastPoNumber()
	{
		$result = '';
		$this->db->trans_start();
		$this->db->query('LOCK TABLE m_po_master WRITE');
		$this->db->select_max('po_number');
		$this->db->from('m_po_master');
		$this->db->query('UNLOCK TABLES');
		$query = $this->db->get();
		$result = $query->row();
		$this->db->trans_complete();
		return $result;
	}

	/**
	 * Save PO data
	 */
	public function save_po_data($po_master_data, $items_arr, $m_po_address, $po_attachment, $documentlable)
	{
		$po_id = $items = $address = $attachment = '';
		$this->db->trans_start();
		$this->db->query('LOCK TABLE m_po_master WRITE');
		$this->db->insert('m_po_master', $po_master_data);
		$po_id = $this->db->insert_id();
		$this->db->query('UNLOCK TABLES');
		if($po_id)
		{
			if(!empty($items_arr))
				$result['po_items'] = $this->save_po_items($items_arr, $po_id);
			if(!empty($m_po_address))
				$result['address_id'] =  $this->save_po_address($m_po_address, $po_id);
			if(!empty($po_attachment))
				$result['po_attachment'] = $this->save_po_attachment($po_attachment,$documentlable, $po_id);
		}
		$this->db->trans_complete();
		$result['po_id'] = $po_id;
		return $result;
	}

	/**
	 * Get vendor details by field and values
	 */
	public function get_po_by_field($field = '', $value = '')
	{
		$result = array();
		if(!$field || !$value)
			return $result;

		$this->db->trans_start();
		$this->db->query('LOCK TABLE m_po_master WRITE');
		$this->db->select('id, vendor_id, po_number, email, shipping_company, shipping_method, internal_notes, payment_trans_id, payment_term, other_payment_term, tracking_number, shipping_account_number, is_posted, is_landed, is_received, is_discrepancy, is_closed, qb_po_id, created_by, created_at, updated_at', 'received_at');
		$this->db->from('m_po_master');
		$this->db->where("$field", $value);
		$this->db->query('UNLOCK TABLES');
		$query  = $this->db->get();
		$result = $query->row();
		$this->db->trans_complete();
		return $result;
	}

	/**
	 *	Save po address billing and shipping
	 */
	public function save_po_address($m_po_address_billing, $po_id){
		foreach ($m_po_address_billing as $key => $value)
		{
			$m_po_address_billing[$key]['po_id'] = $po_id;
		}
		$result = array();
		$this->db->trans_start();
		$result = $this->db->insert_batch('m_po_address', $m_po_address_billing);
		$insert_id = $this->db->insert_id();
		$this->db->trans_complete();
		return $insert_id;
	}

	/**
	 *	Save po address billing and shipping
	 */
	public function save_po_attachment($po_attachment,$documentlable, $po_id){
		$insert_id = '';
		foreach ($po_attachment as $key => $value)
		{
			$po_attachment[$key] = $value;
			$po_attachment[$key]['document_label'] = $documentlable[$key];
			$po_attachment[$key]['po_id'] = $po_id;
			if(!isset($value['po_attachment']))
			{
				$po_attachment[$key]['po_attachment'] = '';
			}
		}
		$this->db->trans_start();
		$result = $this->db->insert_batch('m_po_attachment', $po_attachment);
		$insert_id = $this->db->insert_id();
		$this->db->trans_complete();
	}

	/**
	 *	Insert Po attachment from edit PO section
	 */
	public function insert_po_attachment($po_attachment)
	{
		$insert_id = '';
		$this->db->trans_start();
		$result = $this->db->insert_batch('m_po_attachment', $po_attachment);
		$insert_id = $this->db->insert_id();
		$this->db->trans_complete();
		return $insert_id;
	}

	/*
	 * Update label for attachment
	 */
	public function update_attachment_label($label_arr)
	{
		$result = '';
		$this->db->trans_start();
		$result = $this->db->update_batch('m_po_attachment', $label_arr, 'id');
		$result = $this->db->affected_rows();
		$this->db->trans_complete();
		return $result;
	}

	/**
	 *	PO items save
	 */
	public function save_po_items($items_arr, $po_id){
		$items_master = array();
		if(isset($items_arr['sku']))
		{
			for($i = 0; $i < count($items_arr['sku']); $i++)
			{
				$items_master[$i]['po_id'] = $po_id;
				$items_master[$i]['sku'] = $items_arr['sku'][$i];
				$items_master[$i]['qty'] = $items_arr['qty'][$i];
				$items_master[$i]['price'] = $items_arr['price'][$i];
				$items_master[$i]['description'] = $items_arr['description'][$i];
				$items_master[$i]['created_at'] = CURRENT_DATE_TIME;
				$items_master[$i]['updated_at'] = CURRENT_DATE_TIME;
			}
		}
		else
		{
			$items_master = $items_arr;
		}
		$insert_id = '';
		if(!empty($items_master))
		{
			$this->db->trans_start();
			$result = $this->db->insert_batch('m_po_items', $items_master);
			$insert_id = $this->db->insert_id();
			$this->db->trans_complete();
		}
		return $insert_id;
	}

	/*
	 * Insert items in batch
	 */
	public function add_po_items($items_arr)
	{
		$insert_id = '';
		$this->db->trans_start();
		$result = $this->db->insert_batch('m_po_items', $items_arr);
		$insert_id = $this->db->insert_id();
		$this->db->trans_complete();
		return $insert_id;
	}

	/**
	 * Get PO data
	 */
	public function get_po_master_data($po_id){
		$result = array();
		$this->db->trans_start();
		$this->db->select('id, vendor_id, po_number, po_amount, email, shipping_company, shipping_method, internal_notes, payment_trans_id, payment_term, other_payment_term, tracking_number, shipping_account_number, is_posted, is_landed, is_received, is_discrepancy, is_closed, qb_po_id, created_by, created_at, updated_at', 'received_at');
		$this->db->where('id', $po_id);
		$this->db->from('m_po_master');
		$query = $this->db->get();
		$result = $query->result_array();
		$this->db->trans_complete();
		return $result;
	}

	/**
	 * Get PO address information
	 */
	public function get_po_address_data($po_id){
		$result = array();
		$this->db->trans_start();
		$this->db->select('id, po_id, address_type, vendor_id,street_address_1,street_address_2,city,zipcode,telephone,fax,state,country');
		$this->db->where('po_id', $po_id);
		$this->db->from('m_po_address');
		$query = $this->db->get();
		$result = $query->result_array();
		$this->db->trans_complete();
		return $result;
	}

	/**
	 * Get PO items data
	 */
	public function get_po_items_data($po_id){
		$result = array();
		$this->db->trans_start();
		$this->db->select('id, po_id, sku, description, price, qty, created_at,updated_at');
		$this->db->where('po_id', $po_id);
		$this->db->from('m_po_items');
		$query = $this->db->get();
		$result = $query->result_array();
		$this->db->trans_complete();
		return $result;
	}

	/**
	 * Get PO attachemt information
	 */
	public function get_po_attachment_data($po_id){
		$insert_id = '';
		$result = array();
		$this->db->trans_start();
		$this->db->select('id, po_id, po_attachment, document_type,document_label');
		$this->db->where('po_id', $po_id);
		$this->db->from('m_po_attachment');
		$query = $this->db->get();
		$result = $query->result_array();
		$this->db->trans_complete();
		return $result;
	}

	/*
	 * Get PO attachments
	 */
	public function get_po_attachment($po_id)
	{
		$insert_id = '';
		$result = array();
		$this->db->trans_start();
		$this->db->select('id, po_id, document_type, po_attachment,document_label');
		$this->db->where('po_id', $po_id);
		$this->db->from('m_po_attachment');
		$query = $this->db->get();
		$result = $query->result_array();
		$this->db->trans_complete();
		return $result;
	}

	/*
	 * Use to remove document
	 */
	public function remove_doc($doc_id)
	{
		$result = '';
		$this->db->trans_start();
		$result = $this->db->delete('m_po_attachment', array('id' => $doc_id));
		if($this->db->affected_rows()):
			$result = TRUE;
		else:
			$result = FALSE;
		endif;
		$this->db->trans_complete();
		return $result;
	}

	/*
	 * Use to Update PO data
	 */
	public function update_po($po_id, $po_data)
	{
		$result = '';
		$this->db->trans_start();
		$this->db->where('id', $po_id);
		$this->db->update('m_po_master', $po_data);
		$result = $this->db->affected_rows();
		$this->db->trans_complete();
		return $result;        
	}

	/*
	 * Update address
	 */
	public function update_po_address($address_arr)
	{
		$this->db->trans_start();
		$result = $this->db->update_batch('m_po_address', $address_arr, 'id');
		$result = $this->db->affected_rows();
		$this->db->trans_complete();
		return $result;
	}

	/*
	 * Update invoice items
	 */
	public function update_po_items($data)
	{
		$this->db->trans_start();
		$result = $this->db->update_batch('m_po_items', $data, 'id');
		$result = $this->db->affected_rows();
		$this->db->trans_complete();
		return $result;
	}

	/*
	 * Use for PO Listing
	 */
	public function _get_datatables_query()
	{
		$i = 0;
		$sales_rep_ids = get_sales_rep_ids();
		$this->db->select('po_master.id, po_master.created_at, po_master.po_number, po_vendor.vendor_name, po_master.tracking_number, po_master.po_amount, po_master.is_posted, po_master.is_landed, po_master.is_received, po_master.is_discrepancy, po_master.is_closed, po_master.is_paid');        
		$this->db->from('m_po_master as po_master');
		$this->db->join('m_po_items as po_items', 'po_items.po_id = po_master.id');
		$this->db->join('m_vendor_master as po_vendor', 'po_vendor.id = po_master.vendor_id');
		/* Add date range filter*/
		if(isset($_GET['is_date_search']) && $_GET['is_date_search'] == 'yes')
		{
			if(isset($_GET['start_date']) && $_GET['start_date'] != '')
			{
				$start_date = date("Y-m-d", strtotime($_GET['start_date']));
				$this->db->where('DATE(po_master.created_at) >=', $start_date);
			}
			if (isset($_GET['end_date']) && $_GET['end_date'] != '')
			{
				$end_date = date("Y-m-d", strtotime($_GET['end_date']));
				$this->db->where('DATE(po_master.created_at) <=', $end_date);
			}
		}

		/* Add Order status filter*/
		if(isset($_GET['order_status']) && !empty($_GET['order_status']))
		{
			$order_status = $_GET['order_status'];
			if(trim(strtolower($order_status)) == 'is_landed')
			{
				$this->db->group_start();
				$this->db->where('po_master.is_landed', 1);
				$this->db->group_end();

				$this->db->group_start();
				$this->db->or_where('po_master.is_received', 0);
				$this->db->or_where('po_master.is_discrepancy', 0);
				$this->db->group_end();
			}
			else if(trim(strtolower($order_status)) == 'is_received')
			{
				$this->db->group_start();
				$this->db->where('po_master.is_received', 1);
				$this->db->where('po_master.is_discrepancy', 0);
				$this->db->group_end();
			}
			else if(trim(strtolower($order_status)) == 'is_discrepancy')
			{
				$this->db->group_start();
				$this->db->where('po_master.is_discrepancy', 1);
				$this->db->group_end();
			}
			else if(trim(strtolower($order_status)) == 'is_posted')
			{
            	//Posted should be posted; not Landed, Received, and/or discrepancy
				$this->db->group_start();
				$this->db->where('po_master.is_posted', 1);
				$this->db->where('po_master.is_landed', 0);
				$this->db->where('po_master.is_received', 0);
				$this->db->where('po_master.is_discrepancy', 0);
				$this->db->group_end();
			}
		}
		/* Add Payment status filter*/
		if(isset($_GET['payment_status']) && $_GET['payment_status'] != '')
		{
			$this->db->group_start();
			$this->db->where('po_master.is_paid', $_GET['payment_status']);
			$this->db->group_end();
		}
		/* Add Open/Closed filter*/
		if(isset($_GET['rma_closed_status']) && $_GET['rma_closed_status'] != '')
		{
			$this->db->group_start();
			$this->db->where('po_master.is_closed', $_GET['rma_closed_status']);
			$this->db->group_end();
		}

        foreach ($this->column_search as $item) // loop column 
        {
            if(isset($_GET['search']['value'])) // if datatable send POST for search
            {
            	if($i===0)
            	{
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_GET['search']['value']);
                }
                else
                {
                	$this->db->or_like($item, $_GET['search']['value']);
                } 
                if(count($this->column_search) - 1 == $i)
                    $this->db->group_end();
                }
                $i++;
            }         
        if(isset($_GET['order'])) // here order processing
        {
        	$this->db->order_by($this->column_order[$_GET['order']['0']['column']], $_GET['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
        	$order = $this->order;
        	$this->db->order_by(key($order), $order[key($order)]);
        }
        $this->db->group_by("po_items.po_id");
    }

    public function get_datatables()
    {
    	$this->_get_datatables_query();
    	if(isset($_GET['length']) && $_GET['length'] != -1) {
    		$this->db->limit($_GET['length'], $_GET['start']);
    	}
    	$query = $this->db->get();
    	return $query->result();
    }

    public function count_filtered()
    {
    	$this->_get_datatables_query();
    	$query = $this->db->get();
    	return $query->num_rows();
    }

    public function count_all()
    {
    	$this->db->from($this->table);
    	return $this->db->count_all_results();
    }
}
?>