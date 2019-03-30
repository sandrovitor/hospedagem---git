<?php

if(!isset($_REQUEST['funcao']) || $_REQUEST['funcao'] == '') {
	echo 'Acesso negado!';
	exit();
}


function pehConsulta($fid) { // FID = Formulario ID
	include('conecta.php');
	if($fid != 0) {
		$abc = $pdo->query('SELECT peh.*, cidade.cidade AS cong_cidade, cidade.estado AS cong_estado FROM `peh` LEFT JOIN cidade ON peh.congregacao_cidade_id = cidade.id WHERE peh.id = '.(int)$fid);
	} else {
		echo 'Acesso negado!';
		exit();
	}
	
	if($abc->rowCount() > 0) {
		while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
			if($reg->tipo_hospedagem == 'casa') {
				$tipo_hospedagem = '<input type="radio" value="casa" disabled checked> Casa Particular &nbsp; <input type="radio" value="hotel" disabled> Hotel';
			} else {
				$tipo_hospedagem = '<input type="radio" value="casa" disabled> Casa Particular &nbsp; <input type="radio" value="hotel" disabled checked> Hotel';
			}
				
			if($reg->transporte == 'SIM') {
				$transporte = '<input type="radio" value="SIM" disabled checked> Sim &nbsp; <input type="radio" value="NÃO" disabled> Não';
			} else {
				$transporte = '<input type="radio" value="SIM" disabled> Sim &nbsp; <input type="radio" value="NÃO" disabled checked> Não';
			}
			echo <<<HTML
					<h4 class="text-center"><strong>PEDIDO ESPECIAL DE HOSPEDAGEM <small>(Nº.: $reg->id)</small></strong></h4>
			
					<div class="row">
						<div class="col-xs-6">
							<div class="row">
								<div class="col-xs-12">
									<dl>
										<dt>Nome:</dt>
										<dd>$reg->nome</dd>
									</dl>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12">
									<dl>
										<dt>Endereço:</dt>
										<dd>$reg->endereco</dd>
									</dl>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6 col-xs-12">
									<dl>
										<dt>Cidade:</dt>
										<dd>$reg->cidade</dd>
									</dl>
								</div>
								<div class="col-sm-3 col-xs-6">
									<dl>
										<dt>Estado:</dt>
										<dd>$reg->estado</dd>
									</dl>
								</div>
								<div class="col-sm-3 col-xs-6">
									<dl>
										<dt>País:</dt>
										<dd>BRA</dd>
									</dl>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-6">
									<dl>
										<dt>Telefone Residencial:</dt>
										<dd>$reg->tel_res</dd>
									</dl>
								</div>
								<div class="col-xs-6">
									<dl>
										<dt>Telefone Celular:</dt>
										<dd>$reg->tel_cel</dd>
									</dl>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12">
									<dl>
										<dt>Email:</dt>
										<dd>$reg->email</dd>
									</dl>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6 col-xs-12">
									<dl>
										<dt>Congregação:</dt>
										<dd>$reg->congregacao</dd>
									</dl>
								</div>
								<div class="col-sm-6 col-xs-12">
									<dl>
										<dt>Cidade:</dt>
										<dd>$reg->cong_cidade/$reg->cong_estado</dd>
									</dl>
								</div>
							</div>
						</div>
						<div class="col-xs-6">
							<div class="row">
								<div class="col-xs-12">
									<dl>
										<dt>Cidade do Congresso:</dt>
										<dd>$reg->congresso_cidade</dd>
									</dl>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12">
									<dl>
										<dt>Primeira noite que precisará do quarto:</dt>
										<dd><input type="date" disabled value="$reg->check_in"></dd>
									</dl>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12">
									<dl>
										<dt>Última noite que precisará do quarto:</dt>
										<dd><input type="date" disabled value="$reg->check_out"></dd>
									</dl>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12">
									<dl>
										<dt>Tipo de acomodação:</dt>
										<dd>$tipo_hospedagem</dd>
									</dl>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12">
									<dl>
										<dt>Quanto pode pagar por esse quarto, por noite (em reais):</dt>
										<dd>R$ $reg->pagamento</dd>
									</dl>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12">
									<dl>
										<dt>Terá transporte próprio enquanto estiver na cidade do congresso:</dt>
										<dd>$transporte</dd>
									</dl>
								</div>
							</div>
		
				
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<table class="table table-responsive table-hover table-striped">
								<tbody>
HTML;
			$x = 1;
			for($i=1; $i<=4; $i++) {
				$nome_campo = 'oc'.$i.'_nome';
				$ocupante['nome'] = $reg->$nome_campo;
				$nome_campo = 'oc'.$i.'_idade';
				$ocupante['idade'] = $reg->$nome_campo;
				$nome_campo = 'oc'.$i.'_sexo';
				$ocupante['sexo'] = $reg->$nome_campo;
				$nome_campo = 'oc'.$i.'_parente';
				$ocupante['parente'] = $reg->$nome_campo;
				$nome_campo = 'oc'.$i.'_etnia';
				$ocupante['etnia'] = $reg->$nome_campo;
				$nome_campo = 'oc'.$i.'_privilegio';
				$ocupante['privilegio'] = $reg->$nome_campo;
		
		
				echo <<<HTML
									<tr><td>
										<h5><strong>OCUPANTE $i</strong></h5>
HTML;
				if($ocupante['nome'] <> '') {
					echo <<<HTML
											<div class="row">
												<div class="col-sm-3 col-xs-6">
													<dl>
														<dt>Nome:</dt>
														<dd>$ocupante[nome]</dd>
													</dl>
												</div>
												<div class="col-sm-1 col-xs-3">
													<dl>
														<dt>Idade:</dt>
														<dd>$ocupante[idade]</dd>
													</dl>
												</div>
												<div class="col-sm-1 col-xs-3">
													<dl>
														<dt>Sexo:</dt>
														<dd>$ocupante[sexo]</dd>
													</dl>
												</div>
												<div class="col-sm-3 col-xs-6">
													<dl>
														<dt>Parentesco:</dt>
														<dd>$ocupante[parente]</dd>
													</dl>
												</div>
												<div class="col-sm-2 col-xs-3">
													<dl>
														<dt>Etnia:</dt>
														<dd>$ocupante[etnia]</dd>
													</dl>
												</div>
												<div class="col-sm-2 col-xs-3">
													<dl>
														<dt>Privilégio:</dt>
														<dd>$ocupante[privilegio]</dd>
													</dl>
												</div>
											</div>
HTML;
						
						
				} else {
					echo <<<HTML
										<div class="row">
											<div class="col-xs-12 text-center">
												<i> >> VAZIO << </i>
											</div>
										</div>
HTML;
				}
				echo <<<HTML
									</td></tr>
HTML;
			}
				
				
			echo <<<HTML
		
								</tbody>
							</table>
						</div>
					</div>
HTML;
		}
	}
}

