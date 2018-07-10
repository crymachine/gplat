<?php
/**
  * campaigns_sms model.
 */
class campaigns_sms {
	protected static $_table_name = 'gplat_campaigns_sms';

	// int(11) unsigned
	private $_id = 0;
	/**
	 * Gets/Sets id property.
	*/
	public function id($value = null) {
		if($value != null) {
			$this->_id = $value;
		}
		return $this->$_id;
	}
	// int(11) unsigned
	private $_campaign_id = 0;
	/**
	 * Gets/Sets campaign_id property.
	*/
	public function campaign_id($value = null) {
		if($value != null) {
			$this->_campaign_id = $value;
		}
		return $this->$_campaign_id;
	}
	// tinyint(1) unsigned
	private $_hi_quality_smpp = 0;
	/**
	 * Gets/Sets hi_quality_smpp property.
	*/
	public function hi_quality_smpp($value = null) {
		if($value != null) {
			$this->_hi_quality_smpp = $value;
		}
		return $this->$_hi_quality_smpp;
	}
	// varchar(191)
	private $_sender = '';
	/**
	 * Gets/Sets sender property.
	*/
	public function sender($value = null) {
		if($value != null) {
			$this->_sender = $value;
		}
		return $this->$_sender;
	}
	// varchar(191)
	private $_message_body = '';
	/**
	 * Gets/Sets message_body property.
	*/
	public function message_body($value = null) {
		if($value != null) {
			$this->_message_body = $value;
		}
		return $this->$_message_body;
	}
	// tinyint(1) unsigned
	private $_delivery_notification = 0;
	/**
	 * Gets/Sets delivery_notification property.
	*/
	public function delivery_notification($value = null) {
		if($value != null) {
			$this->_delivery_notification = $value;
		}
		return $this->$_delivery_notification;
	}
	// varchar(191)
	private $_contacts_file_path = '';
	/**
	 * Gets/Sets contacts_file_path property.
	*/
	public function contacts_file_path($value = null) {
		if($value != null) {
			$this->_contacts_file_path = $value;
		}
		return $this->$_contacts_file_path;
	}
	// int(11) unsigned
	private $_contacts_count = 0;
	/**
	 * Gets/Sets contacts_count property.
	*/
	public function contacts_count($value = null) {
		if($value != null) {
			$this->_contacts_count = $value;
		}
		return $this->$_contacts_count;
	}
	// datetime
	private $_creation_datetime = null;
	/**
	 * Gets/Sets creation_datetime property.
	*/
	public function creation_datetime($value = null) {
		if($value != null) {
			$this->_creation_datetime = $value;
		}
		return $this->$_creation_datetime;
	}
	// datetime
	private $_modification_datetime = null;
	/**
	 * Gets/Sets modification_datetime property.
	*/
	public function modification_datetime($value = null) {
		if($value != null) {
			$this->_modification_datetime = $value;
		}
		return $this->$_modification_datetime;
	}
	// int(11) unsigned
	private $_owner = 0;
	/**
	 * Gets/Sets owner property.
	*/
	public function owner($value = null) {
		if($value != null) {
			$this->_owner = $value;
		}
		return $this->$_owner;
	}

	/**
	 * Adds/Edits model.owner property.
	*/
	public function save() {
		$rv = new gplat_action_result();
		if($this->id() > 0 ) {
			$model = R::load(self::$_table_name, $this->id());
			if($model->id > 0 && $model->id == $this->id()) {
				$model->campaign_id = $this->campaign_id;
				$model->hi_quality_smpp = $this->hi_quality_smpp;
				$model->sender = $this->sender;
				$model->message_body = $this->message_body;
				$model->delivery_notification = $this->delivery_notification;
				$model->contacts_file_path = $this->contacts_file_path;
				$model->contacts_count = $this->contacts_count;
				$model->creation_datetime = $this->creation_datetime;
				$model->modification_datetime = $this->modification_datetime;
				$model->owner = $this->owner;
				R::store($model);
				$rv->gplat_action_result::return_ok($model);
			} else if($model->id <= 0) {
				$model->campaign_id = $this->campaign_id;
				$model->hi_quality_smpp = $this->hi_quality_smpp;
				$model->sender = $this->sender;
				$model->message_body = $this->message_body;
				$model->delivery_notification = $this->delivery_notification;
				$model->contacts_file_path = $this->contacts_file_path;
				$model->contacts_count = $this->contacts_count;
				$model->creation_datetime = $this->creation_datetime;
				$model->modification_datetime = $this->modification_datetime;
				$model->owner = $this->owner;
				$id = R::store($model);
				if($id > 0) {
					$rv->gplat_action_result::return_ok($model);
				} else {
					$rv = gplat_action_result::return_ko(__('Oops! Error ' . error_01x003 . ' occurred, do not be angry we will solve it soon.', gplat_textdomain), error_01x003);
				}
			} else if($model->id > 0 && $model->id != $this->id()) {
				$rv = gplat_action_result::return_ko(__('Oops! Error ' . error_01x002 . ' occurred, do not be angry we will solve it soon.', gplat_textdomain), error_01x002);
			} else {
				$rv = gplat_action_result::return_ko(__('Oops! Error ' . error_01x001 . ' occurred, do not be angry we will solve it soon.', gplat_textdomain), error_01x001);
			}
		}
		return $rv;
	}

	public static function load($id = null) {
		$rv = new gplat_action_result();
		if($id != null) {
			$c = R::find( self::$_table_name, ' id = :id ', [ ':id' => $id ]);
			$rv = gplat_action_result::return_ok($c);
		} else {
			$c = R::findAll( self::$_table_name );
			$rv = gplat_action_result::return_ok($c);
		}
		return $rv;
	}

	public static function new($campaign_id = 0, $hi_quality_smpp = 0, $sender = '', $message_body = '', $delivery_notification = 0, $contacts_file_path = '', $contacts_count = 0, $creation_datetime = null, $modification_datetime = null, $owner = 0) {
		$rv = new gplat_action_result();
		$model = R::dispense(self::$_table_name);
		$model->campaign_id = $campaign_id;
		$model->hi_quality_smpp = $hi_quality_smpp;
		$model->sender = $sender;
		$model->message_body = $message_body;
		$model->delivery_notification = $delivery_notification;
		$model->contacts_file_path = $contacts_file_path;
		$model->contacts_count = $contacts_count;
		$model->creation_datetime = $creation_datetime;
		$model->modification_datetime = $modification_datetime;
		$model->owner = $owner;
		$id = R::store($model);
		if(!id) {
			$rv = gplat_action_result::return_ok($id);
		} else {
			$rv = gplat_action_result::return_ko(__('Oops! Error ' . error_01x003 . ' occurred, do not be angry we will solve it soon.', gplat_textdomain), error_01x003);
		}
		return $rv;
	}
}
