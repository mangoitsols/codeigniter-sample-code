<!-- Content Wrapper. Contains page content -->
<?php
$current_user = '';
$current_user = $this->session->userdata('id');
$country = get_country_list();
?>
<div class="content-wrapper">
  <!-- Main content -->
  <section class="content">
    <!---Block Separate Section 01 start-->
    <div class="card card-default color-palette-bo">
      <div class="card-header">
        <div class="d-inline-block">
          <h3 class="card-title"> <i class="fa fa-plus"></i> Add New Purchase Order</h3>
        </div>
        <div class="d-inline-block float-right">
          <a href="<?= base_url('po/list'); ?>" class="btn btn-success"><i class="fa fa-list"></i>  PO List</a>
        </div>
      </div>
    </div>
    <?php $this->load->view('admin/includes/_messages.php') ?>
    <?php echo form_open_multipart(base_url('po/po/create'), 'class="form-horizontal" id="add_po"');?>
    <!---Block Separate Section 02 Start-->
    <div class="separate-section-box">
      <div class="box-header">
        <div class="d-inline-block">
          <h3 class="card-title"> Vendor Information </h3>
        </div>
      </div>
      <div class="box-body">
        <div class="user-information-container separate-section">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="vendor" class="control-label">Vendor<abbr>*</abbr></label>
                <select class="form-control" id="vendor" name="vendor">
                  <option value="">Select Vendor</option>
                  <?php
                    if(!empty($vendorList)){
                      foreach($vendorList as $vendor){
                      if(isset($vendor['status']) && $vendor['status'] == 1){?>
                          <option value="<?php echo $vendor['id']; ?>" <?php echo ($vendor['id'] == set_value('vendor')) ? 'selected': ''; ?>><?php echo $vendor['vendor_name'];?></option>
                        <?php
                        }
                      }
                    } 
                  ?>
                </select>
                <span id="vendor_error" class="custom-error error" style="display: none;">This field is required.</span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="email" class="control-label">Email<abbr>*</abbr></label>
                <input type="email" name="email" class="form-control <?php echo set_value('email'); ?>" id="email" placeholder="<?php echo EMAIL_PLACE_HOLDER; ?>" value="<?php echo set_value('email'); ?>">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--Address information block start-->
    <div class="separate-section-box">
      <div class="box-header">
        <div class="d-inline-block">
          <h3 class="card-title">Address Information </h3>
        </div>
      </div>
      <div class="box-body">
        <div class="customer-address-container separate-section">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group street-address">
                <label class="control-label">Street Address<abbr>*</abbr></label>
                <input type="text" name="billing_street_address_1" class="form-control billing-address" id="billing_street_address_1" placeholder="" value="<?php echo set_value('billing_street_address_1'); ?>">
                <input type="text" name="billing_street_address_2" class="form-control" id="billing_street_address_2" placeholder="" value="<?php echo set_value('billing_street_address_2'); ?>">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                  <label class="control-label">City<abbr>*</abbr></label>
                  <input type="text" name="billing_city" class="form-control" id="billing_city" placeholder="" value="<?php echo set_value('billing_city'); ?>">
                </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                  <label class="control-label">Country<abbr>*</abbr></label>
                  <select name="billing_country" class="form-control" id="billing_country">
                    <option value="">Select country</option>
                    <?php
                    $selected_country = '';
                    if(set_value('billing_country'))
                        $selected_country = set_value('billing_country');
                      else
                        $selected_country = 'US';
                    foreach ($country as $country_key => $country_value) { ?>
                      <option value="<?php echo $country_value['country_code']; ?>" <?php echo ($selected_country == $country_value['country_code']) ? 'selected': ''; ?>><?php echo $country_value['country_name']; ?></option>
                    <?php } ?>
                  </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                  <label class="control-label">State<abbr>*</abbr></label>
                  <input type="text" name="billing_state_input" class="form-control" id="billing_state_input" placeholder="" value="<?php echo set_value('billing_state_input'); ?>" style="display: <?php echo ($selected_country != 'US' && $selected_country != '') ? 'block' : 'none'; ?>;">

                  <select name="billing_state" class="form-control" id="billing_state" style="display: <?php echo ($selected_country == 'US' || $selected_country == '') ? 'block' : 'none'; ?>;">
                    <option value="">Select State</option>
                    <?php
                    foreach ($state_list as $state_key => $state_value) { ?>
                      <option value="<?php echo $state_value['state_code']; ?>" <?php echo (set_value('billing_state') == $state_value['state_code'] ) ? 'selected' : ''; ?> data-sales-tax="<?php echo $state_value['sales_tax_amount']; ?>"><?php echo $state_value['name']; ?></option>
                    <?php } ?>
                  </select>
                  <span id="custom_billing_state_error" class="custom-error error" style="display: none;">This field is required.</span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                  <label class="control-label">Zip Code<abbr>*</abbr></label>
                    <input type="text" name="billing_zipcode" class="form-control" id="billing_zipcode" placeholder="" value="<?php echo set_value('billing_zipcode'); ?>">
                </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                  <label for="billing_contact" class="control-label">Contact Number</label>
                  <input type="text" name="billing_contact" class="form-control number_field" id="billing_contact" placeholder="" value="<?php echo set_value('billing_contact'); ?>">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="billing_fax" class="control-label">Fax</label>
                <input type="text" name="billing_fax" class="form-control" id="billing_fax" placeholder="" value="<?php echo set_value('billing_fax'); ?>">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--Address information block end-->
    <!--Default configuration address block start -->
    <div class="separate-section-box customer-shipping-address-container">
      <div class="box-header">
        <div class="d-inline-block">
          <h3 class="card-title">Delivery address</h3>
        </div>
      </div>
      <div class="box-body"><div class="separate-section">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                  <label class="control-label">Street Address<abbr>*</abbr></label>
                  <input type="text" name="shipping_street_address_1" class="form-control billing-address" id="shipping_street_address_1" placeholder="" value="<?php if(isset($defaultDeliveryAddress['street_address_1'])){ echo $defaultDeliveryAddress['street_address_1'];} ?>">
                  <input type="text" name="shipping_street_address_2" class="form-control" id="shipping_street_address_2" placeholder="" value="<?php  if(isset($defaultDeliveryAddress['street_address_2'])){ echo $defaultDeliveryAddress['street_address_2'];} ?>">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">City<abbr>*</abbr></label>
                <input type="text" name="shipping_city" class="form-control" id="shipping_city" placeholder="" value="<?php if(isset($defaultDeliveryAddress['shipping_city'])){ echo $defaultDeliveryAddress['shipping_city'];} ?>">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                  <label class="control-label">Country<abbr>*</abbr></label>
                  <select name="shipping_country" class="form-control" id="shipping_country">
                    <option value="">Select country</option>
                    <?php
                     $selected_country = '';
                      if(set_value('shipping_country'))
                        $selected_country = set_value('shipping_country');
                      else
                        $selected_country = $defaultDeliveryAddress['shipping_country'];
                      foreach ($country as $country_key => $country_value) {?>
                      <option value="<?php echo $country_value['country_code']; ?>" <?php echo ($selected_country==$country_value['country_code']) ? 'selected': ''; ?>><?php echo $country_value['country_name']; ?></option>
                    <?php } ?>
                  </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                  <label class="control-label">State<abbr>*</abbr></label>
                  <input type="text" name="shipping_state_input" class="form-control" id="shipping_state_input" placeholder="" value="<?php echo set_value('shipping_state_input'); ?>" style="display: <?php echo ($selected_country != 'US' && $selected_country != '') ? 'block' : 'none'; ?>;">
                  <select name="shipping_state" class="form-control" id="shipping_state" style="display: <?php echo ($selected_country == 'US' || $selected_country == '') ? 'block' : 'none'; ?>;">
                    <option value="">Select State</option>
                    <?php
                    foreach ($state_list as $state_key => $state_value) { ?>
                      <option value="<?php echo $state_value['state_code']; ?>" <?php echo (set_value('shipping_state') == $state_value['state_code'] || ( isset($defaultDeliveryAddress['shipping_state']) && $defaultDeliveryAddress['shipping_state']== $state_value['state_code']) ) ? 'selected' : ''; ?> data-sales-tax="<?php echo $state_value['sales_tax_amount']; ?>" ><?php echo $state_value['name']; ?></option>
                    <?php } ?>
                  </select>
                  <span id="custom_shipping_state_error" class="custom-error error" style="display: none;">This field is required.</span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                  <label class="control-label">Zip Code<abbr>*</abbr></label>
                  <input type="text" name="shipping_zipcode" class="form-control" id="shipping_zipcode" placeholder="" value="<?php if(isset($defaultDeliveryAddress['shipping_zipcode'])){ echo $defaultDeliveryAddress['shipping_zipcode'];} ?>">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                  <label for="shipping_contact" class="control-label">Contact Number</label>
                  <input type="text" name="shipping_contact" class="form-control number_field" id="shipping_contact" placeholder="" value="<?php if(isset($defaultDeliveryAddress['shipping_contact'])){ echo $defaultDeliveryAddress['shipping_contact'];} ?>">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                  <label for="shipping_fax" class="control-label">Fax</label>
                  <input type="text" name="shipping_fax" class="form-control" id="shipping_fax" placeholder="" value="<?php if(isset($defaultDeliveryAddress['shipping_fax'])){ echo $defaultDeliveryAddress['shipping_fax'];} ?>">
              </div>
            </div>
          </div>
        </div></div>
    </div>
    <!--Default configuration address block end -->
    <!--payment term block start-->
    <div class="separate-section-box">
      <div class="box-header">
        <div class="d-inline-block">
          <h3 class="card-title"> Payment Information </h3>
        </div>
      </div>
      <div class="box-body">
      <div class="shipping-carrier-information">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="tracking_number" class="control-label">Tracking Number</label>
                <input type="text" name="tracking_number" class="form-control " id="email" placeholder="Enter Tracking Number" value="">
              </div>
            </div>
            <?php
              $action_arr['controller'] = 'po';
              $action_arr['action']     = 'payment_id';
              $check_permission = get_permission_by_action($action_arr);
              if($check_permission == TRUE): 
            ?>
            <div class="col-md-6">
              <div class="form-group">
                <label for="payment_id" class="control-label">Payment Id</label>
                <input type="text" name="payment_id" class="form-control " id="payment_id" placeholder="Enter Payment Id" value="">
              </div>
            </div>
          <?php endif; ?>
          </div>
        </div>
        <div class="tax-taxable-container separate-section">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="payment_term" class="control-label">Payment Terms<abbr>*</abbr></label>
                  <select class="payment_term form-control" id="payment_term" name="payment_term">
                    <option value="">Select payment term</option>
                    <?php foreach (CUSTOMER_PAYMENT_TERMS as $payment_key => $payment_value): ?>
                      <option value="<?php echo  $payment_key;?>" <?php echo (set_value('payment_term') == $payment_key) ? 'selected' : ''; ?>><?php echo  $payment_value;?></option>
                    <?php endforeach;?>
                  </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="other_terms_container" style="display: none;">
                <label class="control-label">Other</label>
                <input type="text" name="other_terms" class="form-control" id="other_terms" placeholder="" value="<?php echo set_value('other_terms'); ?>">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--payment term block end -->
    <!-- shipping information block start-->
    <div class="separate-section-box">
      <div class="box-header">
        <div class="d-inline-block">
          <h3 class="card-title"> Shipping Information</h3>
        </div>
      </div>
      <div class="box-body">
        <div class="shipping-carrier-information">
          <!-- Loader -->
          <!-- <div class="loader-container" id="loading" style="display: none;">
            <div class="lds-ripple"><div></div><div></div></div>
          </div> -->
          <!-- End Loader -->
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                  <label for="shipping_company" class="control-label">Shipping Company<abbr>*</abbr></label>
                  <?php
                    $selected_shipping_company = '';
                      if(set_value('shipping_company'))
                        $selected_shipping_company = set_value('shipping_company');
                      else
                        $selected_shipping_company = 'UPS';
                  ?>
                  <select class="shipping_company form-control" id="shipping_company" name="shipping_company">
                    <option value="">Select shipping company</option>
                    <?php foreach (SHIPPING_COMPANY as $carrier_key => $carrier_value): ?>
                      <option value="<?php echo  $carrier_key;?>" <?php echo (trim(strtolower($selected_shipping_company)) == trim(strtolower($carrier_key))) ? 'selected' : ''; ?>><?php echo  $carrier_value;?></option>
                    <?php endforeach;?>
                  </select>
              </div>
            </div>
            <?php
              $selected_shipping_method = '';
              if(set_value('shipping_method'))
                $selected_shipping_method = set_value('shipping_method');
              else
                $selected_shipping_method = 'UPS Ground';
            ?>
            <div class="col-md-6 shipping-method-container" style="display: <?php echo ($selected_shipping_method || set_value('other_shipping_method')) ? 'block;': 'none'; ?>;">
              <div class="form-group">
                  <label for="shipping_method" class="control-label">Shipping Method<abbr>*</abbr></label>
                  <select class="shipping_method form-control" id="shipping_method" name="shipping_method">
                    <option value="">Select shipping method</option>
                    <?php
                      if(isset($shipping_method_list) && !empty($shipping_method_list) && $selected_shipping_company):
                      foreach ($shipping_method_list as $key => $value):
                        if(strtolower($value['company']) == strtolower($selected_shipping_company)):?>
                          <option value="<?php echo $value['name']; ?>" <?php echo (trim(strtolower($selected_shipping_method)) == trim(strtolower($value['name']))) ? 'selected': ''; ?>><?php echo $value['name']; ?></option>
                      <?php
                        endif;
                      endforeach;
                    endif;?>
                  </select>
                  <span id="shipping_method_required" class="error" style="display: none;">This field is required</span>
              </div>
            </div>
            <div class="col-md-6 other-method-container" style="display: <?php echo (set_value('other_shipping_method')) ? 'block': 'none'; ?>;">
              <div class="form-group">
                  <label for="shipping_method" class="control-label">Shipping Method<abbr>*</abbr></label>
                  <input type="text" name="other_shipping_method" class="form-control" id="other_shipping_method" placeholder="" value="<?php echo set_value('other_shipping_method'); ?>">
                  <span id="other_method_required" class="error" style="display: none;">This field is required</span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="shipping_account_number" class="control-label">Default Shipping Account Number</label>
                <input type="text" name="shipping_account_number" class="form-control" id="shipping_account_number" placeholder="" value="<?php if(isset($defaultDeliveryAddress['shipping_account_number'])){ echo $defaultDeliveryAddress['shipping_account_number'];} ?>">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- shipping information block end-->
    <!--create item block start -->
    <div class="separate-section-box">
      <div class="box-header">
        <div class="d-inline-block">
          <h3 class="card-title"> Create items</h3>
        </div>
      </div>
      <div class="box-body">
        <span id="address_error" style="display: none;">Please select state</span>
        <div class="all-item-container">
            <div class="row create-items-heading">
              <div class="field-3">
                <div class="form-group control-label">
                  <label for="sku">SKU<abbr>*</abbr></label>
                </div>
              </div>
              <div class="field-3">
                <div class="form-group">
                  <label for="absent">Description</label>
                </div>
              </div>
              <div class="field-3">
                <div class="form-group control-label">
                  <label for="quantity">Quantity<abbr>*</abbr></label>
                </div>
              </div>
              <div class="field-3">
                <div class="form-group control-label">
                  <label for="Price per unit">Price per unit<abbr>*</abbr></label>
                </div>
              </div>
            </div>
            <div class="row items-field-container">
              <?php if(!empty(set_value('sku'))):
                $sku = set_value('sku');
                $description = set_value('description');
                $qty_arr = set_value('qty');
                $price_per_unit = set_value('price_per_unit');
                $subtotal = $total = $sale_tax_amount = $sales_tax_percent = 0.00;
                for($i = 0; $i < count($sku); $i++) { // start for loop
                  $subtotal = $subtotal + ($qty_arr[$i] * number_format((float)$price_per_unit[$i], 2, '.', ''));
                ?>
                <div class="row clone-container" id="clone_container_<?php echo $i?>">
                  <div class="field-3">
                    <div class="form-group">
                      <input type="text" name="sku[]" class="form-control sku-input" id="sku_<?php echo $i?>" value="<?php echo $sku[$i]?>">
                    </div>
                  </div>
                  <div class="field-3">
                    <div class="form-group">
                      <textarea class="form-control description-input" id="description_<?php echo $i?>" name="description[]"><?php echo $description[$i]; ?></textarea>
                    </div>
                  </div>
                  <div class="field-3">
                    <div class="form-group">
                      <input type="number" name="qty[]" class="form-control qty-input number_field" id="qty_<?php echo $i?>" data-bind-id="<?php echo $i?>" value="<?php echo $qty_arr[$i]?>">
                    </div>
                  </div>
                  <div class="field-3">
                    <div class="form-group price-container">
                      <span class="currency-symbol">$</span>
                      <input type="text" name="price_per_unit[]" class="form-control price-per-unit-input number_field" data-bind-id="<?php echo $i?>" id="price_per_unit_<?php echo $i?>" value="<?php echo $price_per_unit[$i]?>">
                    </div>
                  </div>
                </div>
                <?php }; // end for loop ?>
                <?php else:?>
                  <div class="row clone-container" id="clone_container_1">
                  <div class="field-3">
                    <div class="form-group">
                      <input type="text" name="sku[]" class="form-control sku-input" id="sku_1">
                    </div>
                  </div>
                  <div class="field-3">
                    <div class="form-group">
                      <textarea class="form-control description-input" id="description_1" name="description[]"></textarea>
                    </div>
                  </div>
                  <div class="field-3">
                    <div class="form-group">
                      <input type="number" name="qty[]" class="form-control qty-input number_field" id="qty_1" data-bind-id="1">
                    </div>
                  </div>
                  <div class="field-3">
                    <div class="form-group price-container">
                      <span class="currency-symbol">$</span>
                      <input type="number" name="price_per_unit[]" class="form-control price-per-unit-input number_field" data-bind-id="1" id="price_per_unit_1" data-bind-id="1">
                    </div>
                  </div>
                </div>
                <?php endif;?>
            </div>
        </div>
        <?php
          /*Use to add sales tax amount*/
          if(set_value('billing_country') || set_value('shipping_country'))
          {
              $total = number_format((float)$subtotal, 2, '.', '');
          }
          else{
            $total = $subtotal = number_format((float)0.00, 2, '.', '');
          }
        ?>
        <div class="all-price-calculation-container">
          <table>
            <tr>
              <th>Total: </th>
              <td id="total_amount" data-bind-price-total="0.00">$<?php echo $total;?></td>
            </tr>
          </table>
        </div>
        <div class="row">
          <div class="box-footer">
            <input type="button" name="submit" value="Add More" class="btn btn-info pull-left" id="add_more_items">
          </div>          
        </div>
      </div>
    </div>
    <!--create item block end -->
    <!--upload documnet block start-->
    <div class="separate-section-box invoice-notes">
      <div class="box-header">
        <div class="d-inline-block">
          <h3 class="card-title">Upload Documents</h3>
        </div>
      </div>
      <div class="box-body">
        <div class="clone-other-doc-container">
          <div class="row clone-other-doc-field" id="clone_other_doc_container_1">
          <div class="col-md-6">
            <div class="form-group">
              <input type="documentlable" name="documentlable[]" class="form-control documentlable" id="documentlable_1" placeholder="" value="">
            </div>
          </div>
            <div class="col-md-5">
              <div class="form-group">
                <div id="other_document">
                  <div class="input-group">
                    <div class="">
                      <label class="control-label">&nbsp;</label>
                      <input type="file" class="additional-document" name="other_document[]" id="other_document_1">
                       <!-- <span id="custom_other_document_lable_error_1" class="custom-error error" style="display: none;">This field is required.</span> -->
                    </div>
                  </div>
                </div>              
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="box-footer">
            <input type="button" name="submit" value="Add More" class="btn btn-info pull-left" id="add_more_documents">
          </div>          
        </div>
      </div>
    </div> 
    <!--upload documnet block end-->
    <!--internal note block start -->
    <div class="separate-section-box invoice-notes">
      <div class="box-header">
        <div class="d-inline-block">
          <h3 class="card-title">Notes </h3>
        </div>
      </div>
      <div class="box-body">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
                <label for="internal_notes" class="control-label">Internal Notes</label>
                <textarea name="internal_notes" class="form-control" id="internal_notes" value="<?php echo set_value('internal_notes'); ?>"><?php echo set_value('internal_notes'); ?></textarea>
              </div>
          </div>
        </div>
      </div>
    </div>
    <!--internal note block end -->
    <!--footer button start-->
    <div class="row">
      <div class="btn-wrapper">
        <input type="submit" name="save_for_later" id="save_for_later" value="Save for later" class="submit-button btn btn-info pull-left">
        <input type="submit" name="save_and_post" id="save_and_post" value="Save and post" class="submit-button btn btn-info pull-left">
        <input type="submit" name="save_post_email" id="save_post_email" value="Save, post and email" class="submit-button btn btn-info pull-left">
      </div>
    </div>
    <!--footer button end-->
    <?php echo form_close(); ?>  
  </section>
