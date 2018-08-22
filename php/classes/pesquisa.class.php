<?
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Pesquisa {

	private $props = []; 
	public $valores_atualizar = array();
	
	public function cadastrar() {
		// var_dump($this->toArray()); return;

		$questionario = $this->questionario;
		$grupos = $this->grupos;
		$perguntas = $this->perguntas;

		$questionario['id_questionario'] = DBcreate('questionario', $questionario);

		foreach ($grupos as $key => $value) {
			$grupos[$key]['id_questionario'] = $questionario['id_questionario'];
		}
		foreach ($grupos as $key => $value) {
			$grupos[$key]['id_grupo_perguntas'] = DBcreate('grupo_perguntas', $value);
		}

		foreach ($perguntas as $key => $value) {
			$perguntas[$key]['id_grupo'] = $grupos[$value['id_grupo']]['id_grupo_perguntas'];
		}
		foreach ($perguntas as $key => $value) {
			$perguntas[$key]['id_pergunta'] = DBcreate('pergunta', $value);
		}

		$retorno = [
			'questionario' => $questionario,
			'grupos' => $grupos,
			'perguntas' => $perguntas
		];
	
		return array('estado'=>1, 'mensagem'=>'Questionario criado com sucesso!', 'retorno'=>$retorno);
	}

	public function excluir() {
		$id = $this->id_questionario;
		// EXCLUIR PERGUNTAS
		DBdelete('pergunta p INNER JOIN grupo_perguntas g ON p.id_grupo = g.id_grupo_perguntas', "where g.id_questionario = {$id}", 'p.*');
		// EXCLUIR GRUPOS
		DBdelete('grupo_perguntas', "where id_questionario = {$id}");
		// EXCLUIR QUESTIONARIO
		DBdelete('questionario', "where id_questionario = {$id}");
	
		return array('estado'=>1, 'mensagem'=>'Questionario excluido com sucesso!');
	}

	public function gerar() {
		$id = $this->id_questionario;
		$respostas = $this->respostas;
		$id_empresa = $this->id_empresa;

		$id_relatorio = DBcreate('relatorio', [
			'id_questionario' => $id,
			'id_empresa' => $id_empresa
		]);

		$temp = [];
		foreach ($respostas as $key => $value) {
			array_push($temp, [
				'id_pergunta' => $key,
				'id_relatorio' => $id_relatorio,
				'valor' => $value
			]);
		}
		DBcreateVarios('resposta', $temp);

		return array('estado'=>1, 'mensagem'=>'Questionario respondido com sucesso!<br> Gerando relatorio...', 'id'=> $id_relatorio);
	}

	public function gerarPdf() {
		$pdf = base64_decode($this->pdf);
		file_put_contents("../../server/relatorios/{$this->nome}.pdf", $pdf);

		$empresa = DBselect('empresa', 'where id_empresa = '.$this->id_empresa, 'email_login, nome');

		$this->email = $empresa[0]['email_login'];
		$this->nome = $empresa[0]['nome'];

		if ($this->mensagem('Relatório contendo minhas respostas', 'Strong Security', $pdf)==1) return array('estado'=>1);
		else return array('estado'=>2, 'mensagem'=>'Erro ao enviar E-mail');
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
	
	public function mensagem($texto_mensagem, $titulo = "Strong Security", $anexo = null) {

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

		$mail->addAddress($this->email, $this->nome);

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

		if ($anexo!=null) {
			$mail->addStringAttachment($anexo, 'Relatório.pdf');
		}

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