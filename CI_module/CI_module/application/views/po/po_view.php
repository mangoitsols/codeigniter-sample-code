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
    <div class="card card-default color-palette-bo">
      <div class="card-header">
        <div class="d-inline-block">
          <h3 class="card-title"> <i class="fa fa-plus"></i>Purchase Order <?php echo ($po_master_data['po_number'])? $po_master_data['po_number'] : '';?><span class="header-date"> Date : <?php echo($po_master_data['created_at'])? date("m/d/Y", strtotime($po_master_data['created_at']) ) : ''; ?></span> </h3>
        </div>
        <!--log-->
        <div class="d-inline-block float-right">
          <a href="<?= base_url('po/list'); ?>" class="btn btn-success"><i class="fa fa-list"></i>  PO List</a>
        </div>
        <?php if($price_permission == TRUE):?>
          <div class="d-inline-block float-right success download-pdf-container">
            <i class="fa fa-download edit-pdf-download"></i><input type="submit" name="download_pdf" value="Save PDF" class="input-pdf-download">
          </div>
        <?php endif; ?>
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
                <label for="vendor" class="control-label">Vendor: </label>
                <?php
                if(!empty($vendorList)){
                  foreach($vendorList as $vendor){
                    if($vendorInfo->id == $vendor['id'])
                      echo $vendor['vendor_name'];
                    }
                } ?>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="email" class="control-label">Email: </label>
                <?php echo ($po_master_data['email']) ? $po_master_data['email'] : ''?>
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
                <label class="control-label">Street Address: </label>
                <?php echo $b_street_address_1; ?>
                <?php echo $b_street_address_2; ?>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">City</label>
                <?php echo $b_city; ?>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">Country: </label>
                  <?php
                  $selected_country = '';
                  $selected_country = $b_country;
                  foreach ($country as $country_key => $country_value) {
                    echo ($selected_country == $country_value['country_code']) ? $country_value['country_name']: ''; 
                  } ?>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                  <label class="control-label">State: </label>
                  <span style="display: <?php echo ($selected_country != 'US' && $selected_country != '') ? 'inline-block' : 'none'; ?>;"><?php echo $b_state; ?></span>
                  <span style="display: <?php echo ($selected_country == 'US' || $selected_country == '') ? 'inline-block' : 'none'; ?>;">
                    <?php
                    foreach ($state_list as $state_key => $state_value) {
                      echo ( $b_state == $state_value['state_code'] ) ? $state_value['name'] : '';
                    } ?>
                  </span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                  <label class="control-label">Zip Code: </label>
                  <?php echo $b_zipcode; ?>
                </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                  <label for="billing_contact" class="control-label">Contact Number: </label>
                  <?php echo $b_telephone; ?>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="billing_fax" class="control-label">Fax: </label>
                <?php echo $b_fax; ?>
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
                  <label class="control-label">Street Address: </label>
                  <?php echo $sh_street_address_1; ?>
                  <?php  echo $sh_street_address_2; ?>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">City: </label>
                <?php echo $sh_city ;?>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                  <label class="control-label">Country: </label>
                    <?php
                    $selected_country = '';
                    $selected_country = $sh_country;
                    foreach ($country as $country_key => $country_value) {
                      echo ($selected_country == $country_value['country_code']) ? $country_value['country_name']: '';
                    } ?>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                  <label class="control-label">State</label>
                  <span style="display: <?php echo ($selected_country != 'US' && $selected_country != '') ? 'inline-block' : 'none'; ?>;"><?php echo $sh_state ;?></span>
                  <span style="display: <?php echo ($selected_country == 'US' || $selected_country == '') ? 'inline-block' : 'none'; ?>;">
                    <?php
                    foreach ($state_list as $state_key => $state_value) {
                      echo ($sh_state == $state_value['state_code']) ? $state_value['name'] : '';
                    } ?>
                  </span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                  <label class="control-label">Zip Code: </label>
                  <?php echo $sh_zipcode; ?>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                  <label for="shipping_contact" class="control-label">Contact Number: </label>
                  <?php echo $sh_telephone; ?>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                  <label for="shipping_fax" class="control-label">Fax: </label>
                  <?php echo $sh_fax; ?>
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
                <label for="tracking_number" class="control-label">Tracking Number: </label>
                <?php echo (isset($po_master_data['tracking_number']) )? $po_master_data['tracking_number']: '';?>
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
                <label for="payment_id" class="control-label">Payment Id: </label>
                <?php echo (isset($po_master_data['payment_trans_id']) )? $po_master_data['payment_trans_id']: '';?>
              </div>
            </div>
          <?php endif; ?>  
          </div>
        </div>
        <div class="tax-taxable-container separate-section">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="payment_term" class="control-label">Payment Terms: </label>
                  <?php foreach (CUSTOMER_PAYMENT_TERMS as $payment_key => $payment_value): ?>
                    <?php echo ($po_master_data['payment_term'] == $payment_key) ? $payment_value : ''; ?>
                  <?php endforeach;?>
              </div>
            </div>
            <?php
            $other_term = 'none';
            if(trim(strtolower($po_master_data['payment_term'])) == trim(strtolower('other')))
              $other_term = 'block';
            ?>
              <div class="col-md-6">
                <div class="other_terms_container" style="display: <?php echo $other_term;?>;">
                  <label class="control-label">Other: </label>
                  <?php echo  $po_master_data['other_payment_term'];?>
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
            else{
              $other_ship_value = '';
              $other_shipping = 'none;';
            }
            ?>
          <!-- End Loader -->
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                  <label for="shipping_company" class="control-label">Shipping Company: </label>
                  <?php foreach (SHIPPING_COMPANY as $carrier_key => $carrier_value):
                    echo ($po_master_data['shipping_company'] == $carrier_key) ? $carrier_value : '';
                  endforeach;?>
              </div>
            </div>
            <div class="col-md-6 shipping-method-container" style="display: <?php echo $shipping_method_drp; ?>;">
              <div class="form-group">
                  <label for="shipping_method" class="control-label">Shipping Method: </label>
                  <?php
                    if(isset($shipping_method_list) && !empty($shipping_method_list) && $po_master_data['shipping_method']):
                      foreach ($shipping_method_list as $key => $value):
                        if(strtolower($value['company']) == strtolower($po_master_data['shipping_company'])):
                          echo ($po_master_data['shipping_method'] == $value['name']) ? $value['name']: '';
                        endif;
                      endforeach; 
                    endif;?>
              </div>
            </div>
            <div class="col-md-6 other-method-container" style="display: <?php echo $other_shipping; ?>;">
              <div class="form-group">
                  <label for="shipping_method" class="control-label">Shipping Method: </label>
                  <?php echo $other_ship_value; ?>
              </div>
            </div>            
            <div class="col-md-6">
              <div class="form-group">
                <label for="shipping_account_number" class="control-label">Default Shipping Account Number</label>
                <?php echo $po_master_data['shipping_account_number'];?>
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
        <div class="all-item-container">
            <div class="row create-items-heading">
              <div class="field-3">
                <div class="form-group control-label">
                  <label for="sku">SKU</label>
                </div>
              </div>
              <div class="field-3">
                <div class="form-group">
                  <label for="absent">Description</label>
                </div>
              </div>
              <div class="field-3">
                <div class="form-group control-label">
                  <label for="quantity">Quantity</label>
                </div>
              </div>
              <div class="field-3">
                <div class="form-group control-label">
                  <label for="Price per unit">Price per unit</label>
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
                      <?php echo $povalue['sku'];?>
                    </div>
                  </div>
                  <div class="field-3">
                    <div class="form-group">
                      <?php echo $povalue['description'];?>
                    </div>
                  </div>
                  <div class="field-3">
                    <div class="form-group">
                      <?php echo $povalue['qty'];?>
                    </div>
                  </div>
                  <div class="field-3">
                    <div class="form-group price-container">
                      <?php if($price_permission == TRUE):?>
                        <span class="currency-symbol">$</span>
                        <?php echo $povalue['price'];?>
                      <?php else: ?>
                        <?php echo '-';?>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
                <?php 
                    $unitTotal = $povalue['qty'] * $povalue['price'];
                    $subtotal = $subtotal + $unitTotal;
                    $total = $total + $unitTotal;
                    $i++;
                }; // end for loop ?>
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
              if($price_permission == TRUE)
                echo '$'.$total;
              else
                echo '-';
              ?>
              </td>
            </tr>
          </table>
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
              <?php echo $po_attachmentValue['document_label'];?>
            </div>
          </div>
            <div class="col-md-6">
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
          </div>
            <?php
                $i++;
            } 
          }?>
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
                <label for="internal_notes" class="control-label">Internal Notes: </label>
                <?php echo (isset($po_master_data['internal_notes']) && !empty($po_master_data['internal_notes'])) ? $po_master_data['internal_notes'] : 'NA'; ?>
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
                  <input disabled class="tgl_checkbox tgl-ios" id="is_posted" type="checkbox" value="<?php echo $checkedValue; ?>" <?php echo $checked ; ?>/>
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
                  <input disabled class="tgl_checkbox tgl-ios" id="is_landed" type="checkbox" value="<?php echo $checkedValue; ?>" <?php echo $checked ; ?> />
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
                  <input disabled class="tgl_checkbox tgl-ios" id="is_received" type="checkbox" value="<?php echo $checkedValue; ?>" <?php echo $checked ; ?> />
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
                  <input disabled class="tgl_checkbox tgl-ios" id="is_discrepancy" type="checkbox" value="<?php echo $checkedValue; ?>" <?php echo $checked ; ?> />
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
                  <input disabled class="tgl_checkbox tgl-ios" id="is_closed" type="checkbox" value="<?php echo $checkedValue; ?>" <?php echo $checked ; ?> />
                  <label for="is_closed"></label>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--checkbox block end-->
  </section>
</div>