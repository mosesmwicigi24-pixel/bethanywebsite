<?php $this->load->view('be/includes/header'); ?>

	<div id="main_content">

			<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header">
				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
							<a href="#" class="breadcrumb-item">Settings</a>
							<span class="breadcrumb-item active">Store Information</span>
						</div>

						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>
				</div>
			</div>
			<!-- /page header -->


			<!-- Content area -->
			<div class="content pt-0">

				<div class="d-md-flex align-items-md-start">

					<div class="sidebar sidebar-light sidebar-component sidebar-component-left bg-transparent border-0 shadow-0 sidebar-expand-md">

						<!-- Sidebar content -->
						<div class="sidebar-content">

							<!-- Sub navigation -->
							<div class="card">
								<div class="card-header bg-transparent header-elements-inline p-2">
									<span class="text-uppercase font-size-sm font-weight-semibold"><i class="icon-gear"></i> Store Global Settings</span>
									<div class="header-elements">
										<div class="list-icons">
					                		<a class="list-icons-item" data-action="collapse"></a>
				                		</div>
			                		</div>
								</div>

								<div class="card-body p-0">
									<ul class="nav nav-sidebar" data-nav-type="accordion">
										<li class="nav-item-header text-info"><b>Store Information</b></li>
										<li class="nav-item">
											<a href="<?php echo base_url();?>be/settings/store_information" class="nav-link <?php if ($cur_sub == 'Store Information'){ echo 'active'; } ?>"><i class="icon-circle-small"></i> Store Information</a>
										</li>
										<li class="nav-item">
											<a href="<?php echo base_url();?>be/settings/outlets" class="nav-link <?php if ($cur_sub == 'Outlets'){ echo 'active'; } ?>"><i class="icon-circle-small"></i> Outlets</a>
										</li>
										<li class="nav-item-header text-info"><b>Locations</b></li>
										<li class="nav-item">
											<a href="<?php echo base_url();?>be/settings/countries" class="nav-link <?php if ($cur_sub == 'Countries'){ echo 'active'; } ?>"><i class="icon-circle-small"></i> Countries &amp; Regions</a>
										</li>
										<li class="nav-item">
											<a href="<?php echo base_url();?>be/settings/shipping_zones" class="nav-link <?php if ($cur_sub == 'Shipping Zones'){ echo 'active'; } ?>"><i class="icon-circle-small"></i> Shipping Zones</a>
										</li>
										<li class="nav-item">
											<a href="<?php echo base_url();?>be/settings/pickup_locations" class="nav-link <?php if ($cur_sub == 'Pickup Locations'){ echo 'active'; } ?>"><i class="icon-circle-small"></i> Pickup Locations</a>
										</li>
										<li class="nav-item-header text-info"><b>Money &amp; Payments</b></li>
										<li class="nav-item">
											<a href="<?php echo base_url();?>be/settings/currencies" class="nav-link <?php if ($cur_sub == 'Currencies'){ echo 'active'; } ?>"><i class="icon-circle-small"></i> Currencies</a>
										</li>
										<li class="nav-item">
											<a href="<?php echo base_url();?>be/settings/banks" class="nav-link <?php if ($cur_sub == 'Banks'){ echo 'active'; } ?>"><i class="icon-circle-small"></i> Banks &amp; Bank Branches</a>
										</li>
										<li class="nav-item">
											<a href="<?php echo base_url();?>be/settings/tax_rates" class="nav-link <?php if ($cur_sub == 'Tax Rates'){ echo 'active'; } ?>"><i class="icon-circle-small"></i> Tax Rates</a>
										</li>
										<li class="nav-item">
											<a href="<?php echo base_url();?>be/settings/credit_terms" class="nav-link <?php if ($cur_sub == 'Credit Terms'){ echo 'active'; } ?>"><i class="icon-circle-small"></i> Credit Payment Terms</a>
										</li>
										<!-- <li class="nav-item">
											<a href="#" class="nav-link"><i class="icon-circle-small"></i> Payment Options</a>
										</li> -->
										<li class="nav-item">
											<a href="<?php echo base_url();?>be/settings/mpesa_settings" class="nav-link <?php if ($cur_sub == 'M-Pesa Settings'){ echo 'active'; } ?>"><i class="icon-circle-small"></i> M-Pesa Settings</a>
										</li>
										<li class="nav-item">
											<a href="<?php echo base_url();?>be/settings/pesapal_settings" class="nav-link <?php if ($cur_sub == 'Pesapal Settings'){ echo 'active'; } ?>"><i class="icon-circle-small"></i> Pesapal Settings</a>
										</li>
										<li class="nav-item-header text-info"><b>Communication</b></li>
										<li class="nav-item">
											<a href="<?php echo base_url();?>be/settings/email_settings" class="nav-link <?php if ($cur_sub == 'Email Settings'){ echo 'active'; } ?>"><i class="icon-circle-small"></i> Email Settings</a>
										</li>
										<li class="nav-item">
											<a href="<?php echo base_url();?>be/settings/email_notification_settings" class="nav-link <?php if ($cur_sub == 'Email Notification Settings'){ echo 'active'; } ?>"><i class="icon-circle-small"></i> Email Notification Settings</a>
										</li>
										<li class="nav-item">
											<a href="<?php echo base_url();?>be/settings/email_templates" class="nav-link <?php if ($cur_sub == 'Email Templates'){ echo 'active'; } ?>"><i class="icon-circle-small"></i> Email Templates</a>
										</li>
										<li class="nav-item">
											<a href="<?php echo base_url();?>be/settings/sms_settings" class="nav-link <?php if ($cur_sub == 'SMS Settings'){ echo 'active'; } ?>"><i class="icon-circle-small"></i> Bulk SMS Settings</a>
										</li>
										<li class="nav-item">
											<a href="<?php echo base_url();?>be/settings/lnm_contacts_reconciliation" class="nav-link <?php if ($cur_sub == 'LNM Contacts Reconciliation'){ echo 'active'; } ?>"><i class="icon-circle-small"></i> LNM Contacts Reconciliation</a>
										</li>
										<li class="nav-item-header text-info"><b>Other Settings</b></li>
										<li class="nav-item">
											<a href="<?php echo base_url();?>be/settings/prefixes" class="nav-link <?php if ($cur_sub == 'Prefixes'){ echo 'active'; } ?>"><i class="icon-circle-small"></i> Prefixes</a>
										</li>
										<li class="nav-item">
											<a href="<?php echo base_url();?>be/settings/sale_comments" class="nav-link <?php if ($cur_sub == 'Sale Comments'){ echo 'active'; } ?>"><i class="icon-circle-small"></i> Sale Comments</a>
										</li>
										<li class="nav-item">
											<a href="<?php echo base_url();?>be/settings/void_reasons" class="nav-link <?php if ($cur_sub == 'Void Reasons'){ echo 'active'; } ?>"><i class="icon-circle-small"></i> Void Reasons</a>
										</li>
										<li class="nav-item">
											<a href="<?php echo base_url();?>be/settings/bitly_settings" class="nav-link <?php if ($cur_sub == 'Bitly Settings'){ echo 'active'; } ?>"><i class="icon-circle-small"></i> Bitly Settings</a>
										</li>
									</ul>
								</div>
							</div>
							<!-- /sub navigation -->
						</div>
						<!-- /sidebar content -->

					</div>
					<!-- /left sidebar component -->


<?php $this->load->view($main_content); ?>

</div>

<?php $this->load->view('be/includes/footer'); ?>