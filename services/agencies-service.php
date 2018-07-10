<?php
$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once $parse_uri[0] . 'wp-load.php';

class agencies_service {
	public static function request_eval() {
		if(isset($_GET['service'])) {
			eval('self::' . $_GET['service'] . '();');
		}
	}

	public static function get_agencies() {
		$order_column = $_GET['order'][0]['column'];
		$order_dir = $_GET['order'][0]['dir'];
		$search_value = $_GET['search']['value'];
		$start = $_GET['start'];
		$limit = $_GET['length'];
		$table_name = 'gplat_agencies';
		$order_by = ' order by ' . array_keys(R::inspect($table_name))[$order_column] . ' ' . $order_dir . ' limit ' . $start . ', ' . $limit;
		$total_data = 0;
		if($search_value) {
			$fields = array(
				'agency_name',
				'description'
			);
			$where = array();
			foreach($fields as $field) {
				$where[] = ' ' . $field . ' LIKE \'%' . $search_value . '%\' ';
			}
			$where_text = join(' OR ', $where);
			$where_text .= $order_by;
			$result = R::findAll($table_name, $where_text);
		} else {
			$result = R::findAll($table_name, $order_by);
		}
		$data = array();
		foreach($result as $item) {
			$data_array = array();
			foreach($item as $key => $value) {
				$data_array[$key] = $value;
			}
			$data_array['owner'] = get_userdata((int)$item->owner)->user_login;
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
			"where_sql"				=> $where_text,
			"order_by_sql"			=> $order_by
		);
		echo json_encode($json_data);		
	}

	public static function get_agency() {
	}

	public static function edit_agency() {
		$rv = '';
		try {
			$agency_id = $_POST['agency_id'];
			$agency_name = $_POST['agency_name'];
			$description = $_POST['description'];

			$entity = R::load('gplat_agencies', $agency_id);
			$entity->agency_name = $agency_name;
			$entity->description = $description;
			$entity->modification_datetime = current_time('mysql',false);
			R::store($entity);

			$rv = __('Agency updated successfully', gplat_textdomain);
		} catch(Exception $e) {
			$rv = $e->getMessage();
		}
		echo $rv;
	}
}

agencies_service::request_eval();