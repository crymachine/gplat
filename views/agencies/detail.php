<?php
	$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
	require_once dirname( dirname( $parse_uri[0] ) ) . '/wp-load.php';

	if(!isset($_GET['id'])){
		wp_redirect(admin_url('admin.php?page=gplat-admin-error&id=0x001'));
		exit;
	}
	$id = $_GET['id'];
	$entity = R::findOne('gplat_agencies', 'id = :id', [ ':id' => $id ]);
	if(!$entity->id) {
		wp_redirect(admin_url('admin.php?page=gplat-admin-error&id=0x001'));
		exit;
	}
?>
<style>
	.nav-link, .nav-link:visited{text-decoration:underline !important;}
</style>

<div class="wrap">
	<div class="container-fluid">
		<div id="notifier_container"></div>
		<div class="row">
			<div class="col-1"><img src="<?php echo gplat_dir_url . 'images/logo_symbol_256x256.png' ?>" style="width:128px;height:auto;" class="rounded mx-auto d-block" alt="gplat"></div>
			<div class="col-md-6">
				<h1 class="wp-heading-inline">gplat</h1>
				<h2><?php _e('Agency Details', gplat_textdomain) ?></h2>
			</div>
			<div class="col-md-3"></div>
			<div class="col-md-2 text-left">
				<dl class="row mt-3">
	  				<dd class="col-sm-9 small" title="<?php _e('Created By', gplat_textdomain) ?>"><i class="fas fa-user-edit"></i> <?php echo get_userdata((int)$entity->owner)->user_login ?></dd>
	  				<dd class="col-sm-9 small" title="<?php _e('Creation Date', gplat_textdomain) ?>"><i class="far fa-clock"></i> <?php echo $entity->creation_datetime ?></dd>
	  				<dd class="col-sm-9 small" title="<?php _e('Last Modification Date', gplat_textdomain) ?>"><i class="fas fa-pencil-alt"></i> <?php echo $entity->modification_datetime ?></dd>
				</dl>
			</div>
		</div>
		<div class="row">
			<form id="form" action="<?php echo gplat_dir_url . 'services/agencies-service.php?service=edit_agency' ?>" method="post" class="needs-validation container-fluid" novalidate>
				<input type="hidden" id="agency_id" name="agency_id" value="<?php echo $entity->id ?>" />
				<div class="form-row">
					<div class="col-md-4 mb-3">
						<label for="agency_name"><?php _e('Agency Name', gplat_textdomain) ?></label>
						<input type="text" id="agency_name" name="agency_name" class="form-control" aria-describedby="agency_name_help" placeholder="<?php _e('Agency name', gplat_textdomain) ?>" value="<?php echo $entity->agency_name ?>">
						<small id="agency_name_help" class="form-text text-muted">
							<?php _e('Good place for input description/help text...', gplat_textdomain) ?> 
						</small>
						<div class="valid-tooltip"><?php _e('Ok', gplat_textdomain) ?></div>
					</div>
					<div class="col-md-8 mb-3">
						<label for="description"><?php _e('Description', gplat_textdomain) ?></label>
						<input type="text" id="description" name="description" class="form-control" aria-describedby="description_help" placeholder="<?php _e('Description', gplat_textdomain) ?>" value="<?php echo $entity->description ?>">
						<small id="description_help" class="form-text text-muted">
							<?php _e('Good place for input description/help text...', gplat_textdomain) ?> 
						</small>
						<div class="valid-tooltip"><?php _e('Ok', gplat_textdomain) ?></div>
					</div>
				</div>
				<div class="form-row">
					<div class="col-md-12 mb-5 text-right">
						<a href="<?php echo admin_url('admin.php?page=gplat-admin-agencies') ?>" role="button" class="btn btn-default btn-sm"><?php _e('Cancel', gplat_textdomain) ?></a>
						<button id="save_button" type="submit" class="btn btn-primary btn-sm"><?php _e('Save', gplat_textdomain) ?></button>
					</div>
				</div>
			</form>
		</div>

		<div class="row">
			<div class="col-md-12 mt-5 mb-3">
				<h4><?php _e('Agency Campaigns', gplat_textdomain) ?></h4>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<table id="r_table" class="table table-hover table-sm" style="border-collapse: none"></table>				
			</div>
		</div>

	</div>
