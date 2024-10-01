<?php
// Impede o acesso direto a este arquivo
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Funções relacionadas à envio de emails
class Imgno_email {
	// Construtor

	private $config = null;
	function __construct($args = array()) {
		$this->CI =& get_instance();

		// parent::__construct();
		// Prefixo da database
		$this->prefixo_db = $this->CI->model->db->dbprefix;
		$this->config = $this->CI->model->db->query('SELECT * FROM '.$this->prefixo_db.'contato_config WHERE id = 1')->row();
		// Configurações de contato
		if($this->config) {
			
			// Conta para envio
			$this->s_name = $this->config->nome_envio;
			$this->s_email = $this->config->email_envio;
			$this->email_resposta = $this->config->email_resposta;
			$this->s_pass = $this->config->senha_envio;
			$this->envio_smtp = ($this->s_name && $this->s_email && $this->s_pass)? 1 : 0;
			
			// Emails de notificação
			$this->emails_admin = ($this->config->email2?array($this->config->email1,$this->config->email2):$this->config->email1);
			
			$this->email_trabalhe_conosco = $this->config->email_trabalhe_conosco;

		} else return FALSE;
	}
	
	// Envia um email usando SMTP 	

	function enviarEmail($destinatarios, $assunto, $mensagem, $attachment = null){
		
		// Carrega a biblioteca
		$this->CI->load->library('email');

		if($this->envio_smtp) {
			// Configuração
			$config = array();
			$config['protocol'] = "smtp";
			$config['smtp_host'] =  $this->config->seguranca.'://'.$this->config->smtp;
			$config['smtp_port'] = $this->config->porta;
			$config['smtp_user'] = $this->s_email;
			$config['smtp_pass'] = $this->s_pass;
			$config['charset'] = "utf-8";
			$config['mailtype'] = "html";
			$config['newline'] = "\r\n";
		} else {
			$config = array(
				'useragent' => "Imagine",
				'mailpath' => "/usr/sbin/sendmail",
				'protocol' => "sendmail",
				'smtp_host' => "",
				'smtp_user' => "",
				'smtp_pass' => "",
				'charset' => "utf-8",
				'mailtype' => "html",
				'newline' => "\r\n"
			);
		}
			$this->CI->email->initialize($config);
			
			$destinatarios = (array)$destinatarios;
			$this->CI->email->from($this->s_email, $this->s_name);
			$this->CI->email->to($destinatarios);
			$this->CI->email->reply_to($this->email_resposta);
			//$this->email->cc('another@another-example.com');
			//$this->email->bcc('them@their-example.com');
			$this->CI->email->subject($assunto);
			$this->CI->email->message($mensagem);
			
			// Envio
			$sent = $this->CI->email->send();
		
		// Retorno
		return $sent;
	}

	function enviarEmailAdmin($mensagem, $assunto = false, $attachment = null, $TrabalheConosco = false,$destinatario_admin = null){

		if($destinatario_admin){
			return $this->enviarEmail($destinatario_admin, $assunto? $assunto : 'Contato - '. $this->s_name, $mensagem, $attachment);
		}else{
			if($TrabalheConosco == true && $this->email_trabalhe_conosco)
			{
				return $this->enviarEmail($this->email_trabalhe_conosco, $assunto? $assunto : 'Contato - '. $this->s_name, $mensagem, $attachment);
			}
			else
			{
				return $this->enviarEmail($this->emails_admin, $assunto? $assunto : 'Contato - '. $this->s_name, $mensagem, $attachment);
			}
		}
	}

	function comporMensagem($mensagem, $assunto = null, $destinatario = null, $attachment = null, $template = true, $mensagem_padrao = false, $destinatario_admin = null){
		if(!$mensagem || !$assunto) return;

		if(!$destinatario)
		{
			
			if(isset($mensagem['email_trabalhe_conosco']) && $mensagem['email_trabalhe_conosco'] == true)
			{
				$TrabalheConosco = true;
			}
			else
			{
				$TrabalheConosco = false;
			}

			$mensagem = $this->msgforAdmin($mensagem);
		}
		elseif($template){
			if(is_array($mensagem)){
				$mensagem = $this->setTemplate($mensagem['tipo_email'],$mensagem_padrao, $mensagem['params']);
			}else{
				$mensagem = $this->setTemplate($mensagem,$mensagem_padrao);
			}
		}
		
		return ($destinatario)? $this->enviarEmail($destinatario, $assunto, $mensagem, $attachment) : $this->enviarEmailAdmin($mensagem, $assunto, $attachment,$TrabalheConosco,$destinatario_admin);
		
	}