</div>
<!-- datepicker -->
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/select2/select2.min.css">
<script src="<?php echo base_url() ?>assets/plugins/select2/select2.full.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url() ?>assets/js/jquery/jquery.validate.js"></script>
<script src="<?php echo base_url() ?>assets/js/po/po.js"></script>
<script type="text/javascript">
  var totalDocument = 1;
  $('#vendor').select2();
  $(document).ready(function(){
    shipping_method_list = '<?php echo json_encode($shipping_method_list); ?>';
    state = '<?php echo json_encode($state_list); ?>';
    
    if(shipping_method_list)
      shipping_method_list = $.parseJSON(shipping_method_list);

    $("#vendor").change(function(){
      var vendorId = '';
      vendorId = $(this).val();
      if(vendorId ==''){

      }else{
        $('#email').val();
        $('#billing_street_address_1').val('');
        $('#billing_street_address_2').val('');
        $('#billing_country').val('');
        $('#billing_zipcode').val('');
        $('#billing_contact').val('');
        $('#billing_fax').val('');
        $.ajax({
          url: '<?php echo base_url("po/po/load_vendor"); ?>', 
          type: 'post',
          data: {
              '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>',
              'vendorId': vendorId
          },
          beforeSend: function() {
              $("#loading").show();
          },
          success: function(response){
            var result = html = country = '';
            result = $.parseJSON(response);
            /* check permission */            
            if(typeof result.access_denied != "undefined" ){
            }
            else if(typeof result.customer_not_exist != "undefined" ){
              window.location.href = "<?php echo base_url('po/create');?>";
            }
            else{
              $("#email").val(result.vendorInfo.email);
              $("#billing_street_address_1").val(result.vendorAddressDetail[0].street_address_1);
              $("#billing_street_address_2").val(result.vendorAddressDetail[0].street_address_2);
              $("#billing_country").val(result.vendorAddressDetail[0].country);
              if(result.vendorAddressDetail[0].country =='US'){
                $("#billing_state").val(result.vendorAddressDetail[0].state);
              }
              else{
                $('#billing_state_input').val(result.vendorAddressDetail[0].state);
                $('#billing_state_input').show();
                $('#billing_state').hide();
              }
              $("#billing_zipcode").val(result.vendorAddressDetail[0].zipcode);
              $("#billing_fax").val(result.vendorAddressDetail[0].fax);
              $("#billing_contact").val(result.vendorAddressDetail[0].telephone);
              $("#billing_city").val(result.vendorAddressDetail[0].city);
              
              if(result.vendorInfo.shipping_carrier){
                $('#shipping_company').val(result.vendorInfo.shipping_carrier);
                $('#shipping_company').trigger('change');
              }
              if(result.vendorInfo.payment_term){
                $('#payment_term').val(result.vendorInfo.payment_term);
              }
            }
          }
        });
      }
    });
    /*
     * Use for payment term
     */
    $("#shipping_company").change(function(){
      $("#loading").show();
      var shipping_company = html = '';
      shipping_company = $(this).val();
      
      if(shipping_company != ''){
        if(shipping_company == 'Other'){
          $(".other-method-container").show();
          $(".shipping-method-container").hide();
        }
        else if(shipping_company == 'Freight' || shipping_company == 'Local Pickup'){
          $(".other-method-container").hide();
          $(".shipping-method-container").hide();
        }
        else {
          $(".other-method-container").hide();
          $(".shipping-method-container").show();
          /* Use for shipping method */
          $(shipping_method_list).each(function(shipping_key, shipping_val){
            if(shipping_company == shipping_val['company'])
              html += '<option value="'+shipping_val['name']+'">'+shipping_val['name']+'</option>';
          });
          $("#shipping_method").html(html);
        }
      }
      else {
        $(".other-method-container").hide();
        $(".shipping-method-container").hide();
      }
      $("#loading").hide();
    });
    
    /*
     * Clone other upload document field
     */
    $("#add_more_documents").on('click', function(e){
        e.preventDefault();
        var clone = $(".clone-other-doc-field").eq(0).clone();
        var new_id = $("input.additional-document").length+1;
        clone.find("i.removeclass").attr("id");
        $clone = clone.find("i.removeclass").attr("id","remove_"+new_id); 
        $clone = clone.append('<div class="col-md-1 text-center reporting-field-9"><div class="form-group"><label class="delete-data"><i class="fa fa-remove removeclass" id="remove_'+new_id+'" data-attr="'+new_id+'"></i></label></div></div>');
        $clone = clone.find("div.clone-other-doc-field").attr("id","clone_other_doc_container_"+new_id)
        $clone = clone.find("input.additional-document").attr("id","other_document_"+new_id);
        $clone = clone.find("input.documentlable").attr("id","documentlable_"+new_id);
        $(".clone-other-doc-container").append(clone);
        $("#documentlable_"+new_id).val('');
        $("#other_document_"+new_id).val('');
        /*For remove row*/
        $('.removeclass').on('click', function(e){
          $(this).parents('.clone-other-doc-field').remove();
        });
    });
    /**
    *   add more document 
    */
    $('#add_document').click(function(){
      totalDocument = totalDocument + 1;
      var documentHtml = '<input type="file" id="upload_document_'+totalDocument+'" name="upload_document[]" />'
      $('.document-container').append(documentHtml);
    })
    /*
    * Update price when qty will changed
    */
    function qty_price()
    {
      $(document).on("click blur", ".qty-input", function (){
        $('.price-per-unit-input').trigger('click');
      });
    }
    /*
     * Update total price when price update will changed
     */
    function calculate_price()
    {
      $(document).on("click blur", ".price-per-unit-input", function () {
        var sum = 0;
        $('.price-per-unit-input').each(function() {
          var qty = price = data_bind_id = amount = '';
          price = $(this).val();
          data_bind_id = $(this).attr('data-bind-id');
          qty = $("#qty_"+data_bind_id).val();
          amount = (qty*price);
          sum+=amount;
        });
        total = sum;
        $("#total_amount").text('$'+total.toFixed(2));
        $("#total_amount").attr('data-bind-price-total', total.toFixed(2));
      });
    }
    //add more item
    /*
     * Use to add new item row
     */
     $("#add_more_items").on('click', function(e){
         e.preventDefault();
         var clone = $(".clone-container").eq(0).clone();
         var new_id = $("input.sku-input").length+1;
         clone.find("i.removeclass").attr("id");
         $clone = clone.find("i.removeclass").attr("id","remove_"+new_id); 
         $clone = clone.append('<div class="reporting-field-9"><div class="form-group"><label class="delete-data"><i class="fa fa-remove removeclass" id="remove_'+new_id+'" data-attr="'+new_id+'"></i></label></div></div>');
         $clone = clone.find("div.clone-container").attr("id","clone_container_"+new_id)
         $clone = clone.find("input.sku-input").attr("id","sku_input_"+new_id);
         $clone = clone.find(".description-input").attr("id","description_"+new_id);
         $clone = clone.find("input.qty-input").attr("id","qty_"+new_id);
         $clone = clone.find("input.price-per-unit-input").attr("id","price_per_unit_"+new_id);
         $clone = clone.find("input.part-number-input").attr("id","part_number_"+new_id);
         $clone = clone.find("input.serial-number-input").attr("id","serial_number_"+new_id);
         $(".items-field-container").append(clone);
         $('#sku_input_'+new_id).val('');
         $('#description_'+new_id).val('');
         $('#qty_'+new_id).val('');
         $('#qty_'+new_id).attr('data-bind-id', new_id);
         $('#price_per_unit_'+new_id).val('');
         $('#price_per_unit_'+new_id).attr('data-bind-id', new_id);
         $('#part_number_'+new_id).val('');
         $('#serial_number_'+new_id).val('');
         remove_row();
         calculate_price();
         qty_price();
     });
    remove_row();
    calculate_price();
    qty_price();
     /* 
     * Function use to remove row by click on remove icon
     */
    function remove_row()
    {
      $('.removeclass').on('click', function(e){
        var data_bind_id = subtotal = amount = '';
        data_bind_id = $(this).attr('data-attr');
        qty = $("#qty_"+data_bind_id).val();
        price = $("#price_per_unit_"+data_bind_id).val();
        if(typeof qty != "undefined" && typeof price != "undefined" && price != '' && qty != ''){
          amount = (qty*price);
          $("#total_amount").text('$'+amount.toFixed(2));
          $("#total_amount").attr('data-bind-price-total', amount.toFixed(2));
        }
        $(this).parents('.clone-container').remove();
      });
    }
  });
</script>