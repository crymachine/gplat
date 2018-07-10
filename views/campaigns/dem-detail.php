<?php
	$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
	require_once dirname( dirname( $parse_uri[0] ) ) . '/wp-load.php';
	require_once gplat_dir_path . 'commons.php';
	
	if(!isset($_GET['id'])){
		wp_redirect(admin_url('admin.php?page=gplat-admin-error&id=0x001'));
		exit;
	}
	$id = $_GET['id'];
	$entity = R::findOne('gplat_campaigns', 'id = :id', [ ':id' => $id ]);
	if(!$entity) {
		wp_redirect(admin_url('admin.php?page=gplat-admin-error&id=0x001'));
		exit;
	}
	$agency = R::load('gplat_agencies', $entity->agency_id);
	$dem_campaign = R::findOne('gplat_campaigns_dem', 'campaign_id = :id', [ ':id' => $entity->id ]);
	$sent = R::getRow("SELECT count(id) as 'count' FROM gplat_cid" . $dem_campaign->campaign_id . " WHERE sent_datetime is not null");
	$sent_count = !$sent ? 0 : $sent['count'];
	$delivery = R::getRow("SELECT COUNT(id) as 'count' FROM gplat_cid" . $dem_campaign->id . " WHERE delivery_datetime is not null");
	$read = R::getRow("SELECT COUNT(id) as 'count' FROM gplat_cid" . $dem_campaign->id . " WHERE read_datetime is not null");
	$laeads = R::getRow("SELECT COUNT(id) as 'count' FROM gplat_cid" . $dem_campaign->id . " WHERE landing_datetime is not null");
?>
<?php gplat_commons::page_common_styles() ?>
<div class="wrap">
	<div class="container-fluid">
		
		<div id="notifier_container"></div>

		<div class="row pb-4 border-bottom">
			<div class="col-8">
				<h1 class="wp-heading-inline">gplat</h1>
				<h2><?php _e('Campaign Details', gplat_textdomain) ?></h2>
			</div>
			<div class="col-4 text-right pt-5">
				<button type="button" class="btn btn-light btn-sm"><i class="fas fa-file-pdf mr-2 text-danger"></i><?php _e('Export to PDF', gplat_textdomain) ?></button>
				<button type="button" class="btn btn-light btn-sm"><i class="fas fa-file-excel mr-2 text-success"></i><?php _e('Export to Excel', gplat_textdomain) ?></button>
				<button type="button" class="btn btn-light btn-sm"><i class="fas fa-envelope-square mr-2 text-info"></i><?php _e('Send via Email', gplat_textdomain) ?></button>
			</div>
		</div>

		<div class="row mt-2">
			<div class="col-8">
				<div class="container-fluid">
					<div class="row">
						<div class="col-12 font-weight-bold">
							<div class="small"><?php echo $agency ? $agency->agency_name : '' ?></div>
							<?php echo $entity->campaign_name ?>
						</div>
					</div>
					<div class="row">
						<div class="col-12"><?php echo $entity->description ?></div>
					</div>
					<div class="row">
						<div class="col-12">
							<ul class="nav">
								<li class="nav-item mr-5">
									<i class="far fa-envelope" style="font-size" title="<?php _e('DEM campaign type', gplat_textdomain) ?>"></i>
									<span class="small font-weight-bold"><?php echo gplat_commons::translate_campaign_type($entity->campaign_type) ?></span>
								</li>
								<li class="nav-item mr-5">
									<?php echo gplat_commons::status_to_icon($entity->status) ?>
									<span class="small"><?php echo gplat_commons::translate_status($entity->status) ?></span>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="col-4 text-right">
				<dl>
	  				<dd class="small" title="<?php _e('Created By', gplat_textdomain) ?>"><?php echo get_userdata((int)$entity->owner)->user_login ?><i class="fas fa-user-edit ml-2"></i></dd>
	  				<dd class="small" title="<?php _e('Creation Date', gplat_textdomain) ?>"><?php echo $entity->creation_datetime ?><i class="far fa-clock ml-2"></i></dd>
	  				<dd class="small" title="<?php _e('Last Modification Date', gplat_textdomain) ?>"><?php echo $entity->modification_datetime ?><i class="fas fa-pencil-alt ml-2"></i></dd>
				</dl>
			</div>
		</div>

		<div class="row mt-4">
			<div class="col-7">
				<h5><?php _e('Email Message', gplat_textdomain) ?></h5>
				<div class="border rounded p-3 bg-light" style="height:300px;overflow:auto;">
					<?php echo $dem_campaign->html ?>
				</div>
			</div>
			<div class="col-5">
				<h5><?php _e('Script', gplat_textdomain) ?></h5>
				<div class="border rounded p-3 bg-light" style="height:300px;overflow:auto;">
					<code><?php echo strip_tags(str_replace(array('<', '>'), array('&lt;', '&gt;'), $dem_campaign->script_text)); ?></code>
				</div>
			</div>
		</div>

		<div class="row mt-5">
			<div class="col-3 text-center">
			</div>
			<div class="col-3 text-center">
				<div id="sent_del_msg_donut" class="text-left" style="height: 200px;"></div>
			</div>
			<div class="col-3 text-center">
				<div id="read_leads_msg_donut" class="text-left" style="height: 200px;"></div>
			</div>
			<div class="col-3 text-center">
			</div>
		</div>

		<div class="row mt-5">
			<div class="col-6">
				<h4><?php _e('Sent/Delivering/Landing Reports', gplat_textdomain) ?></h4><br/>
				<table id="r_table_del_not" class="table table-hover table-sm" style="border-collapse: none"></table>
			</div>
			<div class="col-6">
				<h4><?php _e('Links Reports', gplat_textdomain) ?></h4><br/>
				<table id="r_table_hrefs" class="table table-hover table-sm" style="border-collapse: none"></table>
			</div>
		</div>

	</div>
