<?
require "../database/conexao.php";
require "../classes/empresa.class.php";
require "../util/listarArquivos.php";
require "../vendor/autoload.php";

if (isset($_POST['funcao'])) {
	if (!isset($_SESSION)) session_start();
	$dados = $_POST;
	$arquivos = $_FILES;
	$funcao = $dados['funcao']; unset($dados['funcao']);
	$usuario_sessao = isset($_SESSION['empresa'])?unserialize($_SESSION['empresa']):0;
	
	if ($funcao=="cadastro") {
		$empresa = new Empresa($dados);
		$result = $empresa->cadastrar();
		
		echo json_encode($result);
		exit;
	} 
	else if ($funcao=="login") {
		$empresa = new Empresa($dados);
		$result = $empresa->login();
		
		echo json_encode($result);
		exit;
	}
	else if ($funcao=="atualizar") {
		$temp = unserialize($_SESSION['empresa']);

		foreach($dados as $key => $value) {
			if ($value==$temp->$key and $key!="id_empresa") {
				unset($dados[$key]);
			}
		}

		$empresa = new Empresa($dados);
		$result = $empresa->atualizar($arquivos);

		foreach($result['atualizado'] as $key => $value) {
			$temp->$key = $value;
		}
		
		$_SESSION['empresa'] = serialize($temp);
		
		echo json_encode($result);
		exit;
	}
	else if ($funcao=="solicitarVoucher") {
		$empresa = new Empresa($dados);
		$result = $empresa->solicitarVoucher();

		echo json_encode($result);
		exit;
	}
	else if ($funcao=="recuperarSenha") {
		$empresa = new Empresa($dados);
		$result = $empresa->recuperarSenha();
		
		echo json_encode($result);
		exit;
	}
} 
else {
	echo json_encode(array(
		'estado' => 2,
		'mensagem' => "Post inexistente"
	));
	exit;
}
?>