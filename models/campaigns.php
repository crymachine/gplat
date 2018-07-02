<?php
/**
  * campaigns model.
 */
class campaigns {
	protected static $_table_name = 'gplat_campaigns';

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
	private $_agency_id = 0;
	/**
	 * Gets/Sets agency_id property.
	*/
	public function agency_id($value = null) {
		if($value != null) {
			$this->_agency_id = $value;
		}
		return $this->$_agency_id;
	}
	// varchar(191)
	private $_campaign_name = '';
	/**
	 * Gets/Sets campaign_name property.
	*/
	public function campaign_name($value = null) {
		if($value != null) {
			$this->_campaign_name = $value;
		}
		return $this->$_campaign_name;
	}
	// varchar(191)
	private $_description = '';
	/**
	 * Gets/Sets description property.
	*/
	public function description($value = null) {
		if($value != null) {
			$this->_description = $value;
		}
		return $this->$_description;
	}
	// varchar(191)
	private $_campaign_type = '';
	/**
	 * Gets/Sets campaign_type property.
	*/
	public function campaign_type($value = null) {
		if($value != null) {
			$this->_campaign_type = $value;
		}
		return $this->$_campaign_type;
	}
	// varchar(191)
	private $_status = '';
	/**
	 * Gets/Sets status property.
	*/
	public function status($value = null) {
		if($value != null) {
			$this->_status = $value;
		}
		return $this->$_status;
	}
	// datetime
	private $_start_datetime = null;
	/**
	 * Gets/Sets start_datetime property.
	*/
	public function start_datetime($value = null) {
		if($value != null) {
			$this->_start_datetime = $value;
		}
		return $this->$_start_datetime;
	}
	// datetime
	private $_end_datetime = null;
	/**
	 * Gets/Sets end_datetime property.
	*/
	public function end_datetime($value = null) {
		if($value != null) {
			$this->_end_datetime = $value;
		}
		return $this->$_end_datetime;
	}
	// varchar(191)
	private $_elaboration_info = '';
	/**
	 * Gets/Sets elaboration_info property.
	*/
	public function elaboration_info($value = null) {
		if($value != null) {
			$this->_elaboration_info = $value;
		}
		return $this->$_elaboration_info;
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
				$model->agency_id = $this->agency_id;
				$model->campaign_name = $this->campaign_name;
				$model->description = $this->description;
				$model->campaign_type = $this->campaign_type;
				$model->status = $this->status;
				$model->start_datetime = $this->start_datetime;
				$model->end_datetime = $this->end_datetime;
				$model->elaboration_info = $this->elaboration_info;
				$model->creation_datetime = $this->creation_datetime;
				$model->modification_datetime = $this->modification_datetime;
				$model->owner = $this->owner;
				R::store($model);
				$rv->gplat_action_result::return_ok($model);
			} else if($model->id <= 0) {
				$model->agency_id = $this->agency_id;
				$model->campaign_name = $this->campaign_name;
				$model->description = $this->description;
				$model->campaign_type = $this->campaign_type;
				$model->status = $this->status;
				$model->start_datetime = $this->start_datetime;
				$model->end_datetime = $this->end_datetime;
				$model->elaboration_info = $this->elaboration_info;
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

	public static function new($agency_id = 0, $campaign_name = '', $description = '', $campaign_type = '', $status = '', $start_datetime = null, $end_datetime = null, $elaboration_info = '', $creation_datetime = null, $modification_datetime = null, $owner = 0) {
		$rv = new gplat_action_result();
		$model = R::dispense(self::$_table_name);
		$model->agency_id = $agency_id;
		$model->campaign_name = $campaign_name;
		$model->description = $description;
		$model->campaign_type = $campaign_type;
		$model->status = $status;
		$model->start_datetime = $start_datetime;
		$model->end_datetime = $end_datetime;
		$model->elaboration_info = $elaboration_info;
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
