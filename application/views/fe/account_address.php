    <div class="ps-page--single">
        <div class="ps-breadcrumb">
            <div class="container">
                <ul class="breadcrumb">
                    <li><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li><a href="<?php echo base_url(); ?>account">Account</a></li>
                    <li>My Shipping Address</li>
                </ul>
            </div>
        </div>
        <div class="ps-vendor-store">
            <div class="container">
                <div class="ps-section__container">
                    <div class="ps-section__left">
                        <div class="ps-block--vendor">
                            <div class="ps-block__container">


                                <div class="item1-links  mb-0">
                                    <a href="<?php echo base_url();?>account" class="d-flex border-bottom"> <span class="icon1 mr-3"><i class="icon icon-user"></i></span> My Account </a>
                                    <a href="<?php echo base_url();?>account/orders" class="d-flex  border-bottom"> <span class="icon1 mr-3"><i class="icon icon-cart"></i></span> Orders </a>
                                    <a href="<?php echo base_url();?>account/favorites" class="d-flex  border-bottom"> <span class="icon1 mr-3"><i class="icon icon-heart"></i></span> Favorites </a>
                                    <a href="<?php echo base_url();?>account/edit" class="d-flex border-bottom"> <span class="icon1 mr-3"><i class="icon icon-user"></i></span> Edit Account </a>
                                    <a href="<?php echo base_url();?>account/address" class="active d-flex border-bottom"> <span class="icon1 mr-3"><i class="icon icon-map-marker"></i></span> Address Book </a>
                                    <a href="<?php echo base_url();?>account/logout" class="d-flex"> <span class="icon1 mr-3"><i class="icon icon-lock"></i></span> Logout </a>
                                </div>


                            </div>
                        </div>
                    </div>
                    <div class="ps-section__right">
                        <div class="ps-form--account">
                            <ul class="ps-tab-list text-left">
                                <li class="active"><a href="#edit-address"><i class="icon icon-map-marker"></i></span> My Shipping Address</a></li>
                            </ul>
                            <div class="ps-tabs">
                                <div class="ps-tab bg-white border-none active" id="edit-address">
                                    <?php foreach ($account as $row): ?>
                                        <form id="frm_edit_address" name="frm_edit_address" class="" method="post" onsubmit="return submit_update_address();">

                                            <div class="row">
                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label class="form-label text-dark">First Name <span class="text-danger">*</span></label>
                                                                <input id="shipping_first_name" name="shipping_first_name" type="text" class="form-control" placeholder="" value="<?php echo $row->shipping_first_name; ?>">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label text-dark">Last Name <span class="text-danger">*</span></label>
                                                                <input id="shipping_last_name" name="shipping_last_name" type="text" class="form-control" placeholder="" value="<?php echo $row->shipping_last_name; ?>">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label class="form-label text-dark">Email Address <span class="text-danger">*</span></label>
                                                                <input id="shipping_email_address" name="shipping_email_address" type="email" class="form-control" placeholder="" value="<?php echo $row->shipping_email_address; ?>">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label text-dark">Phone Number <span class="text-danger">*</span></label>
                                                                <input id="shipping_phone_number" name="shipping_phone_number" type="text" class="form-control" placeholder="" value="<?php echo $row->shipping_phone_number; ?>">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <label>Delivery Addresss <span class="text-danger">*</span></label>
                                                                <div class="form-group__content">
                                                                    <textarea id="shipping_street_address" name="shipping_street_address" class="form-control" rows="3" placeholder="Street Name / Building / Apartment No. / Floor"><?php echo $row->shipping_street_address; ?></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label>Country <span class="text-danger">*</span></label>
                                                                <div class="form-group__content">
                                                                    <select id="shipping_country_id" name="shipping_country_id" class="form-control" data-placeholder="Select Country" data-fouc>
                                                                        <option value="">Select Country</option>
                                                                        <?php foreach ($countries as $row2): ?>
                                                                            <option value="<?php echo $row2->country_id; ?>" <?php if ($row->shipping_country_id == $row2->country_id){ echo 'selected'; } ?>><?php echo $row2->country_name; ?></option>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>Region <span class="text-danger">*</span></label>
                                                                <div class="form-group form-group-feedback form-group-feedback-right mb-2">
                                                                    <select id="shipping_region_id" name="shipping_region_id" class="form-control" data-placeholder="Select Region" data-fouc>
                                                                        <option value="">Select Region</option>
                                                                    </select>
                                                                    <div id="shipping_region_loader" class="form-control-feedback display-none">
                                                                        <i class="fa fa-spinner fa-spin text-success"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <hr>
                                                    <div class="form-group text-right">
                                                        <button id="btn_edit_address" class="btn btn-success font-weight-semibold"><i class="icon-check"></i> UPDATE ADDRESS</button>
                                                    </div>
                                                </div>
                                            </div>

                                        </form>
                                        <script type="text/javascript">
                                            cur_shipping_region_id = '<?php echo $row->shipping_region_id; ?>';

                                            $(document).ready(function() {
                                                $("#shipping_country_id").trigger("change");
                                            });
                                        </script>
                                    <?php endforeach; ?>

                                </div>

                            </div>
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
    </div>