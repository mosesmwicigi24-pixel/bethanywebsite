                            <script>
                                $(".datatable-init").DataTable({
                                    responsive: { details: !0 },
                                    autoWidth: !1,
                                    dom:
                                        '<"row justify-between g-2"<"col-7 col-sm-6 text-left"f><"col-5 col-sm-6 text-right"<"datatable-filter"l>>><"datatable-wrap my-3"t><"row align-items-center"<"col-7 col-sm-12 col-md-9"p><"col-5 col-sm-12 col-md-3 text-left text-md-right"i>>',
                                    language: {
                                        search: "",
                                        searchPlaceholder: "Type in to Search",
                                        lengthMenu: "<span class='d-none d-sm-inline-block'>Show</span><div class='form-control-select'> _MENU_ </div>",
                                        info: "_START_ -_END_ of _TOTAL_",
                                        infoEmpty: "No records found",
                                        infoFiltered: "( Total _MAX_  )",
                                        paginate: { first: "First", last: "Last", next: "Next", previous: "Prev" },
                                    }
                                });
                            </script>     
                            <table class="datatable-init nk-tb-list nk-tb-ulist table" data-auto-responsive="true">
                                <thead class="bg-lighter">
                                    <tr class="nk-tb-item nk-tb-head">
                                        <th class="nk-tb-col nk-tb-col-check">
                                            <div class="custom-control custom-control-sm custom-checkbox notext">
                                                <input type="checkbox" class="custom-control-input" id="uid" /><label class="custom-control-label" for="uid"></label>
                                            </div>
                                        </th>
                                        <th class="nk-tb-col"><span class="">Photo</span></th>
                                        <th class="nk-tb-col tb-col-mb"><span class="">Customer Name</span></th>
                                        <th class="nk-tb-col tb-col-md"><span class="">Email Address</span></th>
                                        <th class="nk-tb-col tb-col-md"><span class="">Phone No.</span></th>
                                        <th class="nk-tb-col nk-tb-col-tools text-right"></th>
                                    </tr>
                                </thead>
                                <tbody>                       
                                    <?php foreach ($customers as $row): ?>

                                        <tr class="nk-tb-item">
                                            <td class="nk-tb-col nk-tb-col-check">
                                                <div class="custom-control custom-control-sm custom-checkbox notext">
                                                    <input type="checkbox" class="custom-control-input" id="uid1" /><label class="custom-control-label" for="uid1"></label>
                                                </div>
                                            </td>
                                            <td class="nk-tb-col">
                                                <div class="user-card">
                                                    <div class="user-avatar user-avatar-sm bg-warning">
                                                        <?php if($row->profile_picture_thumb != '' && file_exists("./uploads/customer_profile_pictures/thumbs/" . $row->profile_picture_thumb)): ?>
															<img src="<?php echo base_url();?>uploads/customer_profile_pictures/thumbs/<?php echo $row->profile_picture_thumb; ?>" class="" width="100" alt="">
					                                    <?php else: ?>
					                                    	&mdash;
					                                    <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="nk-tb-col tb-col-lg">
                                                <div class="user-name"><span class="tb-lead"><?php echo $row->first_name . ' ' . $row->last_name; ?></span></div>
                                            </td>
                                            <td class="nk-tb-col tb-col-mb">
                                                <?php echo $row->email_address; ?>
                                            </td>
                                            <td class="nk-tb-col tb-col-mb">
                                                <?php echo $row->phone_number; ?>
                                            </td>
                                            <td class="nk-tb-col nk-tb-col-tools">
                                                <ul class="nk-tb-actions gx-1">
                                                    <li>
                                                        <div class="drodown">
                                                            <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <ul class="link-list-opt no-bdr">
                                                                    <li>
                                                                        <?php if ($sbr_pos_customers_edit == true): ?>
                                                                            <a href="<?php echo base_url(); ?>pos/sales/customer_edit/<?php echo $row->customer_id; ?>"><em class="icon ni ni-edit"></em><span>Edit Customer</span></a>
                                                                        <?php endif; ?>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>


                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                                    