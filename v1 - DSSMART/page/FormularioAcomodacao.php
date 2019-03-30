<?php 
include('../app/session.php');
include('../app/conecta.php');
if($_SESSION['nivel'] == 1) {
	echo 'ACESSO NEGADO!';
	exit();
}
?>
				<script>
				var codQuart = 2;
				function mostraQuarto(codigo) {
					if(codQuart <= 4) {
						if($("#quarto"+codQuart).css("display") == 'none') {
							$("#quarto"+codQuart).fadeIn();
							$("input[name='quarto"+codQuart+"'").val('yes');
						}
						codQuart++;
					} else { // Se a contagem passar de 4, mostra alerta.
						alert('Limite máximo de quartos atingido: '+ (codQuart-1));
					}
				}
				
				</script>
				<h3 class="text-center"><strong>FORMULÁRIO DE ACOMODAÇÃO</strong><br></h3>
			
				<div class="alert alert-danger visible-xs">
					<strong>Sinal vermelho!</strong><br>
					Esse formulário não pode ser preenchido/exibido em dispositivos móveis. <i><strong>Utilize um computador ou equipamento de tela maior.</strong></i>
				</div>
				<form action="formulario.php" method="post" class="hidden-xs">
					<div class="form-group row" id="quarto1">
						<div class="col-xs-12 col-sm-2">
							<h5 class="pull-right hidden-xs" style="text-align:right;"><strong>QUARTO 1</strong><br>
							<small><a onclick="mostraQuarto()">(Adicionar outro quarto)</a></small></h5>
						</div>
						<div class="col-xs-6 col-sm-5">
							<div class="well sm-well">
								<div class="row text-center">
									<label>Quantidade de camas</label>
								</div>
								<div class="row">
									<div class="col-xs-6">
										<input type="number" name="quarto1__sol_qtd" class="form-control"  placeholder="Solteiro">
									</div>
									<div class="col-xs-6">
										<input type="number" name="quarto1__cas_qtd" class="form-control"  placeholder="Casal">
									</div>
								</div>
							</div>
						</div>
						<div class="col-xs-6 col-sm-5">
							<div class="well sm-well">
								<div class="row text-center">
									<label>Preço do quarto por dia</label>
								</div>
								<div class="row">
									<div class="col-xs-6">
										<input type="number" name="quarto1_valor1" class="form-control" placeholder="Um no quarto">
									</div>
									<div class="col-xs-6">
										<input type="number" name="quarto1_valor2" class="form-control" placeholder="Dois ou mais no quarto">
									</div>
								</div>
							</div>
						</div>
						
					</div>
					<div class="form-group row" id="quarto2" style="display:none">
						<div class="col-xs-12 col-sm-2">
							<h5 class="pull-right hidden-xs" style="text-align:right;"><strong>QUARTO 2</strong><br>
							<small><a onclick="mostraQuarto()">(Adicionar outro quarto)</a></small></h5>
						</div>
						<div class="col-xs-6 col-sm-5">
							<div class="well sm-well">
								<div class="row text-center">
									<label>Quantidade de camas</label>
								</div>
								<div class="row">
									<div class="col-xs-6">
										<input type="number" name="quarto2__sol_qtd" class="form-control"  placeholder="Solteiro">
									</div>
									<div class="col-xs-6">
										<input type="number" name="quarto2__cas_qtd" class="form-control"  placeholder="Casal">
									</div>
								</div>
							</div>
						</div>
						<div class="col-xs-6 col-sm-5">
							<div class="well sm-well">
								<div class="row text-center">
									<label>Preço do quarto por dia</label>
								</div>
								<div class="row">
									<div class="col-xs-6">
										<input type="number" name="quarto2_valor1" class="form-control" placeholder="Um no quarto">
									</div>
									<div class="col-xs-6">
										<input type="number" name="quarto2_valor2" class="form-control" placeholder="Dois ou mais no quarto">
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group row" id="quarto3" style="display:none">
						<div class="col-xs-12 col-sm-2">
							<h5 class="pull-right hidden-xs" style="text-align:right;"><strong>QUARTO 3</strong><br>
							<small><a onclick="mostraQuarto()">(Adicionar outro quarto)</a></small></h5>
						</div>
						<div class="col-xs-6 col-sm-5">
							<div class="well sm-well">
								<div class="row text-center">
									<label>Quantidade de camas</label>
								</div>
								<div class="row">
									<div class="col-xs-6">
										<input type="number" name="quarto3__sol_qtd" class="form-control"  placeholder="Solteiro">
									</div>
									<div class="col-xs-6">
										<input type="number" name="quarto3__cas_qtd" class="form-control"  placeholder="Casal">
									</div>
								</div>
							</div>
						</div>
						<div class="col-xs-6 col-sm-5">
							<div class="well sm-well">
								<div class="row text-center">
									<label>Preço do quarto por dia</label>
								</div>
								<div class="row">
									<div class="col-xs-6">
										<input type="number" name="quarto3_valor1" class="form-control" placeholder="Um no quarto">
									</div>
									<div class="col-xs-6">
										<input type="number" name="quarto3_valor2" class="form-control" placeholder="Dois ou mais no quarto">
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group row" id="quarto4" style="display:none">
						<div class="col-xs-12 col-sm-2">
							<h5 class="pull-right hidden-xs" style="text-align:right;"><strong>QUARTO 4</strong></h5>
						</div>
						<div class="col-xs-6 col-sm-5">
							<div class="well sm-well">
								<div class="row text-center">
									<label>Quantidade de camas</label>
								</div>
								<div class="row">
									<div class="col-xs-6">
										<input type="number" name="quarto4__sol_qtd" class="form-control"  placeholder="Solteiro">
									</div>
									<div class="col-xs-6">
										<input type="number" name="quarto4__cas_qtd" class="form-control"  placeholder="Casal">
									</div>
								</div>
							</div>
						</div>
						<div class="col-xs-6 col-sm-5">
							<div class="well sm-well">
								<div class="row text-center">
									<label>Preço do quarto por dia</label>
								</div>
								<div class="row">
									<div class="col-xs-6">
										<input type="number" name="quarto4_valor1" class="form-control" placeholder="Um no quarto">
									</div>
									<div class="col-xs-6">
										<input type="number" name="quarto4_valor2" class="form-control" placeholder="Dois ou mais no quarto">
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-10 col-sm-offset-2">
							<div class="form-group">
								<label>Os quartos estão disponíveis nos dias:</label>
								<label class="checkbox-inline"><input type="checkbox" name="dias[]" value="1">Domingo</label>
								<label class="checkbox-inline"><input type="checkbox" name="dias[]" value="2">Segunda</label>
								<label class="checkbox-inline"><input type="checkbox" name="dias[]" value="3">Terça</label>
								<label class="checkbox-inline"><input type="checkbox" name="dias[]" value="4">Quarta</label>
								<label class="checkbox-inline"><input type="checkbox" name="dias[]" value="5">Quinta</label>
								<label class="checkbox-inline"><input type="checkbox" name="dias[]" value="6">Sexta</label>
								<label class="checkbox-inline"><input type="checkbox" name="dias[]" value="7">Sábado</label>
								<label class="checkbox-inline"><input type="checkbox" name="dias[]" value="todos"><strong>Todos</strong></label>
								
							</div>
						</div>
					</div>
					<div class="form-group row">
						<div class="col-sm-3 col-sm-offset-2">
							<label>Em que andar ficam os quartos?<br> <small>(Assuma térreo como 0)</small></label>
							<input type="number" name="andar" value="0" class="form-control">
						</div>
						<div class="col-sm-3">
							<label>Poderá prover condução?</label>
							<select name="transporte" class="form-control">
								<option value="1">Sim</option>
								<option value="0">Não</option>
							</select>
						</div>
						<div class="col-sm-4">
							<label>É o lar de Testemunhas de Jeová?</label>
							<select name="casa_tj" class="form-control">
								<option value="1">Sim</option>
								<option value="0">Não</option>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<label>Observações:</label>
								<textarea rows="2" name="obs1" class="form-control"></textarea>
							</div>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-sm-6">
							<h5 CLASS="text-center"><strong>ENDEREÇO DO HOSPEDEIRO</strong></h5>
							<div class="form-group">
								<label>Nome:</label>
								<input type="text" name="nome" class="form-control">
							</div>
							<div class="form-group">
								<label>Endereço <small>(incluir bairro)</small>:</label>
								<input type="text" name="endereco" class="form-control">
							</div>
							<div class="row">
								<div class="col-sm-6">
									<label>Telefone:</label>
									<input type="text" name="telefone" class="form-control">
								</div>
								<div class="col-sm-6">
									<label>Cidade:</label>
									<select name="cidade" class="form-control">
										<?php 
										$abc = $pdo->query('SELECT * FROM cidade WHERE hospedeiro = 1');
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
							<h5 CLASS="text-center"><strong>PUBLICADOR QUE INDICOU</strong><br>
							<small>(Publicador que conseguiu a hospedagem, se não for o hospedeiro)</small></h5>
							<div class="form-group">
								<label>Publicador:</label>
								<input type="text" name="publicador_nome" class="form-control">
							</div>
							<div class="form-group">
								<label>Telefone</label>
								<input type="text" name="publicador_tel" class="form-control">
							</div>
						</div>
					</div>
					
					<hr>
					<div class="well sm-well">
						<h4><strong>SECRETÁRIO DA CONGREGAÇÃO</strong></h4>
						<div class="form-group row">
							<div class="col-sm-4">
								<label>Nome da Congregação:</label>
								<input type="text" name="cong_nome" class="form-control">
								<br>
								<label>Cidade:</label>
								<select name="cong_cidade" class="form-control">
									<?php 
									$abc = $pdo->query('SELECT * FROM cidade WHERE hospedeiro = 1');
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
							<div class="col-sm-4">
								<label>Nome do Secretário:</label>
								<input type="text" name="cong_sec" class="form-control">
								<br>
								<label>Telefone do Secretário:</label>
								<input type="text" name="cong_tel" class="form-control">
							</div>
							<div class="col-sm-4">
								<label>Condição do(s) quarto(s):</label><br>
								<label class="radio-inline"><input type="radio" name="condicao" value="excelente">Excelente</label>
								<label class="radio-inline"><input type="radio" name="condicao" value="boa">Boa</label>
								<label class="radio-inline"><input type="radio" name="condicao" value="razoavel">Razoável</label>
								<br>
								<label>Observações</label>
								<textarea rows="2" name="obs2" class="form-control"></textarea>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<input type="hidden" name="formulario_tipo" value="FAc">
								<input type="hidden" name="quarto1" value="yes">
								<input type="hidden" name="quarto2" value="not">
								<input type="hidden" name="quarto3" value="not">
								<input type="hidden" name="quarto4" value="not">
								<button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-send"></span> Enviar</button>
							</div>
						</div>
					</div>
					
					
				</form>