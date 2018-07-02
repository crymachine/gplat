<?php

class gplat_action_result {
	protected $_return_message = '';
	// Gets or sets return_message value.
	public function return_message($value = null) {
		if($value !== null) {
			$this->_return_message = $value;
		}
		return $this->_return_message;
	}

	protected $_return_value = null;
	// Gets or sets return_value value.
	public function return_value($value = null) {
		if($value !== null) {
			$this->_return_value = $value;
		}
		return $this->_return_value;
	}
	protected $_exception = null;
	// Gets or sets exception value.
	public function exception($value = null) {
		if($value !== null) {
			$this->_exception = $value;
		}
		return $this->_exception;
	}

	protected $_error_code = null;
	// Gets or sets exception value.
	public function error_code($value = null) {
		if($value !== null) {
			$this->_error_code = $value;
		}
		return $this->_error_code;
	}

	protected $_result = false;
	// Gets or sets result value.
	public function result($value = null) {
		if($value !== null) {
			$this->_result = $value;
		}
		return $this->_result;
	}

	public static function return_ok($return_value = null){
		$rv = new gplat_action_result();
		$rv->return_value = $return_value;
		$rv->return_message = 'ok';
		$rv->result = true;
		$rv->error_code = '';
		$rv->exception = null;
		return $rv;
	}
	/**
	 * @param $return_message
	 * @param $error_code
	 * @param $exception
	 * @param $return_value
	 */
	public static function return_ko($return_message = '', $error_code = '', $exception = null, $return_value = null){
		$rv = new gplat_action_result();
		$rv->return_value = $return_value;
		$rv->return_message = $return_message;
		$rv->result = false;
		$rv->error_code = $error_code;
		$rv->exception = $exception;
		return $rv;
	}	
}