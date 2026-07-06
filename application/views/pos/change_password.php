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
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="data-head mb-4"><h6 class="overline-title">Change Password</h6></div>
                                                                    </div>
                                                                </div>

                                                                <form id="frm_change_password" name="frm_change_password" method="post" class="is-alter" onsubmit="return submit_change_password();">

                                                                    <div class="row gy-4">
                                                                        <input type="hidden" id="system_user_id" name="system_user_id" value="<?php echo $row->system_user_id; ?>">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group mb-0">
                                                                                <label class="form-label" for="old_password">Old Password<span class="text-danger">*</span></label>
                                                                                <input type="password" class="form-control" id="old_password" name="old_password" placeholder="Old Password" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row gy-4">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group mb-0">
                                                                                <label class="form-label" for="new_password">New Password<span class="text-danger">*</span></label>
                                                                                <input type="password" class="form-control" id="new_password" name="new_password" placeholder="New Password" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row gy-4">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="form-label" for="confirm_password">Confirm Password<span class="text-danger">*</span></label>
                                                                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row gy-4">
                                                                        <div class="col-12">
                                                                            <ul class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
                                                                                <li> <button type="submit" id="btn_change_password" class="btn btn-primary" ><i class="ion-checkmark-circled mr-1"></i>Submit</button></li>
                                                                            </ul>
                                                                        </div>                                                
                                                                        
                                                                    </div>
                                                                </form>
                                                                
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
                                                                        <a href="<?php echo base_url();?>pos/auth/profile"><em class="icon ni ni-user-fill-c"></em><span>Profile Information</span></a>
                                                                    </li>
                                                                    <li>
                                                                        <a class="active" href="<?php echo base_url();?>pos/auth/change_password"><em class="icon ni ni-lock-alt-fill"></em><span>Change Password</span></a>
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
                                        


                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>