	function setTemplate($mensagem,$mensagem_padrao = false, $params = null){

		if($mensagem_padrao){
			$config_mensagem = $this->CI->model->db->query('SELECT '.$mensagem.' as msg FROM '.$this->prefixo_db.'contato_email_mensagem WHERE id = 1')->row('msg');
	
			$this->CI->load->library('Imgno_util', '', 'ImgnoUtil');	
			$strings_replace = array();
			if(!empty($params['nm'])) $strings_replace['Nome'] = $params['nm'];
			if(!empty($params['lista_produtos'])) $strings_replace['Lista-Produtos'] = $params['lista_produtos'];
			$mensagem = $this->CI->ImgnoUtil->replace_tags($config_mensagem, $strings_replace);

			// $mensagem = $config_mensagem;
		}

		$html = '<div style="background:#f9f9f9;">';
		$html .= '	<div style="margin:auto; padding:30px 0px; max-width:680px;min-width:320px;width:100% ">';
		$html .= '		<div style="text-align:right; margin-bottom:5px;"></div>';
		$html .= '		<div style="background:#FFF; border-top:3px solid '.$this->config->email_cor_borda.'; border-left:1px solid #dddddd; border-right:1px solid #dddddd; background-clip:padding-box;border-radius:0 0 3px 3px;color:#525252;font-family:\'Helvetica Neue\',Arial,sans-serif;font-size:15px;line-height:22px;overflow:hidden;">';
		// $html .= '			<div style="background:'.$this->config->email_cor_fundo.'; text-align:center; padding:20px 0px;"><img style="max-height: 115px;max-width: 230px;" src="'.base_url().'arquivos/imagens/contato/'.$this->config->logomarca.'"></div>';
		$html .= '			<div style="padding:20px 20px 20px">'.$mensagem.'</div>';
		$html .= '		</div>';
		$html .= '		<img style="max-width:100%;width:100%" src="'.base_url().'arquivos/imagens/contato/'.'baixo-email.png">';
		$html .= '		<table style="border-collapse:collapse;color:#545454;font-family:\'Helvetica Neue\',Arial,sans-serif;font-size:13px;line-height:20px;margin:-10px auto 0px;max-width:100%;width:100%;" cellspacing="0" cellpadding="0" border="0" width="100%">';
		$html .= '			<tbody>';
		$html .= '				<tr>';



		$html .= '					<td style="color:#999999;font-size:11px;vertical-align: top;padding-right: 10px;" width="25%">';
		$html .=                   		'<a href="'.base_url().'"><img style="max-height: 80px;max-width: 120px;" src="'.base_url().'arquivos/imagens/contato/'.$this->config->logomarca.'"></a>';
		$html .= '					</td>';
		$html .= '					<td width="75%" style="text-align: end;">';
		$stl = 'max-width:20px; margin-top:5px; margin-right:2px;';
		if($this->config->redes_facebook) $html .= '<a href="'.$this->config->redes_facebook.'"><img style="'.$stl.' margin-left:20px;" src="'.base_url().'arquivos/imagens/contato/'.'facebook.png"></a> ';
		if($this->config->redes_instagram) $html .= '<a href="'.$this->config->redes_instagram.'"><img style="'.$stl.'" src="'.base_url().'arquivos/imagens/contato/'.'instagram.png"></a> ';
		if($this->config->redes_twitter) $html .= '<a href="'.$this->config->redes_twitter.'"><img style="'.$stl.'" src="'.base_url().'arquivos/imagens/contato/'.'twitter.png"></a> ';
		if($this->config->redes_youtube) $html .= '<a href="'.$this->config->redes_youtube.'"><img style="'.$stl.'" src="'.base_url().'arquivos/imagens/contato/'.'youtube.png"></a> ';
		if($this->config->redes_linkedin) $html .= '<a href="'.$this->config->redes_linkedin.'"><img style="'.$stl.'" src="'.base_url().'arquivos/imagens/contato/'.'linkedin.png"></a> ';
		if($this->config->redes_pinterest) $html .= '<a href="'.$this->config->redes_pinterest.'"><img style="'.$stl.'" src="'.base_url().'arquivos/imagens/contato/'.'pinterest.png"></a>';
		if($this->config->redes_tiktok) $html .= '<a href="'.$this->config->redes_tiktok.'"><img style="'.$stl.'" src="'.base_url().'arquivos/imagens/contato/'.'tiktok.png"></a>';
		$html .= '					</td>';
		
		
		$html .= '				</tr>';
		$html .= '			</tbody>';
		$html .= '		</table>';
		$html .= '	</div>';
		$html .= '</div>';

		return $html;
	}

