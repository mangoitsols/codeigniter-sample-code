<?php
$current_user = '';
$current_user = $this->session->userdata('id');
?>
<!-- DataTables -->
<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/datatables/dataTables.bootstrap4.css"> 

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <section class="content">
    <!-- For Messages -->
    <?php $this->load->view('admin/includes/_messages.php') ?>
    <!---Block Separate Section 01 Start-->
    <div class="separate-section-box">
      <div class="box-header">
        <div class="d-inline-block">
          <h3 class="card-title"> PO Filters </h3>
        </div>
        <div class="d-inline-block float-right">
          <a href="<?= base_url('po/create'); ?>" class="btn btn-success"><i class="fa fa-plus"></i>Create PO</a>
        </div>
      </div>
      <div class="box-body">
        <div class="user-information-container separate-section">
          <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                  <label for="start_date" class="control-label">Start Date</label>
                  <div class="input-group date">
                    <input class="form-control datepicker-search-filter" type="input" id="start_date" name="start_date" value=""/>
                    <span class="input-group-addon start-custom-calender"><span class="fa fa-calendar"></span></span>
                  </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="end_date" class="control-label">End Date</label>
                    <div class="input-group date">
                      <input class="form-control datepicker-search-filter" type="input" id="end_date" name="end_date" value=""/>
                      <span class="input-group-addon end-custom-calender"><span class="fa fa-calendar"></span></span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="payment_status" class="control-label">Payment Status</label>
                  <select name="payment_status" class="form-control" id="payment_status">
                    <option value="">Select Payment Status</option>
                    <option value="0">Not-Paid</option>
                    <option value="1">Paid</option>
                  </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="rma_closed_status" class="control-label">Open/Closed</label>
                  <select name="rma_closed_status" class="form-control" id="rma_closed_status">
                    <option value="">Select Status</option>
                    <option value="0">Open</option>
                    <option value="1">Closed</option>
                  </select>
              </div>
            </div>
            <div class="col-md-4">
              <?php if(!empty(PO_STATUS)): ?>
              <div class="form-group">
                <label for="order_status" class="control-label" >Order Status</label>
                  <select name="order_status" class="form-control" id="order_status">
                    <option value="">All Order Statuses</option>
                    <?php foreach (PO_STATUS as $order_key => $order_value):?>
                      <option value="<?php echo $order_key; ?>"><?php echo $order_value;?></option>
                    <?php endforeach;?>
                  </select>
              </div>
              <?php endif;?>
            </div>
            <div class="col-md-12">
              <input type="button" name="search" id="search" value="Search" class="btn btn-info" />
              <input type="button" name="reset" id="reset" value="Reset" class="btn btn-info">
            </div>
          </div>
        </div>
      </div>
    </div>
    <!---Block Separate Section 01 End-->

    <div class="card">
      <div class="card-body table-responsive">
          <table id="invoice_list" class="table table-bordered table-striped" width="100%">
            <thead>
              <tr>
                <th rowspan="2">Created Date</th>
                <th rowspan="2">PO Number</th>
                <th rowspan="2">Vendor Name</th>
                <th rowspan="2">Tracking Number</th>
                <th rowspan="2">Amount</th>
                <th rowspan="2">Paid</th>
                <th colspan="5">Status</th>
                <th rowspan="2">Action</th>
              </tr>              
              <tr>
                <th>Posted</th>
                <th>Landed</th>
                <th>Received</th>
                <th>Discrepancy</th>
                <th>Closed</th>
              </tr>
            </thead>
          </table>
      </div>
    </div>
  </section>  
</div>
<!-- DataTables -->
<script src="<?php echo base_url() ?>assets/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/dataTables.bootstrap4.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $('.datepicker-search-filter').datepicker({
      format: 'mm-dd-yyyy',
      autoclose: true
    });
    var order_status = '';
    order_status = $('#order_status').val();
    if(order_status == '' ){
      order_status = '';
    }
    fetch_data('no', start_date = '', end_date = '', order_status);
    /*
     * Fetch invoice list
     */
    function fetch_data(is_date_search, start_date = '', end_date ='', order_status ='', payment_status = '', rma_closed_status = ''){
      var table = $('#invoice_list').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": {
          "url": "<?=base_url('po/po/datatable_json')?>",
          "data": {is_date_search:is_date_search,
                start_date : start_date,
                end_date : end_date,
                order_status : order_status,
                payment_status : payment_status,
                rma_closed_status: rma_closed_status
            },
        },
        "order": [[ 1, "desc" ]],
        'pageLength': 20,
        "columnDefs": [
          { "targets": 0, "name": "po_master.created_at", 'searchable':false, 'orderable':true},
          { "targets": 1, "name": "po_master.po_number", 'searchable':true, 'orderable':true},
          { "targets": 2, "name": "po_vendor.vendor_name", 'searchable':true, 'orderable':true},
          { "targets": 3, "name": "po_master.tracking_number", 'searchable':true, 'orderable':true},
          { "targets": 4, "name": "po_master.amount", 'searchable':false, 'orderable':false},
          { "targets": 5, "name": "paid", 'searchable':false, 'orderable':false},
          { "targets": 6, "name": "Posted", 'searchable':false, 'orderable':false},
          { "targets": 7, "name": "Landed", 'searchable':false, 'orderable':false},
          { "targets": 8, "name": "Received", 'searchable':false, 'orderable':false},
          { "targets": 9, "name": "Discripency", 'searchable':false, 'orderable':false},
          { "targets": 10, "name": "Closed", 'searchable':false, 'orderable':false},
          { "targets": 11, "name": "action", 'searchable':false, 'orderable':false}
        ]
      });      
    }

    /*
     * Filter by Date
     */
    $('#search').click(function(){
      var is_date_search = 'yes';
      var start_date = end_date = order_status = payment_status = rma_closed_status = '';
      start_date = $('#start_date').val();
      end_date = $('#end_date').val();
      order_status = $('#order_status').val();
      rma_closed_status = $('#rma_closed_status').val();
      if(order_status == '')
      {
        order_status = '';
      }
      if(start_date == '' && end_date == '')
      {
        is_date_search = 'no';
      }
      payment_status = $('#payment_status').val();
      $('#invoice_list').DataTable().destroy();
      fetch_data(is_date_search, start_date, end_date, order_status, payment_status, rma_closed_status);
    });

    /*
     * Reset filter
     */
    $("#reset").click(function(){
      var start_date = end_date = order_status = payment_status = rma_closed_status = '';
      $('#start_date').val('');
      $('#end_date').val('');
      $('#order_status').val('');
      $('#payment_status').val('');
      $('#rma_closed_status').val('');

      start_date = $('#start_date').val();
      end_date = $('#end_date').val();
      order_status = $('#order_status').val();
      payment_status = $('#payment_status').val();
      rma_closed_status = $('#rma_closed_status').val();
      if(start_date != '' || end_date != '' || order_status != '' || payment_status != '' || rma_closed_status != '')
      {
        $('#start_date').val('');
        $('#end_date').val('');
        $('#order_status').val('');
        $('#payment_status').val('');
        $('#rma_closed_status').val('');
      }
      else
      {
        $('#invoice_list').DataTable().destroy();
        fetch_data('no');
      }
    });

    /* show calender on click event */
    $(".start-custom-calender").click(function(){
        $('#start_date').datepicker('show');
    });
    $(".end-custom-calender").click(function(){
      $('#end_date').datepicker('show');
    });
    
  });
</script>