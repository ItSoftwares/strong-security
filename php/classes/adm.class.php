<?

class Adm {

	private $props = []; 
	public $valores_atualizar = array();
	
	public function cadastrar() {
		$this->gerarHash(8);
		$this->senha = md5($this->senha.$this->hash);

		DBcreate('adm', $this->toArray());
	
		return array('estado'=>1, 'mensagem'=>'Conta criada com sucesso!');
	}
	
	public function login() {
		if (filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
			$result = DBselect('adm');

			if (count($result)==0) {
				$result = $this->cadastrar();
			}

			$result = DBselect('adm', "where email = '{$this->email}' and id_adm = 1");
			
			// Verifica se usuário está cadastrado
			if (count($result) > 0) {
				$result = $result[0];
				// Verifica se senha está correta

				$senha = $this->senha;
				$senha = md5($this->senha.$result['hash']);

				if ($result['senha'] == $senha) {
					unset($_SESSION['adm']);

					// CAPTURAR TODAS AS INFORMAÇÕES DO ADM
					$dados = $result;
					$this->fromArray($dados);
					$this->valores_atualizar = array();
					
					// TEMPO PARA EXPIRAR SESSÃO
					$_SESSION['expire'] = time();

					// IDENTIFICAR SESSÃO
					$_SESSION['donoSessao']=md5('sat'.$_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']);
					// session_name($_SESSION['donoSessao']);
					
					// RETORNO
					$_SESSION['adm'] = serialize($this);
					
					return array('estado'=>1, 'mensagem'=> "Redirecionando...");
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
	
	public function atualizar($arquivos = null, $tipoUsuario) {
		if ($this->estaDeclarado('email')) {
			$email = DBselect($tipoUsuario, "where email='{$this->email}' and id<>{$this->id}");
			
			if (count($email)>0) {
				return array('estado'=>2, 'mensagem'=>"Já existe um usuário com esse Email!");
			}
		}
		
		if ($this->senha=="") {
			$this->unsetAtributo('senha');
		}
		else if ($tipoUsuario=='adm') {
			$this->hash = time();
			$this->senha = md5($this->senha.$this->hash);
		}
		
		if ($arquivos!=null and is_uploaded_file($arquivos['imagem-perfil']['tmp_name'])) {
			$this->foto_perfil = $this->mudarFoto($arquivos['imagem-perfil'], $this->larguraImagem, $this->alturaImagem);
			$this->unsetAtributo('alturaImagem');
			$this->unsetAtributo('larguraImagem');
		}
		
		$temp = $this->id;
		DBupdate($tipoUsuario, $this->valores_atualizar, "where id={$temp}");
		
		$this->valores_atualizar = array();
		return array('estado'=>1, 'mensagem'=>"Informações atualizadas com sucesso!", 'atualizado' => $this->toArray());
	}

	public function atualizarVoucher() {
		DBupdate('solicitacao_voucher', [
			'estado' => $this->estado
		], 'where id_empresa = '.$this->id_empresa);

		return array('estado'=>1, 'mensagem'=>"Solicitação respondida com sucesso!");
	}
	
	public function mensagem($texto_mensagem, $titulo = "Noivas Criciuma") {

		// echo realpath(dirname(__DIR__) . '/..')."/html/emailGeral.html"; exit;
		$mensagem = file_get_contents(realpath(dirname(__DIR__) . '/..')."/html/emailGeral.html");
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

		$mail->addAddress($this->email, $this->nome);

		$mail->SMTPDebug = 0;                            // Enable verbose debug output

		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = 'br712.hostgator.com.br';  // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = 'contato@portal.feiradanoivacriciuma.com.br';                 // SMTP username
		$mail->Password = 'noivas123';                           // SMTP password
		$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
		$mail->Port = 587;                                  // TCP port to connect to

		$mail->setFrom('contato@portal.feiradanoivacriciuma.com.br', 'Noivas Criciuma');

		$mail->DEBUG = 0;
		$mail->Subject = $titulo.' - Noivas Criciuma';
		$mail->isHTML(true);
		$mail->Body = $mensagem;
		$mail->CharSet = 'UTF-8';

		if (!$mail->send()) {
			// erro
			// echo $mail->ErrorInfo;
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
	    $this->hash = implode($pass); //turn the array into a string
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