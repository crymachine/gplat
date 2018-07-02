<?php
/**
  * campaigns_dem model.
 */
class campaigns_dem {
	protected static $_table_name = 'gplat_campaigns_dem';

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
	// varchar(191)
	private $_uid = '';
	/**
	 * Gets/Sets uid property.
	*/
	public function uid($value = null) {
		if($value != null) {
			$this->_uid = $value;
		}
		return $this->$_uid;
	}
	// text
	private $_script_text = '';
	/**
	 * Gets/Sets script_text property.
	*/
	public function script_text($value = null) {
		if($value != null) {
			$this->_script_text = $value;
		}
		return $this->$_script_text;
	}
	// text
	private $_html = '';
	/**
	 * Gets/Sets html property.
	*/
	public function html($value = null) {
		if($value != null) {
			$this->_html = $value;
		}
		return $this->$_html;
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
				$model->uid = $this->uid;
				$model->script_text = $this->script_text;
				$model->html = $this->html;
				$model->creation_datetime = $this->creation_datetime;
				$model->modification_datetime = $this->modification_datetime;
				$model->owner = $this->owner;
				R::store($model);
				$rv->gplat_action_result::return_ok($model);
			} else if($model->id <= 0) {
				$model->campaign_id = $this->campaign_id;
				$model->uid = $this->uid;
				$model->script_text = $this->script_text;
				$model->html = $this->html;
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

	public static function new($campaign_id = 0, $uid = '', $script_text = '', $html = '', $creation_datetime = null, $modification_datetime = null, $owner = 0) {
		$rv = new gplat_action_result();
		$model = R::dispense(self::$_table_name);
		$model->campaign_id = $campaign_id;
		$model->uid = $uid;
		$model->script_text = $script_text;
		$model->html = $html;
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
