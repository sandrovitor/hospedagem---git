<?php
include('session.php');
if(!isset($_REQUEST['funcao']) || $_REQUEST['funcao'] == '') {
	echo 'Acesso negado!';
	exit();
}


function pehConsulta($fid, $links) { // FID = Formulario ID
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
			
			if($reg->revisar == 1 && ($_SESSION['nivel'] == 1 || $_SESSION['nivel'] == 20)) {
				$token = md5($reg->id.session_id());
				$revisar = '&nbsp; <span class="label label-danger" data-toggle="tooltip" title="É PRECISO REVISAR ESSE FORMULÁRIO"><span class="glyphicon glyphicon-alert"></span> REV</span> <a href="formulario.edita.php?tipo=peh&pehid='.$reg->id.'&token='.$token.'" target="_blank" class="btn btn-sm btn-success" title="Editar" data-toggle="tooltip"><span class="glyphicon glyphicon-edit"></span></a>';
			} else {
				$revisar = '';
			}
			
			echo <<<HTML
					<h4 class="text-center"><strong>PEDIDO ESPECIAL DE HOSPEDAGEM <small>(Nº.: $reg->id)</small></strong> $revisar</h4>
					
HTML;
			
			// Se já estiver vinculado com uma acomodação.
			if($reg->fac_id != 0) {
				// Verifica se escreve na página com LINKS
				if($links == 0) { // SEM LINK
					echo <<<DADOS
					<div class="text-center"><kbd><span class="glyphicon glyphicon-ok"></span> JÁ VINCULADO A ACOMODAÇÃO >>> <div class="label label-info" style="font-size:11px; letter-spacing: 1px;">ID: $reg->fac_id</div></kbd></div>
					<hr>
DADOS;
				} else if($links == 1) { // COM LINK PARA FORMULARIO DE ACOMODAÇÃO [pagina de gerenciamento]
					echo <<<DADOS
					<div class="text-center"><kbd><span class="glyphicon glyphicon-ok"></span> JÁ VINCULADO A ACOMODAÇÃO >>> <div class="label label-info" style="font-size:11px; letter-spacing: 1px;">ID: <a class="btn btn-link" data-toggle="popover" data-placement="top" data-content="<a class='btn btn-primary' onclick='facCarrega($reg->fac_id,1); zeraFacSelect();'>Visualizar Acomodação <span class='glyphicon glyphicon-arrow-right'></span></a>">$reg->fac_id</a></div></kbd></div>
					<hr>
DADOS;
				}
			}
			 
			
			echo <<<HTML
			
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

function facConsulta($fid, $links) { // FID = Formulario ID
	include('conecta.php');
	if($fid != 0) {
		$abc = $pdo->query('SELECT fac.*, cidade.cidade AS cidade_nome, cidade.estado FROM fac LEFT JOIN cidade ON fac.cidade = cidade.id WHERE fac.id = '.(int)$fid);
	} else {
		echo 'Acesso negado!';
		exit();
	}
	
	if($abc->rowCount() > 0) {
		while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
			
		    if($reg->revisar == 1 && ($_SESSION['nivel'] == 10 || $_SESSION['nivel'] == 20)) {
		        $token = md5($reg->id.session_id());
		        $revisar = '&nbsp; <span class="label label-danger" data-toggle="tooltip" title="É PRECISO REVISAR ESSE FORMULÁRIO"><span class="glyphicon glyphicon-alert"></span> REV</span> <a href="formulario.edita.php?tipo=fac&facid='.$reg->id.'&token='.$token.'" target="_blank" class="btn btn-sm btn-success" title="Editar" data-toggle="tooltip"><span class="glyphicon glyphicon-edit"></span></a>';
		    } else {
		        $revisar = '';
		    }
		    
		    
			echo <<<DADOS
				<h4 class="text-center"><strong>FORMULÁRIO DE ACOMODAÇÃO <small>(Nº.: $reg->id)</small></strong> $revisar</h4>
DADOS;
			
			// Pesquisa se o formulário já atendeu algum pedido de hospedagem.
			$def = $pdo->query('SELECT peh.id FROM peh WHERE peh.fac_id = '.$fid);
			if($def->rowCount() > 0) {
				$fac_id = '';
				while($lin = $def->fetch(PDO::FETCH_OBJ)) {
					$fac_id .= $lin->id.', ';
				}
				$fac_id = substr($fac_id, 0, -2);
				
				if($links == 0) { // SEM LINK
					echo <<<DADOS
				<div class="text-center"><kbd><span class="glyphicon glyphicon-ok"></span> ATENDENDO PEDIDO(s) DE HOSPEDAGEM >>> <div class="label label-info" style="font-size:11px; letter-spacing: 1px;">ID: $fac_id</div></kbd></div>
				<hr>
DADOS;
				} else if($links == 1) { // COM LINK PARA PEDIDO DE HOSPEDAGEM [pagina de gerenciamento]
					$fac_id = explode(', ', $fac_id);
					
					$link = '';
					
					foreach($fac_id as $peh_id) {
						$link .= <<<DADOS
					<a class="btn btn-link" data-toggle="popover" data-placement="top" data-content="<a class='btn btn-primary' onclick='pehCarrega($peh_id,1); zeraPehSelect();'><span class='glyphicon glyphicon-arrow-left'></span> Visualizar Pedido</a>">$peh_id</a> 
DADOS;
					}
						
					
					echo <<<DADOS
				<div class="text-center"><kbd><span class="glyphicon glyphicon-ok"></span> ATENDENDO PEDIDO(s) DE HOSPEDAGEM >>> <div class="label label-info" style="font-size:11px; letter-spacing: 1px;">ID: $link</div></kbd></div>
				<hr>
DADOS;
					unset($peh_id, $fac_id, $link);
				}
			}
			
			
			
				
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

function revisaForms($tipo, $fid) { // FID = Formulario ID
    include('conecta.php');
    
    if($fid != 0) {
        if($tipo == 'peh') {
            $abc = $pdo->prepare('UPDATE peh SET revisar = 1 WHERE id = :fid');
        } else if($tipo == 'fac') {
            $abc = $pdo->prepare('UPDATE fac SET revisar = 1 WHERE id = :fid');
        } else {
            exit('Parametros inválidos!');
        }
        
        $abc->bindValue(':fid', $fid, PDO::PARAM_INT);
        try {
            $abc->execute();
            echo 'success';
        } catch(PDOException $e) {
            exit('Erro na operação: '.$e->getMessage());
        }
    } else {
        exit('Acesso negado!');
    }
}

function apagaPEH($fid, $token) {
	include('conecta.php');
	if($fid != 0 && $token != '') {
		$token_novo = md5($fid.session_id());
		
		if($token_novo == $token) {
			
			$abc = $pdo->prepare('DELETE FROM `peh` WHERE `id` = :fid');
			$abc->bindValue(':fid', $fid, PDO::PARAM_INT);
			
			$abc->execute();
			exit('OK');
			
		} else {
			exit('Erro 403: Acesso negado! [Token incorreto]');
		}
	} else {
		exit('Erro 403: Acesso negado!');
	}
}

function apagaFAC($fid, $token) {
	include('conecta.php');
	if($fid != 0 && $token != '') {
		$token_novo = md5($fid.session_id());
	
		if($token_novo == $token) {
				
			// Desvincula Acomodação de algum Pedido de Hospedagem
			$abc = $pdo->prepare('UPDATE `peh` SET `fac_id` = 0 WHERE `fac_id` = :fid');
			$abc->bindValue('fid', $fid, PDO::PARAM_INT);
			$abc->execute();
			
			// Remove acomodação
			$abc = $pdo->prepare('DELETE FROM `fac` WHERE `id` = :fid');
			$abc->bindValue(':fid', $fid, PDO::PARAM_INT);
				
			$abc->execute();
			exit('OK');
				
		} else {
			exit('Erro 403: Acesso negado! [Token incorreto]');
		}
	} else {
		exit('Erro 403: Acesso negado!');
	}
}

function adm_usuCarrega($id) {
	if($id <> 0 && $id <> '') { // Só executa se for diferente de zero ou vazio
		include('conecta.php');
		$abc = $pdo->query('SELECT * FROM login WHERE id = '.$id);
		
		if($abc->rowCount() > 0) {
			$reg = $abc->fetch(PDO::FETCH_OBJ);
			
			
			// Tratamento de dados antes de exibir no formulário
			
			if($reg->nivel == 1) {
				$nivel = [' selected', '', '']; // Array para os niveis
			} else if($reg->nivel == 10) {
				$nivel = ['', ' selected', '']; // Array para os niveis
			} else if($reg->nivel == 20) {
				$nivel = ['', '', ' selected']; // Array para os niveis
			}
			
			if($reg->login_data <> '0000-00-00 00:00:00') {
				$login_data = new DateTime($reg->login_data);
				$login_data = $login_data->format('d/m/Y H:i'); // Formata DATA do ultimo login
			} else {
				$login_data = 'USUÁRIO NOVO';
			}
			
			echo <<<DADOS

		<form id="form_usu">
			<div class="row">
				<div class="col-xs-6 col-sm-4">
					<div class="form-group">
						<label for="nome">Nome:</label>
						<input class="form-control" type="text" id="nome" name="nome" maxlength="30" value="$reg->nome" required>
					</div>
					<div class="form-group">
						<label for="sobrenome">Sobrenome:</label>
						<input class="form-control" type="text" id="sobrenome" name="sobrenome" maxlength="30" value="$reg->sobrenome" required>
					</div>
					<div class="form-group">
						<label for="usuario">Usuário:</label>
						<input class="form-control" type="text" id="usuario" name="usuario" maxlength="30" value="$reg->usuario" required>
					</div>
					<div class="form-group">
						<label for="nivel">Nível de acesso</label>
						<select class="form-control" id="nivel" name="nivel">
							<option value="1"$nivel[0]>Solicitante de Hospedagem</option>
							<option value="10"$nivel[1]>Responsável da Hospedagem na Cidade</option>
							<option value="20" $nivel[2]>Administrador do Sistema</option>
						</select>
					</div>
					<div class="form-group">
						<label for="tel_res">Telefone Residencial:</label>
						<input class="form-control" type="text" id="tel_res" name="tel_res" value="$reg->tel_res" maxlength="15">
					</div>
					<div class="form-group">
						<label for="tel_cel">Telefone Celular:</label>
						<input class="form-control" type="text" id="tel_cel" name="tel_cel" value="$reg->tel_cel" maxlength="15">
					</div>
					<div class="form-group">
						<label for="email">E-mail:</label>
						<input class="form-control" type="text" id="email" name="email" value="$reg->email" maxlength="40">
					</div>
				</div>
				<div class="col-xs-6 col-sm-4">
					<h5><span class="glyphicon glyphicon-cog"></span> <strong>DEMAIS OPERAÇÕES</strong></h5><hr>
					<div class="row">
						<div class="col-xs-12">
							<div class="checkbox">
								<label><input type="checkbox" name="reset_senha" value="Y">Resetar senha para o padrão do sistema (<strong><i class="bg-danger">123</i></strong>)?</label>
							</div>
							<div class="checkbox">
								<label><input type="checkbox" name="reset_tentativas" value="Y">Zerar tentativas de login?</label>
							</div>
							
							
						</div>
					</div>
					
					<br><br>
					<h5><span class="glyphicon glyphicon-info-sign"></span> <strong>INFORMAÇÕES</strong></h5><hr>
					<div class="row" style="margin-bottom: 15px;">
						<div class="col-xs-6">
							<button type="button" class="btn btn-info btn-block">Tentativas: <span class="badge">$reg->tentativas</span></button>
						</div>
						<div class="col-xs-6">
							<button type="button" class="btn btn-info btn-block">Acessos: <span class="badge">$reg->login_qtd</span></button>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<label>Último login:</label>
							<input type="text" class="form-control" value="$login_data" disabled>
						</div>
					</div>
				</div>
				<div class="col-xs-6 col-sm-4">
					<h5><span class="glyphicon glyphicon-check"></span> <strong>BOTÕES</strong></h5>
					<input type="hidden" name="usu_id" value="$reg->id">
					<button type="button" class="btn btn-success" onclick="usuSalva()"><span class="glyphicon glyphicon-save"></span> Salvar</button>
					<button type="reset" class="btn btn-warning"><span class="glyphicon glyphicon-refresh"></span> Resetar</button>
				</div>
			</div>
		</form>
DADOS;

			exit();
		} else {
			exit('ERRO 404: Usuário não encontrado. Contate administrador.');
		}
	}
}

function adm_usuSalva() {
	if(!isset($_POST['nome']) || $_POST['nome'] == '' || !isset($_POST['sobrenome']) || $_POST['sobrenome'] == '' || !isset($_POST['usuario']) || $_POST['usuario'] == '' || !isset($_POST['id']) || $_POST['id'] == '') {
		exit('Houve um erro. O mínimo de informações exigido não foi enviado.');
	}
	
	include('conecta.php');
	
	$query = 'UPDATE login SET ';
	
	// NOME
	$query .= 'nome = "'.addslashes($_POST['nome']).'", ';
	// SOBRENOME
	$query .= 'sobrenome = "'.addslashes($_POST['sobrenome']).'", ';
	// USUARIO
	$query .= 'usuario = "'.$_POST['usuario'].'", ';
	// NIVEL
	$query .= 'nivel = '.$_POST['nivel'].', ';
	// TELEFONE RESIDENCIAL
	$query .= 'tel_res = "'.$_POST['tel_res'].'", ';
	// TELEFONE CELULAR
	$query .= 'tel_cel = "'.$_POST['tel_cel'].'", ';
	// EMAIL
	$query .= 'email = "'.$_POST['email'].'", ';
	
	if($_POST['reset_senha'] == 'Y') {
		// RESETA SENHA
		$query .= 'senha = "'.hash("sha256", "123").'", ';
	}
	if($_POST['reset_tentativas'] == 'Y') {
		// RESETA TENTATIVAS
		$query .= 'tentativas = 0, ';
	}
	
	$query = substr($query, 0, -2);
	
	$query .= ' WHERE id = '.$_POST['id'];
	
	$abc = $pdo->query($query);
	if($abc->rowCount() > 0) {
		echo <<<DADOS

		<div class="alert alert-success">
			<strong>Sucesso!</strong> Informações atualizadas com sucesso...
		</div>
DADOS;
	exit();
	} else {
		echo <<<DADOS
		
		<div class="alert alert-info">
			<strong>INFORMAÇÃO:</strong> Nada foi alterado no nosso banco de dados.
		</div>
DADOS;
	exit();
	}
	
}

function atend_Carrega($id, $usuario) {
	if($id == 0 || $id == '' || $usuario == '') {
		exit('Erros nos parâmetros de busca... Tente novamente.');
	}
	
	include('conecta.php');
	// Busca pedido de hospedagem no banco de dados
	$abc = $pdo->prepare('SELECT peh.*, cidade.cidade AS congregacao_cidade, cidade.estado FROM peh LEFT JOIN cidade ON peh.congregacao_cidade_id = cidade.id WHERE peh.id = :id');
	$abc->bindValue(":id", $id, PDO::PARAM_INT);
	$abc->execute();
	
	if($abc->rowCount() > 0) {
		$reg = $abc->fetch(PDO::FETCH_OBJ);
		
		echo <<<DADOS

						<h4 class="text-center"><strong>INFORMAÇÕES</strong></h4>
						<table class="table table-bordered table-responsive">
							<tbody>
								<tr>
									<td><strong>Pedido nº:</strong> $reg->id</td>
									<td><strong>Congregação:</strong> $reg->congregacao</td>
									<td><strong>Cidade:</strong> $reg->congregacao_cidade/$reg->estado</td>
								</tr>
								<tr>
									<td colspan="3">
										<strong>Ocupantes:</strong>
									</td>
								</tr>
DADOS;

		
		if($reg->oc1_nome <> '') { // OCUPANTE 1
			echo <<<DADOS
			
								<tr>
									<td>$reg->oc1_nome</td>
									<td><strong>Idade:</strong> $reg->oc1_idade</td>
									<td><strong>Privilégio:</strong> $reg->oc1_privilegio</td>
								</tr>
DADOS;
		}
		if($reg->oc2_nome <> '') { // OCUPANTE 2
			echo <<<DADOS
			
								<tr>
									<td>$reg->oc2_nome</td>
									<td><strong>Idade:</strong> $reg->oc2_idade</td>
									<td><strong>Privilégio:</strong> $reg->oc2_privilegio</td>
								</tr>
DADOS;
		}
		if($reg->oc3_nome <> '') { // OCUPANTE 3
			echo <<<DADOS
			
								<tr>
									<td>$reg->oc3_nome</td>
									<td><strong>Idade:</strong> $reg->oc3_idade</td>
									<td><strong>Privilégio:</strong> $reg->oc3_privilegio</td>
								</tr>
DADOS;
		}
		if($reg->oc4_nome <> '') { // OCUPANTE 4
			echo <<<DADOS
			
								<tr>
									<td>$reg->oc4_nome</td>
									<td><strong>Idade:</strong> $reg->oc4_idade</td>
									<td><strong>Privilégio:</strong> $reg->oc4_privilegio</td>
								</tr>
DADOS;
		}
		
		

		echo <<<DADOS

							</tbody>
						</table>
						<br>
DADOS;

		
		// Verifica se já foi atendido por uma acomodação
		if($reg->fac_id != 0) { // PEDIDO ATENDIDO
			$def = $pdo->query('SELECT fac.*, cidade.cidade, cidade.estado FROM fac LEFT JOIN cidade ON fac.cidade = cidade.id WHERE fac.id = '.$reg->fac_id);
			
			if($def->rowCount() > 0) {
				$lin = $def->fetch(PDO::FETCH_OBJ);
				
				// Trata telefone do hospedeiro
				if($lin->telefone <> '') {
					$tel1 = $lin->telefone;
					$tel1 = '('.substr($tel1, 0,2).') '.substr($tel1, 2, -4).'-'.substr($tel1, -4, 4);
				} else {
					$tel1 = '-';
				}
				
				// Trata telefone do publicador que indicou
				if($lin->publicador_tel <> '') {
					$tel2 = $lin->publicador_tel;
					$tel2 = '('.substr($tel2, 0,2).') '.substr($tel2, 2, -4).'-'.substr($tel2, -4, 4);
				} else {
					$tel2 = '-';
				}
				
				// CASA DE TJ
				if($lin->casa_tj == 0) {
					$casa_tj = '<span style="color:#ff0000; margin-left: 10px; font-weight: bold;"><span class="glyphicon glyphicon-remove"></span> NÃO</span>';
				} else {
					$casa_tj = '<span style="color:#33cc33; margin-left: 10px; font-weight: bold;"><span class="glyphicon glyphicon-ok"></span> SIM</span>';
				}
				
				// CAMAS
				$cama_sol = $lin->quarto1_sol_qtd + $lin->quarto2_sol_qtd + $lin->quarto3_sol_qtd + $lin->quarto4_sol_qtd;
				$cama_cas = $lin->quarto1_cas_qtd + $lin->quarto2_cas_qtd + $lin->quarto3_cas_qtd + $lin->quarto4_cas_qtd;
				$camas = $cama_cas + $cama_sol;
				
				if($lin->publicador_nome == '') {
					$pub_nome = '-';
				} else {
					$pub_nome = $lin->publicador_nome;
				}
				
				
				echo <<<DADOS
				
						<table class="table table-bordered table-responsive">
							<tbody>
								<tr>
									<td colspan="3"><strong>Acomodação nº:</strong> $lin->id</td>
								</tr>
								<tr>
									<td><strong>Hospedeiro:</strong> $lin->nome</td>
									<td><strong>Endereço:</strong> $lin->endereco - $lin->cidade/$lin->estado</td>
									<td><strong>Telefone:</strong> $tel1</td>
								</tr>
								<tr>
									<td><strong>Qtd. de camas?</strong> <span class="badge" style="font-size:14px; margin-left:10px;">$camas</span></td>
									<td><strong>Lar de Testemunha de Jeová?</strong> $casa_tj</td>
									<td><strong>Condição do quarto:</strong><br> <span style="color:#33cc33; font-weight: bold; text-transform:uppercase;"><span class="glyphicon glyphicon-ok"></span> $lin->condicao</span></td>
								</tr>
								<tr>
									<td colspan="2"><strong>Publicador que indicou:</strong> $pub_nome</td>
									<td><strong>Telefone:</strong> $tel2</td>
								</tr>
							</tbody>
						</table>
DADOS;
			} else { // PEDIDO NÃO ENCONTRADO
				echo <<<DADOS
				
						<table class="table table-bordered table-responsive">
							<tbody>
								<tr>
									<td colspan="3"><strong>Acomodação nº:</strong> <span class="label label-danger" style="font-size:16px"> PEDIDO NÃO ENCONTRADO </span></td>
								</tr>
							</tbody>
						</table>
DADOS;
			}
			
			
		} else { // PEDIDO ABERTO!
			echo <<<DADOS
			
						<table class="table table-bordered table-responsive">
							<tbody>
								<tr>
									<td colspan="3"><strong>Acomodação nº:</strong> <span class="label label-warning" style="font-size:16px"> EM ABERTO </span></td>
								</tr>
							</tbody>
						</table>
DADOS;
		}
		
		// RESPONSÁVEL PELA ACOMODAÇÃO
		
		// Procura cidade no banco de dados
		$def = $pdo->query('SELECT cidade.*, login.nome, login.sobrenome, login.tel_res, login.tel_cel, login.email FROM cidade LEFT JOIN login ON cidade.resp_id = login.id WHERE cidade.id = '.$reg->congregacao_cidade_id);
		$lin = $def->fetch(PDO::FETCH_OBJ);
		
		if($lin->tel_res != '' || $lin->tel_cel != '') {
			// Trata telefone do hospedeiro
			if($lin->tel_res <> '') {
				$tel1 = $lin->tel_res;
				$tel1 = '('.substr($tel1, 0,2).') '.substr($tel1, 2, -4).'-'.substr($tel1, -4, 4);
			} else {
				$tel1 = '';
			}
			
			// Trata telefone do publicador que indicou
			if($lin->tel_cel <> '') {
				$tel2 = $lin->tel_cel;
				$tel2 = '('.substr($tel2, 0,2).') '.substr($tel2, 2, -4).'-'.substr($tel2, -4, 4);
			} else {
				$tel2 = '';
			}
			
			if($tel1 != '' && $tel2 != '') {
				$telefone = $tel1.'; '.$tel2;
			} else {
				$telefone = $tel1.$tel2;
			}
			
		} else {
			$telefone = '-';
		}
		
		/*
		 * LINK PARA ACESSO EXTERNO!
		 * 
		 * VALIDATION = Código criptografado em MD5. O código é: 'hospedagem' + codigo do pedido de hospedagem (enviado no TARGET) + 'ls3'.
		 * TARGET = Número do pedido de hospedagem em encriptação BASE64.
		 * 
		 * Em QR Codes, o link vai ficar um pouco diferente. O padrão é: TARGET + 'qrcode' + VALIDATION.
		 * Isso permite que várias variáveis viajem juntas na mesma QueryString.
		 */
		$validation = md5('hospedagem'.$reg->id.'ls3');
		$target = base64_encode($reg->id);
		// LINK NORMAL
		$link = 'http://'.$_SERVER['HTTP_HOST'].'/moreinfo.php?validation='.$validation.'&target='.$target;
		// LINK QRCODE
		$link_qr = 'http://'.$_SERVER['HTTP_HOST'].'/moreinfo.php?validation='.$target.'-qrcode-'.$validation;
		$link_qr = str_replace("&", "&amp;", $link_qr);
		echo <<<DADOS

						<table class="table table-bordered table-responsive">
							<tbody>
								<tr>
									<td colspan="3" class="text-center"><strong>RESPONSÁVEL PELA ACOMODAÇÃO</strong></td>
								</tr>
								<tr>
									<td><strong>Nome:</strong> $lin->nome $lin->sobrenome</td>
									<td><strong>Telefone(s):</strong> $telefone</td>
									<td><strong>E-mail:</strong> $lin->email</td>
								</tr>
							</tbody>
						</table>
						<table class="table table-bordered table-responsive">
							<tbody>
								<tr>
									<td style="vertical-align: middle;"><strong>Compartilhar:</strong> <a href="$link" target="_blank">$link</a></td>
									<td> <a data-toggle="modal" data-target="#Modal1"><img class="img-responsive" src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=$link_qr"></a> </td>
								</tr>
							</tbody>
						</table>

						<div id="Modal1" class="modal fade" role="dialog">
							<div class="modal-dialog modal-lg">
								<!-- CONTEUDO DO MODAL -->
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal">&times;</button>
										<h4 class="modal-title"><strong><span class="glyphicon glyphicon-qrcode"></span> QRCODE AMPLIADO</strong></h4>
									</div>
									<div class="modal-body" id="qr_modal" style="text-align:center">
										<img class="img-responsive" src="https://api.qrserver.com/v1/create-qr-code/?size=400x400&data=$link_qr" style="margin:0 auto">
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
									</div>
								</div>
							</div>
						</div>
DADOS;
		exit();
	} else {
		exit('Nada encontrado!');
	}
	
	

}

function adm_apagaCidade($id) {
	if($id > 0) {
		include('conecta.php');
		
		$abc = $pdo->prepare('DELETE FROM `cidade` WHERE `cidade`.`id` = :id');
		$abc->bindValue(":id", $id, PDO::PARAM_INT);
		$abc->execute();
		
		exit('OK');
	} else {
		echo 'UM ERRO OCORREU: código da cidade inválido. CONTATE DESENVOLVEDOR.';
		exit();
	}
}

function adm_reseta($arr) {
    if($_SESSION['nivel'] == 20) {
        if($arr == '' || !isset($arr)) {
            
        } else {
            $opcoes = explode(';', substr($arr, 0, -1));
            $msg = '';
            include('conecta.php');
            foreach($opcoes as $item) {
                switch($item) {
                    case 'DESV': // Desvincular PEH de FAC
                        $msg .= '<strong>Desvinculando: </strong> ';
                        try{
                            $abc = $pdo->query('UPDATE peh SET fac_id = 0 WHERE fac_id <> 0');
                            $msg .= 'OK<br>';
                        } catch (PDOException $e) {
                            $msg .= 'Erro: <i>'.$e->getMessage().'</i><br>';
                        }
                        break;
                        ########################################################################
                    case 'PEH_REV': // Revisar todos PEH
                        $msg .= '<strong>Revisando PEH: </strong> ';
                        try{
                            $abc = $pdo->query('UPDATE peh SET revisar = 1 WHERE revisar = 0');
                            $msg .= 'OK<br>';
                        } catch (PDOException $e) {
                            $msg .= 'Erro: <i>'.$e->getMessage().'</i><br>';
                        }
                        break;
                        ########################################################################
                    case 'FAC_REV': // Revisar todos FAC
                        $msg .= '<strong>Revisando FAC: </strong> ';
                        try{
                            $abc = $pdo->query('UPDATE fac SET revisar = 1 WHERE revisar = 0');
                            $msg .= 'OK<br>';
                        } catch (PDOException $e) {
                            $msg .= 'Erro: <i>'.$e->getMessage().'</i><br>';
                        }
                        break;
                        ########################################################################
                    case 'DATA_DEL':
                        $msg .= '<strong>Removendo DATAS: </strong> ';
                        try{
                            $abc = $pdo->query('UPDATE peh SET check_in = "0000-00-00", check_out = "0000-00-00" WHERE 1');
                            $msg .= 'OK<br>';
                        } catch (PDOException $e) {
                            $msg .= 'Erro: <i>'.$e->getMessage().'</i><br>';
                        }
                        break;
                        ########################################################################
                    case 'CIDADE_DEL':
                        $msg .= '<strong>Removendo CIDADE do CONGRESSO: </strong> ';
                        try{
                            $abc = $pdo->query('UPDATE peh SET congresso_cidade = "" WHERE congresso_cidade <> ""');
                            $msg .= 'OK<br>';
                        } catch (PDOException $e) {
                            $msg .= 'Erro: <i>'.$e->getMessage().'</i><br>';
                        }
                        break;
                        ########################################################################
                    default:
                        break;
                }
            }
            
            echo <<<DADOS
<div class="alert alert-info">
    $msg
    <strong>CONCLUÍDO!</strong>
    <hr>
    <small>Em caso de erro, tire um print da tela e comunique ao desenvolvedor.</small>
</div>
DADOS;
        }
    } else {
        echo 'Operação não permitida';
    }
}

/*
 * ###################################################### PRINT
 */
function print_listaForms($formtipo, $formqtd) {
	$impressao = new Impressao();
	$impressao->listaForms($formtipo, $formqtd);
}

function print_imprimeForms($formtipo, $formqtd, $form3) {
	$impressao = new Impressao();
	$impressao->imprimeForms($formtipo, $formqtd, $form3);
}


switch($_REQUEST['funcao']) {
		
	case 'pehConsulta':
		pehConsulta($_REQUEST['id'], $_REQUEST['links']);
		break;
		
	case 'facConsulta':
		facConsulta($_REQUEST['id'], $_REQUEST['links']);
		break;
		
	case 'revisaForms':
	    revisaForms($_POST['tipo'], $_POST['fid']);
	    break;
		
	case 'apagaPEH':
		apagaPEH($_REQUEST['fid'], $_REQUEST['token']);
		break;
		
	case 'apagaFAC':
		apagaFAC($_REQUEST['fid'], $_REQUEST['token']);
		break;
		
	case 'adm_usuCarrega':
		adm_usuCarrega($_POST['id']);
		break;
		
	case 'adm_usuSalva':
		adm_usuSalva();
		break;
		
	case 'atend_Carrega':
		atend_Carrega($_POST['id'], $_POST['usuario']);
		break;
		
	case 'adm_apagaCidade':
		adm_apagaCidade($_POST['id']);
		break;
		
	case 'adm_reseta':
	    adm_reseta($_POST['arr']);
	    break;
		
		
	case 'print_listaForms':
		print_listaForms($_POST['formtipo'], $_POST['formqtd']);
		break;
		
	case 'print_imprimeForms':
		print_imprimeForms($_POST['formtipo'], $_POST['formqtd'], $_POST['form3']);
		break;
		
	default:
		echo 'Erro 404: Not found';
		break;
}