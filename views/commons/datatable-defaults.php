processing: true,
serverSide: true,
deferRender: true,
lengthMenu: [ [15, 50, 100], [15, 50, 100] ],
autoWidth: false,
fixedHeader: { header: true, footer: true },
pagingType: "full_numbers",
language: {
	lengthMenu: "<?php _e('Display', gplat_textdomain) ?>  _MENU_ <?php _e('records per page', gplat_textdomain) ?>",
	zeroRecords: "<?php _e('No items to display', gplat_textdomain) ?>",
	info: "<?php _e('Showing page', gplat_textdomain) ?> _PAGE_ <?php _e('of', gplat_textdomain) ?> _PAGES_",
	infoEmpty: "<?php _e('No itemes to display', gplat_textdomain) ?>",
	infoFiltered: "(<?php _e('filtered from', gplat_textdomain) ?> _MAX_ <?php _e('total records', gplat_textdomain) ?>)",
	loadingRecords: "<div class='lds-ellipsis'><div></div><div></div><div></div><div></div></div>",
	processing: "<div class='lds-ellipsis'><div></div><div></div><div></div><div></div></div>",
	paginate: {
		first: "<i class='fas fa-fast-backward'></i>",
		previous: "<i class='fas fa-step-backward'></i>",
		next: "<i class='fas fa-step-forward'></i>",
		last: "<i class='fas fa-fast-forward'></i>"
	},
	aria: {
		paginate: {
			first: "<i class='fas fa-fast-backward'></i>",
			previous: "<i class='fas fa-step-backward'></i>",
			next: "<i class='fas fa-step-forward'></i>",
			last: "<i class='fas fa-fast-forward'></i>"
		}
	}
},