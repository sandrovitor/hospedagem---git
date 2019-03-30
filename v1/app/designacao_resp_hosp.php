<?php
/*
 * ########################################################################
 * 
 * SCRIPT PARA ALIMENTAR TABELA DE DESIGNAÇÕES DE 'CIDADES COM HOSPEDAGEM'
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
	
	switch($_POST['funcao']) {
		case 'SalvaResponsavel':
			SalvaResponsavel_1($_POST['id'], $_POST['resp_id']);
			break;
	}
}

include('conecta.php');


/* AJUDAS */
$funcao1=<<<DADOS
Pessoa responsável em providenciar as acomodações, atender os pedidos de hospedagem e sanar dúvidas sobre hospedagem da sua cidade designada.

<br><br><small>Clique em qualquer lugar para fechar</small>
DADOS;
$funcao1_titulo=<<<DADOS
<strong>Responsável: O que é?</strong>
DADOS;
/* FIM AJUDAS */
?>

					<table class="table table-condensed table-bordered">
						<thead>
							<tr>
								<th>Cód.</th>
								<th>Cidade</th>
								<th>Responsável
								<a href="javascript:void(0)"  data-toggle="popover" data-trigger="focus" title="<?php echo $funcao1_titulo;?>" data-content="<?php echo $funcao1;?>" data-placement="right"><span class="glyphicon glyphicon-question-sign"></span></a>
								</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							// LISTA DE USUÁRIOS
							$select = '';
							$abc = $pdo->query('SELECT id, nome, sobrenome, nivel FROM login WHERE nivel >= 10 ORDER BY nivel ASC, nome ASC, sobrenome ASC');
							if($abc->rowCount() > 0) {
								$select .= '<option value="0">Escolha:</option>';
								$nivel = '';
								while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
									if($nivel == '' || $nivel <> $reg->nivel) {
										if($nivel <> '') {
											$select .= '</optgroup>';;
										}
											
										switch($reg->nivel) {
											case 1:
												$select .= '<optgroup label="Solicitante de Hospedagem">';
												break;
													
											case 10:
												$select .= '<optgroup label="Responsável da Hospedagem na Cidade">';
												break;
													
											case 20:
												$select .= '<optgroup label="Administrador">';
												break;
										}
										$nivel = $reg->nivel;
									}
									$select .= '<option value="'.$reg->id.'">'.$reg->nome.' '.$reg->sobrenome.'</option>';
								}
								$select .= '</optgroup>';
							} else {
								$select .= '<option value="0">NINGUÉM ENCONTRADO</option>';
							}
							//FIM LISTA DE USUÁRIOS
							
							
							
							$abc = $pdo->query('SELECT cidade.*, login.nome, login.sobrenome FROM cidade LEFT JOIN login ON cidade.resp_id = login.id WHERE cidade.hospedeiro = 1 ORDER BY cidade.cidade ASC');
							if($abc->rowCount() > 0) {
								while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
									//var_dump($reg);
									
									
									if($reg->resp_id == 0) {
										$responsavel = '<i>VAZIO</i>';
										$bg = 'bg-danger';
									} else {
										$responsavel = $reg->nome.' '.$reg->sobrenome;
										$bg = '';
									}
									echo <<<DADOS

							<tr class="$bg">
								<td>$reg->id</td>
								<td>$reg->cidade/$reg->estado</td>
								<td>
									<div id="div$reg->id">
										<span>$responsavel</span>
										<button type="button" class="btn btn-sm btn-success pull-right" onclick="formRespHosp($reg->id);"><span class="glyphicon glyphicon-edit"></span></button>
									</div>
									<form class="form-inline" id="form$reg->id" style="display:none">
										<div class="form-group">
											<select name="resp" class="form-control">
												$select
											</select>
										</div>
										<button type="button" class="btn btn-sm btn-default" onclick="formRespHospSalva($reg->id)">Salvar</button>
										<button type="button" class="btn btn-sm btn-default" onclick="formRespHospCancel($reg->id);">Cancelar</button>
									</form>
								</td>
							</tr>
DADOS;
								}
							}
							?>
						</tbody>
					</table>
