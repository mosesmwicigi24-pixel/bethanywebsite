<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <?php if (!isset($customer)): ?>
                	<div class="nk-block nk-block-lg">
                        <div class="nk-block-head nk-block-head-sm">
    					    <div class="nk-block-between">
    					        <div class="nk-block-head-content"><h5 class="nk-block-title page-title"><em class="icon ni ni-plus-c"></em> New Customer</h5></div>
                                <div class="nk-block-head-content">
                                    <div class="toggle-wrap nk-block-tools-toggle">
                                        <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                                        <div class="toggle-expand-content" data-content="pageMenu">
                                            <ul class="nk-block-tools g-3">
                                                <li class="nk-block-tools-opt">
                                                    <a href="<?php echo base_url(); ?>pos/sales/customers" class="btn btn-icon btn-sm btn-primary d-md-none"><em class="icon ni ni-users-fill"></em></a>
                                                    <a href="<?php echo base_url(); ?>pos/sales/customers" class="btn btn-primary btn-sm d-none d-md-inline-flex"><em class="icon ni ni-users-fill"></em><span>Customers List</span></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
    					    </div>
    					</div>

                        <div class="card card-preview col-md-6">
                            <div class="card-inner">
                                <form id="frm_add_customer" name="frm_add_customer" method="post" onsubmit="return save_customer();">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="first_name">First Name<span class="text-danger">*</span></label>
                                                <div class="form-control-wrap"><input type="text" class="form-control" id="first_name" name="first_name" required /></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="last_name">Last Name<span class="text-danger">*</span></label>
                                                <div class="form-control-wrap"><input type="text" class="form-control" id="last_name" name="last_name" required /></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">    
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="email_address">Email Address<span class="text-danger">*</span></label>
                                                <div class="form-control-wrap"><input type="email" class="form-control" id="email_address" name="email_address" required /></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="phone_number">Phone Number<span class="text-danger">*</span> <small>(Format:0700123456)</small></label>
                                                <div class="form-control-wrap"><input type="text" class="form-control" id="phone_number" name="phone_number" required /></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="gender">Gender</label>
                                                <div class="form-control-wrap">
                                                    <select class="form-control select2" data-placeholder="Select Gender" id="gender" name="gender">
                                                        <option label="" value=""></option>
                                                        <option value="Female">Female</option>
                                                        <option value="Male">Male</option>
                                                        <option value="Other">Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="birth_date">Birth Date <small>(yyyy-mm-dd)</small></label>
                                                <div class="form-control-wrap">
                                                    <!-- <div class="form-icon form-icon-left">
                                                        <em class="icon ni ni-calendar"></em>
                                                    </div> -->
                                                    <input type="text" id="birth_date" name="birth_date" class="form-control date-picker" data-date-format="yyyy-mm-dd">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="password">Password<span class="text-danger">*</span></label>
                                                <div class="form-control-wrap"><input type="password" class="form-control" id="password" name="password" required /></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label" for="confirm_password">Confirm Password<span class="text-danger">*</span></label>
                                                <div class="form-control-wrap"><input type="password" class="form-control" id="confirm_password" name="confirm_password" required /></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group text-right"><button type="submit" id="btn_add_customer" class="btn btn-primary"><em class="icon ni ni-save"></em> Save Customer</button></div>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        	
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($customer as $row): ?>
                        <div class="nk-block nk-block-lg">
                            <div class="nk-block-head nk-block-head-sm">
                                <div class="nk-block-between">
                                    <div class="nk-block-head-content"><h5 class="nk-block-title page-title"><em class="icon ni ni-edit"></em> Edit Customer</h5></div>
                                    <div class="nk-block-head-content">
                                        <div class="toggle-wrap nk-block-tools-toggle">
                                            <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                                            <div class="toggle-expand-content" data-content="pageMenu">
                                                <ul class="nk-block-tools g-3">
                                                    <li class="nk-block-tools-opt">
                                                        <a href="<?php echo base_url(); ?>pos/sales/customers" class="btn btn-icon btn-sm btn-primary d-md-none"><em class="icon ni ni-users-fill"></em></a>
                                                        <a href="<?php echo base_url(); ?>pos/sales/customers" class="btn btn-primary btn-sm d-none d-md-inline-flex"><em class="icon ni ni-users-fill"></em><span>Customers List</span></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card card-preview col-md-6">
                                <div class="card-inner">
                                    <form id="frm_edit_customer" name="frm_edit_customer" method="post" onsubmit="return update_customer();">
                                        <input type="hidden" id="customer_id" name="customer_id" value="<?php echo $row->customer_id; ?>">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="first_name">First Name<span class="text-danger">*</span></label>
                                                    <div class="form-control-wrap"><input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $row->first_name; ?>" required /></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="last_name">Last Name<span class="text-danger">*</span></label>
                                                    <div class="form-control-wrap"><input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $row->last_name; ?>" required /></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">    
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="email_address">Email Address<span class="text-danger">*</span></label>
                                                    <div class="form-control-wrap"><input type="email" class="form-control" id="email_address" name="email_address" value="<?php echo $row->email_address; ?>" required /></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="phone_number">Phone Number<span class="text-danger">*</span> <small>(Format:0700123456)</small></label>
                                                    <div class="form-control-wrap"><input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo $row->phone_number; ?>" required /></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="gender">Gender</label>
                                                    <div class="form-control-wrap">
                                                        <select class="form-control select2" data-placeholder="Select Gender" id="gender" name="gender">
                                                            <option label="" value=""></option>
                                                            <option value="Female" <?php if ($row->gender == 'Female'){ echo 'selected'; } ?>>Female</option>
                                                            <option value="Male" <?php if ($row->gender == 'Male'){ echo 'selected'; } ?>>Male</option>
                                                            <option value="Other" <?php if ($row->gender == 'Other'){ echo 'selected'; } ?>>Other</option>
                                                            
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="birth_date">Birth Date <small>(yyyy-mm-dd)</small></label>
                                                    <div class="form-control-wrap">
                                                        <!-- <div class="form-icon form-icon-left">
                                                            <em class="icon ni ni-calendar"></em>
                                                        </div> -->
                                                        <input type="text" id="birth_date" name="birth_date" class="form-control date-picker" data-date-format="yyyy-mm-dd" value="<?php echo $row->birth_date; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group text-right"><button type="submit" id="btn_edit_customer" class="btn btn-primary"><em class="icon ni ni-save"></em> Update Customer</button></div>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                                
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>






            </div>
        </div>
    </div>
</div>