	function enviarEmailRCompraConfirmadaAsaas($id_cobranca){
        $pedido = $this->CI->model->db->query('SELECT * FROM '.$this->prefixo_db.'financeiro_pedido WHERE id_asaas_cobranca = "'.$id_cobranca.'" ')->row();
        if($pedido){
            $dados_cliente = $this->CI->model->db->query('SELECT * FROM '.$this->prefixo_db.'clientes WHERE id = "'.$pedido->id_cliente.'" ')->row();
            $pedido_produtos = $this->CI->model->db->query('SELECT * FROM '.$this->prefixo_db.'financeiro_pedido_produto WHERE id_pedido = "'.$pedido->id.'" ')->result();
            
            if($pedido_produtos){
                $assunto = 'Confirmação de compra';
                $mensagem = '';
				// $mensagem .= '<p>Olá, '.$dados_cliente->nome.'.</p>';
				
				$mensagem .= '<table width="100%" style="border-collapse: collapse;">';
					$mensagem .= '<tbody>';
						foreach($pedido_produtos as $pedido_produto){
							$mensagem .= '<tr style="border-bottom: 1px dashed #000;">';
								$mensagem .= '<td style="padding: 10px 0;">';
									$mensagem .= $pedido_produto->nome;
								$mensagem .= '</td>';

								$mensagem .= '<td width="25%" style="padding: 10px 0;">';
									$mensagem .= 'Qtd '.$pedido_produto->quantidade;
								$mensagem .= '</td>';

								$mensagem .= '<td width="25%" style="padding: 10px 0;">';
									$mensagem .= 'R$ '.number_format($pedido_produto->valor,2,",",".");
								$mensagem .= '</td>';

							$mensagem .= '</tr>';
						}
					$mensagem .= '</tbody>';
				$mensagem .= '</table>';

				$mensagem .= '<table width="" style="border-collapse: collapse; margin: 25px auto;">';
					$mensagem .= '<tbody>';
						$mensagem .= '<tr>'; 
							$mensagem .= '<td width="50%" style="padding: 0 0;">';
								$mensagem .= '<b>Subtotal: </b>';
							$mensagem .= '</td>';
							$mensagem .= '<td width="50%" style="padding: 0 0; text-align: end;">';
								$mensagem .= 'R$ '.number_format($pedido->total,2,",",".");
							$mensagem .= '</td>';
						$mensagem .= '</tr>';

						if($pedido->total - $pedido->valor_total_pagar > 0){
							$mensagem .= '<tr>'; 
								$mensagem .= '<td width="50%" style="padding: 0 0;">';
									$mensagem .= '<b>Desconto: </b>';
								$mensagem .= '</td>';
								$mensagem .= '<td width="50%" style="padding: 0 0; text-align: end;">';
									$mensagem .= 'R$ '.number_format($pedido->total - $pedido->valor_total_pagar,2,",",".");
								$mensagem .= '</td>';
							$mensagem .= '</tr>';
						}

						$mensagem .= '<tr style="font-size: 20px;">'; 
							$mensagem .= '<td width="50%" style="padding: 10px 0;">';
								$mensagem .= '<b>Total: </b>';
							$mensagem .= '</td>';
							$mensagem .= '<td width="50%" style="padding: 10px 0; text-align: end;">';
								$mensagem .= '<b>R$ '.number_format($pedido->valor_total_pagar,2,",",".").'</b>';
							$mensagem .= '</td>';
						$mensagem .= '</tr>';

					$mensagem .= '</tbody>';
				$mensagem .= '</table>';


				$mensagem = $this->setTemplate('mensagem_confirmacao_compra', true, array('nm'=>$dados_cliente->nome, 'lista_produtos'=>$mensagem));
				$this->enviarEmail($dados_cliente->email, $assunto, $mensagem);
            }
        }

        return;  
    }

