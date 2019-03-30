<?php 
include('../app/session.php');
include('../app/conecta.php');
if($_SESSION['nivel'] == 10) {
	echo 'ACESSO NEGADO!';
	exit();
}
?>
				<script>
					$(document).ready(function(){
						$("[name='nome']").change(function(){
							$("[name='oc1_nome']").val($("[name='nome']").val());
						});
						
						
					});
				</script>
				<form action="formulario.php" method="POST">
					<h3 class="text-center"><strong>PEDIDO ESPECIAL DE HOSPEDAGEM</strong></h3>
					
					<h4><strong>PUBLICADOR</strong></h4>
					<hr>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label for="nome">Nome:</label>
								<input type="text" name="nome" id="nome" class="form-control" autofocus>
								<span class="help-block">O mesmo que no campo ocupante 1 abaixo</span>
							</div>
							
							<div class="form-group">
								<label for="endereco">Endereço:</label>
								<input type="text" name="endereco" id="endereco" class="form-control">
							</div>
							
							<div class="form-group row">
								<div class="col-xs-6">
									<label for="cidade">Cidade:</label>
									<input type="text" name="cidade" id="cidade" class="form-control">
								</div>
								<div class="col-xs-3">
									<label for="estado">Estado:</label>
									<select name="estado" id="estado" class="form-control">
										<option value="AC">Acre</option>
										<option value="AL">Alagoas</option>
										<option value="AP">Amapá</option>
										<option value="AM">Amazonas</option>
										<option value="BA">Bahia</option>
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
								<div class="col-xs-3">
									<label for="pais">País:</label>
									<select disabled name="pais" id="pais" class="form-control">
										<option value="BRA">Brasil</option>
									</select>
								</div>
							</div>
							
							<div class="form-group row">
								<div class="col-xs-6">
									<label for="tel_res">Telefone Residencial:</label>
									<input type="number" name="tel_res" id="tel-res" class="form-control">
									<span class="help-block">Formato: (xx) xxxx-xxxx [Só números]</span>
								</div>
								<div class="col-xs-6">
									<label for="tel_cel">Telefone Celular:</label>
									<input type="number" name="tel_cel" id="tel_cel" class="form-control">
									<span class="help-block">Formato: (xx) x xxxx-xxxx [Só números]</span>
								</div>
							</div>
							
							<div class="form-group">
								<label for="email">E-mail:</label>
								<input type="email" name="email" id="email" class="form-control">
							</div>
							
							<div class="form-group row">
								<div class="col-xs-6">
									<label for="congregacao">Congregação:</label>
									<input type="text" name="congregacao" id="congregacao" class="form-control">
								</div>
								<div class="col-xs-6">
									<label for="congregacao_cidade_id">Cidade:</label>
									<select id="congregacao_cidade_id" name="congregacao_cidade_id" class="form-control">
									<?php 
									if($_SESSION['nivel'] == 20) { // Se for administrador, exibe todas as cidades
										$abc = $pdo->query('SELECT * FROM cidade WHERE hospedeiro = 0 ORDER BY cidade ASC');
									} else { // Só exibe as cidades que o usuário é solicitante
										$abc = $pdo->query('SELECT * FROM cidade WHERE hospedeiro = 0 AND solicitante_id = '.$_SESSION['id'].' ORDER BY cidade ASC');
									}
									if($abc->rowCount() > 0) {
										while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
											echo <<<DADOS

										<option value="$reg->id">$reg->cidade/$reg->estado</option>
DADOS;
										}
									}
									?>
									</select>
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="congresso_cidade">Cidade do congresso:</label>
								<input type="text" name="congresso_cidade" id="congresso_cidade" class="form-control">
							</div>
							
							<div class="form-group">
								<label for="check_in">Primeira noite que precisará do quarto:</label>
								<input type="date" name="check_in" id="check_in" class="form-control">
							</div>
							
							<div class="form-group">
								<label for="check_out">Última noite que precisará do quarto:</label>
								<input type="date" name="check_out" id="check_out" class="form-control">
							</div>
							
							<div class="form-group">
								<label for="tipo_hospedagem">Tipo de acomodação:</label>
								<div class="radio" id="tipo_hospedagem">
									<label><input type="radio" name="tipo_hospedagem" value="casa"> Casa particular</label>
									<label><input type="radio" name="tipo_hospedagem" value="hotel"> Hotel</label>
								</div>
							</div>
							
							<div class="form-group">
								<label for="pagamento">Quanto pode pagar por esse quarto, <span style="font-weight:bold; text-decoration: underline;">por noite (em reais)?</span></label>
								<input type="number" name="pagamento" id="pagamento" class="form-control">
								<span class="help-block">Veja a seção "Quartos de Hotel" no fim do formulário</span>
							</div>
							
							<div class="form-group">
								<label for="transporte">Terá transporte próprio enquanto estiver na cidade do congresso?</label>
								<div class="radio" id="transporte">
									<label><input type="radio" name="transporte" value="SIM"> Sim</label>
									<label><input type="radio" name="transporte" value="NÃO"> Não</label>
								</div>
							</div>
						</div>
					</div>
					
					<br>
					<h4><strong>OCUPANTES DO QUARTO <small>(É recomendado o máximo de 2 pessoas por casa.)</small></strong></h4>
					
					<table class="table table-responsive table-hover table-striped">
						<tbody>
							<tr><td>
								<h5><strong>Ocupante 1</strong></h5>
								<div class="form-group row">
									<div class="col-sm-3">
										<label for="oc1_nome">Nome:</label>
										<input type="text" name="oc1_nome" id="oc1_nome" class="form-control">
									</div>
									<div class="col-sm-1">
										<label for="oc1_idade">Idade:</label>
										<input type="number" name="oc1_idade" id="oc1_idade" class="form-control">
									</div>
									<div class="col-sm-1">
										<label for="oc1_sexo">Sexo:</label>
										<select name="oc1_sexo" id="oc1_sexo" class="form-control">
											<option value="M">Masculino</option>
											<option value="F">Feminino</option>
										</select>
									</div>
									<div class="col-sm-3">
										<label for="oc1_parente">Parentesco entre os ocupantes:</label>
										<input type="text" name="oc1_parente" id="oc1_parente" class="form-control">
									</div>
									<div class="col-sm-2">
										<label for="oc1_etnia">Etnia:</label>
										<input type="text" name="oc1_etnia" id="oc1_etnia" class="form-control">
									</div>
									<div class="col-sm-2">
										<label for="oc1_privilegio" style="font-size: 80%">Publicador, pioneiro, etc...</label>
										<input type="text" name="oc1_privilegio" id="oc1_privilegio" class="form-control">
									</div>
								</div>
							</td></tr>
							<tr><td>
								<h5><strong>Ocupante 2</strong></h5>
								<div class="form-group row">
									<div class="col-sm-3">
										<label for="oc2_nome">Nome:</label>
										<input type="text" name="oc2_nome" id="oc2_nome" class="form-control">
									</div>
									<div class="col-sm-1">
										<label for="oc2_idade">Idade:</label>
										<input type="number" name="oc2_idade" id="oc2_idade" class="form-control">
									</div>
									<div class="col-sm-1">
										<label for="oc2_sexo">Sexo:</label>
										<select name="oc2_sexo" id="oc2_sexo" class="form-control">
											<option value="M">Masculino</option>
											<option value="F">Feminino</option>
										</select>
									</div>
									<div class="col-sm-3">
										<label for="oc2_parente">Parentesco entre os ocupantes:</label>
										<input type="text" name="oc2_parente" id="oc2_parente" class="form-control">
									</div>
									<div class="col-sm-2">
										<label for="oc2_etnia">Etnia:</label>
										<input type="text" name="oc2_etnia" id="oc2_etnia" class="form-control">
									</div>
									<div class="col-sm-2">
										<label for="oc2_privilegio" style="font-size: 80%">Publicador, pioneiro, etc...</label>
										<input type="text" name="oc2_privilegio" id="oc2_privilegio" class="form-control">
									</div>
								</div>
							</td></tr>
							<tr><td>
								<h5><strong>Ocupante 3</strong></h5>
								<div class="form-group row">
									<div class="col-sm-3">
										<label for="oc3_nome">Nome:</label>
										<input type="text" name="oc3_nome" id="oc3_nome" class="form-control">
									</div>
									<div class="col-sm-1">
										<label for="oc3_idade">Idade:</label>
										<input type="number" name="oc3_idade" id="oc3_idade" class="form-control">
									</div>
									<div class="col-sm-1">
										<label for="oc3_sexo">Sexo:</label>
										<select name="oc3_sexo" id="oc3_sexo" class="form-control">
											<option value="M">Masculino</option>
											<option value="F">Feminino</option>
										</select>
									</div>
									<div class="col-sm-3">
										<label for="oc3_parente">Parentesco entre os ocupantes:</label>
										<input type="text" name="oc3_parente" id="oc3_parente" class="form-control">
									</div>
									<div class="col-sm-2">
										<label for="oc3_etnia">Etnia:</label>
										<input type="text" name="oc3_etnia" id="oc3_etnia" class="form-control">
									</div>
									<div class="col-sm-2">
										<label for="oc3_privilegio" style="font-size: 80%">Publicador, pioneiro, etc...</label>
										<input type="text" name="oc3_privilegio" id="oc3_privilegio" class="form-control">
									</div>
								</div>
							</td></tr>
							<tr><td>
								<h5><strong>Ocupante 4</strong></h5>
								<div class="form-group row">
									<div class="col-sm-3">
										<label for="oc4_nome">Nome:</label>
										<input type="text" name="oc4_nome" id="oc4_nome" class="form-control">
									</div>
									<div class="col-sm-1">
										<label for="oc4_idade">Idade:</label>
										<input type="number" name="oc4_idade" id="oc4_idade" class="form-control">
									</div>
									<div class="col-sm-1">
										<label for="oc4_sexo">Sexo:</label>
										<select name="oc4_sexo" id="oc4_sexo" class="form-control">
											<option value="M">Masculino</option>
											<option value="F">Feminino</option>
										</select>
									</div>
									<div class="col-sm-3">
										<label for="oc4_parente">Parentesco entre os ocupantes:</label>
										<input type="text" name="oc4_parente" id="oc4_parente" class="form-control">
									</div>
									<div class="col-sm-2">
										<label for="oc4_etnia">Etnia:</label>
										<input type="text" name="oc4_etnia" id="oc4_etnia" class="form-control">
									</div>
									<div class="col-sm-2">
										<label for="oc4_privilegio" style="font-size: 80%">Publicador, pioneiro, etc...</label>
										<input type="text" name="oc4_privilegio" id="oc4_privilegio" class="form-control">
									</div>
								</div>
							</td></tr>
						</tbody>
					</table>
					
					
					
					
					<br>
					
					
					<br>
					
					
					<br>
					
					
					<br>
					<div class="well sm-well">
						<h4><strong>SECRETÁRIO DA CONGREGAÇÃO</strong></h4>
						<div class="form-group">
							<label for="motivo">Explique por que esta é uma necessidade especial:</label>
							<textarea rows="2" class="form-control" name="motivo" id="motivo"></textarea>
						</div>
						
						<div class="form-group row">
							<div class="col-xs-4">
								<label for="secretario_nome">Nome do Secretário:</label>
								<input type="text" name="secretario_nome" id="secretario_nome" class="form-control">
							</div>
							<div class="col-xs-4">
								<label for="secretario_tel">Telefone do Secretário:</label>
								<input type="number" name="secretario_tel" id="secretario_tel" class="form-control">
							</div>
							<div class="col-xs-4">
								<label for="secretario_email">E-mail do Secretário:</label>
								<input type="text" name="secretario_email" id="secretario_email" class="form-control">
							</div>
						</div>
					</div>
					
					<br>
					<hr>
					<h4 class="text-center"><strong>LEIA COM ATENÇÃO AS INFORMAÇÕES ABAIXO ANTES DE PREENCHER ESTE FORMULÁRIO</strong></h4>
					
					<div style="column-count:2">
						Se você tem boa reputação na congregação e tem necessidades especiais que nem você nem a congregação têm condições de cuidar, você pode pedir hospedagem por meio do Departamento de Hospedagem do congresso. Não espere chegará cidade do congresso para fazer isso.
						<br>
						Deve-se preencher um Pedido Especial de Hospedagem (CO-5a) para cada quarto. Devem constar neste formulário somente os nomes das pessoas que ocuparão o mesmo quarto. Se por causa do transporte ou de outros motivos você precisar ficar num local perto de outro grupo, grampeie ou prenda com um clipe os formulários de Pedido Especial de Hospedagem, para que fiquem juntos.
						<br>
						Digite ou escreva as informações em letras de fôrma no formulário e o entregue ao secretário da congregação. Ele fará a verificação, assinará e enviará o formulário para o Departamento de Hospedagem do congresso a que você assistirá.
						<br>
						O Departamento de Hospedagem se esforçará para atender seu pedido. Pedimos que aceite as acomodações que forem escolhidas para você, visto que muito trabalho está envolvido nesses preparativos.
						<br>
						<strong>Quartos de hotel:</strong> Para obter os precos atualizados, consulte a Lista de Estabelecimentos Recomendados. Em geral é exigido o depósito de uma diária, creditado ao hotel, para garantir sua reserva. Lembre-se de que sua conduta no hotel deve ser irrepreensível, pois queremos glorificar o nome de Jeová.
						<br>
						<strong>Quartos em casas particulares:</strong> Esses são apenas para aqueles que não têm condições de pagar diárias de hotel. Geralmente, acomodar grupos grandes num único lugar não é fácil. Portanto, é melhor planejar grupos menores, de duas a quatro pessoas. Isso diminuirá o número de acomodações necessárias e tornará mais fácil encontrar um lugar para seu grupo. Se receber hospedagem numa casa particular, seria amoroso de sua parte contatar o hospedeiro para confirmar a data e a hora aproximada de sua chegada. Visto que você será hóspede na casa dele, tenha bom critério ao programar sua chegada, para que não seja numa hora inconveniente para o hospedeiro. Sua conduta como hóspede deve refletir excelentes princípios e qualidades cristãs.
					</div>
					
					<br>
					<div class="form-group">
						<input type="hidden" name="formulario_tipo" value="PEH">
						<button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-send"></span> <strong>Enviar pedido</strong></button>
					</div>
					
				</form>