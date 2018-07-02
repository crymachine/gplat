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
	$sms_campaign = R::findOne('gplat_campaigns_sms', 'campaign_id = :id', [ ':id' => $entity->id ]);
	$sent = R::getRow("SELECT count(id) as 'count' FROM gplat_cid" . $sms_campaign->campaign_id . " WHERE sent_datetime is not null");
	$sent_count = !$sent ? 0 : $sent['count'];
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
									<i class="fas fa-mobile-alt" title="<?php _e('SMS campaign type', gplat_textdomain) ?>"></i>
									<span class="small font-weight-bold"><?php echo gplat_commons::translate_campaign_type($entity->campaign_type) ?></span>
								</li>
								<?php if($sms_campaign->hi_quality_smpp) { ?>
								<li class="nav-item mr-5">
									<i class="fas fa-star text-warning" title="<?php _e('High Quality SMS', gplat_textdomain) ?>"></i>
									<span class="small"><?php _e('High Quality SMS', gplat_textdomain) ?></span>
								</li>
								<?php } ?>
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
			<div class="col-4">
				<h5><?php _e('SMS Message', gplat_textdomain) ?></h5>
				<div class="border rounded p-3 bg-light" style="height:300px;">
					<div class="mb-3 text-muted" title="<?php _e('from', gplat_textdomain) ?>"><span class="small font-weight-bold"><?php _e('from', gplat_textdomain) ?></span> <pre><?php echo $sms_campaign->sender ?></pre></div>
					<div title="<?php echo $sms_campaign->message_body ?>">
						<span class="small font-weight-bold  text-muted"><?php _e('message', gplat_textdomain) ?></span>
						<pre class="text-dark"><?php echo $sms_campaign->message_body ?></pre>
					</div>
				</div>
			</div>
			<div class="col-8 text-center">
			<?php if($sms_campaign->hi_quality_smpp) { ?>
				<div id="areachart" style="width:100%;height:300px;"></div>
			<?php } ?>			
			</div>
		</div>

		<div class="row mt-5">
			<div class="col-3"></div>
			<div class="col-3">
				<?php if($sms_campaign->hi_quality_smpp) { ?><div id="del_undel_msg_donut" class="text-left" style="height: 200px;"></div><?php } ?>
			</div>
			<div class="col-3">
				<?php if($sms_campaign->hi_quality_smpp) { ?><div id="land_msg_donut" class="text-left" style="height: 200px;"></div><?php } ?>
			</div>
			<div class="col-3"></div>
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
			ajax: "<?php echo gplat_dir_url . 'services/campaigns-service.php?service=get_sms_campaign_del_not&campaign=' . $entity->id ?>",
			<?php include gplat_dir_path . 'views/commons/datatable-defaults.php' ?>
			searching: false,
			columns: [
				{ title: "<?php _e('Phone', gplat_textdomain) ?>", width: "180px", data: null, className: "small code", type: "string",
					fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
						var stars_count = 5;
    					var rv = oData.phone.substring(0, oData.phone.length - stars_count) + "*".repeat(stars_count);
						$(nTd).html("<span title=\"" + oData.sending_uid + "\">" + rv + "</span>");
        		} },
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
			ajax: "<?php echo gplat_dir_url . 'services/campaigns-service.php?service=get_sms_campaign_links&campaign=' . $entity->id ?>",
			<?php include gplat_dir_path . 'views/commons/datatable-defaults.php' ?>
			searching: false,
			columns: [
				{ title: "<?php _e('Ip Address', gplat_textdomain) ?>", width: "180px", data: "ip_address", className: "small code", type: "string" },
				{ title: "<?php _e('Url', gplat_textdomain) ?>", data: "url", className: "small text-truncate", type: "string" },
				{ title: "<?php _e('Transaction Code', gplat_textdomain) ?>", data: "transaction_uid", width: "120px", className: "small code", type: "string" },
				{ title: "<?php _e('Date', gplat_textdomain) ?>", data: "creation_datetime", width: "120px", className: "small", type: "date" },
			],
		});
	});
	google.charts.load("current", {packages:["corechart"]});
	google.charts.setOnLoadCallback(drawChart);
	function drawChart() {
		var data = google.visualization.arrayToDataTable([
			['<?php _e('Date', gplat_textdomain) ?>', '<?php _e('Delivered', gplat_textdomain) ?>', '<?php _e('Sent', gplat_textdomain) ?>', '<?php _e('Leads', gplat_textdomain) ?>'],
<?php	
	$earlier = new DateTime($entity->start_datetime);
	$later = new DateTime();
	$diff = $later->diff($earlier)->format("%a") + 1;
	$chart_data = array();
	$date = $earlier;
	$delivered_count = 0;
	for($i = 0; $i <= $diff; $i++) {
		$count = R::getRow("select count(id) as 'count' from gplat_cid" . $sms_campaign->campaign_id . " where year(sent_datetime) = " . $date->format('Y') . " and month(sent_datetime) = " . $date->format('m') . " and day(sent_datetime) = " . $date->format('d'));
		$value = R::getRow("select count(id) as 'count' from gplat_cid" . $sms_campaign->campaign_id . " where year(delivery_datetime) = " . $date->format('Y') . " and month(delivery_datetime) = " . $date->format('m') . " and day(delivery_datetime) = " . $date->format('d'));
		$landing = R::getRow("select count(id) as 'count' from gplat_cid" . $sms_campaign->campaign_id . " where year(landing_datetime) = " . $date->format('Y') . " and month(landing_datetime) = " . $date->format('m') . " and day(landing_datetime) = " . $date->format('d'));
		$chart_data[] = '[\'' . $date->format('d/m') . '\', ' . $value['count'] . ', ' . $count['count'] . ', ' . $landing['count'] . ']';
		$date = $earlier->add(new DateInterval('P1D'));
		$delivered_count = $delivered_count + $value['count'];
		if($delivered_count == $sent_count) {
			break;
		}
	}
	echo join(',', $chart_data);
?>
		]);
		var options = {
			animation:{
				startup: true,
        		duration: 1500,
        		easing: 'inAndOut',
      		},
			areaOpacity: 0.1,
			vAxis: {minValue: 0}
		};
		var chart = new google.visualization.AreaChart(document.getElementById('areachart'));
		chart.draw(data, options);
	}
