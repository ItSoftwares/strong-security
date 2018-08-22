<?
require "../database/conexao.php";
require "../classes/adm.class.php";
require "../util/listarArquivos.php";
require "../vendor/autoload.php";

if (isset($_POST['funcao'])) {
	if (!isset($_SESSION)) session_start();
	$dados = $_POST;
	$arquivos = $_FILES;
	$funcao = $dados['funcao']; unset($dados['funcao']);
	
	if ($funcao=="login") {
		$adm = new Adm($dados);
		$result = $adm->login();
		
		echo json_encode($result);
		exit;
	}
	else if ($funcao=="atualizar") {
		$eu = isset($dados['eu'])?true:false; unset($dados['eu']);
		$tipoadm = isset($dados['tipoadm'])?$dados['tipoadm']:'visitante'; unset($dados['tipoadm']);
		
		if ($eu) {
			$temp = unserialize($_SESSION['adm']);

			foreach($dados as $key => $value) {
				if ($value==$temp->$key and $key!="id") {
					unset($dados[$key]);
				}
			}

			$dados['foto_perfil'] = $temp->foto_perfil;
		}
		
		$adm = new adm($dados);
		$result = $adm->atualizar($arquivos, $tipoadm);

		if ($eu) {
			foreach($result['atualizado'] as $key => $value) {
				$temp->$key = $value;
			}
			
			$_SESSION['adm'] = serialize($temp);
		}
		
		echo json_encode($result);
		exit;
	}
	if ($funcao=="atualizarVoucher") {
		$adm = new Adm($dados);
		$result = $adm->atualizarVoucher();
		
		echo json_encode($result);
		exit;
	}
	else if ($funcao=="emailModerador") {
		$adm = new adm($dados);
		$result = $adm->emailModerador();

		echo json_encode($result);
		exit;
	}
	else if ($funcao=="recuperarSenha") {
		$adm = new adm($dados);
		$result = $adm->recuperarSenha();
		
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