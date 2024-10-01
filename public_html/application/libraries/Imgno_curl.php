<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); // Impede o acesso direto a este arquivo
// Processa as requisições de envio de mensagens
class EnvioCURL{
	// Mantém os emails dos assinantes que deram erro
	var $erros;
	
	// Gera requisições paralelas usando CURL
	function enviar($id_campanha, $assinantes, $block_smtp = 0) {
		// Adiciona o envio de cada assinante na lista de processos
		$link_base = base_url('enviar-email-usuario/'.  .'/'.  .'/')
		foreach($assinantes as $assinante) {
			$this->startRequest("{$link_base}&ida={$assinante->id}&token={$assinante->token}",'',$assinante->email);
		}
		
		// Indica que o sistema só deve prosseguir após todas as chamadas serem concluídas
		$this->finishAllRequests();
		
		// Retorna os emails que deram erro de envio
		return $this->erros;
	}
	
	// Verifica se alguma das requisições pendentes foi concluída
	private function checkForCompletedRequests(){
		// Call select to see if anything is waiting for us
		if (curl_multi_select($this->multi_handle, 0.0) === -1) return;
		
		// Since something's waiting, give curl a chance to process it
		do {
			$mrc = curl_multi_exec($this->multi_handle, $active);
		} while ($mrc == CURLM_CALL_MULTI_PERFORM);
		
		// Now grab the information about the completed requests
		while ($info = curl_multi_info_read($this->multi_handle)) {
			$ch = $info['handle'];
			if (isset($this->outstanding_requests[$ch])) {
				$content = curl_multi_getcontent($ch);
				if($content != '1'){
					$request = $this->outstanding_requests[$ch];
					$this->erros[] = $request['user_data'];
				}
			}
			unset($this->outstanding_requests[$ch]);
			curl_multi_remove_handle($this->multi_handle, $ch);
		}
	}
	
	#--------------------------------------------------------------------------------------------------------------------------------------------
	# Conteúdo da classe ParallelCURL
	public $max_requests;
	public $options;
	public $outstanding_requests;
	public $multi_handle;
	
	public function __construct($in_max_requests = 20, $in_options = array()) {
		$this->max_requests = $in_max_requests;
		$this->options = $in_options;
		$this->outstanding_requests = array();
		$this->multi_handle = curl_multi_init();
	}
	
	// Ensure all the requests finish nicely
	public function __destruct() {
		$this->finishAllRequests();
	}
	
	// Sets how many requests can be outstanding at once before we block and wait for one to finish before starting the next one
	public function setMaxRequests($in_max_requests) {
		$max_requests = $in_max_requests;
	}
	
	// Sets the options to pass to curl, using the format of curl_setopt_array()
	public function setOptions($in_options) {
		$options = $in_options;
	}
	
	// Start a fetch from the $url address, calling the $callback function passing the optional $user_data value.
	// The callback should accept 3 arguments, the url, curl handle and user data, eg on_request_done($url, $ch, $user_data);
	public function startRequest($url, $callback, $user_data = array()) {
	if($this->max_requests > 0)
		$this->waitForOutstandingRequestsToDropBelow($this->max_requests);
		
		$ch = curl_init();
		curl_setopt_array($ch, $this->options);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_multi_add_handle($this->multi_handle, $ch);
		
		$this->outstanding_requests[$ch] = array(
			'url' => $url,
			'callback' => $callback,
			'user_data' => $user_data,
		);
		
		$this->checkForCompletedRequests();
	}
	
	// You *MUST* call this function at the end of your script. It waits for any running requests to complete, and calls their callback functions
	public function finishAllRequests() {
		$this->waitForOutstandingRequestsToDropBelow(1);
	}
	
	// Blocks until there's less than the specified number of requests outstanding
	private function waitForOutstandingRequestsToDropBelow($max) {
		while (1) {
			$this->checkForCompletedRequests();
			if (count($this->outstanding_requests)<$max) break;
			usleep(10000);
		}
	}
}
?>