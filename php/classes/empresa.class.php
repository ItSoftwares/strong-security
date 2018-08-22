<?
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Empresa {

	private $props = []; 
	public $valores_atualizar = array();
	
	public function cadastrar() {
		$senha_digitada = $this->senha;
		
		// var_dump($this->toArray());
		$result = DBselect('empresa', "where email_login = '{$this->email_login}'", 'email_login');
		
		if (count($result)>0) {
			// $result = $result[0];
			return array('estado'=>2, 'mensagem'=>"Já existe alguma empresa cadastrada com esse email!");
		} else {
			$this->email_contato = $this->email_login;

			$this->hash = $this->gerarHash(10);
			$this->senha = md5($this->senha.$this->hash);

			$dados = array_filter($this->toArray());

			$retorno = "Conta criada com sucesso!";
			
			// ENVIAR EMAIL DE CONFIRMAÇÃO DE CADASTRO
			$mensagem = "<p>{$this->nome}</p>";
			$mensagem .= "<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quaerat laborum molestias officia sit rerum architecto atque, similique numquam modi minima facilis labore enim cupiditate suscipit quidem hic eos nam, ad.</p>";
			
			$result = $this->mensagem($mensagem, "Bem-vindo a nossa plataforma");

			if ($result==1) {
				DBcreate('empresa', $this->toArray());
			} else {
				return array('estado'=>2, 'mensagem'=>"Problemas ao enviar email, tente novamente mais tarde!");
			}
			
			return array('estado'=>1);
		}
	}
	
	public function login() {
		if (filter_var($this->email_login, FILTER_VALIDATE_EMAIL)) {
			$result = DBselect('empresa', "where email_login = '{$this->email_login}'");
			
			// Verifica se usuário está cadastrado
			if (count($result)>0) {
				$result = $result[0];
				// Verifica se senha está correta

				if ($result['senha'] == md5($this->senha.$result['hash'])) {
					unset($_SESSION['empresa']);

					// CAPTURAR TODAS AS INFORMAÇÕES DO empresa
					$dados = $result;
					$this->fromArray($dados);
					$this->valores_atualizar = array();
					DBupdate('empresa', ['id_empresa'=>$this->id_empresa], "where id_empresa = {$this->id_empresa}");

					if ($this->id_voucher!=null) {
						$voucher = DBselect('solicitacao_voucher', 'where id_solicitacao_voucher = '.$this->id_voucher)[0];
						$_SESSION['voucher'] = $voucher;
					}
					
					// TEMPO PARA EXPIRAR SESSÃO
					$_SESSION['expire'] = time();

					// IDENTIFICAR SESSÃO
					$_SESSION['donoSessao']=md5('sat'.$_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']);
					// session_name($_SESSION['donoSessao']);
					
					// RETORNO
					$_SESSION['empresa'] = serialize($this);
					
					return array('estado'=>1, 'mensagem'=> "Login bem sucedido, redirecionando...");
				} else {
					return array('estado'=>2, 'mensagem'=> "Senha incorreta para esta conta!");
				}
			}
			return array('estado'=>2, 'mensagem'=> "Credenciais inválidas!");
		} else {
			return array('estado'=>2, 'mensagem'=> "Digite um email válido");
		}
	}

	public function carregar($id, $tipo)	{
		$result = DBselect($tipo, "where id={$id}");

		if (count($result)>0) {
			$this->fromArray($result[0]);
			return true;
		}

		return false;
	}

	public function recuperarSenha() {
		$result = DBselect('adm', "where email = '{$this->email}'");
		$result2 = DBselect('visitante', "where email = '{$this->email}'");
		$result3 = DBselect('expositor', "where email = '{$this->email}'");

		if (count($result)==0 and count($result2)==0 and count($result3)==0) {
			return array('estado'=>2, 'mensagem'=>"Email não encontrado!");
		}

		if (count($result)>0) {
			$this->fromArray($result[0]);
			$tipo = "adm";
		}
		else if (count($result2)>0) {
			$this->fromArray($result2[0]);
			$tipo = "visitante";
		}
		else {
			$this->fromArray($result3[0]);
			$tipo = "expositor";
		}

		// var_dump($this->toArray()); exit;

		// link válido por 1 hora
		$agora = time();
		$url = "portal.noivacriciuma.com.br/recuperarSenha?hash={$this->senha}&time={$agora}&id={$this->id}&tipo={$tipo}";

		$texto = "<p>Você solicitou recuperação de senha. Para alterar sua senha acesse o link abaixo clicando no botão ou colando o LINK diretamente no navegador!</p>";
		$texto .= "<a href='{$url}' class='botao' target='_BLANK'>Clique Aqui</a>";
		$texto .= "<br>";
		$texto .= "<p>Não consegue acessar o link? Copie e cole-o no navegador: {$url}</p>";

		if ($this->mensagem($texto, "Recuperar senha")==1) {
			return array('estado'=>1, 'mensagem'=>"Email enviado com sucesso, verifique sua caixa de emails!");
		} else {
			return array('estado'=>2, 'mensagem'=>"Problemas ao enviar email, tente novamente mais tarde!");
		}
	}

	public function solicitarVoucher() {
		$voucher = [
			'id_empresa' => $this->id_empresa,
			'voucher' => $this->gerarHash(8)
		];
		$voucher['id_voucher'] = DBcreate('solicitacao_voucher', $voucher);
		$voucher['estado'] = 0;

		$this->id_voucher = $voucher['id_voucher'];
		$this->atualizar();
		$_SESSION['voucher'] = $voucher;

		return array('estado'=>1, 'mensagem'=>"Solicitação de Voucher #{$voucher['id_voucher']} feito com sucesso!");
	}
	
	public function atualizar($arquivos = null) {
		if ($this->estaDeclarado('email_login')) {
			$email_login = DBselect('empresa', "where email_login='{$this->email_login}' and id_empresa<>{$this->id_empresa}");
			
			if (count($email_login)>0) {
				return array('estado'=>2, 'mensagem'=>"Já existe um usuário com esse Email!");
			}
		}
		
		if ($this->estaDeclarado('senha') and strlen($this->senha)>0) {
			$this->gerarHash(10);
			$this->senha = md5($this->senha.$this->hash);
		} else $this->unsetAtributo('senha');
		
		$temp = $this->id_empresa;
		DBupdate('empresa', $this->valores_atualizar, "where id_empresa = {$temp}");
		
		$this->valores_atualizar = array();
		return array('estado'=>1, 'mensagem'=>"Informações atualizadas com sucesso!", 'atualizado' => $this->toArray());
	}
	
	public function mensagem($texto_mensagem, $titulo = "Strong Security") {

		// echo realpath(dirname(__DIR__) . '/..')."/html/emailGeral.html"; exit;
		$mensagem = file_get_contents(realpath(dirname(__DIR__) . '/..')."/assets/html/emailGeral.html");
		$mensagem = str_replace("--TITULO--", $titulo, $mensagem);
		$mensagem = str_replace("--MENSAGEM--", $texto_mensagem, $mensagem);

		$mail = new PHPMailer;

		$mail->SMTPOptions = array(
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true
			)
		);

		$mail->addAddress($this->email_login, $this->nome);

		$mail->SMTPDebug = 0;                            // Enable verbose debug output

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'gap@strongsecurity.com.br';                 // SMTP username
        $mail->Password = 'S1st3m4g4p!@';                           // SMTP password
        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465;                                    // TCP port to connect to

        $mail->setFrom('gap@strongsecurity.com.br', 'Strong Security GAP');

		$mail->DEBUG = 0;
		$mail->Subject = $titulo=='Strong Security'?$titulo:$titulo.' - Strong Security';
		$mail->isHTML(true);
		$mail->Body = $mensagem;
		$mail->CharSet = 'UTF-8';

		if (!$mail->send()) {
			// erro
			echo $mail->ErrorInfo;
			return 2;
		} else {
			$mail->ClearAllRecipients();
			
			return 1;
		}
	}

	public function gerarHash($tamanho) {
	    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
	    $pass = array(); //remember to declare $pass as an array
	    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
	    for ($i = 0; $i < $tamanho; $i++) {
	        $n = rand(0, $alphaLength);
	        $pass[] = $alphabet[$n];
	    }
	    return implode($pass); //turn the array into a string
	}

	public function toArray() {
		return $this->props;
	}
	
	public function fromArray($post) {
		foreach($post as $key => $value) {
			$this->props[$key] = $value;
			$this->valores_atualizar[$key] = $value;
		}
	}
	
	public function unsetAtributo($chave) {
		unset($this->props[$chave]);
		unset($this->valores_atualizar[$chave]);
	}
	
	public function estaDeclarado($chave) {
		if (isset($this->props[$chave])) return true;
		else return false;
	}
	
	// Gets e Sets
	public function __get($name) {
		if (isset($this->props[$name])) {
			return $this->props[$name];
		} else {
			return false;
		}
	}

	public function __set($name, $value) {
		$this->props[$name] = $value;
		$this->valores_atualizar[$name] = $value;
	}
	
	public function __wakeup(){
		foreach (get_object_vars($this) as $k => $v) {
			$this->{$k} = $v;
		}
	}
	
	public function __construct($dados=null) {
		if ($dados!=null) {
			$this->fromArray($dados);
		}
	}
}

?>