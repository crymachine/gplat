<?php

class gplat_commons {
	public static function startsWith($haystack, $needle) {
		 $length = strlen($needle);
		 return (substr($haystack, 0, $length) === $needle);
	}
	public static function endsWith($haystack, $needle) {
		$length = strlen($needle);
	
		return $length === 0 || 
		(substr($haystack, -$length) === $needle);
	}
	public static function dir_is_empty($dir) {
		$handle = opendir($dir);
		while (false !== ($entry = readdir($handle))) {
			if ($entry != "." && $entry != "..") {
				return false;
			}
		}
		return true;
	}
	public static function dump($context = null) {
		echo('<div style="position:fixed;top: 40px;left: 170px;right:10px;overflow:hidden;border: 1px solid #ccc;background-color:rgba(255,255,255,0.98);padding:40px;z-index:9999;">');
		echo '<h1 style="border-bottom:1px solid #eee;padding-bottom:20px;">dump</h1><div style="overflow:auto;height:500px;border:1px solid #ccc;background-color:#fafafa;padding:20px;"><pre>';
		var_dump($context);
		echo('</pre></div></div>');
	}
	public static function numberize( $n, $precision = 2 ){
		if ($n < 900) {
			// 0 - 900
			$n_format = number_format($n, $precision);
			$suffix = '';
		} else if ($n < 900000) {
			// 0.9k-850k
			$n_format = number_format($n / 1000, $precision);
			$suffix = 'K';
		} else if ($n < 900000000) {
			// 0.9m-850m
			$n_format = number_format($n / 1000000, $precision);
			$suffix = 'M';
		} else if ($n < 900000000000) {
			// 0.9b-850b
			$n_format = number_format($n / 1000000000, $precision);
			$suffix = 'B';
		} else {
			// 0.9t+
			$n_format = number_format($n / 1000000000000, $precision);
			$suffix = 'T';
		}
	  // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
	  // Intentionally does not affect partials, eg "1.50" -> "1.50"
		if ( $precision > 0 ) {
			$dotzero = '.' . str_repeat( '0', $precision );
			$n_format = str_replace( $dotzero, '', $n_format );
		}
		return $n_format . $suffix;
	}	
	public static function normalize_name($text) {
		return ucwords(str_replace('_', ' ', $text));
	}
	public static function translate_status($status) {
		$rv = __('Unknown', gplat_textdomain);
		switch($status) {
			case 'COMPLETED':
				$rv = __('Completed', gplat_textdomain);
				break;
			case 'COMPLETED WITH ERRORS':
				$rv = __('Completed with errors', gplat_textdomain);
				break;
			case 'SENDING':
				$rv = __('Sending', gplat_textdomain);
				break;
			case 'PREPARING':
				$rv = __('Preparing', gplat_textdomain);
				break;
		}
		return $rv;
	}
	public static function status_to_icon($status) {
		$rv = "";
		switch($status) {
			case 'COMPLETED':
				$rv = "<i class='fa fa-check-circle text-success' title='" . self::translate_status($status) . "'></i>";
				break;
			case 'ERROR':
				$rv = "<i class='fa fa-exclamation-triangle text-danger' title='" . self::translate_status($status) . "'></i>";
				break;
			case 'SENDING':
				$rv = "<i class='fa fa-share-square text-secondary' title='" . self::translate_status($status) . "'></i>";
				break;
			case 'PREPARING':
				$rv = "<i class='fa fa-cogs text-secondary' title='" . self::translate_status($status) . "'></i>";
				break;
		}
		return $rv;
	}
	public static function translate_campaign_type($campaign_type) {
		$rv = __('Unknown', gplat_textdomain);
		switch($campaign_type) {
			case 'SMS':
				$rv = __('SMS Campaign', gplat_textdomain);
				break;
			case 'DEM':
				$rv = __('DEM Campaign', gplat_textdomain);
				break;
		}
		return $rv;
	}
	public static function create_google_short_url($apiKey, $longUrl) {
		$postData = array('longUrl' => $longUrl, 'key' => $apiKey);
		$jsonData = json_encode($postData);
		$curlObj = curl_init();

		curl_setopt($curlObj, CURLOPT_URL, 'https://www.googleapis.com/urlshortener/v1/url');
		curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curlObj, CURLOPT_HEADER, 0);
		curl_setopt($curlObj, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
		curl_setopt($curlObj, CURLOPT_POST, 1);
		curl_setopt($curlObj, CURLOPT_POSTFIELDS, $jsonData);

		$response = curl_exec($curlObj);
		$json = json_decode($response);
		curl_close($curlObj);
		$rv = false;
		if($json->status == 'OK') {
			$rv = $json->id;
		}
		return $rv;
	}
	public static function page_common_styles() { ?>
	<style>
		.nav-link, .nav-link:visited{text-decoration:underline !important;}
		.lds-ellipsis{display: inline-block;position: relative;width: 64px;height: 64px;}
		.lds-ellipsis div {position: absolute;top: 27px;width: 11px;height: 11px;border-radius: 50%;background: #ccc;animation-timing-function: cubic-bezier(0, 1, 1, 0);}
		.lds-ellipsis div:nth-child(1) {left: 6px;animation: lds-ellipsis1 0.6s infinite;}
		.lds-ellipsis div:nth-child(2) {eft: 6px;animation: lds-ellipsis2 0.6s infinite;}
		.lds-ellipsis div:nth-child(3) {left: 26px;animation: lds-ellipsis2 0.6s infinite;}
		.lds-ellipsis div:nth-child(4) {left: 45px;animation: lds-ellipsis3 0.6s infinite;}
		@keyframes lds-ellipsis1 {0% {transform: scale(0);}	100% {transform: scale(1);}}
		@keyframes lds-ellipsis3 {0% {transform: scale(1);}	100% {transform: scale(0);}}
		@keyframes lds-ellipsis2 {0% {transform: translate(0, 0);} 100% {transform: translate(19px, 0);}}
	</style>
<?php
	}
}