<?php
$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once $parse_uri[0] . 'wp-load.php';
require_once gplat_dir_path . 'commons.php';

class campaigns_service {
	public static function request_eval() {
		if(isset($_GET['service'])) {
			eval('self::' . $_GET['service'] . '();');
		}
	}
	public static function get_agency_campaigns() {
		$agency_id = $_GET['agency'];
		$order_column = $_GET['order'][0]['column'];
		$order_dir = $_GET['order'][0]['dir'];
		$search_value = $_GET['search']['value'];
		$start = $_GET['start'];
		$limit = $_GET['length'];
		$table_name = 'gplat_campaigns';
		$inspect_array = array(
			'campaign_id',
			'agency_id',
			'campaign_name',
			'campaign_description',
			'campaign_type',
			'campaign_status',
			'campaign_creation_datetime',
			'campaign_modification_datetime',
			'campaign_owner',
			'is_hq_sms_campaign'
		);
		$order_by = ' order by ' . $inspect_array[$order_column] . ' ' . $order_dir . ' limit ' . $start . ', ' . $limit;
		$total_data = 0;
		$sql = "SELECT c.id as campaign_id, c.agency_id, c.campaign_name, c.description as campaign_description, c.campaign_type as campaign_type, c.`status` as campaign_status, c.creation_datetime as campaign_creation_datetime, c.modification_datetime as campaign_modification_datetime, c.owner as campaign_owner, cs.hi_quality_smpp as is_hq_sms_campaign FROM gplat_campaigns c LEFT JOIN gplat_campaigns_sms cs ON c.id = cs.campaign_id WHERE c.agency_id = " . $agency_id;
		$where = array();
		if($search_value) {
			$fields = array(
				'c.campaign_name',
				'c.description',
				'c.campaign_type',
				'c.`status`',
				'c.owner'
			);
			foreach($fields as $field) {
				$where[] = ' ' . $field . ' LIKE \'%' . $search_value . '%\' ';
			}
			$sql .= ' AND (' . join(' OR ', $where) . ') ' . $order_by;
		} else {
			$sql .= $order_by;
		}
		$result = R::getAll($sql);
		$data = array();
		foreach($result as $item) {
			$data_array = array();
			foreach($item as $key => $value) {
				$data_array[$key] = $value;
			}
			$data_array['owner'] = get_userdata((int)$item['campaign_owner'])->user_login;
			$data[] = $data_array;
		}
		$total_data = count($data);
		$json_data = array(
			"draw"            		=> intval( $_GET['draw'] ),
			"recordsTotal"    		=> intval($total_data),
			"recordsFiltered" 		=> intval($total_data),
			"data"            		=> $data,
			"order_by"				=> $order_column,
			"search_value"			=> $search_value,
			"start"					=> $start,
			"limit"					=> $limit,
			"where_sql"				=> join(' OR ', $where),
			"order_by_sql"			=> $order_by,
			"full_sql"				=> $sql
		);
		echo json_encode($json_data);
	}
	public static function get_campaigns() {
		$order_column = $_GET['order'][0]['column'];
		$order_dir = $_GET['order'][0]['dir'];
		$search_value = $_GET['search']['value'];
		$start = $_GET['start'];
		$limit = $_GET['length'];
		$table_name = 'gplat_campaigns';
		$inspect_array = array(
			'id',
			'agency_id', 
			'agency_name',
			'campaign_name',
			'description',
			'campaign_type',
			'status',
			'start_datetime', 
			'owner',
			'creation_datetime',
			'modification_datetime'
		);
		$order_by = ' order by ' . $inspect_array[$order_column] . ' ' . $order_dir . ' limit ' . $start . ', ' . $limit;
		$total_data = 0;
		$sql = "SELECT c.id, a.id as agency_id, a.agency_name, c.campaign_name, c.description, c.campaign_type, c.`status`, c.start_datetime, c.owner as campaign_owner, c.creation_datetime, c.modification_datetime FROM gplat_campaigns c JOIN gplat_agencies a on c.agency_id = a.id LEFT JOIN gplat_campaigns_dem cd on cd.campaign_id = c.id LEFT JOIN gplat_campaigns_sms cs ON cs.campaign_id = c.id ";
		$where = array();
		if($search_value) {
			$fields = array(
				'c.campaign_name',
				'c.description',
				'c.campaign_type',
				'a.agency_name',
				'c.owner'
			);
			foreach($fields as $field) {
				$where[] = ' ' . $field . ' LIKE \'%' . $search_value . '%\' ';
			}
			$sql .= ' WHERE ' . join(' OR ', $where) . $order_by;
		} else {
			$sql .= $order_by;
		}
		$result = R::getAll($sql);
		$data = array();
		foreach($result as $item) {
			$data_array = array();
			foreach($item as $key => $value) {
				$data_array[$key] = $value;
			}
			$data_array['owner'] = get_userdata((int)$item['campaign_owner'])->user_login;
			$data[] = $data_array;
		}
		$total_data = count($data);
		$json_data = array(
			"draw"            		=> intval( $_GET['draw'] ),
			"recordsTotal"    		=> intval($total_data),
			"recordsFiltered" 		=> intval($total_data),
			"data"            		=> $data,
			"order_by"				=> $order_column,
			"search_value"			=> $search_value,
			"start"					=> $start,
			"limit"					=> $limit,
			"where_sql"				=> join(' OR ', $where),
			"order_by_sql"			=> $order_by,
			"full_sql"				=> $sql
		);
		echo json_encode($json_data);
	}
	public static function get_sms_campaign() {
		$campaign_id = $_GET['campaign'];
		$order_column = $_GET['order'][0]['column'];
		$order_dir = $_GET['order'][0]['dir'];
		$search_value = $_GET['search']['value'];
		$start = $_GET['start'];
		$limit = $_GET['length'];
		$table_name = 'gplat_campaigns';
		$inspect_array = array(
			'id',
			'agency_id', 
			'agency_name',
			'campaign_name',
			'description',
			'campaign_type',
			'status',
			'start_datetime', 
			'owner',
			'creation_datetime',
			'modification_datetime'
		);
		$order_by = ' order by ' . $inspect_array[$order_column] . ' ' . $order_dir . ' limit ' . $start . ', ' . $limit;
		$total_data = 0;
		$sql = "SELECT c.id, c.campaign_name, c.description, c.campaign_type, c.`status`, c.start_datetime, c.owner as campaign_owner, c.creation_datetime, c.modification_datetime FROM " . $table_name . " c LEFT JOIN gplat_campaigns_dem cd on cd.campaign_id = c.id LEFT JOIN gplat_campaigns_sms cs ON cs.campaign_id = c.id WHERE c.id = " . $campaign_id;
		$where = array();
		if($search_value) {
			$fields = array(
				'c.campaign_name',
				'c.description',
				'c.campaign_type',
				'a.agency_name',
				'c.owner'
			);
			foreach($fields as $field) {
				$where[] = ' ' . $field . ' LIKE \'%' . $search_value . '%\' ';
			}
			$sql .= ' AND (' . join(' OR ', $where) . ') ' . $order_by;
		} else {
			$sql .= $order_by;
		}
		$result = R::getAll($sql);
		$data = array();
		foreach($result as $item) {
			$data_array = array();
			foreach($item as $key => $value) {
				$data_array[$key] = $value;
			}

			$data[] = $data_array;
		}
		$total_data = count($data);
		$json_data = array(
			"draw"            		=> intval( $_GET['draw'] ),
			"recordsTotal"    		=> intval($total_data),
			"recordsFiltered" 		=> intval($total_data),
			"data"            		=> $data,
			"order_by"				=> $order_column,
			"search_value"			=> $search_value,
			"start"					=> $start,
			"limit"					=> $limit,
			"where_sql"				=> join(' OR ', $where),
			"order_by_sql"			=> $order_by,
			"full_sql"				=> $sql
		);
		echo json_encode($json_data);
	}
	public static function get_sms_campaign_del_not() {
		$campaign_id = $_GET['campaign'];
		$order_column = $_GET['order'][0]['column'];
		$order_dir = $_GET['order'][0]['dir'];
		$search_value = $_GET['search']['value'];
		$start = $_GET['start'];
		$limit = $_GET['length'];
		$table_name = 'gplat_cid' . $campaign_id;
		$inspect_array = array(
			'id',
			'campaign_id', 
			'phone',
			'sending_uid',
			'sent_datetime',
			'transaction_uid',
			'delivery_datetime',
			'read_datetime', 
			'landing_datetime'
		);
		$order_by = ' order by ' . $inspect_array[$order_column] . ' ' . $order_dir . ' limit ' . $start . ', ' . $limit;
		$total_data = 0;
		$sql = "SELECT * FROM " . $table_name;
		$where = array();
		if($search_value) {
			$fields = array(
				'phone',
				'sending_uid',
				'transaction_uid'
			);
			foreach($fields as $field) {
				$where[] = ' ' . $field . ' LIKE \'%' . $search_value . '%\' ';
			}
			$result = R::getAll($sql . join(' OR ', $where) . $order_by);
		} else {
			$result = R::getAll($sql . $order_by);
		}
		$data = array();
		foreach($result as $item) {
			$data_array = array();
			foreach($item as $key => $value) {
				$data_array[$key] = $value;
			}
			$data[] = $data_array;
		}
		$total_data = count(R::getAll($sql));
		$json_data = array(
			"draw"            		=> intval( $_GET['draw'] ),
			"recordsTotal"    		=> intval($total_data),
			"recordsFiltered" 		=> intval($total_data),
			"data"            		=> $data,
			"order_by"				=> $order_column,
			"search_value"			=> $search_value,
			"start"					=> $start,
			"limit"					=> $limit,
			"where_sql"				=> join(' OR ', $where),
			"order_by_sql"			=> $order_by,
			"full_sql"				=> $sql
		);
		echo json_encode($json_data);
	}
	public static function get_dem_campaign_del_not() {
		$campaign_id = $_GET['campaign'];
		$order_column = $_GET['order'][0]['column'];
		$order_dir = $_GET['order'][0]['dir'];
		$search_value = $_GET['search']['value'];
		$start = $_GET['start'];
		$limit = $_GET['length'];
		$table_name = 'gplat_cid' . $campaign_id;
		$inspect_array = array(
			'id',
			'campaign_id', 
			'phone',
			'sending_uid',
			'sent_datetime',
			'transaction_uid',
			'delivery_datetime',
			'read_datetime', 
			'landing_datetime'
		);
		$order_by = ' order by ' . $inspect_array[$order_column] . ' ' . $order_dir . ' limit ' . $start . ', ' . $limit;
		$total_data = 0;
		$sql = "SELECT * FROM " . $table_name;
		$where = array();
		if($search_value) {
			$fields = array(
				'phone',
				'sending_uid',
				'transaction_uid'
			);
			foreach($fields as $field) {
				$where[] = ' ' . $field . ' LIKE \'%' . $search_value . '%\' ';
			}
			$result = R::getAll($sql . join(' OR ', $where) . $order_by);
		} else {
			$result = R::getAll($sql . $order_by);
		}
		$data = array();
		foreach($result as $item) {
			$data_array = array();
			foreach($item as $key => $value) {
				$data_array[$key] = $value;
			}
			$data[] = $data_array;
		}
		$total_data = count(R::getAll($sql));
		$json_data = array(
			"draw"            		=> intval( $_GET['draw'] ),
			"recordsTotal"    		=> intval($total_data),
			"recordsFiltered" 		=> intval($total_data),
			"data"            		=> $data,
			"order_by"				=> $order_column,
			"search_value"			=> $search_value,
			"start"					=> $start,
			"limit"					=> $limit,
			"where_sql"				=> join(' OR ', $where),
			"order_by_sql"			=> $order_by,
			"full_sql"				=> $sql
		);
		echo json_encode($json_data);
	}
	public static function get_dem_campaign_links() {
		$campaign_id = $_GET['campaign'];
		$order_column = $_GET['order'][0]['column'];
		$order_dir = $_GET['order'][0]['dir'];
		$search_value = $_GET['search']['value'];
		$start = $_GET['start'];
		$limit = $_GET['length'];
		$table_name = 'gplat_link_cid' . $campaign_id;
		$inspect_array = array(
			'id',
			'campaign_id', 
			'ip_address',
			'contact',
			'url',
			'transaction_uid',
			'creation_datetime',
			'device_info'
		);
		$order_by = ' order by ' . $inspect_array[$order_column] . ' ' . $order_dir . ' limit ' . $start . ', ' . $limit;
		$total_data = 0;
		$sql = "SELECT * FROM " . $table_name;
		$where = array();
		if($search_value) {
			$fields = array(
				'contact'
			);
			foreach($fields as $field) {
				$where[] = ' ' . $field . ' LIKE \'%' . $search_value . '%\' ';
			}
			$result = R::getAll($sql . join(' OR ', $where) . $order_by);
		} else {
			$result = R::getAll($sql . $order_by);
		}
		$data = array();
		foreach($result as $item) {
			$data_array = array();
			foreach($item as $key => $value) {
				$data_array[$key] = $value;
			}
			$data[] = $data_array;
		}
		$total_data = count(R::getAll($sql));
		$json_data = array(
			"draw"            		=> intval( $_GET['draw'] ),
			"recordsTotal"    		=> intval($total_data),
			"recordsFiltered" 		=> intval($total_data),
			"data"            		=> $data,
			"order_by"				=> $order_column,
			"search_value"			=> $search_value,
			"start"					=> $start,
			"limit"					=> $limit,
			"where_sql"				=> join(' OR ', $where),
			"order_by_sql"			=> $order_by,
			"full_sql"				=> $sql
		);
		echo json_encode($json_data);
	}
	public static function get_sms_campaign_links() {
		$campaign_id = $_GET['campaign'];
		$order_column = $_GET['order'][0]['column'];
		$order_dir = $_GET['order'][0]['dir'];
		$search_value = $_GET['search']['value'];
		$start = $_GET['start'];
		$limit = $_GET['length'];
		$table_name = 'gplat_link_cid' . $campaign_id;
		$inspect_array = array(
			'id',
			'campaign_id', 
			'ip_address',
			'phone',
			'url',
			'transaction_uid',
			'device_info',
			'creation_datetime'
		);
		$order_by = ' order by ' . $inspect_array[$order_column] . ' ' . $order_dir . ' limit ' . $start . ', ' . $limit;
		$total_data = 0;
		$sql = "SELECT * FROM " . $table_name;
		$where = array();
		if($search_value) {
			$fields = array(
				'phone'
			);
			foreach($fields as $field) {
				$where[] = ' ' . $field . ' LIKE \'%' . $search_value . '%\' ';
			}
			$result = R::getAll($sql . join(' OR ', $where) . $order_by);
		} else {
			$result = R::getAll($sql . $order_by);
		}
		$data = array();
		foreach($result as $item) {
			$data_array = array();
			foreach($item as $key => $value) {
				$data_array[$key] = $value;
			}
			$data[] = $data_array;
		}
		$total_data = count(R::getAll($sql));
		$json_data = array(
			"draw"            		=> intval( $_GET['draw'] ),
			"recordsTotal"    		=> intval($total_data),
			"recordsFiltered" 		=> intval($total_data),
			"data"            		=> $data,
			"order_by"				=> $order_column,
			"search_value"			=> $search_value,
			"start"					=> $start,
			"limit"					=> $limit,
			"where_sql"				=> join(' OR ', $where),
			"order_by_sql"			=> $order_by,
			"full_sql"				=> $sql
		);
		echo json_encode($json_data);
	}
}

campaigns_service::request_eval();