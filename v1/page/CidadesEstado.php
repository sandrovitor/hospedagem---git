<?php 
if(isset($_POST['cidade']) && $_POST['cidade'] <> '') {
	// Verifica se cidade já existe no banco de dados
	$def = $pdo->prepare('SELECT * FROM cidade WHERE cidade LIKE :cidade AND estado = :estado');
	$def->bindValue(":cidade", $_POST['cidade'], PDO::PARAM_STR);
	$def->bindValue(":estado", $_POST['estado'], PDO::PARAM_STR);
	
	$def->execute();
	
	
	if($def->rowCount() == 0) {
		$abc = $pdo->prepare('INSERT INTO cidade (id, cidade, estado, hospedeiro, resp_id, solicitante_id) VALUES (NULL, :cidade, :estado, :hospedeiro, :resp, :solicitante)');
		$abc->bindValue(":cidade", addslashes($_POST['cidade']), PDO::PARAM_STR);
		$abc->bindValue(":estado", $_POST['estado'], PDO::PARAM_STR);
		$abc->bindValue(":hospedeiro", (int)$_POST['hospedeiro'], PDO::PARAM_INT);
		
		if((int)$_POST['hospedeiro'] == '0') { // Cidade visitante
			$abc->bindValue(":resp", "0", PDO::PARAM_INT);
			$abc->bindValue(":solicitante", (int)$_POST['solicitante'], PDO::PARAM_INT);
		} else { // Cidade hospedeira
			$abc->bindValue(":resp", (int)$_POST['resp'], PDO::PARAM_INT);
			$abc->bindValue(":solicitante", "0", PDO::PARAM_INT);
		}
		
		try {
			$abc->execute();
			echo <<<DADOS
			
	<div class="alert alert-success">
		<strong>Sucesso!</strong> Mais uma cidade cadastrada no banco de dados. Confira na lista de cidades.
	</div>
DADOS;
			
		} catch(Exception $e) {
			echo <<<DADOS
	
	<div class="alert alert-danger">
		<strong>Aconteceu um erro!</strong>
DADOS;
			echo $e->getMessage();
			echo <<<DADOS
	</div>
DADOS;
		}
	} else {
		// Existe uma cidade com o mesmo nome e pertencente ao mesmo estado, já cadastrada no banco de dados.
		echo <<<DADOS
		
	<div class="alert alert-warning">
		<strong>Uma coisa não está certa!</strong> Essa cidade já existe por aqui... Verifique na lista de cidades, antes de tentar de novo.
	</div>
DADOS;
		
	}
	
}

?>
<script>
function escolheResp() {
	$("#form1_hospedeiro").val();
	if($("#form1_hospedeiro").val() == 0) {
		$("#campo2").show();
		$("#campo1").hide();
	} else if($("#form1_hospedeiro").val() == 1) {
		$("#campo1").show();
		$("#campo2").hide();
	} else {
		$("#campo1").hide();
		$("#campo2").hide();
	}
}

function showConfirm(id) {
	$("[id|='confirm']").hide();
	$("#confirm-"+id).show();
	$("#button_confirm_exc").data("id", id);
}

function enviaConfirm(id) {
	$.post(funcPage, {
		funcao: 'adm_apagaCidade',
		id: id
	}, function(data){
		if(data == 'OK') {
			alert('Cidade excluída com sucesso. Clique em OK para atualizar...');
			location.href="admin.php?page=20";
		} else {
			alert(data);
		}
	}, 'html');
}
</script>
<?php 
$funcao_cidade =<<<DADOS
<strong>Hospedagem:</strong> Cidade responsável em acomodar quem vem de fora.<br>
<strong>Visitante:</strong> Cidade que precisará ser acomodada, pois vem de fora.

<br><br><small>Clique em qualquer lugar para fechar</small>
DADOS;

?>

<h4><strong><span class="glyphicon glyphicon-globe"></span> CIDADES/ESTADOS</strong></h4><hr>