<?php
if($sms_campaign->hi_quality_smpp) {
	$delivered = R::getRow("SELECT count(id) as 'count' FROM gplat_cid" . $sms_campaign->campaign_id . " WHERE delivery_datetime is not null");
	$delivered_count = $delivered ? $delivered['count'] : 0;

	$undelivered = R::getRow("SELECT count(id) as 'count' FROM gplat_cid" . $sms_campaign->campaign_id . " WHERE delivery_datetime is null");
	$undelivered_count = $undelivered ? $undelivered['count'] : 0;

	$read = R::getRow("SELECT count(id) as 'count' FROM gplat_cid" . $sms_campaign->campaign_id . " WHERE read_datetime is not null");
	$read_count = !$read ? 0 : $read['count'];

	$landing = R::getRow("SELECT count(id) as 'count' FROM gplat_cid" . $sms_campaign->campaign_id . " WHERE landing_datetime is not null");
	$landing_count = !$landing ? 0 : $landing['count'];
?>
	new Morris.Donut({
		element: 'del_undel_msg_donut',
		data: [
			{ label: '<?php _e('Sent', gplat_textdomain) ?>', value:  <?php echo $sent_count ?>},
<?php if($undelivered_count > 0) { ?>			
			{ label: '<?php _e('Delivered', gplat_textdomain) ?>', value: <?php echo $delivered_count ?> }
<?php } ?>
		],
		colors: ['#b6e038', '#319cce'],
		resize: true
	});
	new Morris.Donut({
		element: 'land_msg_donut',
		data: [
			{ label: '<?php _e('Read', gplat_textdomain) ?>', value:  <?php echo $read_count ?>},
			{ label: '<?php _e('Leads', gplat_textdomain) ?>', value:  <?php echo $landing_count ?>}
		],
		colors: ['#b6e038', '#319cce'],
		resize: true
	});
<?php } ?>
</script>