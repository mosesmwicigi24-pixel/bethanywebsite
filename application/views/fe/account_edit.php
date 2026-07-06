    <div class="ps-page--single">
        <div class="ps-breadcrumb">
            <div class="container">
                <ul class="breadcrumb">
                    <li><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li>My Account</li>
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
                                    <a href="<?php echo base_url();?>account/edit" class="active d-flex border-bottom"> <span class="icon1 mr-3"><i class="icon icon-user"></i></span> Edit Account </a>
                                    <a href="<?php echo base_url();?>account/address" class="d-flex border-bottom"> <span class="icon1 mr-3"><i class="icon icon-map-marker"></i></span> Address Book </a>
                                    <a href="<?php echo base_url();?>account/logout" class="d-flex"> <span class="icon1 mr-3"><i class="icon icon-lock"></i></span> Logout </a>
                                </div>


                            </div>
                        </div>
                    </div>
                    <div class="ps-section__right">
                        <div class="ps-form--account">
                            <ul class="ps-tab-list text-left">
                                <li class="active"><a href="#edit-account"><i class="icon icon-user"></i></span> Edit Account</a></li>
                                <li><a href="#change-password"><i class="icon icon-finger-tap"></i></span> Change Password</a></li>
                            </ul>
                            <div class="ps-tabs">
                                <div class="ps-tab bg-white border-none active" id="edit-account">
                                    <?php foreach ($account as $row): ?>
                                        <form id="frm_edit_account" name="frm_edit_account" class="" method="post" onsubmit="return submit_update_account();">

                                            <div class="row">
                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label class="form-label text-dark">First Name <span class="text-danger">*</span></label>
                                                                <input id="account_first_name" name="first_name" type="text" class="form-control" placeholder="" value="<?php echo $row->first_name; ?>">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label text-dark">Last Name <span class="text-danger">*</span></label>
                                                                <input id="account_last_name" name="last_name" type="text" class="form-control" placeholder="" value="<?php echo $row->last_name; ?>">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label class="form-label text-dark">Email Address <span class="text-danger">*</span></label>
                                                                <input id="account_email_address" name="email_address" type="email" class="form-control" placeholder="" value="<?php echo $row->email_address; ?>">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label text-dark">Phone Number <span class="text-danger">*</span></label>
                                                                <input id="account_phone_number" name="phone_number" type="text" class="form-control" placeholder="" value="<?php echo $row->phone_number; ?>">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label class="form-label text-dark">Gender</label>
                                                                <select id="gender" name="gender" class="form-control select2" data-placeholder="Choose Gender" data-fouc aria-hidden="true">
                                                                    <option value="">Choose Gender</option>
                                                                    <option value="Female" <?php if ($row->gender == 'Female'){ echo 'selected'; } ?>>Female</option>
                                                                    <option value="Male" <?php if ($row->gender == 'Male'){ echo 'selected'; } ?>>Male</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label text-dark">Date of Birth</label>
                                                                <input id="account_birth_date" name="birth_date" type="text" class="form-control pickadate" placeholder="" value="<?php echo $row->birth_date; ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="form-group text-right">
                                                        <button id="btn_edit_account" class="btn btn-success font-weight-semibold"><i class="icon-check"></i> UPDATE ACCOUNT</button>
                                                    </div>
                                                </div>
                                            </div>

                                        </form>
                                    <?php endforeach; ?>

                                </div>
                                <div class="ps-tab bg-white border-none" id="change-password">
                                    <form id="frm_change_password" name="frm_change_password" class="" method="post" onsubmit="return submit_change_password();">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div id="div_change_password_error" class="alert alert-danger display-none font-13"></div>
                                                <div id="div_change_password_success" class="alert alert-success display-none font-13"></div>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label class="form-label text-dark">Old Password <span class="text-danger">*</span></label>
                                                            <input id="old_password" name="old_password" type="password" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label class="form-label text-dark">New Password <span class="text-danger">*</span></label>
                                                            <input id="new_password" name="new_password" type="password" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label class="form-label text-dark">Confirm Password <span class="text-danger">*</span></label>
                                                            <input id="confirm_password" name="confirm_password" type="password" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>

                                                <hr>
                                                <div class="form-group text-right">
                                                    <button id="btn_change_password" class="btn btn-success font-weight-semibold"><i class="icon-check"></i> CHANGE PASSWORD</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
    </div>