<?
require "../database/conexao.php";
require "../classes/pesquisa.class.php";
require "../util/listarArquivos.php";
require "../vendor/autoload.php";

if (isset($_POST['funcao'])) {
	if (!isset($_SESSION)) session_start();
	$dados = $_POST;
	$arquivos = $_FILES;
	$funcao = $dados['funcao']; unset($dados['funcao']);
	
	if ($funcao=="cadastrar") {
		$pesquisa = new Pesquisa($dados);
		$result = $pesquisa->cadastrar();
		
		echo json_encode($result);
		exit;
	} 
	else if ($funcao=="excluir") {
		$pesquisa = new Pesquisa($dados);
		$result = $pesquisa->excluir();
		
		echo json_encode($result);
		exit;
	}
	else if ($funcao=="gerar") {
		$pesquisa = new Pesquisa($dados);
		$result = $pesquisa->gerar();
		
		echo json_encode($result);
		exit;
	}
	else if ($funcao=="gerarPdf") {
		$pesquisa = new Pesquisa($dados);
		// echo strlen($dados['conteudo']); exit;
		$result = $pesquisa->gerarPdf();
		
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
}
else {
	echo json_encode(array(
		'estado' => 2,
		'mensagem' => "Post inexistente"
	));
	exit;
}
?>