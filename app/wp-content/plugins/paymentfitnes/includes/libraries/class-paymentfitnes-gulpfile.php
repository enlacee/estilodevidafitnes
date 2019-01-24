<?php
/**
 *
 */
class PaymentFitnesGulpfile {

	private $data;

	public function __contructor() {
		self::$instance = $this;
	}

	/**
	* Get instance
	*
	*@return Object
	*/
	public static function getInstance()
	{
		 if (!isset(self::$instance)) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public static function getDataJson() {
		$json = false;

		$pathFile = plugin_dir_path( PAYMENTFITNES_FILE ) . 'gulpfiledata.json';
		if (file_exists($pathFile) === true) {
			$str = file_get_contents($pathFile);
			$json = json_decode($str, true);
		}

		return $json;
	}

	public static function getFileNameMD5($fileName) {
		$dataJson = self::getDataJson();
		$file_md5 = !empty($dataJson['md5']) ? $dataJson['md5'] : '';
		$newFileName = false;

		$strExtension = substr($fileName, strrpos($fileName, '.') + 0);
		$newFileName = str_replace($strExtension, '', $fileName) . "_{$file_md5}{$strExtension}";

		return $newFileName;
	}

}
