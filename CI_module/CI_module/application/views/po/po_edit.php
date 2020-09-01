<!-- Content Wrapper. Contains page content -->
<?php
$current_user = '';
$current_user = $this->session->userdata('id');
$country = get_country_list();
$price_permission = '';
$action_arr['controller'] = 'po';
$action_arr['action']     = 'prices';
$price_permission = get_permission_by_action($action_arr);
?>
<div class="content-wrapper">
  <!-- Main content -->
  <section class="content">
    <!---Block Separate Section 01 start-->
    <?php $this->load->view('admin/includes/_messages.php') ?>
    <?php echo form_open_multipart(base_url('po/po/edit/'.$po_master_data['id']), 'class="form-horizontal" id="add_po"');?>
    <div class="card card-default color-palette-bo">
      <div class="card-header">
        <div class="d-inline-block">
          <h3 class="card-title"> <i class="fa fa-plus"></i>Edit Purchase Order <?php echo ($po_master_data['po_number'])? $po_master_data['po_number'] : '';?><span class="header-date"> Date : <?php echo($po_master_data['created_at'])? date("m/d/Y", strtotime($po_master_data['created_at']) ) : ''; ?></span> </h3>
        </div>
        <!--log-->
        <div class="d-inline-block float-right">
          <a href="<?= base_url('po/list'); ?>" class="btn btn-success"><i class="fa fa-list"></i>  PO List</a>
        </div>
        <?php //if($price_permission == TRUE):?>
          <div class="d-inline-block float-right success download-pdf-container">
            <i class="fa fa-download edit-pdf-download"></i><input type="submit" name="download_pdf" value="Save PDF" class="input-pdf-download">
          </div>
        <?php //endif; ?>
        <div class="d-inline-block float-right">
          <a href="<?= base_url('log/list/po/'.$po_master_data['id']); ?>" class="btn btn-success"><i class="fa fa-list"></i>  PO Log</a>
        </div>
        <!--log-->
      </div>
    </div>
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
                        ?>
                        <option value="<?php echo $vendor['id']; ?>" <?php if($vendorInfo->id == $vendor['id']){echo 'selected'; } ?>><?php echo $vendor['vendor_name'];?></option>
                        <?php
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
                <input type="email" name="email" class="form-control" id="email" placeholder="<?php echo EMAIL_PLACE_HOLDER; ?>" value="<?php echo ($po_master_data['email']) ? $po_master_data['email'] : ''?>">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--Address information block start-->
    <?php 
    $b_street_address_1= $b_street_address_2 = $b_city = $b_zipcode = $b_telephone = $b_fax = $b_state = $b_country = '';
    $sh_street_address_1= $sh_street_address_2 = $sh_city = $sh_zipcode = $sh_telephone = $sh_fax = $sh_state = $sh_country = '';
        foreach ($m_po_address as $m_po_addressKey => $m_po_addressValue) {
          if($m_po_addressValue['address_type']==1){
            $b_street_address_1 = $m_po_addressValue['street_address_1'];
            $b_street_address_2 = $m_po_addressValue['street_address_2'];
            $b_city = $m_po_addressValue['city'];
            $b_zipcode = $m_po_addressValue['zipcode'];
            $b_telephone = $m_po_addressValue['telephone'];
            $b_fax = $m_po_addressValue['fax'];
            $b_state = $m_po_addressValue['state'];
            $b_country = $m_po_addressValue['country'];

          }
          elseif($m_po_addressValue['address_type']==2){
            $sh_street_address_1 = $m_po_addressValue['street_address_1'];
            $sh_street_address_2 = $m_po_addressValue['street_address_2'];
            $sh_city = $m_po_addressValue['city'];
            $sh_zipcode = $m_po_addressValue['zipcode'];
            $sh_telephone = $m_po_addressValue['telephone'];
            $sh_fax = $m_po_addressValue['fax'];
            $sh_state = $m_po_addressValue['state'];
            $sh_country = $m_po_addressValue['country'];
          }
        }
    ?>
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
                <input type="text" name="billing_street_address_1" class="form-control billing-address" id="billing_street_address_1" placeholder="" value="<?php echo $b_street_address_1; ?>">
                <input type="text" name="billing_street_address_2" class="form-control" id="billing_street_address_2" placeholder="" value="<?php echo $b_street_address_2; ?>">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                  <label class="control-label">City<abbr>*</abbr></label>
                  <input type="text" name="billing_city" class="form-control" id="billing_city" placeholder="" value="<?php echo $b_city; ?>">
                </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                  <label class="control-label">Country<abbr>*</abbr></label>
                  <select name="billing_country" class="form-control" id="billing_country">
                    <option value="">Select country</option>
                    <?php
                    $selected_country = '';
                    $selected_country = $b_country;
                    foreach ($country as $country_key => $country_value) { ?>
                      <option value="<?php echo $country_value['country_code']; ?>" <?php echo ($selected_country == $country_value['country_code']) ? 'selected': ''; ?>><?php echo $country_value['country_name']; ?></option>
                    <?php } ?>
                  </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                  <label class="control-label">State<abbr>*</abbr></label>
                  <input type="text" name="billing_state_input" class="form-control" id="billing_state_input" placeholder="" value="<?php echo $b_state; ?>" style="display: <?php echo ($selected_country != 'US' && $selected_country != '') ? 'block' : 'none'; ?>;">

                  <select name="billing_state" class="form-control" id="billing_state" style="display: <?php echo ($selected_country == 'US' || $selected_country == '') ? 'block' : 'none'; ?>;">
                    <option value="">Select State</option>
                    <?php
                    foreach ($state_list as $state_key => $state_value) { ?>
                      <option value="<?php echo $state_value['state_code']; ?>" <?php echo ( $b_state == $state_value['state_code'] ) ? 'selected' : ''; ?>><?php echo $state_value['name']; ?></option>
                    <?php } ?>
                  </select>
                  <span id="custom_billing_state_error" class="custom-error error" style="display: none;">This field is required.</span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                  <label class="control-label">Zip Code<abbr>*</abbr></label>
                    <input type="text" name="billing_zipcode" class="form-control" id="billing_zipcode" placeholder="" value="<?php echo $b_zipcode; ?>">
                </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                  <label for="billing_contact" class="control-label">Contact Number</label>
                  <input type="text" name="billing_contact" class="form-control number_field" id="billing_contact" placeholder="" value="<?php echo $b_telephone; ?>">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="billing_fax" class="control-label">Fax</label>
                <input type="text" name="billing_fax" class="form-control" id="billing_fax" placeholder="" value="<?php echo $b_fax; ?>">
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
                  <input type="text" name="shipping_street_address_1" class="form-control billing-address" id="shipping_street_address_1" placeholder="" value="<?php echo $sh_street_address_1; ?>">
                  <input type="text" name="shipping_street_address_2" class="form-control" id="shipping_street_address_2" placeholder="" value="<?php  echo $sh_street_address_2; ?>">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">City<abbr>*</abbr></label>
                <input type="text" name="shipping_city" class="form-control" id="shipping_city" placeholder="" value="<?php echo $sh_city ;?>">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                  <label class="control-label">Country<abbr>*</abbr></label>
                  <select name="shipping_country" class="form-control" id="shipping_country">
                    <option value="">Select country</option>
                    <?php
                     $selected_country = '';
                     $selected_country = $sh_country;
                      foreach ($country as $country_key => $country_value) {?>
                      <option value="<?php echo $country_value['country_code']; ?>" <?php echo ($selected_country == $country_value['country_code']) ? 'selected': ''; ?>><?php echo $country_value['country_name']; ?></option>
                    <?php } ?>
                  </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                  <label class="control-label">State<abbr>*</abbr></label>
                  <input type="text" name="shipping_state_input" class="form-control" id="shipping_state_input" placeholder="" value="<?php echo $sh_state ;?>" style="display: <?php echo ($selected_country != 'US' && $selected_country != '') ? 'block' : 'none'; ?>;">
                  <select name="shipping_state" class="form-control" id="shipping_state" style="display: <?php echo ($selected_country == 'US' || $selected_country == '') ? 'block' : 'none'; ?>;">
                    <option value="">Select State</option>
                    <?php
                    foreach ($state_list as $state_key => $state_value) { ?>
                      <option value="<?php echo $state_value['state_code']; ?>" <?php echo ($sh_state == $state_value['state_code']) ? 'selected' : ''; ?>><?php echo $state_value['name']; ?></option>
                    <?php } ?>
                  </select>
                  <span id="custom_shipping_state_error" class="custom-error error" style="display: none;">This field is required.</span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                  <label class="control-label">Zip Code<abbr>*</abbr></label>
                  <input type="text" name="shipping_zipcode" class="form-control" id="shipping_zipcode" placeholder="" value="<?php echo $sh_zipcode; ?>">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                  <label for="shipping_contact" class="control-label">Contact Number</label>
                  <input type="text" name="shipping_contact" class="form-control number_field" id="shipping_contact" placeholder="" value="<?php echo $sh_telephone; ?>">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                  <label for="shipping_fax" class="control-label">Fax</label>
                  <input type="text" name="shipping_fax" class="form-control" id="shipping_fax" placeholder="" value="<?php echo $sh_fax; ?>">
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
                <input type="text" name="tracking_number" class="form-control " id="email" placeholder="Enter Tracking Number" value="<?php echo (isset($po_master_data['tracking_number']) )? $po_master_data['tracking_number']: '';?>">
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
                <input type="text" name="payment_id" class="form-control " id="payment_id" placeholder="Enter Payment Id" value="<?php echo (isset($po_master_data['payment_trans_id']) )? $po_master_data['payment_trans_id']: '';?>">
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
                      <option value="<?php echo  $payment_key;?>" <?php echo ($po_master_data['payment_term'] == $payment_key) ? 'selected' : ''; ?>><?php echo  $payment_value;?></option>
                    <?php endforeach;?>
                  </select>
              </div>
            </div>
            <?php
            $other_term = 'none';
            if(trim(strtolower($po_master_data['payment_term'])) == trim(strtolower('other')))
              $other_term = 'block';
            ?>
              <div class="col-md-6">
                <div class="other_terms_container" style="display: <?php echo $other_term;?>;">
                  <label class="control-label">Other</label>
                  <input type="text" name="other_terms" class="form-control" id="other_terms" placeholder="" value="<?php echo  $po_master_data['other_payment_term'];?>">
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
          <div class="loader-container" id="loading" style="display: none;">
            <div class="lds-ripple"><div></div><div></div></div>
          </div>
           <?php
            $shipping_method_drp = 'block';
            if(isset($po_master_data['shipping_company']) && trim(strtolower($po_master_data['shipping_company'])) == trim(strtolower('other'))){
              $other_shipping = 'block';
              $shipping_method_drp = 'none';
              $other_ship_value = $po_master_data['shipping_method'];
            }
            else if(set_value('other_shipping_method') && !empty(set_value('other_shipping_method'))){
              $other_shipping = 'block';
              $other_ship_value = set_value('other_shipping_method');
              $shipping_method_drp = 'none';
            }
            elseif(trim(strtolower($po_master_data['shipping_company'])) == trim(strtolower('Freight'))){
              $shipping_method_drp = 'none';
              $other_shipping = 'none;';
              $other_ship_value = '';
            }
            else{
              $other_ship_value = '';
              $other_shipping = 'none;';
            }
            ?>
          <!-- End Loader -->
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                  <label for="shipping_company" class="control-label">Shipping Company<abbr>*</abbr></label>
                  <select class="shipping_company form-control" id="shipping_company" name="shipping_company">
                    <option value="">Select shipping company</option>
                    <?php foreach (SHIPPING_COMPANY as $carrier_key => $carrier_value): ?>
                      <option value="<?php echo  $carrier_key;?>" <?php echo (trim(strtolower($po_master_data['shipping_company'])) == trim(strtolower($carrier_key))) ? 'selected' : ''; ?>><?php echo  $carrier_value;?></option>
                    <?php endforeach;?>
                  </select>
              </div>
            </div>
            <div class="col-md-6 shipping-method-container" style="display: <?php echo $shipping_method_drp; ?>;">
              <div class="form-group">
                  <label for="shipping_method" class="control-label">Shipping Method<abbr>*</abbr></label>
                  <select class="shipping_method form-control" id="shipping_method" name="shipping_method">
                    <option value="">Select shipping method</option>
                    <?php
                    if(isset($shipping_method_list) && !empty($shipping_method_list) && $po_master_data['shipping_method']):
                      foreach ($shipping_method_list as $key => $value):
                        if(strtolower($value['company']) == strtolower($po_master_data['shipping_company'])):?>
                          <option value="<?php echo $value['name']; ?>" <?php echo (trim(strtolower($po_master_data['shipping_method'])) == trim(strtolower($value['name']))) ? 'selected': ''; ?>><?php echo $value['name']; ?></option>
                      <?php
                        endif;
                      endforeach; 
                    endif;?>
                  </select>
                  <span id="shipping_method_required" class="error" style="display: none;">This field is required</span>
              </div>
            </div>
            <div class="col-md-6 other-method-container" style="display: <?php echo $other_shipping; ?>;">
              <div class="form-group">
                  <label for="shipping_method" class="control-label">Shipping Method<abbr>*</abbr></label>
                  <input type="text" name="other_shipping_method" class="form-control" id="other_shipping_method" placeholder="" value="<?php echo $other_ship_value; ?>">
                  <span id="other_method_required" class="error" style="display: none;">This field is required</span>
              </div>
            </div>            
            <div class="col-md-6">
              <div class="form-group">
                <label for="shipping_account_number" class="control-label">Default Shipping Account Number</label>
                <input type="text" name="shipping_account_number" class="form-control" id="shipping_account_number" placeholder="" value="<?php echo $po_master_data['shipping_account_number'];?>">
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
              <?php
              if(!empty($po_items) ):
                $i = 1;
                $subtotal = 0.00;
                $total = 0.00;
                foreach ($po_items as $pokey => $povalue) {
                  $unitTotal = 0;
                ?>
                <div class="row clone-container" id="clone_container_<?php echo $i?>">
                  <div class="field-3">
                    <div class="form-group">
                      <input type="text" name="sku[]" class="form-control sku-input" id="sku_<?php $i ?>" value="<?php echo $povalue['sku'];?>">
                    </div>
                  </div>
                  <div class="field-3">
                    <div class="form-group">
                      <textarea class="form-control description-input" id="description_<?php echo $i?>" name="description[]"><?php echo $povalue['description'];?></textarea>
                    </div>
                  </div>
                  <div class="field-3">
                    <div class="form-group">
                      <input type="number" name="qty[]" class="form-control qty-input number_field" id="qty_<?php echo $i?>" data-bind-id="<?php echo $i?>" value="<?php echo $povalue['qty'];?>">
                    </div>
                  </div>
                  <div class="field-3">
                    <div class="form-group price-container">
                      <span class="currency-symbol">$</span>
                      <input type="text" name="price_per_unit[]" class="form-control price-per-unit-input number_field" data-bind-id="<?php echo $i?>" id="price_per_unit_<?php echo $i?>" value="<?php echo $povalue['price'];?>">
                    </div>
                  </div>
                </div>
                <?php 
                    $unitTotal = $povalue['qty'] * $povalue['price'];
                    $subtotal = $subtotal + $unitTotal;
                    $total = $total + $unitTotal;
                    $i++;
                }; // end for loop ?>
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
        <div class="all-price-calculation-container">
          <table>            
            <tr>
              <th>Total: </th>
              <td id="total_amount">
              <?php
              $total = ($total) ? $total: 0.00;
              $total = number_format((float)$total, 2, '.', '');
              echo '$'.$total;?>
              </td>
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
        <span class="remove-success success"></span>
        <span class="remove-error error" style="display: none"></span>
        <?php
            $i = 1;
            $other_document_size = sizeof($po_attachment);
            if($other_document_size > 0){
              foreach($po_attachment as $po_attachmentKey => $po_attachmentValue){ 
          ?>
          <div class="row clone-other-doc-field" id="clone_other_doc_container_<?php echo $po_attachmentValue['id'];?>">
          <div class="col-md-6">
            <div class="form-group">
              <input type="text" name="document_label[]" class="form-control document_label" id="document_label_<?php echo $i; ?>" placeholder="" value="<?php echo $po_attachmentValue['document_label'];?>">
            </div>
          </div>
            <div class="col-md-5">
              <div class="form-group">
                <div id="other_document">
                  <div class="input-group">
                    <div class="">
                      <label class="control-label">&nbsp;</label>
                      <?php
                        if(!empty($po_attachmentValue['po_attachment'])){
                      ?>
                      <a href="<?php echo base_url().'uploads/po_attachment/'.$po_attachmentValue['po_attachment'];?>"><?php echo $po_attachmentValue['po_attachment']; ?></a>
                      <?php   
                        } 
                      ?>
                    </div>
                  </div>
                </div>              
              </div>
            </div>
            <div class="col-md-1 text-center">
              <div class="form-group">
                <label class="delete-data">
                  <i class="fa fa-remove removeclass" id="remove_<?php echo $i;?>" data-bind-id="<?php echo $po_attachmentValue['id'];?>"></i>
                </label>
              </div>
            </div>
          </div>
            <?php
                $i++;
            } 
          }
            ?>
            <div class="row clone-other-doc-field" id="clone_other_doc_container_<?php echo $i;?>">
          <div class="col-md-6">
            <div class="form-group">
              <input type="text" name="document_label[]" class="form-control document_label" id="document_label_<?php echo $i; ?>" placeholder="" value="">
            </div>
          </div>
            <div class="col-md-6">
              <div class="form-group">
                <div id="other_document">
                  <div class="input-group">
                    <div class="">
                      <label class="control-label">&nbsp;</label>
                      <input type="file" class="additional-document" name="other_document[]" id="other_document_<?php echo $i; ?>">
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
                <textarea name="internal_notes" class="form-control" id="internal_notes" value="<?php echo $po_master_data['shipping_company']; ?>"><?php echo $po_master_data['internal_notes']; ?></textarea>
              </div>
          </div>
        </div>
      </div>
    </div>
    <!--internal note block end -->
    <!--checkbox block start-->
    <div class="separate-section-box">
      <div class="box-header">
        <div class="d-inline-block">
          <h3 class="card-title"> Order status </h3>
        </div>
      </div>
      <div class="box-body">
        <div class="tax-taxable-container separate-section">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <div class="checkbox-container">
                  <label for="is_posted" class="control-label">Posted:</label>
                  <?php 
                  $checkedValue = '';
                  $checked = '';
                    if(isset($po_master_data['is_posted']) ){
                      if($po_master_data['is_posted']==1){
                        $checkedValue = 1;
                        $checked = 'checked';
                      }
                      else{
                          $checkedValue = 0;
                          $checked = '';
                      }
                    } 
                  ?>
                  <input name="is_posted" class="tgl_checkbox tgl-ios" id="is_posted" type="checkbox" value="<?php echo $checkedValue; ?>" <?php echo $checked ; ?>/>
                  <label for="is_posted"></label>
                </div>
                <?php
                  $action_arr['controller'] = 'po';
                  $action_arr['action']     = 'landed';
                  $check_permission = get_permission_by_action($action_arr);
                  if($check_permission == TRUE): 
                ?>
                <div class="checkbox-container">
                  <label for="is_landed" class="control-label">Landed:</label>
                  <?php 
                  $checkedValue = '';
                  $checked = '';
                    if(isset($po_master_data['is_landed']) ){
                      if($po_master_data['is_landed']==1){
                        $checkedValue = 1;
                        $checked = 'checked';
                      }
                      else{
                          $checkedValue = 0;
                          $checked = '';
                      }
                    } 
                  ?>
                  <input name="is_landed" class="tgl_checkbox tgl-ios" id="is_landed" type="checkbox" value="<?php echo $checkedValue; ?>" <?php echo $checked ; ?> />
                  <label for="is_landed"></label>
                </div>
              <?php endif;?>
              <?php
                $action_arr['controller'] = 'po';
                $action_arr['action']     = 'received';
                $check_permission = get_permission_by_action($action_arr);
                if($check_permission == TRUE): 
              ?>
                <div class="checkbox-container">
                  <label for="is_received" class="control-label">Received:</label>
                  <?php 
                  $checkedValue = '';
                  $checked = '';
                    if(isset($po_master_data['is_received']) ){
                      if($po_master_data['is_received']==1){
                        $checkedValue = 1;
                        $checked = 'checked';
                      }
                      else{
                          $checkedValue = 0;
                          $checked = '';
                      }
                    } 
                  ?>
                  <input name="is_received" class="tgl_checkbox tgl-ios" id="is_received" type="checkbox" value="<?php echo $checkedValue; ?>" <?php echo $checked ; ?> />
                  <label for="is_received"></label>
                </div>
              <?php endif; ?>  
                <div class="checkbox-container">
                  <label for="is_discrepancy" class="control-label">Discripency:</label>
                  <?php 
                  $checkedValue = '';
                  $checked = '';
                  if(isset($po_master_data['is_discrepancy'])){
                    if($po_master_data['is_discrepancy'] == 1){
                      $checkedValue = 1;
                      $checked = 'checked';
                    }
                    else{
                        $checkedValue = 0;
                        $checked = '';
                    }
                  }?>
                  <input name="is_discrepancy" class="tgl_checkbox tgl-ios" id="is_discrepancy" type="checkbox" value="<?php echo $checkedValue; ?>" <?php echo $checked ; ?> />
                  <label for="is_discrepancy"></label>
                </div>
                <div class="checkbox-container">
                  <label for="is_closed" class="control-label">closed:</label>
                  <?php 
                  $checkedValue = '';
                  $checked = '';
                  if(isset($po_master_data['is_closed']) ){
                    if($po_master_data['is_closed']==1){
                      $checkedValue = 1;
                      $checked = 'checked';
                    }
                    else{
                      $checkedValue = 0;
                      $checked = '';
                    }
                  } 
                  ?>
                  <input name="is_closed" class="tgl_checkbox tgl-ios" id="is_closed" type="checkbox" value="<?php echo $checkedValue; ?>" <?php echo $checked ; ?> />
                  <label for="is_closed"></label>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--checkbox block end-->
    <!--footer button start-->
    <div class="row">
      <div class="btn-wrapper">
        <?php if(isset($po_master_data['is_posted']) && $po_master_data['is_posted'] == 0):?>
          <input type="submit" name="save_for_later" id="save_for_later" value="Save for later" class="btn btn-info pull-left">
        <?php endif ?>
        <input type="submit" name="save_and_post" id="save_and_post" value="Save and post" class="btn btn-info pull-left">
        <input type="submit" name="save_post_email" id="save_post_email" value="Save, post and email" class="btn btn-info pull-left">
        
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
  var totalDocument = "<?php echo $other_document_size; ?>";
  if(totalDocument ==0){
    totalDocument = 1
  }
  else{
    totalDocument = parseInt(totalDocument) + 1;
  }
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
              $("#billing_zipcode").val(result.vendorAddressDetail[0].zipcode);
              $("#billing_fax").val(result.vendorAddressDetail[0].fax);
              $("#billing_contact").val(result.vendorAddressDetail[0].telephone);
              $("#billing_city").val(result.vendorAddressDetail[0].city);
              $("#billing_state").val(result.vendorAddressDetail[0].state);
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
   * Remove additional document
   */
  $('.fa.fa-remove.removeclass').click(function(){
    if (!confirm("Do you want to delete this file?")){
      return false;
    }
    var doc_id = '';
    doc_id = $(this).attr('data-bind-id');
    $.ajax({
      url: '<?php echo base_url("po/po/remove_doc"); ?>', 
      type: 'post',
      data: {
        '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>',
        'doc_id': doc_id,
        'po_id': "<?php echo $po_master_data['id']?>"
      },
      beforeSend: function() {
        $("#loading").show();
      },
      success: function(response){
        var result = html = '';
        result = $.parseJSON(response);
        if(typeof result.access_denied != "undefined"){
          window.location.href = "<?php echo base_url('access');?>";
        } else {
          if(typeof result.success != "success" ){
            $('.remove-success').html(result.success);
            $('.remove-error').hide();
            $('.remove-success').show();
            $('#clone_other_doc_container_'+doc_id).remove();
          } else if(typeof result.error != "error"){
            $('.remove-error').html(result.error);
            $('.remove-error').show();
            $('.remove-success').hide();
          }
        }
        if(result.success)
        $("#loading").hide();
      }
    });
  });
    /*
     * Use for payment term
     */
    $("#shipping_company").change(function(){
      $("#loading").show();
      $('#shipping_method_required').hide();
      var shipping_company = html = '';
      shipping_company = $(this).val();
      html = '<option value="">Select shipping method</option>';
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
        var total_row = totalDocument;
        total_row = parseInt(total_row) + 1;
        var other_doc_html = '<div class="row clone-other-doc-field" id="clone_other_doc_container_'+total_row+'"><div class="col-md-6"><div class="form-group"><input type="text" name="document_label[]" class="form-control document_label" id="document_label_'+total_row+'"/></div></div><div class="col-md-5"><div class="form-group"><div id="other_document_"'+total_row+'><div class="input-group"><div class=""><lable class="control-label"></lable><input type="file" class="additional-document" name="other_document[]" id="other_document_'+total_row+'"/></div></div></div></div></div><div class="col-md-1 text-center reporting-field-9"><div class="form-group"><lable class="delete-data"><i class="fa fa-remove removeclass" id="remove_'+total_row+'" data-attr="'+total_row+'"></i></lable></div></div></div>';
        $('.clone-other-doc-container').append(other_doc_html);
        totalDocument = total_row;
        refreshEvent();
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
        $(document).on("click blur", ".qty-input", function () {
          //calculate_price();
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
          var qty = price = data_bind_id = amount = 0;
          price = $(this).val();
          data_bind_id = $(this).attr('data-bind-id');
          qty = $("#qty_"+data_bind_id).val();
          amount = (qty * price);
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
        subtotal = $("#subtotal").attr('data-bind-price-subtotal');
        if(typeof qty != "undefined" && typeof price != "undefined"){
          amount = (qty*price);
          update_subtotal = subtotal - amount;
          $("#subtotal").text('$'+update_subtotal.toFixed(2));
          $("#subtotal").attr('data-bind-price-subtotal', update_subtotal.toFixed(2));
          $("#total_amount").text('$'+update_subtotal.toFixed(2));
          $("#total_amount").attr('data-bind-price-total', update_subtotal.toFixed(2));
        }
        $(this).parents('.clone-container').remove();
      });
    }
    function refreshEvent(){
      $('.fa.fa-remove.removeclass').click(function(){
        var attrId = $(this).attr('id');
        var id = attrId.replace("remove_", "");
        $('#clone_other_doc_container_'+id+'').remove();
      });
    }
  /* 
  * Function use to trigger download feature
  */
  $('.fa.fa-download.edit-pdf-download').click(function()
  {
    $('.input-pdf-download').trigger('click');
  });
  });
</script>
