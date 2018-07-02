<?php
class plugin_activator {
	public static function activate() {
		self::generate_db();
	}
	protected static function generate_db() {
		R::begin();
		try {

			R::commit();

			if(gplat_dal_rigenerate) {
				self::generate_dal();
			}
		} catch( Exception $e ) {
			self::deactivate();
			R::rollback();
			trigger_error($e->getMessage(), E_USER_ERROR);
		}
	}
	private static function gplat_agencies_table($agency_name) {
		$now = current_time('mysql');
		$table = R::xdispense('gplat_agencies');
		$table->wp_user = get_current_user_id();
		$table->agency_name = $agency_name;;
		$table->description = 'Built-in agency for demo purposes.';
		$table->creation_datetime = $now;
		$table->modification_datetime = $now;
		$table->owner = get_current_user_id();
		$id = R::store($table);
		return $table;
	}
	private static function gplat_campaigns_table($agency_id, $campaign_name, $campaign_type, $status) {
		$now = current_time('mysql');
		$table = R::xdispense('gplat_campaigns');
		$table->agency_id = $agency_id;
		$table->campaign_name = $campaign_name;
		$table->description = 'Built-in campaign for demo purposes.';
		$table->campaign_type = $campaign_type;
		$table->status = $status;
		$table->start_datetime = $now;
		$table->end_datetime = $now;
		$table->elaboration_info = '';
		$table->creation_datetime = $now;
		$table->modification_datetime = $now;
		$table->owner = get_current_user_id();
		$id = R::store($table);
		return $table;
	}
	private static function gplat_campaigns_dem_table($campaign_id) {
		$now = current_time('mysql');
		$table = R::xdispense('gplat_campaigns_dem');
		$table->campaign_id = $campaign_id;
		$table->uid = uniqid( 'd', true );
		$table->script_text = '<script>/* gplat SCRIPT CODE */window.addEventListener(\'load\',function(){var forms=document.getElementsByTagName(\'form\');for(var i=0;i<forms.length;i++){var c=\'' . $table->uid . '\';forms[i].addEventListener(\'submit\',function() {var x=new XMLHttpRequest();if(x){var fd=new FormData();fd.append(\'c\',c);x.open(\'post\',\'https://' . $_SERVER['SERVER_NAME'] . '/wp-content/plugins/gplat/services/submit.php\');x.send(fd);}else{console.error(\'Unable to create XMLHttpRequest object.\');}},false);}},false);</script>';
		$table->html = '<h1>DEM CAMPAIGN FOR DEMO</h1><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus enim lacus, porttitor vitae tristique sit amet, venenatis sit amet dui. Etiam sollicitudin tempor bibendum. Quisque a tellus sodales, malesuada dolor nec, cursus augue. Integer quis tortor at elit elementum interdum. Morbi ullamcorper lorem a ligula sagittis, nec commodo massa scelerisque. Nunc et aliquam enim. Vestibulum id nisl sem. Fusce non tempus nibh. Vestibulum dapibus malesuada mattis. Morbi condimentum nulla id vehicula euismod. Curabitur aliquam vehicula ex quis rhoncus. Proin sapien metus, pellentesque eu laoreet sed, laoreet ut felis. Nullam eu nibh sem.</p>';
		$table->creation_datetime = $now;
		$table->modification_datetime = $now;
		$table->owner = get_current_user_id();
		$id = R::store($table);
		return $id;
	}
	private static function gplat_campaigns_sms_table($campaign_id, $hi_quality_smpp, $sender, $contacts_file_path) {
		$now = current_time('mysql');
		$table = R::xdispense('gplat_campaigns_sms');
		$table->campaign_id = $campaign_id;
		$table->hi_quality_smpp = $hi_quality_smpp;
		$table->sender = $sender;
		$table->message_body = 'Hello from gplat platform ' . ($hi_quality_smpp ? 'hi-quality sms service' : '') . '!';
		$table->delivery_notification = $hi_quality_smpp ? true : false;
		$table->contacts_file_path = $contacts_file_path;
		$table->contacts_count = 1000;
		$table->creation_datetime = $now;
		$table->modification_datetime = $now;
		$table->owner = get_current_user_id();
		$id = R::store($table);
		return $id;
	}
	private static function gplat_cid_dem($count, $campaign_id, $creation_datetime) {
		$transaction_uid = '';
		while(strlen($transaction_uid) < 16) {
			$transaction_uid .= $alphabet[rand(0,count($alphabet) - 1)];
		}
		for($i = 0; $i < $count;$i++) {
			$now = current_time('mysql');
			$d = new DateTime($creation_datetime);
			$table_name = 'gplat_cid' . $campaign_id;
			$table = R::xdispense($table_name);
			$table->campaign_id = $campaign_id;
			$table->ip_address = join('.', array(rand(0, 255), rand(0, 255), rand(0, 255), rand(0, 255)));
			$alphabet = str_split('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz');
			$sending_uid = '258-';
			while(strlen($sending_uid) < 36) {
				$sending_uid .= $alphabet[rand(0,count($alphabet) - 1)];
			}
			$table->sending_uid =  $sending_uid;
			$table->sent_datetime = $now;
			if(rand(0,1) == 1) {
				$table->delivery_datetime = $now;
				if(rand(0,1) == 1) {
					$table->read_datetime = $now;
					if(rand(0,1) == 1) {
						$table->landing_datetime = $now;
					}		
				}
			}
			$table->transaction_uid = $transaction_uid;
			R::store($table);
		}
	}
	private static function gplat_cid_sms($count, $campaign_id, $creation_datetime) {
		$now = current_time('mysql');
		$d = new DateTime($creation_datetime);
		$table_name = 'gplat_cid' . $campaign_id;
		$transaction_uid = '';
		while(strlen($transaction_uid) < 16) {
			$transaction_uid .= $alphabet[rand(0,count($alphabet) - 1)];
		}
		for($i = 0; $i < $count;$i++) {
			$table = R::xdispense($table_name);
			$table->campaign_id = $campaign_id;
			$table->phone = 'A';
			$alphabet = str_split('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz');
			$sending_uid = '258-';
			while(strlen($sending_uid) < 36) {
				$sending_uid .= $alphabet[rand(0,count($alphabet) - 1)];
			}
			$table->sending_uid =  $sending_uid;
			$table->sent_datetime = $now;
			if(rand(0,1) == 1) {
				$table->delivery_datetime = $now;
				if(rand(0,1) == 1) {
					$table->read_datetime = $now;
					if(rand(0,1) == 1) {
						$table->landing_datetime = $now;
					}		
				}
			}
			$table->transaction_uid = $transaction_uid;
			$id = R::store($table);
			$table->phone = '393';
			while(strlen($table->phone) < 11) {
				$table->phone .= rand(0, 9);
			}
			R::store($table);
		}
	}
	private static function gplat_link_dem($count, $campaign_id, $creation_datetime) {
		$now = current_time('mysql');
		$d = new DateTime($creation_datetime);
		$table_name = 'gplat_link_cid' . $campaign_id;
		$alphabet = str_split('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz');
		$transaction_uid = '';
		while(strlen($transaction_uid) < 16) {
			$transaction_uid .= $alphabet[rand(0,count($alphabet) - 1)];
		}
		for($i = 0; $i < $count;$i++) {
			$table = R::xdispense($table_name);
			$table->campaign_id = $campaign_id;
			$table->contact = 'nome.cognome@email.com';
			$domains = array('http://www.domain-1.com', 'http://www.foo-domain.com', 'https://www.mydomain-link.com');
			$table->url = $domains[rand(0,count($domains) - 1)];
			$table->transaction_uid = $transaction_uid;
			$table->creation_datetime = $now;
			$table->device_info = 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.71 Safari/537.36 Edge/12.0';
			$table->ip_address = join('.', array(rand(0, 255), rand(0, 255), rand(0, 255), rand(0, 255)));
			R::store($table);
		}
	}
	private static function gplat_link_sms($count, $campaign_id, $creation_datetime) {
		$now = current_time('mysql');
		$d = new DateTime($creation_datetime);
		$table_name = 'gplat_link_cid' . $campaign_id;
		$alphabet = str_split('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz');
		$transaction_uid = '';
		while(strlen($transaction_uid) < 16) {
			$transaction_uid .= $alphabet[rand(0,count($alphabet) - 1)];
		}
		for($i = 0; $i < $count;$i++) {
			$table = R::xdispense($table_name);
			$table->campaign_id = $campaign_id;
			$table->phone = 'A';
			$domains = array('http://www.domain-1.com', 'http://www.foo-domain.com', 'https://www.mydomain-link.com');
			$table->url = $domains[rand(0,count($domains) - 1)];
			$table->transaction_uid = $transaction_uid;
			$table->device_info = 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.71 Safari/537.36 Edge/12.0';
			$table->creation_datetime = $now;
			$table->ip_address = join('.', array(rand(0, 255), rand(0, 255), rand(0, 255), rand(0, 255)));
			$id = R::store($table);
			$table->phone = '393';
			while(strlen($table->phone) < 11) {
				$table->phone .= rand(0, 9);
			}
			R::store($table);
		}
	}
	protected static function generate_dal() {
		$tables = R::inspect();
		$models_dir_path = gplat_dir_path . 'models/';
		$require_models_content = '';
		foreach($tables as $table) {
			if(!self::startsWith($table, 'gplat_')) { continue; }
			if(gplat_dal_strip_table_prefix) {
				$model_name = str_replace(gplat_dal_table_prefix, '', $table);
			}
			$file_name = str_replace('_', '-', $model_name);
			$fhandle = fopen($models_dir_path . $file_name . '.php', "wa+");
			$model_class_text = '';
			$model_class_text .= '<?php' .PHP_EOL;
			$model_class_text .= '/**' . PHP_EOL;
			$model_class_text .= '  * ' . $model_name . ' model.' . PHP_EOL;
			$model_class_text .= ' */' . PHP_EOL;
			$model_class_text .= 'class ' . $model_name . ' {' . PHP_EOL;
			$model_class_text .= '	protected static $_table_name = \'' . $table . '\';' . PHP_EOL . PHP_EOL;
			$fields = R::inspect( $table );
			foreach($fields as $column => $type) {
				$model_class_text .= '	// ' . $type . PHP_EOL;
				$model_class_text .= '	private $_' . $column . ' = ';
				$default_value = '';
				if(strrpos($type, 'bigint') !== false) {
					$default_value = '0';
				} else if(strrpos($type, 'int') !== false) {
					$default_value = '0';
				} else if(strrpos($type, 'double') !== false) {
					$default_value = '0';
				} else if(strrpos($type, 'varchar') !== false) {
					$default_value = '\'\'';
				} else if(strrpos($type, 'longtext') !== false) {
					$default_value = '\'\'';
				} else if(strrpos($type, 'text') !== false) {
					$default_value = '\'\'';
				} else if(strrpos($type, 'tinytext') !== false) {
					$default_value = '\'\'';
				} else if(strrpos($type, 'mediumtext') !== false) {
					$default_value = '\'\'';
				} else if(strrpos($type, 'datetime') !== false) {
					$default_value = 'null';
				} else {
					$default_value = 'null';
				}
				$model_class_text .= $default_value . ';' . PHP_EOL;
				$model_class_text .= '	/**' . PHP_EOL;
				$model_class_text .= '	 * Gets/Sets ' . $column . ' property.' . PHP_EOL;
				$model_class_text .= '	*/' . PHP_EOL;
				$model_class_text .= '	public function ' . $column . '($value = null) {' . PHP_EOL;
				$model_class_text .= '		if($value != null) {' . PHP_EOL;
				$model_class_text .= '			$this->_' . $column . ' = $value;' . PHP_EOL;
				$model_class_text .= '		}' . PHP_EOL;
				$model_class_text .= '		return $this->$_' . $column . ';' . PHP_EOL;
				$model_class_text .= '	}' . PHP_EOL;
			}

			$model_class_text .= PHP_EOL;

			$model_class_text .= '	/**' . PHP_EOL;
			$model_class_text .= '	 * Adds/Edits model.' . $column . ' property.' . PHP_EOL;
			$model_class_text .= '	*/' . PHP_EOL;
			$model_class_text .= '	public function save() {' . PHP_EOL;
			$model_class_text .= '		$rv = new gplat_action_result();' . PHP_EOL;
			$model_class_text .= '		if($this->id() > 0 ) {' . PHP_EOL;
			$model_class_text .= '			$model = R::load(self::$_table_name, $this->id());' . PHP_EOL;
			$model_class_text .= '			if($model->id > 0 && $model->id == $this->id()) {' . PHP_EOL;
			foreach($fields as $column => $type) {
				if($column == 'id') continue;
				$model_class_text .= '				$model->' . $column . ' = $this->' . $column . ';' . PHP_EOL;
			}
			$model_class_text .= '				R::store($model);' . PHP_EOL;
			$model_class_text .= '				$rv->gplat_action_result::return_ok($model);' . PHP_EOL;
			$model_class_text .= '			} else if($model->id <= 0) {' . PHP_EOL;
			foreach($fields as $column => $type) {
				if($column == 'id') continue;
				$model_class_text .= '				$model->' . $column . ' = $this->' . $column . ';' . PHP_EOL;
			}
			$model_class_text .= '				$id = R::store($model);' . PHP_EOL;
			$model_class_text .= '				if($id > 0) {' . PHP_EOL;
			$model_class_text .= '					$rv->gplat_action_result::return_ok($model);' . PHP_EOL;
			$model_class_text .= '				} else {' . PHP_EOL;
			$model_class_text .= '					$rv = gplat_action_result::return_ko(__(\'Oops! Error \' . error_01x003 . \' occurred, do not be angry we will solve it soon.\', gplat_textdomain), error_01x003);' . PHP_EOL;
			$model_class_text .= '				}' . PHP_EOL;
			$model_class_text .= '			} else if($model->id > 0 && $model->id != $this->id()) {' . PHP_EOL;
			$model_class_text .= '				$rv = gplat_action_result::return_ko(__(\'Oops! Error \' . error_01x002 . \' occurred, do not be angry we will solve it soon.\', gplat_textdomain), error_01x002);' . PHP_EOL;
			$model_class_text .= '			} else {' . PHP_EOL;
			$model_class_text .= '				$rv = gplat_action_result::return_ko(__(\'Oops! Error \' . error_01x001 . \' occurred, do not be angry we will solve it soon.\', gplat_textdomain), error_01x001);' . PHP_EOL;
			$model_class_text .= '			}' . PHP_EOL;
			$model_class_text .= '		}' . PHP_EOL;
			$model_class_text .= '		return $rv;' . PHP_EOL;
			$model_class_text .= '	}' . PHP_EOL;

			$model_class_text .= PHP_EOL;

			$model_class_text .= '	public static function load($id = null) {' . PHP_EOL;
			$model_class_text .= '		$rv = new gplat_action_result();' . PHP_EOL;
			$model_class_text .= '		if($id != null) {' . PHP_EOL;
			$model_class_text .= '			$c = R::find( self::$_table_name, \' id = :id \', [ \':id\' => $id ]);' . PHP_EOL;
			$model_class_text .= '			$rv = gplat_action_result::return_ok($c);' . PHP_EOL;
			$model_class_text .= '		} else {' . PHP_EOL;
			$model_class_text .= '			$c = R::findAll( self::$_table_name );' . PHP_EOL;
			$model_class_text .= '			$rv = gplat_action_result::return_ok($c);' . PHP_EOL;
			$model_class_text .= '		}' . PHP_EOL;
			$model_class_text .= '		return $rv;' . PHP_EOL;
			$model_class_text .= '	}' . PHP_EOL;

			$model_class_text .= PHP_EOL;

			$model_class_text .= '	public static function new(';
			$t = '';
			foreach($fields as $column => $type) {
				if($column == 'id') continue;
				$default_value = '';
				if(strrpos($type, 'bigint') !== false) {
					$default_value = '0';
				} else if(strrpos($type, 'int') !== false) {
					$default_value = '0';
				} else if(strrpos($type, 'double') !== false) {
					$default_value = '0';
				} else if(strrpos($type, 'varchar') !== false) {
					$default_value = '\'\'';
				} else if(strrpos($type, 'longtext') !== false) {
					$default_value = '\'\'';
				} else if(strrpos($type, 'text') !== false) {
					$default_value = '\'\'';
				} else if(strrpos($type, 'tinytext') !== false) {
					$default_value = '\'\'';
				} else if(strrpos($type, 'mediumtext') !== false) {
					$default_value = '\'\'';
				} else if(strrpos($type, 'datetime') !== false) {
					$default_value = 'null';
				} else {
					$default_value = 'null';
				}
				$t .= '$' . $column . ' = ' . $default_value . ', ';	
			}
			$t = substr($t, 0, -2);
			$model_class_text .= $t;
 			$model_class_text .= ') {' . PHP_EOL;
			$model_class_text .= '		$rv = new gplat_action_result();' . PHP_EOL;
			$model_class_text .= '		$model = R::dispense(self::$_table_name);' . PHP_EOL;
			foreach($fields as $column => $type) {
				if($column == 'id') continue;
				$model_class_text .= '		$model->' . $column . ' = $' . $column . ';' . PHP_EOL;
			}
			$model_class_text .= '		$id = R::store($model);' . PHP_EOL;
			$model_class_text .= '		if(!id) {' . PHP_EOL;
			$model_class_text .= '			$rv = gplat_action_result::return_ok($id);' . PHP_EOL;
			$model_class_text .= '		} else {' . PHP_EOL;
			$model_class_text .= '			$rv = gplat_action_result::return_ko(__(\'Oops! Error \' . error_01x003 . \' occurred, do not be angry we will solve it soon.\', gplat_textdomain), error_01x003);' . PHP_EOL;
			$model_class_text .= '		}' . PHP_EOL;
			$model_class_text .= '		return $rv;' . PHP_EOL;
			$model_class_text .= '	}' . PHP_EOL;

			$model_class_text .= '}' . PHP_EOL;
			fwrite($fhandle, $model_class_text);
			fclose($fhandle);

			$require_models_content .= 'require_once( plugin_dir_path( __FILE__ ) . \'models/' . $file_name . '.php\');' . PHP_EOL;
		}
		$require_models_content = '<?php' . PHP_EOL . $require_models_content;
		$fhandle = fopen(gplat_require_models_file_path, "wa+");
		fwrite($fhandle, $require_models_content);
		fclose($fhandle);
	}
	public static function deactivate() {
		global $wpdb;
		// Prevedere una sorta di backup dei dati prima di cancellare le tabelle...
		$tables = R::inspect();
		foreach($tables as $table) {
			if(self::startsWith($table, 'gplat_')) {
				$sql = "DROP TABLE IF EXISTS {$table};";
				$wpdb->query( $sql );
			}
		}
	}
	private static function startsWith($haystack, $needle) {
		$length = strlen($needle);
		return (substr($haystack, 0, $length) === $needle);
	}
}