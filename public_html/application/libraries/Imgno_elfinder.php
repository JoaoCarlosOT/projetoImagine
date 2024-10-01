<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//error_reporting(0);
include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'elfinder'.DIRECTORY_SEPARATOR.'elFinderConnector.class.php');
include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'elfinder'.DIRECTORY_SEPARATOR.'elFinder.class.php');
include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'elfinder'.DIRECTORY_SEPARATOR.'elFinderVolumeDriver.class.php');
include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'elfinder'.DIRECTORY_SEPARATOR.'elFinderVolumeLocalFileSystem.class.php');

class Imgno_elfinder {
	public function __construct($opts) {
		$connector = new elFinderConnector(new elFinder($opts));
		$connector->run();
	}
}