<?php
// Impede o acesso direto a este arquivo
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Funções relacionadas à envio de emails
class Imgno_email_envios{
    

    public function __construct() {
        // parent::__construct();

        // Configurações de contato
		$this->CI =& get_instance();

        // Prefixo da database
        $this->prefixo_db = $this->CI->db->dbprefix;

        $config = $this->CI->db->query('SELECT email_envio,senha_envio,email_exibicao,nome_envio,email_resposta,porta,smtp,seguranca FROM '.$this->prefixo_db.'contato_config WHERE id = 1')->row();

        $this->s_host = ($config->seguranca?$config->seguranca.'://':'').$config->smtp;
		$this->s_port = $config->porta;
		$this->s_email = $config->email_envio;
        $this->s_pass = $config->senha_envio;

        $this->nome_envio = $config->nome_envio;
        $this->email_resposta = $config->email_resposta?$config->email_resposta:$config->email_envio;
        
        $this->config_smtp = array(
			'protocol' => "smtp",
			'smtp_host' => $this->s_host,
			'smtp_port' => $this->s_port,
			'smtp_user' => $this->s_email,
			'smtp_pass' => $this->s_pass,
			'charset' => "utf-8",
			'mailtype' => "html",
			'newline' => "\r\n"
        );
        
        // Carrega as bibliotecas necessárias
		$this->CI->load->library('email');
		$this->CI->load->library('Imgno_url', '', 'im_url');
    }

    public function remover_caracteres_moeda($valor){
		return str_replace(',', ".", str_replace(array('R$ ','.'), "", $valor) );
	}

	public function moeda($valor, $cifrao = false){

		return ($cifrao == true?'R$ ':'').number_format($valor,2,",",".");;
	}

    function enviarEmailRecuperacao($token){

        $dados_token = $this->CI->db->query('SELECT * FROM '.$this->prefixo_db.'clientes_recuperar_senha WHERE token = "'.$token.'" ')->row();
        $dados_cliente = $this->CI->db->query('SELECT * FROM '.$this->prefixo_db.'clientes WHERE id = "'.$dados_token->id_cliente.'" ')->row();

        $assunto = 'Recuperação de senha';
        $mensagem  = '<p>Olá, '.$dados_cliente->nome.'.</p>';
        $mensagem .= '<p>Recebemos sua solicitação de recuperação de senha, <a href="'.base_url().'checkout-senha/'.$token.'.html" target="_blank">clique aqui e modifique sua senha</a>.</p>';
        $mensagem  .= '<p>O acesso pode ser usado 1 única vez e será válido somente por 24 horas.</p>';

        $this->enviarMensagem($mensagem,$assunto,$dados_cliente->email);
    }

    function enviarEmailRecuperacaoAlterar($token, $idCliente){

        $dados_cliente = $this->CI->db->query('SELECT * FROM '.$this->prefixo_db.'clientes WHERE id = "'.$idCliente.'" ')->row();

        $assunto = 'Recuperação de senha';
        $mensagem  = '<p>Olá, '.$dados_cliente->nome.'.</p>';
        $mensagem .= '<p>Recebemos sua solicitação de recuperação de senha, <a href="'.base_url().'checkout-senha/'.$token.'.html" target="_blank">clique aqui e modifique sua senha</a>.</p>';
        $mensagem  .= '<p>O acesso pode ser usado 1 única vez e será válido somente por 24 horas.</p>';

        $this->enviarMensagem($mensagem,$assunto,$dados_cliente->email);
    }

    function enviarMensagem($mensagem,$assunto,$enviar_para){
        $this->CI->email->initialize($this->config_smtp);
        $this->CI->email->clear();

		$this->CI->email->from($this->s_email, $this->nome_envio);
		$this->CI->email->message($mensagem);
		$this->CI->email->subject($assunto);
		$this->CI->email->reply_to($this->email_resposta);
		$this->CI->email->to($enviar_para);
        
        return $this->CI->email->send();
    }
}