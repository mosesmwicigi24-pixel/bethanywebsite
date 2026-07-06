                    <?php foreach ($profile as $row): ?>
                        <div class="nk-content">
                            <div class="container-fluid">
                                <div class="nk-content-inner">
                                    <div class="nk-content-body">
                                        <div class="nk-block">
                                            <div class="card">
                                                <div class="card-aside-wrap">
                                                    <div class="card-inner card-inner-lg">
                                                        <div class="nk-block-head nk-block-head-lg">
                                                            <div class="nk-block-between">
                                                                <!-- <div class="nk-block-head-content">
                                                                    <h4 class="nk-block-title">Profile Information</h4>
                                                                </div> -->
                                                                <div class="nk-block-head-content align-self-start d-lg-none">
                                                                    <a href="#" class="toggle btn btn-icon btn-trigger mt-n1" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="nk-block">
                                                            <div class="nk-data data-list">
                                                                <div class="data-head"><h6 class="overline-title">Profile Information</h6></div>
                                                                <div class="data-item" data-toggle="modal" data-target="#profile-edit">
                                                                    <div class="data-col"><span class="data-label">Full Name</span><span class="data-value"><?php echo $row->first_name . ' ' . $row->last_name; ?></span></div>
                                                                    <div class="data-col data-col-end">
                                                                        <span class="data-more">Edit</span>
                                                                    </div>
                                                                </div>
                                                                <div class="data-item">
                                                                    <div class="data-col"><span class="data-label">Email</span><span class="data-value"><?php echo $row->email_address; ?></span></div>
                                                                    <div class="data-col data-col-end">
                                                                        <span class="data-more disable"><em class="icon ni ni-lock-alt"></em></span>
                                                                    </div>
                                                                </div>
                                                                <?php if ($row->phone_number == ''): ?>
                                                                    <div class="data-item" data-toggle="modal" data-target="#profile-edit">
                                                                        <div class="data-col"><span class="data-label">Phone Number</span><span class="data-value text-soft">Not added yet</span></div>
                                                                        <div class="data-col data-col-end">
                                                                            <span class="data-more">Edit</span>
                                                                        </div>
                                                                    </div>
                                                                <?php else: ?>
                                                                    <div class="data-item" data-toggle="modal" data-target="#profile-edit">
                                                                        <div class="data-col"><span class="data-label">Phone Number</span><span class="data-value"><?php echo $row->phone_number; ?></span></div>
                                                                        <div class="data-col data-col-end">
                                                                            <span class="data-more">Edit</span>
                                                                        </div>
                                                                    </div>
                                                                <?php endif; ?>
                                                                <?php if ($row->gender == ''): ?>
                                                                    <div class="data-item" data-toggle="modal" data-target="#profile-edit">
                                                                        <div class="data-col"><span class="data-label">Gender</span><span class="data-value text-soft">Not added yet</span></div>
                                                                        <div class="data-col data-col-end">
                                                                            <span class="data-more">Edit</span>
                                                                        </div>
                                                                    </div>
                                                                <?php else: ?>
                                                                    <div class="data-item" data-toggle="modal" data-target="#profile-edit">
                                                                        <div class="data-col"><span class="data-label">Gender</span><span class="data-value"><?php echo $row->gender; ?></span></div>
                                                                        <div class="data-col data-col-end">
                                                                            <span class="data-more">Edit</span>
                                                                        </div>
                                                                    </div>
                                                                <?php endif; ?>
                                                                <?php if ($row->address == ''): ?>
                                                                    <div class="data-item" data-toggle="modal" data-target="#profile-edit" data-tab-target="#address">
                                                                        <div class="data-col">
                                                                            <span class="data-label">Address</span>
                                                                            <span class="data-value text-soft">Not added yet</span>
                                                                        </div>
                                                                        <div class="data-col data-col-end">
                                                                            <span class="data-more">Edit</span>
                                                                        </div>
                                                                    </div>
                                                                <?php else: ?>
                                                                    <div class="data-item" data-toggle="modal" data-target="#profile-edit" data-tab-target="#address">
                                                                        <div class="data-col">
                                                                            <span class="data-label">Address</span>
                                                                            <span class="data-value"><?php echo $row->address; ?></span>
                                                                        </div>
                                                                        <div class="data-col data-col-end">
                                                                            <span class="data-more">Edit</span>
                                                                        </div>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-aside card-aside-left user-aside toggle-slide toggle-slide-left toggle-break-lg" data-content="userAside" data-toggle-screen="lg" data-toggle-overlay="true">
                                                        <div class="card-inner-group" data-simplebar>
                                                            <div class="card-inner">
                                                                <div class="user-card">
                                                                    <div class="user-avatar bg-primary"><span><em class="icon ni ni-user-alt"></em></span></div>
                                                                    <div class="user-info"><span class="lead-text"><?php echo $row->first_name . ' ' . $row->last_name; ?></span><span class="sub-text"><?php if ($row->is_super_admin == 1) { echo 'Super Admin'; } else { echo $row->user_role_name; } ?></span></div>
                                                                    <div class="user-action">
                                                                        <div class="dropdown">
                                                                            <a class="btn btn-icon btn-trigger mr-n2" data-toggle="dropdown" href="#"><em class="icon ni ni-more-v"></em></a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card-inner p-0">
                                                                <ul class="link-list-menu">
                                                                    <li>
                                                                        <a class="active" href="<?php echo base_url();?>pos/auth/profile"><em class="icon ni ni-user-fill-c"></em><span>Profile Information</span></a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="<?php echo base_url();?>pos/auth/change_password"><em class="icon ni ni-lock-alt-fill"></em><span>Change Password</span></a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="modal fade" role="dialog" id="profile-edit">
                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                <div class="modal-content">
                                    <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
                                    <div class="modal-body modal-body-lg">
                                        <h5 class="title mb-4">Update Profile</h5>
                                        
                                        <form id="frm_update_profile" name="frm_update_profile" method="post" class="is-alter" onsubmit="return submit_update_profile();">

                                            <div class="row gy-4">
                                                <input type="hidden" id="system_user_id" name="system_user_id" value="<?php echo $row->system_user_id; ?>">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-0">
                                                        <label class="form-label" for="first_name">First Name<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $row->first_name; ?>" placeholder="First name" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-0">
                                                        <label class="form-label" for="last_name">Last Name<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $row->last_name; ?>" placeholder="Last name" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-0">
                                                        <label class="form-label" for="email_address">Email Address<span class="text-danger">*</span></label>
                                                        <input type="email" class="form-control text-soft" id="email_address" name="email_address" value="<?php echo $row->email_address; ?>" readonly />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-0">
                                                        <label class="form-label" for="phone_number">Phone Number<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo $row->phone_number; ?>" placeholder="Phone Number" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label" for="gender">Gender</label>
                                                        <select class="form-control select2" data-placeholder="Select Gender" id="gender" name="gender" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                                            <option value="">Select Gender</option>
                                                            <option value="Female" <?php if ($row->gender == 'Female'){ echo 'selected'; } ?>>Female</option>
                                                            <option value="Male" <?php if ($row->gender == 'Male'){ echo 'selected'; } ?>>Male</option>
                                                            <option value="Other" <?php if ($row->gender == 'Other'){ echo 'selected'; } ?>>Other</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label" for="address">Address</label>
                                                        <input type="text" class="form-control" id="address" value="<?php echo $row->address; ?>" name="address" placeholder="Address" />
                                                    </div>
                                                </div>                                                    
                                                
                                                <div class="col-12">
                                                    <ul class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
                                                        <li> <button type="submit" id="btn_update_profile" class="btn btn-primary" ><i class="ion-checkmark-circled mr-1"></i>Submit</button></li>
                                                        <li><a href="javascript:;" data-dismiss="modal" class="link link-light">Cancel</a></li>
                                                    </ul>
                                                </div>                                                
                                                
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>