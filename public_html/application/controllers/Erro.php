<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Erro extends CI_Controller {

	public function index()
	{
		$this->load->helper('url');
		redirect(base_url()."#erroO conteúdo buscado não está disponível");
		// redirect("erro.html");
	}
}