function facConsulta($fid) { // FID = Formulario ID
	include('conecta.php');
	if($fid != 0) {
		$abc = $pdo->query('SELECT fac.*, cidade.cidade AS cidade_nome, cidade.estado FROM fac LEFT JOIN cidade ON fac.cidade = cidade.id WHERE fac.id = '.(int)$fid);
	} else {
		echo 'Acesso negado!';
		exit();
	}
	
	if($abc->rowCount() > 0) {
		while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
				
			echo <<<DADOS
				<h4 class="text-center"><strong>FORMULÁRIO DE ACOMODAÇÃO <small>(Nº.: $reg->id)</small></strong></h4>
DADOS;
				
			$x = 0;
			for($x=1; $x <= $reg->quartos_qtd; $x++) {
				// Construo nome da varíavel.
				$var1 = 'quarto'.$x.'_sol_qtd';
				$var2 = 'quarto'.$x.'_cas_qtd';
				$var3 = 'quarto'.$x.'_valor1';
				$var4 = 'quarto'.$x.'_valor2';
		
				// Crio nome das variáveis e atribuo valor a elas.
				$var1 = $reg->$var1;
				$var2 = $reg->$var2;
				$var3 = $reg->$var3;
				$var4 = $reg->$var4;
		
		
				echo <<<DADOS
				<div class="row">
					<div class="col-xs-12 col-sm-2" style="text-align:right;">
						<h5><strong>QUARTO $x</strong></h5>
					</div>
					<div class="col-xs-6 col-sm-5">
						<div class="well sm-well">
							<div class="row">
								<div class="col-xs-6">
									<dt>Camas Solteiro</dt>
									<dd>$var1</dd>
								</div>
								<div class="col-xs-6">
									<dt>Camas Casal</dt>
									<dd>$var2</dd>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xs-6 col-sm-5">
						<div class="well sm-well">
							<div class="row">
								<div class="col-xs-12 text-center">
									<strong>Preço do quarto por dia</strong>
								</div>
								<div class="col-xs-6">
									<dt>Uma pessoa</dt>
									<dd>R$ $var3</dd>
								</div>
								<div class="col-xs-6">
									<dt>Duas ou mais</dt>
									<dd>R$ $var4</dd>
								</div>
							</div>
						</div>
					</div>
				</div>
DADOS;
			}
				
				
				
			echo <<<DADOS
			<div class="row">
				<div class="col-sm-10 col-sm-offset-2">
					<div class="form-group">
						<label>Os quartos estão disponíveis nos dias:</label><br>
DADOS;
			if($reg->dias == 'todos') {
				echo <<<DADOS
						<label class="checkbox-inline"><input type="checkbox" checked disabled>Domingo</label>
						<label class="checkbox-inline"><input type="checkbox" checked disabled>Segunda</label>
						<label class="checkbox-inline"><input type="checkbox" checked disabled>Terça</label>
						<label class="checkbox-inline"><input type="checkbox" checked disabled>Quarta</label>
						<label class="checkbox-inline"><input type="checkbox" checked disabled>Quinta</label>
						<label class="checkbox-inline"><input type="checkbox" checked disabled>Sexta</label>
						<label class="checkbox-inline"><input type="checkbox" checked disabled>Sábado</label>
						<label class="checkbox-inline"><input type="checkbox" checked disabled><strong>Todos</strong></label>
DADOS;
			} else {
				$dias = $reg->dias;
				$dias = explode(';',$dias);
		
				$dom = '';
				$seg = '';
				$ter = '';
				$qua = '';
				$qui = '';
				$sex = '';
				$sab = '';
				foreach($dias as $dia) {
					switch($dia) {
						case 1: $dom = 'checked'; break;
						case 2: $seg = 'checked'; break;
						case 3: $ter = 'checked'; break;
						case 4: $qua = 'checked'; break;
						case 5: $qui = 'checked'; break;
						case 6: $sex = 'checked'; break;
						case 7: $sab = 'checked'; break;
					}
				}
				echo <<<DADOS
						<label class="checkbox-inline"><input type="checkbox" $dom disabled>Domingo</label>
						<label class="checkbox-inline"><input type="checkbox" $seg disabled>Segunda</label>
						<label class="checkbox-inline"><input type="checkbox" $ter disabled>Terça</label>
						<label class="checkbox-inline"><input type="checkbox" $qua disabled>Quarta</label>
						<label class="checkbox-inline"><input type="checkbox" $qui disabled>Quinta</label>
						<label class="checkbox-inline"><input type="checkbox" $sex disabled>Sexta</label>
						<label class="checkbox-inline"><input type="checkbox" $sab disabled>Sábado</label>
						<label class="checkbox-inline"><input type="checkbox" disabled><strong>Todos</strong></label>
DADOS;
			}
				
			if($reg->andar == 0) {
				$andar = 'Térreo';
			} else {
				$andar = $reg->andar.'º andar';
			}
			if($reg->transporte == 0) {
				$transporte = 'Não';
			} else {
				$transporte = 'Sim';
			}
			if($reg->casa_tj == 0) {
				$casa_tj = 'Não';
			} else {
				$casa_tj = 'Sim';
			}
			echo <<<DADOS
					</div>
				</div>
			</div>
			<div class="row">
				<dl>
					<div class="col-sm-3 col-sm-offset-2">
						<dt>Em que andar ficam os quartos?</dt>
						<dd>$andar</dd>
					</div>
					<div class="col-sm-3">
						<dt>Poderá prover condução?</dt>
						<dd>$transporte</dd>
					</div>
					<div class="col-sm-4">
						<dt>É o lar de Testemunhas de Jeová?</dt>
						<dd>$casa_tj</dd>
					</div>
				</dl>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<dl>
						<dt>Observações:</dt>
						<dd>$reg->obs1</dd>
					</dl>
				</div>
			</div>
					<hr>
DADOS;
				
			echo <<<DADOS
			<div class="row">
				<div class="col-sm-6">
					<h5 CLASS="text-center"><strong>ENDEREÇO DO HOSPEDEIRO</strong></h5>
					<dl>
						<dt>Nome:</dt>
						<dd>$reg->nome</dd>
						<dt>Endereço:</dt>
						<dd>$reg->endereco</dd>
						<dt>Telefone:</dt>
						<dd>$reg->telefone</dd>
						<dt>Cidade:</dt>
						<dd>$reg->cidade_nome/$reg->estado</dd>
					</dl>
				</div>
				<div class="col-sm-6">
					<h5 CLASS="text-center"><strong>PUBLICADOR QUE INDICOU</strong><br>
					<small>(Publicador que conseguiu a hospedagem, se não for o hospedeiro)</small></h5>
						<dl>
							<dt>Publicador:</dt>
							<dd>$reg->publicador_nome</dd>
							<dt>Telefone:</dt>
							<dd>$reg->publicador_tel</dd>
						</dl>
					</div>
				</div>
			</div>
			<hr>
		
DADOS;
				
			$abc = $pdo->query('SELECT cidade.cidade, cidade.estado FROM cidade WHERE cidade.id = '.$reg->cong_cidade);
			$lin = $abc->fetch(PDO::FETCH_OBJ);
				
			$exc = '';
			$boa = '';
			$raz = '';
			switch($reg->condicao) {
				case 'excelente': $exc = 'checked'; break;
				case 'boa': $boa = 'checked'; break;
				case 'razoavel': $raz = 'checked';break;
			}
				
			echo <<<DADOS
			<div class="well sm-well">
				<h4><strong>SECRETÁRIO DA CONGREGAÇÃO</strong></h4>
				<div class="row">
					<div class="col-sm-5">
						<dl>
							<dt>Nome da Congregação:</dt>
							<dd>$reg->cong_nome</dd>
							<dt>Cidade:</dt>
							<dd>$lin->cidade/$lin->estado</dd>
							<dt>Nome do Secretário:</dt>
							<dd>$reg->cong_sec</dd>
							<dt>Telefone do Secretário:</dt>
							<dd>$reg->cong_tel</dd>
						</dl>
					</div>
					<div class="col-sm-7">
						<dl>
							<label>Condição do(s) quarto(s):</label><br>
							<label class="radio-inline"><input type="radio" name="condicao" disabled $exc>Excelente</label><br>
							<label class="radio-inline"><input type="radio" name="condicao" disabled $boa>Boa</label><br>
							<label class="radio-inline"><input type="radio" name="condicao" disabled $raz>Razoável</label>
							<dt>Observações:</dt>
							<dd>$reg->obs2</dd>
						</dl>
					</div>
				</div>
			</div>
DADOS;
				
				
		}
	}
}

