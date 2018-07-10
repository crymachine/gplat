<?php
/**
  * agencies model.
 */
class agencies {
	protected static $_table_name = 'gplat_agencies';

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
	private $_wp_user = 0;
	/**
	 * Gets/Sets wp_user property.
	*/
	public function wp_user($value = null) {
		if($value != null) {
			$this->_wp_user = $value;
		}
		return $this->$_wp_user;
	}
	// varchar(191)
	private $_agency_name = '';
	/**
	 * Gets/Sets agency_name property.
	*/
	public function agency_name($value = null) {
		if($value != null) {
			$this->_agency_name = $value;
		}
		return $this->$_agency_name;
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
				$model->wp_user = $this->wp_user;
				$model->agency_name = $this->agency_name;
				$model->description = $this->description;
				$model->creation_datetime = $this->creation_datetime;
				$model->modification_datetime = $this->modification_datetime;
				$model->owner = $this->owner;
				R::store($model);
				$rv->gplat_action_result::return_ok($model);
			} else if($model->id <= 0) {
				$model->wp_user = $this->wp_user;
				$model->agency_name = $this->agency_name;
				$model->description = $this->description;
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

	public static function new($wp_user = 0, $agency_name = '', $description = '', $creation_datetime = null, $modification_datetime = null, $owner = 0) {
		$rv = new gplat_action_result();
		$model = R::dispense(self::$_table_name);
		$model->wp_user = $wp_user;
		$model->agency_name = $agency_name;
		$model->description = $description;
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
