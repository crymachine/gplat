<?php

?>
<div class="wrap">
	<div class="container-fluid">
		<div class="row">
			<div class="col-1"><img src="<?php echo gplat_dir_url . 'images/logo_symbol_256x256.png' ?>" style="width:128px;height:auto;" class="rounded mx-auto d-block" alt="gplat"></div>
			<div class="col">
				<h1 class="wp-heading-inline">gplat</h1>
				<h2><?php _e('Dashboard', gplat_textdomain) ?></h2>
			</div>
		</div>
		<div class="row">
			<div class="col jumbotron">
				<h1 class="display-4">Hello, world!</h1>
				<p class="lead">This is a simple hero unit, a simple jumbotron-style component for calling extra attention to featured content or information.</p>
				<hr class="my-4">
				<p>It uses utility classes for typography and spacing to space content out within the larger container.</p>
				<p class="lead">
					<a class="btn btn-primary btn-lg" href="#" role="button">Learn more</a>
				</p>
			</div>
		</div>

<?php if(current_user_can('administrator')) { // USER IS SYSTEM ADMINISTRATOR ?>
		<div class="row">
			<div class="col">
				<div class="card bg-light mb-3">
					<div class="card-body text-center">
						<h2><i class="far fa-address-card fa-3x"></i></h2>
						<h5 class="card-title"><?php echo gplat_commons::numberize(R::count('wm_contacts')) . ' ' . _n('Profile available', 'Profiles available', gplat_textdomain) ?></h5>
						<p class="card-text text-left">
							Insert here some text or other...
						</p>
						<a href="<?php echo admin_url('admin.php?page=gplat-admin-contacts') ?>" class="btn btn-default btn-sm"><?php _e('Go to contacts', gplat_textdomain) ?></a>
					</div>
				</div>
			</div>
			<div class="col">
				<div class="card bg-light mb-3">
					<div class="card-body text-center">
						<h2><span class="oi oi-pie-chart"></span></h2>
						<h5 class="card-title">Light card title</h5>
						<p class="card-text text-left">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
						<a href="#" class="btn btn-default btn-sm">Go somewhere</a>
						<a href="#" class="btn btn-secondary btn-sm">Go somewhere</a>
					</div>
				</div>
			</div>
			<div class="col">
				<div class="card bg-light mb-3">
					<div class="card-body text-center">
						<h2><span class="oi oi-pie-chart"></span></h2>
						<h5 class="card-title">Light card title</h5>
						<p class="card-text text-left">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
						<a href="#" class="btn btn-default btn-sm">Go somewhere</a>
						<a href="#" class="btn btn-secondary btn-sm">Go somewhere</a>
					</div>
				</div>
			</div>
			<div class="col">
				<div class="card bg-light mb-3">
					<div class="card-body text-center">
						<h2><span class="oi oi-pie-chart"></span></h2>
						<h5 class="card-title">Light card title</h5>
						<p class="card-text text-left">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
						<a href="#" class="btn btn-default btn-sm">Go somewhere</a>
						<a href="#" class="btn btn-secondary btn-sm">Go somewhere</a>
					</div>
				</div>
			</div>
		</div>
<?php } else if(current_user_can('administrator')) { // USER IS AGENCY ?>
		<div class="row">
			<div class="col">
				<div class="card bg-light mb-3">
					<div class="card-body text-center">
						<h2><i class="far fa-address-card fa-3x"></i></h2>
						<h5 class="card-title"><?php echo gplat_commons::numberize(R::count('wm_contacts')) . ' ' . _n('Profile available', 'Profiles available', gplat_textdomain) ?></h5>
						<p class="card-text text-left">
							Insert here some text or other...
						</p>
						<a href="<?php echo admin_url('admin.php?page=gplat-admin-contacts') ?>" class="btn btn-default btn-sm"><?php _e('Go to contacts', gplat_textdomain) ?></a>
					</div>
				</div>
			</div>
			<div class="col">
				<div class="card bg-light mb-3">
					<div class="card-body text-center">
						<h2><span class="oi oi-pie-chart"></span></h2>
						<h5 class="card-title">Light card title</h5>
						<p class="card-text text-left">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
						<a href="#" class="btn btn-default btn-sm">Go somewhere</a>
						<a href="#" class="btn btn-secondary btn-sm">Go somewhere</a>
					</div>
				</div>
			</div>
			<div class="col">
				<div class="card bg-light mb-3">
					<div class="card-body text-center">
						<h2><span class="oi oi-pie-chart"></span></h2>
						<h5 class="card-title">Light card title</h5>
						<p class="card-text text-left">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
						<a href="#" class="btn btn-default btn-sm">Go somewhere</a>
						<a href="#" class="btn btn-secondary btn-sm">Go somewhere</a>
					</div>
				</div>
			</div>
			<div class="col">
				<div class="card bg-light mb-3">
					<div class="card-body text-center">
						<h2><span class="oi oi-pie-chart"></span></h2>
						<h5 class="card-title">Light card title</h5>
						<p class="card-text text-left">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
						<a href="#" class="btn btn-default btn-sm">Go somewhere</a>
						<a href="#" class="btn btn-secondary btn-sm">Go somewhere</a>
					</div>
				</div>
			</div>
		</div>
<?php } else if(current_user_can('administrator')) { // USER IS PLATFORM ADMINISTRATOR?>
		<div class="row">
			<div class="col">
				<div class="card bg-light mb-3">
					<div class="card-body text-center">
						<h2><i class="far fa-address-card fa-3x"></i></h2>
						<h5 class="card-title"><?php echo gplat_commons::numberize(R::count('wm_contacts')) . ' ' . _n('Profile available', 'Profiles available', gplat_textdomain) ?></h5>
						<p class="card-text text-left">
							Insert here some text or other...
						</p>
						<a href="<?php echo admin_url('admin.php?page=gplat-admin-contacts') ?>" class="btn btn-default btn-sm"><?php _e('Go to contacts', gplat_textdomain) ?></a>
					</div>
				</div>
			</div>
			<div class="col">
				<div class="card bg-light mb-3">
					<div class="card-body text-center">
						<h2><span class="oi oi-pie-chart"></span></h2>
						<h5 class="card-title">Light card title</h5>
						<p class="card-text text-left">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
						<a href="#" class="btn btn-default btn-sm">Go somewhere</a>
						<a href="#" class="btn btn-secondary btn-sm">Go somewhere</a>
					</div>
				</div>
			</div>
			<div class="col">
				<div class="card bg-light mb-3">
					<div class="card-body text-center">
						<h2><span class="oi oi-pie-chart"></span></h2>
						<h5 class="card-title">Light card title</h5>
						<p class="card-text text-left">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
						<a href="#" class="btn btn-default btn-sm">Go somewhere</a>
						<a href="#" class="btn btn-secondary btn-sm">Go somewhere</a>
					</div>
				</div>
			</div>
			<div class="col">
				<div class="card bg-light mb-3">
					<div class="card-body text-center">
						<h2><span class="oi oi-pie-chart"></span></h2>
						<h5 class="card-title">Light card title</h5>
						<p class="card-text text-left">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
						<a href="#" class="btn btn-default btn-sm">Go somewhere</a>
						<a href="#" class="btn btn-secondary btn-sm">Go somewhere</a>
					</div>
				</div>
			</div>
		</div>
<?php } ?>
	</div>
</div>
<script>
	jQuery(document).ready(function($) {
		// Code that uses jQuery's $ can follow here.
	});
</script>