</div>

<script>
	jQuery(document).ready(function($) {
		$('#r_table').DataTable({
			ajax: "<?php echo gplat_dir_url . 'services/campaigns-service.php?service=get_agency_campaigns&agency=' . $entity->id ?>",
			<?php include gplat_dir_path . 'views/commons/datatable-defaults.php' ?>
			columns: [
				{ title: "<?php _e('Id', gplat_textdomain) ?>", data: "campaign_id", width: "40px", className: "small text-right", type: "num" },
				{ 
					title: "",
					data: null, 
					width: "30px",
					className: "small text-center", 
					type: "string",
					fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
						var html = "<i class='far fa-ban' title='<?php _e('Unknown campaign type', gplat_textdomain) ?>'></i>";
						if(oData.campaign_status == "COMPLETED") { 
							html = "<i class='fa fa-check-circle text-success' title='<?php _e('Completed', gplat_textdomain) ?>'></i>";
						} else if(oData.campaign_status == "PREPARING") {
							html = "<i class='far fa-cogs text-secondary' title='<?php _e('Preparing...', gplat_textdomain) ?>'></i>";
						} else if(oData.campaign_status == "SENDING") {
							html = "<i class='far fa-share-square text-secondary' title='<?php _e('Sending...', gplat_textdomain) ?>'></i>";
						} else if(oData.campaign_status == "ERROR") {
							html = "<i class='far fa-exclamation-triangle text-danger' title='<?php _e('Completed with errors', gplat_textdomain) ?>'></i>";
						}
						$(nTd).html(html);
        			}
				},
				{ 
					title: "",
					data: null, 
					width: "30px",
					className: "small text-center", 
					type: "string",
					fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
						var html = "<i class='far fa-question-circle' title='<?php _e('Unknown campaign type', gplat_textdomain) ?>'></i>";
						if(oData.campaign_type == "DEM") { 
							html = "<i class='far fa-envelope' title='<?php _e('DEM campaign type', gplat_textdomain) ?>'></i>";
						} else if(oData.campaign_type == "SMS") {
							html = "<i class='far fa-comment-alt' title='<?php _e('SMS campaign type', gplat_textdomain) ?>'></i>";
						}
						$(nTd).html(html);
        			}
				},
				{
					title: "<?php _e('Campaign', gplat_textdomain) ?>", 
					data: null,
					className: "small text-left", 
					width: "250px", 
					type: "string",
					fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
						if(oData.campaign_type == 'SMS') {
							$(nTd).html('<a href="<?php echo admin_url('admin.php?page=gplat-admin-sms-campaign-detail&id=') ?>' + oData.campaign_id + '">' + oData.campaign_name + '</a>');
						} else if(oData.campaign_type == 'DEM') {
							$(nTd).html('<a href="<?php echo admin_url('admin.php?page=gplat-admin-dem-campaign-detail&id=') ?>' + oData.campaign_id + '">' + oData.campaign_name + '</a>');
						}						
        			}
				},
				{ title: "<?php _e('Description', gplat_textdomain) ?>", data: "campaign_description", className: "small", type: "date" },
				{ title: "<?php _e('Created On', gplat_textdomain) ?>", data: "campaign_creation_datetime", width: "120px", className: "small", type: "date" },
				{ title: "<?php _e('Modified On', gplat_textdomain) ?>", data: "campaign_modification_datetime", width: "120px", className: "small", type: "date" },
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

		$("#form").submit(function(e) {
			$(this).addClass('disabled');
			$.ajax({
				type: $(this).attr("method"),
				url: $(this).attr("action"),
				data: $(this).serialize(),
				success: function(data) {
					$("#form").removeClass('disabled');
					$("#notifier_container").append(
						$('<div class="alert alert-success alert-dismissible fade show" role="alert"><div>' + data + '</div><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>')
					);
				},
				error: function(xhr, ajaxOptions, thrownError) {
					$("#notifier_container").append(
						$('<div class="alert alert-danger alert-dismissible fade show" role="alert"><div>' + thrownError + '</div><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>')
					);
				}
			});
			return false;
		});
	});
</script>