function pehAnalise($fid) {
	include('conecta.php');
	if($fid != 0) {
		$abc = $pdo->query('SELECT peh.*, cidade.cidade AS cong_cidade, cidade.estado AS cong_estado FROM `peh` LEFT JOIN cidade ON peh.congregacao_cidade_id = cidade.id WHERE peh.id = '.(int)$fid);
	} else {
		echo 'Acesso negado!';
		exit();
	}
	
	if($abc->rowCount() > 0) {
		while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
			echo 'Analisando <strong>Pedido de Hospedagem (Nº '.$fid.')</strong>...<br><br>';
			
			echo <<<DADOS
			
			<table class="table table-bordered">
				<tbody>
DADOS;
			
			// ################ Número de ocupantes do pedido
			$ocupantes = 0; $msg = ''; $msg_pop = '';
			if($reg->oc1_nome <> '') {
				$ocupantes++;
			}
			if($reg->oc2_nome <> '') {
				$ocupantes++;
			}
			if($reg->oc3_nome <> '') {
				$ocupantes++;
			}
			if($reg->oc4_nome <> '') {
				$ocupantes++;
			}
			
			if($ocupantes == 0) {
				$ocupantes = '<span class="label label-default">'.$ocupantes.'</span>';
				$msg = '<span class="glyphicon glyphicon-remove"></span> ERRO!';
				$msg_tit = str_replace('"', "'", $msg);
				$msg_pop = 'O pedido não contém nome de nenhum ocupante! Solicite revisão.';
			} else if ($ocupantes > 3) {
				$ocupantes = '<span class="label label-danger">'.$ocupantes.'</span>';
				$msg = '<span class="glyphicon glyphicon-remove red"></span> <strong>Há muitas pessoas.</strong>';
				$msg_tit = str_replace('"', "'", $msg);
				$msg_pop = 'Solicite revisão ou ignore esta mensagem se está tudo certo.';
			} else {
				$ocupantes = '<span class="label label-success">'.$ocupantes.'</span>';
				$msg = '<span class="glyphicon glyphicon-ok green"></span> OK';
				$msg_tit = str_replace('"', "'", $msg);
				$msg_pop = 'Tudo OK';
			}
			
			echo <<<DADOS
					<tr>
						<th><span class="glyphicon glyphicon-user"></span> Pessoas: $ocupantes</th>
						<td><div data-toggle="popover" data-trigger="hover" data-placement="left" title="$msg_tit" data-content="$msg_pop">$msg</div></td>
					<tr>
DADOS;
			unset($ocupantes);
			
			
			// ################## Intervalo de datas.
			$dat1 = new DateTime($reg->check_in);
			$dat2 = new DateTime($reg->check_out);
			
			$dias = $dat2->diff($dat1)->days; // Captura a diferença de dias entre a data de check_in e check_out.
			
			if($dias >= 7) {
				$dias = '<span class="label label-danger">'.$dias.'</span>';
				$msg = '<span class="glyphicon glyphicon-alert red"></span> <strong>Muitos dias</strong>';
				$msg_tit = str_replace('"', "'", $msg);
				$msg_pop = 'Confirme se as datas estão certas. Dê preferência às acomodações disponíveis por 7 dias.';
			} else if($dias >= 5 && $dias < 7) {
				$dias = '<span class="label label-warning">'.$dias.'</span>';
				$msg = '<span class="glyphicon glyphicon-alert red"></span> <strong>Quase uma semana</strong>';
				$msg_tit = str_replace('"', "'", $msg);
				$msg_pop = 'Confirme se as datas estão certas. Dê preferência às acomodações disponíveis por 7 dias.';
			} else {
				$dias = '<span class="label label-success">'.$dias.'</span>';
				$msg = '<span class="glyphicon glyphicon-ok green"></span> OK';
				$msg_tit = str_replace('"', "'", $msg);
				$msg_pop = 'Tudo OK';
			}
			echo <<<DADOS
					<tr>
						<th><span class="glyphicon glyphicon-calendar"></span> Dias: $dias</th>
						<td><div data-toggle="popover" data-trigger="hover" data-placement="left" title="$msg_tit" data-content="$msg_pop">$msg</div></td>
					<tr>
DADOS;
			
			unset($dias);
			
			// ################## Transporte
			if($reg->transporte == 1) {
				$transp = '<span class="label label-success">SIM</span>';
				$msg = '<span class="glyphicon glyphicon-ok green"></span> OK';
				$msg_tit = str_replace('"', "'", $msg);
				$msg_pop = 'Não há com o quê se preocupar.';
			} else {
				$transp = '<span class="label label-danger">NÃO</span>';
				$msg = '<span class="glyphicon glyphicon-alert red"></span> <strong>Atenção!</strong>';
				$msg_tit = str_replace('"', "'", $msg);
				$msg_pop = 'Importante alinhar com o hóspede o que irá fazer para se deslocar ao local do evento.';
			}
			
			echo <<<DADOS
					<tr>
						<th><span class="glyphicon glyphicon-plane"></span> Transporte próprio: $transp</th>
						<td><div data-toggle="popover" data-trigger="hover" data-placement="left" title="$msg_tit" data-content="$msg_pop">$msg</div></td>
					<tr>
DADOS;
			
			
			
			echo <<<DADOS
			
				</tbody>
			</table>
DADOS;
			
			// ###################### CONSIDERAÇÕES FINAIS
			echo <<<DADOS
			
			<br>
			<hr>
			Para demais informações, visualize o pedido de hospedagem.
DADOS;
		}
	}
}

