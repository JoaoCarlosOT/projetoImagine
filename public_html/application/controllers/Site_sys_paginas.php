<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'controllers/site/Site_controller_paginas.php');

class Site_sys_paginas extends Site_controller_paginas {

	public function index()
	{
        $this->inicio();
	}
}
