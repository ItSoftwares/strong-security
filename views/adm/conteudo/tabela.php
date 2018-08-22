<!-- <section class="painel box-window" id="tabela"> -->
<?
$nome = null;
$filtro = 0;
$segmento = 0;
$pagina = 1;

if (!empty($_GET)) {
	if (array_key_exists('nome', $_GET)) $nome = $_GET['nome'];
	if (array_key_exists('filtro', $_GET)) $filtro = $_GET['filtro'];
	if (array_key_exists('segmento', $_GET)) $segmento = $_GET['segmento'];
	if (array_key_exists('pagina', $_GET)) $pagina = $_GET['pagina'];
} 

if (!isset($teste)) require_once('../../../php/database/conexao.php');

$query = 'where ';
$query2 = '';
if (array_key_exists('nome', $_GET)) $query .= "e.nome LIKE '%{$_GET['nome']}%'";
if (array_key_exists('filtro', $_GET)) {
	if ($query!='where ' and $filtro!=0) $query .= ' and ';
	if ($filtro==1) $query .= 'e.id_voucher IS NOT NULL';
	else if ($filtro==2) $query .= 'e.id_voucher IS NULL';
}
if (array_key_exists('segmento', $_GET)) {
	if ($query!='where ') $query .= ' and ';
	$query .= 'e.id_segmento = '.$_GET['segmento'];
}
if (array_key_exists('pagina', $_GET)) {
	$query2 = ' limit '.(($_GET['pagina']-1)*10).', 10';
}

if ($query=='where ') $query='';

$empresas = [];
$empresas = DBselect('empresa e LEFT JOIN solicitacao_voucher s ON e.id_voucher = s.id_solicitacao_voucher', $query.' order by e.nome ASC'.$query2, 'e.*, (select COUNT(id_relatorio) from relatorio where id_empresa = e.id_empresa) relatorios, s.estado, s.voucher');
$relatorios = DBselect('empresa e INNER JOIN relatorio r ON e.id_empresa = r.id_empresa', $query.' order by e.nome ASC'.$query2, 'r.*, (SELECT titulo FROM questionario where id_questionario = r.id_questionario) titulo');
$qtd = DBselect('empresa e', $query, 'COUNT(e.id_empresa) as qtd')[0]['qtd'];

?>
<table class="">
	<tr>
		<!-- <th>ID</th> -->
		<th>Nome</th>
		<th class="centro">Relatórios</th>
		<th class="centro">Voucher</th>
		<th class="centro">Ações</th>
	</tr>
	<?
	$estados = ['Solicitado', 'Negado', 'Autorizado', 'Não possui'];
	$classes = ['solicitado', 'negado', 'autorizado', 'nao-possui'];
	foreach ($empresas as $e) { 
		$estado = $e['estado'];
		$estado = $estado==null?3:$estado;
	?>
	<tr data-id='<? echo $e['id_empresa']; ?>'>
		<!-- <td><? echo $i; ?></td> -->
		<td><? echo $e['nome']; ?></td>
		<td class="centro"><? echo $e['relatorios']; ?></td>
		<td class="centro"><span class="voucher <? echo $classes[$estado]; ?>"><? echo $estados[$estado]; ?></span></td>
		<td class="centro">
			<? if ($estado==0) { ?>
			<button class="botao icon recusar-voucher vermelho" title="Negar Voucher" onclick="atualizarVoucher(1, <? echo $e['id_empresa'] ?>)"><i class="fa fa-lock"></i></button>
			<button class="botao icon liberar-voucher verde" title="Liberar Voucher" onclick="atualizarVoucher(2, <? echo $e['id_empresa'] ?>)"><i class="fa fa-lock-open"></i></button>
			<? } ?>
			<button class="botao icon ver"><i class="fa fa-eye"></i></button>
		</td>
	</tr>
	<?
	}
	?>
</table>

<hr class="linha">

<div id="paginas">
	<ul class="transition-filhos">
		<? 
		$paginas = 9;
		$inicio = $pagina-4>0?$pagina-4:1;
		$paginas -= $pagina-$inicio;
		$fim = $pagina+4<$qtd/10?$pagina+$paginas:ceil($qtd/10);
		// echo $fim;
		for ($i=$inicio; $i <= $fim; $i++) { 
		?>
		<li data-pagina="<? echo $i ?>" class="<? echo $i==$pagina?'selecionado':''; ?>" ><a><? echo $i; ?></a></li>
		<? 	
		} ?>
	</ul>
</div>

<script type="text/javascript">
	empresas = <? echo json_encode($empresas); ?>;
	relatorios = <? echo json_encode($relatorios); ?>;
	qtd = <? echo $qtd; ?>;
	nome = '<? echo $nome; ?>';
	filtro = <? echo $filtro; ?>;
	segmento = <? echo $segmento; ?>;
	pagina = <? echo $pagina; ?>;
</script>
<!-- </section> -->