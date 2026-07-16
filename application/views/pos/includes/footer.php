                    <div class="nk-footer">
                        <div class="container-fluid">
                            <div class="nk-footer-wrap">
                                <div class="nk-footer-copyright">&copy; <?php echo date('Y'); ?> Bethany House POS.</div>
                                <div class="nk-footer-links">
                                    <div class="nk-header-app-name d-xl-none">
                                        <img src="<?php echo base_url();?>assets/pos/images/outlet.png" class="mr-1" alt="">
                                        <div class="nk-header-app-info">
                                            <span class="sub-text">Outlet:</span>
                                            <?php foreach ($active_outlet as $row): ?>
                                                <span class="lead-text"><?php echo $row->outlet_name; ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        
        <script src="<?php echo base_url() . auto_version('assets/pos/js/scripts.js'); ?>"></script>
        <script src="<?php echo base_url() . auto_version('assets/be/js/plugins/ui/moment/moment.min.js'); ?>"></script>
        <script src="<?php echo base_url() . auto_version('assets/pos/js/settings.js'); ?>"></script>        
        <script src="<?php echo base_url() . auto_version('assets/pos/js/charts/chart-ecommerce.js'); ?>"></script>
        <script src="<?php echo base_url() . auto_version('assets/pos/plugins/bootstrap3-editable/js/bootstrap-editable.min.js'); ?>"></script>
        <script src="<?php echo base_url() . auto_version('assets/pos/plugins/xlsx/dist/xlsx.core.min.js'); ?>"></script>
        <script src="<?php echo base_url() . auto_version('assets/pos/plugins/FileSaver/FileSaver.min.js'); ?>"></script>
        <script src="<?php echo base_url() . auto_version('assets/pos/plugins/tableexport/dist/js/tableexport.min.js'); ?>"></script>
        <script src="<?php echo base_url() . auto_version('assets/pos/plugins/chartjs/Chart.min.js'); ?>"></script>
        <script src="<?php echo base_url() . auto_version('assets/pos/js/custom.js'); ?>"></script>
    </body>
</html>