	function enviarEmailAssinaturaConfirmadaAsaas($id_cobranca){
        $pedido = $this->CI->model->db->query('SELECT * FROM '.$this->prefixo_db.'financeiro_assinatura WHERE id_asaas_assinatura = "'.$id_cobranca.'" ')->row();
        if($pedido){
            $dados_cliente = $this->CI->model->db->query('SELECT * FROM '.$this->prefixo_db.'clientes WHERE id = "'.$pedido->id_cliente.'" ')->row();
            
            $assunto = 'Confirmação de assinatura';
            $mensagem = '';
			// $mensagem .= '<p>Olá, '.$dados_cliente->nome.'.</p>';
				
			$mensagem .= '<table width="100%" style="border-collapse: collapse;">';
				$mensagem .= '<tbody>';

						$mensagem .= '<tr style="border-bottom: 1px dashed #000;">';
							$mensagem .= '<td style="padding: 10px 0;">';
								$mensagem .= $pedido->nome;
							$mensagem .= '</td>';

							$mensagem .= '<td width="25%" style="padding: 10px 0;">';
								$mensagem .= 'Qtd '.$pedido->quantidade;
							$mensagem .= '</td>';

							$mensagem .= '<td width="25%" style="padding: 10px 0;">';
								$mensagem .= 'R$ '.number_format($pedido->valor,2,",",".");
							$mensagem .= '</td>';

						$mensagem .= '</tr>';
					
				$mensagem .= '</tbody>';
			$mensagem .= '</table>'; 

			$mensagem = $this->setTemplate('mensagem_confirmacao_compra', true, array('nm'=>$dados_cliente->nome, 'lista_produtos'=>$mensagem));
			$this->enviarEmail($dados_cliente->email, $assunto, $mensagem);            
        }

        return; 
    }

	function enviarEmailRecuperacao($token){

        $dados_token = $this->CI->model->db->query('SELECT * FROM '.$this->prefixo_db.'clientes_recuperar_senha WHERE token = "'.$token.'" ')->row();
        $dados_cliente = $this->CI->model->db->query('SELECT * FROM '.$this->prefixo_db.'clientes WHERE id = "'.$dados_token->id_cliente.'" ')->row();

        $assunto = 'Recuperação de senha';
        $mensagem  = '<p>Olá, '.$dados_cliente->nome.'.</p>';
        $mensagem .= '<p>Recebemos sua solicitação de recuperação de senha, <a href="'.base_url().'checkout-senha/'.$token.'.html" target="_blank">clique aqui e modifique sua senha</a>.</p>';
        $mensagem  .= '<p>O acesso pode ser usado 1 única vez e será válido somente por 24 horas.</p>';

		$mensagem = $this->setTemplate($mensagem);
		
		$this->enviarEmail($dados_cliente->email, $assunto, $mensagem);            
    }