function facAnalise($fid) {
	include('conecta.php');
	if($fid != 0) {
		$abc = $pdo->query('SELECT fac.*, cidade.cidade AS cidade_nome, cidade.estado FROM fac LEFT JOIN cidade ON fac.cidade = cidade.id WHERE fac.id = '.(int)$fid);
	} else {
		echo 'Acesso negado!';
		exit();
	}
	
	if($abc->rowCount() > 0) {
		while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
			echo 'Analisando <strong>Acomodação (Nº '.$fid.')</strong>...<br><br>';
				
			echo <<<DADOS
		
			<table class="table table-bordered">
				<tbody>
DADOS;
			
			// ################## Quantidade de quartos
			$quartos = '<span class="badge">'.$reg->quartos_qtd.'</span>';
			$msg = '<span class="glyphicon glyphicon-ok green"></span> OK';
			$msg_tit = str_replace('"', "'", $msg);
			$msg_pop = 'Tudo OK';
			echo <<<DADOS
					<tr>
						<th><span class="glyphicon glyphicon-lamp"></span> Quartos: $quartos</th>
						<td><div data-toggle="popover" data-trigger="hover" data-placement="left" title="$msg_tit" data-content="$msg_pop">$msg</div></td>
					<tr>
DADOS;
			unset($quartos);
			
			// #################### Quantidade de camas de solteiro e de casal.
			$cama_sol = $reg->quarto1_sol_qtd + $reg->quarto2_sol_qtd + $reg->quarto3_sol_qtd + $reg->quarto4_sol_qtd;
			$cama_cas = $reg->quarto1_cas_qtd + $reg->quarto2_cas_qtd + $reg->quarto3_cas_qtd + $reg->quarto4_cas_qtd;
			
			if($cama_sol == 0) {
				$cama_sol = '<span class="label label-danger">'.$cama_sol.'</span>';
				$msg = '<span class="glyphicon glyphicon-alert red"></span> <strong>Atenção</strong>';
				$msg_tit = str_replace('"', "'", $msg);
				$msg_pop = 'Não há camas de solteiro nesta acomodação...';
			} else {
				$cama_sol = '<span class="label label-success">'.$cama_sol.'</span>';
				$msg = '<span class="glyphicon glyphicon-ok green"></span> OK';
				$msg_tit = str_replace('"', "'", $msg);
				$msg_pop = 'Tudo OK';
			}
			echo <<<DADOS
					<tr>
						<th><span class="glyphicon glyphicon-bed"></span> Cama(s) de solteiro: $cama_sol</th>
						<td><div data-toggle="popover" data-trigger="hover" data-placement="left" title="$msg_tit" data-content="$msg_pop">$msg</div></td>
					<tr>
DADOS;
			
			if($cama_cas == 0) {
				$cama_cas = '<span class="label label-danger">'.$cama_cas.'</span>';
				$msg = '<span class="glyphicon glyphicon-alert red"></span> <strong>Atenção</strong>';
				$msg_tit = str_replace('"', "'", $msg);
				$msg_pop = 'Não há camas de casal nesta acomodação...';
			} else {
				$cama_cas = '<span class="label label-success">'.$cama_cas.'</span>';
				$msg = '<span class="glyphicon glyphicon-ok green"></span> OK';
				$msg_tit = str_replace('"', "'", $msg);
				$msg_pop = 'Tudo OK';
			}
			echo <<<DADOS
					<tr>
						<th><span class="glyphicon glyphicon-bed"></span> Cama(s) de casal: $cama_cas</th>
						<td><div data-toggle="popover" data-trigger="hover" data-placement="left" title="$msg_tit" data-content="$msg_pop">$msg</div></td>
					<tr>
DADOS;
			unset($cama_cas, $cama_sol);
			
			// ######################## Dias disponiveis
			if($reg->dias == 'todos') {
				$dias = '<span class="label label-success" style="letter-spacing: 2px;">TODOS</span>';
				$msg = '<span class="glyphicon glyphicon-ok green"></span> ÓTIMO';
				$msg_pop = 'A acomodação está disponível todos os dias da semana. Excelente!';
			} else if($reg->dias == '') {
				$dias = '<span class="label label-danger">0</span>';
				$msg = '<span class="glyphicon glyphicon-alert red"></span> <strong>ERRO!</strong>';
				$msg_pop = '<strong>Esta acomodação não informou quantos dias ficaria disponível!!</strong>';
			}else {
				$dias = explode(';', $reg->dias);
				$dias = count($dias);
				if($dias >= 1 && $dias <=3) {
					$dias = '<span class="label label-warning">1-3</span>';
					$msg = '<span class="glyphicon glyphicon-ok yellow"></span> Razoável';
					$msg_pop = 'A acomodação está disponível de 1 a 3 dias. Pode não ser suficiente. Analise com cuidado!';
				} else if($dias > 3) {
					$dias = '<span class="label label-success">4 ou mais</span>';
					$msg = '<span class="glyphicon glyphicon-ok green"></span> Bom!';
					$msg_pop = 'Dura o tempo suficiente para um congresso de 3 dias.';
				}
				
			
			}
			$msg_tit = str_replace('"', "'", $msg);
			
			echo <<<DADOS
					<tr>
						<th><span class="glyphicon glyphicon-calendar"></span> Dias: $dias</th>
						<td><div data-toggle="popover" data-trigger="hover" data-placement="left" title="$msg_tit" data-content="$msg_pop">$msg</div></td>
					<tr>
DADOS;
			unset($dias);
			
			// #################### Transporte
			if($reg->transporte == 1) {
				$transp = '<span class="label label-success">SIM</span>';
				$msg = '<span class="glyphicon glyphicon-ok green"></span> OK';
				$msg_tit = str_replace('"', "'", $msg);
				$msg_pop = 'Não há com o quê se preocupar.';
			} else {
				$transp = '<span class="label label-danger">NÃO</span>';
				$msg = '<span class="glyphicon glyphicon-alert red"></span> <strong>Atenção!</strong>';
				$msg_tit = str_replace('"', "'", $msg);
				$msg_pop = 'Dê preferência para hóspedes que possuem transporte próprio.';
			}
				
			echo <<<DADOS
					<tr>
						<th><span class="glyphicon glyphicon-plane"></span> Tem carro?: $transp</th>
						<td><div data-toggle="popover" data-trigger="hover" data-placement="left" title="$msg_tit" data-content="$msg_pop">$msg</div></td>
					<tr>
DADOS;
			unset($transp);
			
			
			echo <<<DADOS
			
				</tbody>
			</table>
DADOS;
			

			// ###################### CONSIDERAÇÕES FINAIS
			echo <<<DADOS
		
			<br>
			<hr>
			Para demais informações, visualize o formulário de acomodação.
DADOS;
		}
	}
}