</div>

<script>
	jQuery(document).ready(function($) {
		$('#r_table_del_not').DataTable({
			ajax: "<?php echo gplat_dir_url . 'services/campaigns-service.php?service=get_dem_campaign_del_not&campaign=' . $entity->id ?>",
			<?php include gplat_dir_path . 'views/commons/datatable-defaults.php' ?>
			searching: false,
			columns: [
				{ title: "<?php _e('Ip Address', gplat_textdomain) ?>", width: "180px", data: "ip_address", className: "small code", type: "string" },
				{ title: "<?php _e('Transaction Code', gplat_textdomain) ?>", data: "transaction_uid", className: "small code", type: "string" },
				{ title: "<?php _e('Sent', gplat_textdomain) ?>", data: "sent_datetime", width: "120px", className: "small", type: "date" },
				{ title: "<?php _e('Delivered', gplat_textdomain) ?>", data: null, width: "120px", className: "small text-center", type: "date",
					fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
						if(oData.delivery_datetime != null) {
							$(nTd).html("<i class=\"fa fa-check-circle text-success\" title=\"" + oData.delivery_datetime + "\"></i>");
						} else {
							$(nTd).html("<i class=\"fa fa-times-circle text-light\" title=\"\"></i>");
						}
        		} },
				{ title: "<?php _e('Landed', gplat_textdomain) ?>", data: null, width: "120px", className: "small text-center", type: "date",
					fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
						if(oData.landing_datetime != null) {
							$(nTd).html("<i class=\"fa fa-check-circle text-success\" title=\"" + oData.landing_datetime + "\"></i>");
						} else {
							$(nTd).html("<i class=\"fa fa-times-circle text-light\" title=\"\"></i>");
						}
        		} },
			],
		});
		$('#r_table_hrefs').DataTable({
			ajax: "<?php echo gplat_dir_url . 'services/campaigns-service.php?service=get_dem_campaign_links&campaign=' . $entity->id ?>",
			<?php include gplat_dir_path . 'views/commons/datatable-defaults.php' ?>
			searching: false,
			columns: [
				{ title: "<?php _e('Ip Address', gplat_textdomain) ?>", width: "180px", data: "ip_address", className: "small code", type: "string" },
				{ title: "<?php _e('Url', gplat_textdomain) ?>", data: "url", className: "small text-truncate", type: "string" },
				{ title: "<?php _e('Transaction Code', gplat_textdomain) ?>", data: "transaction_uid",  width: "120px", className: "small code", type: "string" },
				{ title: "<?php _e('Date', gplat_textdomain) ?>", data: "creation_datetime", width: "120px", className: "small", type: "date" },
			],
		});

		new Morris.Donut({
			element: 'sent_del_msg_donut',
			data: [
				{ label: '<?php _e('Sent', gplat_textdomain) ?>', value:  <?php echo $sent_count ?>},
				{ label: '<?php _e('Delivered', gplat_textdomain) ?>', value: <?php echo $delivery['count'] ?> }
			],
			colors: ['#b6e038', '#319cce'],
			resize: true
		});
		new Morris.Donut({
			element: 'read_leads_msg_donut',
			data: [
				{ label: '<?php _e('Read', gplat_textdomain) ?>', value:  <?php echo $read['count'] ?>},
				{ label: '<?php _e('Leads', gplat_textdomain) ?>', value: <?php echo $laeads['count'] ?> }
			],
			colors: ['#b6e038', '#319cce'],
			resize: true
		});

	});
</script>