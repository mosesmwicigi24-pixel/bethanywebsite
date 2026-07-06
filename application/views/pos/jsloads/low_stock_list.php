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
                                <thead class="bg-warning">
                                    <tr class="nk-tb-item nk-tb-head">
                                        <th class="nk-tb-col nk-tb-col-check">
                                            <div class="custom-control custom-control-sm custom-checkbox notext">
                                                <input type="checkbox" class="custom-control-input" id="uid" /><label class="custom-control-label" for="uid"></label>
                                            </div>
                                        </th>
                                        <th class="nk-tb-col"><span class="text-white">Image</span></th>
                                        <th class="nk-tb-col tb-col-mb"><span class="text-white">Product Name</span></th>
                                        <!-- <th class="nk-tb-col tb-col-lg"><span class="text-white">Unit</span></th> -->
                                        <th class="nk-tb-col tb-col-lg"><span class="text-white">Price</span></th>
                                        <th class="nk-tb-col tb-col-md"><span class="text-white">Available Stock</span></th>
                                        <th class="nk-tb-col tb-col-md"><span class="text-white">Reorder Level</span></th>
                                        <th class="nk-tb-col nk-tb-col-tools text-right"></th>
                                    </tr>
                                </thead>
                                <tbody>                       
                                    <?php foreach ($low_stock_list as $row): ?>

                                        <tr class="nk-tb-item">
                                            <td class="nk-tb-col nk-tb-col-check">
                                                <div class="custom-control custom-control-sm custom-checkbox notext">
                                                    <input type="checkbox" class="custom-control-input" id="uid1" /><label class="custom-control-label" for="uid1"></label>
                                                </div>
                                            </td>
                                            <td class="nk-tb-col">
                                                <div class="user-card">
                                                    <div class="user-avatar user-avatar-sm bg-warning">
                                                        <?php if($row->product_image_thumb != '' && file_exists("./uploads/product_images/thumbs/" . $row->product_image_thumb)): ?>
                                                            <img src="<?php echo base_url();?>uploads/product_images/thumbs/<?php echo $row->product_image_thumb; ?>" class="" width="100" alt="">
                                                        <?php else: ?>
                                                            &mdash;
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="nk-tb-col tb-col-lg">
                                                <div class="user-name"><span class="tb-lead"><?php echo $row->product_name; ?></span></div>
                                                <small>SKU: <?php echo $row->product_sku_code; ?></small>
                                            </td>
                                            <!-- <td class="nk-tb-col tb-col-mb">
                                                <?php echo $row->unit_name; ?>
                                            </td> -->
                                            <td class="nk-tb-col tb-col-mb">
                                                <?php if ($row->sale_price > 0): ?>
                                                    <span class="tb-amount"><?php echo $default_currency . ' ' . number_format($row->sale_price,2); ?></span>
                                                    <span class="text-grey"><small><strike><?php echo $default_currency . ' ' . number_format($row->regular_price,2); ?></strike></small></span>
                                                <?php else: ?>
                                                    <span class="tb-amount"><?php echo $default_currency . ' ' . number_format($row->regular_price,2); ?></span>
                                                <?php endif; ?>
                                            </td>                                        
                                            <td class="nk-tb-col tb-col-mb">
                                                <?php echo number_format($row->available_stock); ?>
                                            </td>
                                            <td class="nk-tb-col tb-col-mb">
                                                <?php echo number_format($row->reorder_level); ?>
                                            </td>
                                            <td class="nk-tb-col nk-tb-col-tools">
                                                <ul class="nk-tb-actions gx-1">
                                                    <li>
                                                        <div class="drodown">
                                                            <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                            <!-- <div class="dropdown-menu dropdown-menu-right">
                                                                <ul class="link-list-opt no-bdr">
                                                                    
                                                                </ul>
                                                            </div> -->
                                                        </div>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>


                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                                    