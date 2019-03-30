<?php
/*
 * ########################################################################
 *
 * SCRIPT PARA ALIMENTAR TABELA DE DESIGNAÇÕES DE 'CIDADES VISITANTES'
 *
 * ########################################################################
 */

// Verifica se há algum dado via POST
if(isset($_POST['funcao']) && $_POST['funcao'] != '') {

	function SalvaResponsavel_1($codigo, $responsavel) {
		/*
		 * TENTA CONECTAR AO BANCO
		 */
		if(file_exists('conecta.php')) {
			include('conecta.php');
		} else {
			include('app/conecta.php');
		}
		/*
		 * FIM DO INCLUDE DE CONEXAO AO BANCO DE DADOS
		 */


		$abc = $pdo->prepare('UPDATE cidade SET resp_id = :resp WHERE id = :id');
		$abc->bindValue(":resp", $responsavel, PDO::PARAM_INT);
		$abc->bindValue(":id", $codigo, PDO::PARAM_INT);

		$abc->execute();
	}
	
	function SalvaSolicitante_1($codigo, $solicitante) {
		/*
		 * TENTA CONECTAR AO BANCO
		 */
		if(file_exists('conecta.php')) {
			include('conecta.php');
		} else {
			include('app/conecta.php');
		}
		/*
		 * FIM DO INCLUDE DE CONEXAO AO BANCO DE DADOS
		 */
	
	
		$abc = $pdo->prepare('UPDATE cidade SET solicitante_id = :sol WHERE id = :id');
		$abc->bindValue(":sol", $solicitante, PDO::PARAM_INT);
		$abc->bindValue(":id", $codigo, PDO::PARAM_INT);
	
		$abc->execute();
	}

	switch($_POST['funcao']) {
		case 'SalvaResponsavel':
			SalvaResponsavel_1($_POST['id'], $_POST['resp_id']);
			break;
			
		case 'SalvaSolicitante':
			SalvaSolicitante_1($_POST['id'], $_POST['sol_id']);
			break;
	}
}

include('conecta.php');


/* AJUDAS */
$funcao1=<<<DADOS
Pessoa responsável em preencher os pedidos de hospedagem para os que sairão da sua cidade designada.
Ele é responsável em comunicar à hospedagem quem irá, quantos virão, quem vai ficar com quem e etc...

<br><br><small>Clique em qualquer lugar para fechar</small>
DADOS;
$funcao1_titulo=<<<DADOS
<strong>Solicitante: O que é?</strong>
DADOS;

$funcao2=<<<DADOS
Pessoa responsável em providenciar as acomodações para esta cidade visitante.