function analiseCruzada($pehid, $facid) { // PEHID = PEH ID;	FACID = FAC ID
	include('conecta.php');
	$abc = $pdo->query('SELECT * FROM peh WHERE id = '.(int)$pehid);
	$peh = $abc->fetch(PDO::FETCH_OBJ);
	
	$abc = $pdo->query('SELECT * FROM fac WHERE id = '.(int)$facid);
	$fac = $abc->fetch(PDO::FETCH_OBJ);
	
	echo '<strong>PEDIDO DE HOSPEDAGEM (ID: '.$pehid.')</strong><br>';
	echo '<strong>FORM. DE ACOMODAÇÃO (ID: '.$facid.')</strong><br>';
	echo 'Cruzando dados... Aguarde!<span class="glyphicon glyphicon-hourglass"></span>';
	

	
	// ################## ABRE TABELA
	echo <<<DADOS
	
	<br><hr>
	<table class="table table-responsive table-bordered table-condensed">
		<thead>
			<tr>
				<th></th>
				<th>PEDIDO<br>HOSPEDAGEM</th>
				<th>FORMULÁRIO<br>ACOMODAÇÃO</th>
			</tr>
		</thead>
		<tbody>
DADOS;
	
	// Pessoas vs camas
	
	
	
	// ################ FECHA TABELA
	echo <<<DADOS
	
		</tbody>
	</table>
DADOS;
	
	

	
}


switch($_REQUEST['funcao']) {
		
	case 'pehConsulta':
		pehConsulta($_REQUEST['id']);
		break;
		
	case 'facConsulta':
		facConsulta($_REQUEST['id']);
		break;
		
	case 'pehAnalise':
		pehAnalise($_REQUEST['id']);
		break;
		
	case 'facAnalise':
		facAnalise($_REQUEST['id']);
		break;
		
	case 'analiseCruzada':
		analiseCruzada($_REQUEST['pehid'], $_REQUEST['facid']);
		break;
		
	default:
		echo 'Erro 404: Not found';
		break;
}