<div class="row">
	<div class="col-xs-12 col-sm-6 col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading">
				<a data-toggle="collapse" data-target="#Panel1"><strong>Nova Cidade</strong></a>
			</div>
			<div id="Panel1" class="panel-collapse collapse in">
				<div class="panel-body">
					<form id="form1" method="post">
						<div class="form-group">
							<label for="form1_cidade">Cidade:
							<span class="glyphicon glyphicon-question-sign" style="cursor:pointer" data-toggle="tooltip" title="Nome da cidade"></span></label>
							<input type="text" id="form1_cidade" name="cidade" class="form-control">
						</div>
						<div class="form-group">
							<label for="form1_estado">Estado:</label>
							<select name="estado" id="form1_estado" class="form-control">
								<option value="AC">Acre</option>
								<option value="AL">Alagoas</option>
								<option value="AP">Amapá</option>
								<option value="AM">Amazonas</option>
								<option value="BA" selected>Bahia</option>
								<option value="CE">Ceará</option>
								<option value="DF">Distrito Federal</option>
								<option value="ES">Espírito Santo</option>
								<option value="GO">Goiás</option>
								<option value="MA">Maranhão</option>
								<option value="MT">Mato Grosso</option>
								<option value="MS">Mato Grosso do Sul</option>
								<option value="MG">Minas Gerais</option>
								<option value="PA">Pará</option>
								<option value="PB">Paraíba</option>
								<option value="PR">Paraná</option>
								<option value="PE">Pernambuco</option>
								<option value="PI">Piauí</option>
								<option value="RJ">Rio de Janeiro</option>
								<option value="RN">Rio Grande do Norte</option>
								<option value="RS">Rio Grande do Sul</option>
								<option value="RO">Rondônia</option>
								<option value="RR">Roraima</option>
								<option value="SC">Santa Catarina</option>
								<option value="SP">São Paulo</option>
								<option value="SE">Sergipe</option>
								<option value="TO">Tocantins</option>
							</select>
						</div>
						<div class="form-group">
							<label for="form1_hospedeiro">Função da cidade:
							<a href="javascript:void(0)"  data-toggle="popover" data-trigger="focus" title="Função da cidade" data-content="<?php echo $funcao_cidade;?>" data-placement="right"><span class="glyphicon glyphicon-question-sign"></span></a>
							</label>
							<select id="form1_hospedeiro" name="hospedeiro" class="form-control" onchange="escolheResp();">
								<option disabled="disabled" selected>Escolha:</option>
								<option value="1">Hospedagem</option>
								<option value="0">Visitante</option>
							</select>
						</div>
						<div class="form-group" id="campo1" style="display:none">
							<label for="form1_resp">Responsável pela cidade:</label>
							<select id="form1_resp" name="resp" class="form-control">
								<?php 
								$abc = $pdo->query('SELECT id, nome, sobrenome, nivel FROM login WHERE nivel >= 10 ORDER BY nivel ASC, nome ASC, sobrenome ASC');
								if($abc->rowCount() > 0) {
									echo '<option value="0">Escolha:</option>';
									$nivel = '';
									while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
										if($nivel == '' || $nivel <> $reg->nivel) {
											if($nivel <> '') {
												echo '</optgroup>';;
											}
											
											switch($reg->nivel) {
												case 1:
													echo '<optgroup label="Solicitante de Hospedagem">';
													break;
													
												case 10:
													echo '<optgroup label="Responsável da Hospedagem na Cidade">';
													break;
													
												case 20:
													echo '<optgroup label="Administrador">';
													break;
											}
											$nivel = $reg->nivel;
										}
										echo '<option value="'.$reg->id.'">'.$reg->nome.' '.$reg->sobrenome.'</option>';
									}
									echo '</optgroup>';
								} else {
									echo '<option value="0">NINGUÉM ENCONTRADO</option>';
								}
								?>
							</select>
						</div>
						<div class="form-group" id="campo2" style="display:none">
							<label for="form1_solicitante">Solicitante da Hospedagem:</label>
							<select id="form1_solicitante" name="solicitante" class="form-control">
								<?php 
								$abc = $pdo->query('SELECT id, nome, sobrenome, nivel FROM login WHERE nivel = 1 ORDER BY nivel ASC, nome ASC, sobrenome ASC');
								if($abc->rowCount() > 0) {
									echo '<option value="0">Escolha:</option>';
									$nivel = '';
									while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
										if($nivel == '' || $nivel <> $reg->nivel) {
											if($nivel <> '') {
												echo '</optgroup>';
											}
											
											switch($reg->nivel) {
												case 1:
													echo '<optgroup label="Solicitante de Hospedagem">';
													break;
													
												case 10:
													echo '<optgroup label="Responsável da Hospedagem na Cidade">';
													break;
													
												case 20:
													echo '<optgroup label="Administrador">';
													break;
											}
											$nivel = $reg->nivel;
										}
										echo '<option value="'.$reg->id.'">'.$reg->nome.' '.$reg->sobrenome.'</option>';
									}
									echo '</optgroup>';
								} else {
									echo '<option value="0">NINGUÉM ENCONTRADO</option>';
								}
								?>
							</select>
						</div>
						<div class="form-group">
							<button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-send"></span> <strong>Cadastrar</strong></button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xs-12 col-sm-6 col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading">
				<a data-toggle="collapse" data-target="#Panel2"><strong>Lista de Cidades</strong></a>
			</div>
			<div id="Panel2" class="panel-collapse collapse in" style="max-height: 400px; overflow: auto;">
				<div class="panel-body">
					<table class="table table-condensed table-hover">
						<thead>
							<tr>
								<th colspan="4" class="text-center"><h4><strong>CIDADES COM HOSPEDAGEM</strong></h4></th>
							</tr>
							<tr>
								<th>Cód.</th>
								<th>Cidade</th>
								<th>Responsável</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php 
							$abc = $pdo->query('SELECT cidade.*, login.nome, login.sobrenome FROM cidade LEFT JOIN login ON cidade.resp_id = login.id WHERE cidade.hospedeiro = 1');
							
							if($abc->rowCount() == 0) {
								echo '<tr><td colspan="4"><i>Nenhuma cidade no sistema</i></td></tr>';
							} else {
								while($lin = $abc->fetch(PDO::FETCH_OBJ)) {
									
									if($lin->resp_id == '0') {
										$responsavel = '<i>-</i>';
									} else {
										$responsavel = $lin->nome.' '.$lin->sobrenome;
									}
									echo <<<DADOS

							<tr>
								<td>$lin->id</td>
								<td>$lin->cidade/$lin->estado</td>
								<td>$responsavel</td>
								<td><button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#ModalConfirm" onclick="showConfirm($lin->id)"><span class="glyphicon glyphicon-trash"></span></button></td>
							</tr>
DADOS;
								}
							}
							?>
						</tbody>
					</table>
					
					<hr>
					
					<table class="table table-condensed table-hover">
						<thead>
							<tr>
								<th colspan="4" class="text-center"><h4><strong>CIDADES VISITANTES</strong></h4></th>
							</tr>
							<tr>
								<th>Cód.</th>
								<th>Cidade</th>
								<th>Solicitante de<br>Hospedagem</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php 
							$abc = $pdo->query('SELECT cidade.*, login.nome, login.sobrenome FROM cidade LEFT JOIN login ON cidade.solicitante_id = login.id WHERE hospedeiro = 0');
							
							if($abc->rowCount() == 0) {
								echo '<tr><td colspan="4"><i>Nenhuma cidade no sistema</i></td></tr>';
							} else {
								while($lin = $abc->fetch(PDO::FETCH_OBJ)) {
									
									if($lin->solicitante_id == '0') {
										$responsavel = '-';
									} else {
										$responsavel = $lin->nome.' '.$lin->sobrenome;
									}
									
									echo <<<DADOS

							<tr>
								<td>$lin->id</td>
								<td>$lin->cidade/$lin->estado</td>
								<td>$responsavel</td>
								<td><button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#ModalConfirm" onclick="showConfirm($lin->id)"><span class="glyphicon glyphicon-trash"></span></button></td>
							</tr>
DADOS;
								}
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xs-12 col-sm-6 col-md-4">
	
	</div>
</div>

<!-- Modal 1 -->
<div id="ModalConfirm" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Confirmar exclusão da cidade?</h4>
			</div>
			<div class="modal-body">
				<?php 
				// Escreve informações sobre as cidades no Modal
				// CIDADES COM HOSPEDAGEM
				$abc = $pdo->query('SELECT cidade.*, login.nome, login.sobrenome FROM cidade LEFT JOIN login ON cidade.resp_id = login.id WHERE cidade.hospedeiro = 1');
				
				if($abc->rowCount() > 0) {
					while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
						
						echo<<<DADOS

				<div id="confirm-$reg->id" style="display:none">
DADOS;
						
						$def = $pdo->query('SELECT DISTINCT fac.id, fac.cidade FROM fac WHERE cidade = '.$reg->id);
						if($def->rowCount() == 0) {
							echo <<<DADOS

					Esta cidade (<i>$reg->cidade/$reg->estado</i>) ainda não possui formulários de acomodação preenchidos.<br><br>
					<strong>Deseja prosseguir com a exclusão?</strong> 
DADOS;
						} else {
							$linhas = $def->rowCount();
							echo <<<DADOS
							
					Esta cidade (<i>$reg->cidade/$reg->estado</i>) possui <strong>$linhas</strong> formulário(s) de acomodação preenchido(s).
					<span class="bg-danger"><strong>A exclusão desta cidade pode ocasionar erros e instabilidades no sistema.</strong></span><br><br>
					<strong>Deseja prosseguir com a exclusão?</strong>
DADOS;

							unset($linhas);
							
						}
						
						
						
						echo<<<DADOS
						
				</div>
DADOS;
					}
				}
				// ############################################
				
				// CIDADES VISITANTES
				$abc = $pdo->query('SELECT cidade.*, login.nome, login.sobrenome FROM cidade LEFT JOIN login ON cidade.solicitante_id = login.id WHERE hospedeiro = 0');
				
				if($abc->rowCount() > 0) {
					if($abc->rowCount() > 0) {
						while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
							echo<<<DADOS
							
				<div id="confirm-$reg->id" style="display:none">
DADOS;
							
							$def = $pdo->query('SELECT DISTINCT peh.id, peh.congregacao_cidade_id FROM `peh` WHERE `congregacao_cidade_id` = '.$reg->id);
							if($def->rowCount() == 0) {
								echo <<<DADOS
								
					Esta cidade (<i>$reg->cidade/$reg->estado</i>) ainda não possui pedidos de hospedagem preenchidos.<br><br>
					<strong>Deseja prosseguir com a exclusão?</strong>
DADOS;
							} else {
								$linhas = $def->rowCount();
								echo <<<DADOS
								
					Esta cidade (<i>$reg->cidade/$reg->estado</i>) possui <strong>$linhas</strong> pedido(s) de hospedagem preenchido(s).
					<span class="bg-danger"><strong>A exclusão desta cidade pode ocasionar erros e instabilidades no sistema.</strong></span><br><br>
					<strong>Deseja prosseguir com a exclusão?</strong>
DADOS;
								
								unset($linhas);
								
							}
							
							
							
							echo<<<DADOS
							
				</div>
DADOS;
							
						}
					}
				}
				
				?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" data-id="0" id="button_confirm_exc" onclick="enviaConfirm($(this).data('id'))">Confirmar</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
			</div>
		</div>
	</div>
</div>