<br><br><small>Clique em qualquer lugar para fechar</small>
DADOS;
$funcao2_titulo=<<<DADOS
<strong>Responsável: O que é?</strong>
DADOS;
/* FIM AJUDAS */
?>

					<table class="table table-condensed table-bordered">
						<thead>
							<tr>
								<th>Cód.</th>
								<th>Cidade</th>
								<th>Solicitante
								<a href="javascript:void(0)"  data-toggle="popover" data-trigger="focus" title="<?php echo $funcao1_titulo;?>" data-content="<?php echo $funcao1;?>" data-placement="right"><span class="glyphicon glyphicon-question-sign"></span></a>
								</th>
								<th>Responsável
								<a href="javascript:void(0)"  data-toggle="popover" data-trigger="focus" title="<?php echo $funcao2_titulo;?>" data-content="<?php echo $funcao2;?>" data-placement="bottom"><span class="glyphicon glyphicon-question-sign"></span></a>
								</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							// LISTA DE USUÁRIOS
							$select_usu = '';
							$abc = $pdo->query('SELECT id, nome, sobrenome, nivel FROM login WHERE nivel >= 10 ORDER BY nivel ASC, nome ASC, sobrenome ASC');
							if($abc->rowCount() > 0) {
								$select_usu .= '<option value="0">Escolha:</option>';
								$nivel = '';
								while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
									if($nivel == '' || $nivel <> $reg->nivel) {
										if($nivel <> '') {
											$select_usu .= '</optgroup>';;
										}
											
										switch($reg->nivel) {
											case 1: $select_usu .= '<optgroup label="Solicitante de Hospedagem">'; break;
											case 10: $select_usu .= '<optgroup label="Responsável da Hospedagem na Cidade">'; break;		
											case 20: $select_usu .= '<optgroup label="Administrador">'; break;
										}
										$nivel = $reg->nivel;
									}
									$select_usu .= '<option value="'.$reg->id.'">'.$reg->nome.' '.$reg->sobrenome.'</option>';
								}
								$select_usu .= '</optgroup>';
							} else {
								$select_usu .= '<option value="0">NINGUÉM ENCONTRADO</option>';
							}
							//FIM LISTA DE USUÁRIOS
							
							// LISTA DE SOLICITANTES
							$select_sol = '';
							$abc = $pdo->query('SELECT id, nome, sobrenome, nivel FROM login WHERE nivel = 1 ORDER BY nivel ASC, nome ASC, sobrenome ASC');
							if($abc->rowCount() > 0) {
								$select_sol .= '<option value="0">Escolha:</option>';
								$nivel = '';
								while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
									if($nivel == '' || $nivel <> $reg->nivel) {
										if($nivel <> '') {
											$select_sol .= '</optgroup>';;
										}
											
										switch($reg->nivel) {
											case 1:	$select_sol .= '<optgroup label="Solicitante de Hospedagem">';break;
											case 10: $select_sol .= '<optgroup label="Responsável da Hospedagem na Cidade">'; break;
											case 20: $select_sol .= '<optgroup label="Administrador">'; break;
										}
										$nivel = $reg->nivel;
									}
									$select_sol .= '<option value="'.$reg->id.'">'.$reg->nome.' '.$reg->sobrenome.'</option>';
								}
								$select_sol .= '</optgroup>';
							} else {
								$select_sol .= '<option value="0">NINGUÉM ENCONTRADO</option>';
							}
							//FIM LISTA DE SOLICITANTES
							
							
							
							$abc = $pdo->query('SELECT cidade.*, login.nome, login.sobrenome FROM cidade LEFT JOIN login ON cidade.solicitante_id = login.id WHERE cidade.hospedeiro = 0 ORDER BY cidade.cidade ASC');
							if($abc->rowCount() > 0) {
								while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
									//var_dump($reg);
									
									// Escreve Solicitante
									if($reg->solicitante_id == 0) {
										$solicitante = '<i>VAZIO</i>';
									} else {
										$solicitante = $reg->nome.' '.$reg->sobrenome;
									}
									
									
									// Escreve Responsável por Hospedar
									if($reg->resp_id == 0) {
										$responsavel = '<i>VAZIO</i>';
									} else {
										$def = $pdo->query('SELECT login.nome, login.sobrenome FROM login WHERE id = '.$reg->resp_id);
										$lin = $def->fetch(PDO::FETCH_OBJ);
										$responsavel = $lin->nome.' '.$lin->sobrenome;
									}
									
									if($reg->solicitante_id == 0 || $reg->resp_id == 0) {
										$bg = 'bg-danger';
									} else {
										$bg = '';
									}
									
									echo <<<DADOS

							<tr class="$bg">
								<td>$reg->id</td>
								<td>$reg->cidade/$reg->estado</td>
								<td>
									<div id="div_sol$reg->id">
										<span>$solicitante</span>
										<button type="button" class="btn btn-sm btn-success pull-right" onclick="formSolHosp('sol', $reg->id);"><span class="glyphicon glyphicon-edit"></span></button>
									</div>
									<form class="form-inline" id="form_sol$reg->id" style="display:none">
										<div class="form-group">
											<select name="resp" class="form-control">
												$select_sol
											</select>
										</div>
										<button type="button" class="btn btn-sm btn-default" onclick="formSolHospSalva('sol', $reg->id)">Salvar</button>
										<button type="button" class="btn btn-sm btn-default" onclick="formSolHospCancel('sol', $reg->id);">Cancelar</button>
									</form>
								</td>
								
								<td>
									<div id="div_resp$reg->id">
										<span>$responsavel</span>
										<button type="button" class="btn btn-sm btn-success pull-right" onclick="formSolHosp('resp', $reg->id);"><span class="glyphicon glyphicon-edit"></span></button>
									</div>
									<form class="form-inline" id="form_resp$reg->id" style="display:none">
										<div class="form-group">
											<select name="resp" class="form-control">
												$select_usu
											</select>
										</div>
										<button type="button" class="btn btn-sm btn-default" onclick="formSolHospSalva('resp', $reg->id)">Salvar</button>
										<button type="button" class="btn btn-sm btn-default" onclick="formSolHospCancel('resp', $reg->id);">Cancelar</button>
									</form>
								</td>
							</tr>
DADOS;
								}
							}
							?>
						</tbody>
					</table>


