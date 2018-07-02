<?php
	$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
	require_once dirname( dirname( $parse_uri[0] ) ) . '/wp-load.php';

	$gplat_agencies_count = R::count('gplat_agencies');
?>
<style>
	.lds-ellipsis{display: inline-block;position: relative;width: 64px;height: 64px;}
	.lds-ellipsis div {position: absolute;top: 27px;width: 11px;height: 11px;border-radius: 50%;background: #ccc;animation-timing-function: cubic-bezier(0, 1, 1, 0);}
	.lds-ellipsis div:nth-child(1) {left: 6px;animation: lds-ellipsis1 0.6s infinite;}
	.lds-ellipsis div:nth-child(2) {eft: 6px;animation: lds-ellipsis2 0.6s infinite;}
	.lds-ellipsis div:nth-child(3) {left: 26px;animation: lds-ellipsis2 0.6s infinite;}
	.lds-ellipsis div:nth-child(4) {left: 45px;animation: lds-ellipsis3 0.6s infinite;}
	@keyframes lds-ellipsis1 {0% {transform: scale(0);}	100% {transform: scale(1);}}
	@keyframes lds-ellipsis3 {0% {transform: scale(1);}	100% {transform: scale(0);}}
	@keyframes lds-ellipsis2 {0% {transform: translate(0, 0);} 100% {transform: translate(19px, 0);}}
</style>
<div class="wrap">
	<div class="container-fluid">
		<div class="row">
			<div class="col-1"><img src="<?php echo gplat_dir_url . 'images/logo_symbol_256x256.png' ?>" style="width:128px;height:auto;" class="rounded mx-auto d-block" alt="gplat"></div>
			<div class="col">
				<h1 class="wp-heading-inline">gplat</h1>
				<h2><?php _e('Agencies', gplat_textdomain) ?></h2>
			</div>
		</div>
		<div class="row">
			<div class="col">
			</div>
		</div>
		<div class="row">
			<div class="col-10">
				<table id="r_table" class="table table-hover table-sm" style="border-collapse: none"></table>
			</div>
			<div class="col">
				<div class="card bg-light mb-3">
					<div class="card-body text-center">
						<h2><i class="far fa-address-card fa-2x"></i></h2>
						<h5 class="card-title"><?php echo gplat_commons::numberize($gplat_agencies_count) . ' ' . _n('Agency available', 'Agencies available', $gplat_agencies_count, gplat_textdomain) ?></h5>
					</div>
				</div>
			</div>
		</div>
	</div>	
</div>

<script>
	jQuery(document).ready(function($) {
		$('#r_table').DataTable({
			ajax: "<?php echo gplat_dir_url . 'services/agencies-service.php?service=get_agencies' ?>",
			<?php include gplat_dir_path . 'views/commons/datatable-defaults.php' ?>
			columns: [
				{ title: "<?php _e('Id', gplat_textdomain) ?>", data: "id", width: "40px", className: "small text-right", type: "num" },
				{
					title: "<?php _e('Agency Name', gplat_textdomain) ?>", 
					data: null,
					className: "small text-left", 
					width: "250px", 
					type: "string",
					fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
						$(nTd).html('<a id="agency_' + oData.id + '" href="<?php echo admin_url('admin.php?page=gplat-admin-agency-detail&id=') ?>' + oData.id + '">' + oData.agency_name + '</a>');
        			}
				},
				{ title: "<?php _e('Description', gplat_textdomain) ?>", data: "description", className: "small", type: "date" },
				{ title: "<?php _e('Created On', gplat_textdomain) ?>", data: "creation_datetime", width: "120px", className: "small", type: "date" },
				{ title: "<?php _e('Modified On', gplat_textdomain) ?>", data: "modification_datetime", width: "120px", className: "small", type: "date" },
				{
					title: "<?php _e('Created By', gplat_textdomain) ?>", 
					data: null,
					className: "small text-left", 
					width: "150px",
					type: "string",
					fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
						$(nTd).html(oData.owner);
        			}
				},
			],
		});

	});
</script>