	function msgforAdmin($params){

		$mensagem = '<p><b>Informações do contato: </b></p>';

		if (isset($params['nm']) && $params['nm']) 
		{	$mensagem .='<spam><b>Nome: </b>'. $params['nm'] .'</spam><br/>';}

		if (isset($params['vaga']) && $params['vaga']) 
		{	$mensagem .='<spam><b>Vaga: </b>'. $params['vaga'] .'</spam><br/>';}

		if (isset($params['empresa']) && $params['empresa']) 
		{	$mensagem .='<spam><b>Empresa: </b>'. $params['empresa'] .'</spam><br/>';}

		if (isset($params['empresa_atividade']) && $params['empresa_atividade']) 
		{	$mensagem .='<spam><b>Atividade da Empresa: </b>'. $params['empresa_atividade'] .'</spam><br/>';}

		if (isset($params['cargo']) && $params['cargo']) 
		{	$mensagem .='<spam><b>Cargo: </b>'. $params['cargo'] .'</spam><br/>';}

		if (isset($params['em']) && $params['em']) 
		{	$mensagem .='<spam><b>Email: </b>'. $params['em'] .'</spam><br/>';}

		if (isset($params['t1']) && $params['t1']) 
		{	$mensagem .='<spam><b>Telefone: </b>'.(!empty($params['dialCode'])?'+ '.$params['dialCode'].' ':'').$params['t1'].'</spam><br/>';}
		if (isset($params['t2']) && $params['t2']) 
		{	$mensagem .='<spam><b>Celular: </b>'.(!empty($params['dialCode2'])?'+ '.$params['dialCode2'].' ':'').$params['t2'].'</spam><br/>';}

		if (isset($params['abe']) && $params['abe']) 
		{	$mensagem .='<spam><b>Aberto: </b>'. $params['abe'] .'</spam><br/>';}

		if (isset($params['nome_estado_cidade_lp']) && $params['nome_estado_cidade_lp']) 
		{	$mensagem .='<spam><b>Cidade: </b>'. $params['nome_estado_cidade_lp'] .'</spam><br/>';}

		if (isset($params['nome_estado_lp']) && $params['nome_estado_lp']) 
		{	$mensagem .='<spam><b>Estado: </b>'. $params['nome_estado_lp'] .'</spam><br/>';}

		if (isset($params['nome_configuracao_lp']) && $params['nome_configuracao_lp']) 
		{	$mensagem .='<spam><b>Produto/Serviço: </b>'. $params['nome_configuracao_lp'] .'</spam><br/>';}

		if (isset($params['msg']) && $params['msg']) 
		{	$mensagem .='<spam><b>Mensagem:</b><br><div style="padding:0 10px;">'.$params['msg'].'</spam><br/>';}

		if (isset($params['attachment']) && $params['attachment'])
		{	$mensagem .='<spam><b>(Arquivo em anexo)</spam><br/>';}
				
		$html = '<div style="background:#f9f9f9;">';
		$html .= '	<div style="margin:auto; padding:30px 0px; max-width:680px;min-width:320px;width:100% ">';
		$html .= '		<div style="text-align:right; margin-bottom:5px;"></div>';
		$html .= '		<div style="background:#FFF; border-top:3px solid '.$this->config->email_cor_borda.'; border-left:1px solid #dddddd; border-right:1px solid #dddddd; background-clip:padding-box;border-radius:0 0 3px 3px;color:#525252;font-family:\'Helvetica Neue\',Arial,sans-serif;font-size:15px;line-height:22px;overflow:hidden;">';
		$html .= '			<div style="background:'.$this->config->email_cor_fundo.'; text-align:center; padding:20px 0px;"><img style="max-height: 115px;max-width: 230px;" src="'.base_url().'arquivos/imagens/contato/'.$this->config->logomarca.'"></div>';
		$html .= '			<div style="padding:20px 20px 20px">'.$mensagem.'</div>';
		$html .= '		</div>';
		if(isset($params['attachment'])  && $params['attachment'])
		{
			$html .= '<img style="max-width:100%;width:100%" src="'.base_url().'arquivos/imagens/contato/'.'baixo-email.png">';
		}
		$html .= '		<table style="border-collapse:collapse;color:#545454;font-family:\'Helvetica Neue\',Arial,sans-serif;font-size:13px;line-height:20px;margin:-10px auto 0px;max-width:100%;width:100%;" cellspacing="0" cellpadding="0" border="0" width="100%">';
		$html .= '		</table>';
		$html .= '	</div>';
		$html .= '</div>';

		return $html;
	}
}
?>