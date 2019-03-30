<?php

class Impressao
{
	/* ############# VARIAVEIS */
	protected $pdo = '';
	
	
	/* ############# METODOS */
	public function __construct()
	{
		if(file_exists('app/conecta.php') == TRUE) {
			include('app/conecta.php');
		} else if(file_exists('conecta.php') == TRUE) {
			include('conecta.php');
		}
		
		$this->pdo = $pdo;
	}
	
	public function listaForms($formtipo, $formqtd, $form3 = '')
	{
		/*
		 * FORMTIPO = Tipo do formulário: Hospedagem ou Acomodação
		 * 
		 * FORMQTD  = Quantidade: Individual, Por cidade, Todos que preenchi, TUDO....
		 * 
		 * FORM3    = Variável de uso generico.
		 * 
		 */
		
		
		if($formtipo == '' || $formtipo == 0 || $formqtd == '' || $formqtd == 0) {
			exit('Erro interno: Parametros incorretos.');
		}
		
		if($formtipo == 1) {// PEH
			switch($formqtd) {
				case 1: //Individual
					if($_SESSION['nivel'] == 1) {
						$sql = 'SELECT peh.id FROM peh LEFT JOIN cidade ON peh.congregacao_cidade_id = cidade.id WHERE cidade.solicitante_id = '.$_SESSION['id'].' ORDER BY peh.id ASC';
					} else if($_SESSION['nivel'] == 10) {
						$sql = 'SELECT peh.id FROM peh LEFT JOIN cidade ON peh.congregacao_cidade_id = cidade.id WHERE cidade.resp_id = '.$_SESSION['id'].' ORDER BY peh.id ASC';
					} else if($_SESSION['nivel'] == 20) {
						$sql = 'SELECT peh.id FROM peh LEFT JOIN cidade ON peh.congregacao_cidade_id = cidade.id WHERE 1 ORDER BY peh.id ASC';
					}
					break;
					
				case 2: // Cidade
					if($_SESSION['nivel'] == 1) {
						$sql = 'SELECT DISTINCT cidade.id, cidade.cidade, cidade.estado FROM peh LEFT JOIN cidade ON peh.congregacao_cidade_id = cidade.id WHERE cidade.solicitante_id = '.$_SESSION['id'].' ORDER BY cidade.estado ASC, cidade.cidade ASC';
					} else if($_SESSION['nivel'] == 10) {
						$sql = 'SELECT DISTINCT cidade.id, cidade.cidade, cidade.estado FROM peh LEFT JOIN cidade ON peh.congregacao_cidade_id = cidade.id WHERE cidade.resp_id = '.$_SESSION['id'].' ORDER BY cidade.estado ASC, cidade.cidade ASC';
					} else if($_SESSION['nivel'] == 20) {
						$sql = 'SELECT DISTINCT cidade.id, cidade.cidade, cidade.estado FROM peh LEFT JOIN cidade ON peh.congregacao_cidade_id = cidade.id WHERE 1 ORDER BY cidade.estado ASC, cidade.cidade ASC';
					}
					break;
					
				default:
					exit('Erro interno: parâmetros inválidos.');
					break;
					
			}
		} else if($formtipo == 2) { //FAC
			switch($formqtd) {
				case 1: // Individual
					if($_SESSION['nivel'] == 10) {
						$sql = 'SELECT fac.id FROM fac LEFT JOIN cidade ON fac.cidade = cidade.id WHERE cidade.resp_id = '.$_SESSION['id'].' ORDER BY fac.id ASC';
					} else if($_SESSION['nivel'] == 20) {
						$sql = 'SELECT fac.id FROM fac LEFT JOIN cidade ON fac.cidade = cidade.id WHERE 1 ORDER BY fac.id ASC';
					}
					break;
					
				case 2: // Cidade
					if($_SESSION['nivel'] == 10) {
						$sql = 'SELECT DISTINCT cidade.id, cidade.cidade, cidade.estado FROM fac LEFT JOIN cidade ON fac.cidade = cidade.id WHERE cidade.resp_id = '.$_SESSION['id'].' ORDER BY cidade.estado ASC, cidade.cidade ASC';
					} else if($_SESSION['nivel'] == 20) {
						$sql = 'SELECT DISTINCT cidade.id, cidade.cidade, cidade.estado FROM fac LEFT JOIN cidade ON fac.cidade = cidade.id WHERE 1 ORDER BY cidade.estado ASC, cidade.cidade ASC';
					}
					break;
				
				default:
					exit('Em implementação!');
					break;
			}
		}
		
		$abc = $this->pdo->query($sql);
		
		echo <<<DADOS

				<option value="0" disabled="disabled" selected="selected">Escolha:</option>
DADOS;
		if($formqtd == 1 && $formtipo == 1) { // PEH INDIVIDUAL
			while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
				echo <<<DADOS

				<option value="$reg->id">Pedido $reg->id</option>
DADOS;
			}
		} else if($formqtd == 1 && $formtipo == 2){ // FAC INDIVIDUAL
			while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
				echo <<<DADOS
				
				<option value="$reg->id">Acomodação $reg->id</option>
DADOS;
			}
		} else if($formqtd == 2) { // PEH E FAC CIDADE
			while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
				echo <<<DADOS
				
				<option value="$reg->id">$reg->cidade/$reg->estado</option>
DADOS;
			}
		}
	}
	
	public function imprimeForms($formtipo, $formqtd, $form3)
	{
		/*
		 * FORMTIPO = Tipo do formulário: Hospedagem ou Acomodação
		 *
		 * FORMQTD  = Quantidade: Individual, Por cidade, Todos que preenchi, TUDO....
		 *
		 * FORM3    = Variável de uso generico.
		 *
		 */
		
		if($formtipo == '' || $formtipo == 0 || $formqtd == '' || $formqtd == 0) {
			exit('Erro interno: Parametros incorretos.');
		}
		
		// Separa trabalho de impressão por nivel de usuários
		if($_SESSION['nivel'] == 1) { // ##################################### NIVEL 1
			if($formtipo == 1) { // PEH
				// CHECA FILTRO: Individual, Cidade, TUDO;
				if($formqtd == 1) { // Individual
					if($form3 == '' || $form3 == 0) {exit('Erro no ultimo parametro.');}
					
					$sql = 'SELECT peh.*, cidade.cidade AS cong_cidade, cidade.estado AS cong_estado FROM peh LEFT JOIN cidade ON peh.congregacao_cidade_id = cidade.id WHERE peh.id = :id';
					$abc = $this->pdo->prepare($sql);
					$abc->bindValue(":id", $form3, PDO::PARAM_INT);
					$abc->execute();
					
					if($abc->rowCount() > 0) {
						$reg = $abc->fetch(PDO::FETCH_OBJ);
						$this->impressaoPEH($reg);
					} else {
						exit('Não encontrado!');
					}
					
				} else if ($formqtd == 2) { // Cidade
					
					if($form3 == '' || $form3 == 0) {exit('Erro no ultimo parametro.');}
					
					$sql = 'SELECT peh.*, cidade.cidade AS cong_cidade, cidade.estado AS cong_estado FROM peh LEFT JOIN cidade ON peh.congregacao_cidade_id = cidade.id WHERE peh.congregacao_cidade_id = :id';
					$sql .= ' ORDER BY cidade.estado ASC, cidade.cidade ASC, peh.id ASC';
					$abc = $this->pdo->prepare($sql);
					$abc->bindValue(":id", $form3, PDO::PARAM_INT);
					$abc->execute();
					
					if($abc->rowCount() > 0) {
						$y = 0;
						while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
							if($y > 0) {echo '<hr>';}
							
							$this->impressaoPEH($reg);
							$y++;
						}
					} else {
						exit('Não encontrado!');
					}
					
				} else if ($formqtd == 5) { // TUDO
					
					$sql = 'SELECT peh.*, cidade.cidade AS cong_cidade, cidade.estado AS cong_estado FROM peh LEFT JOIN cidade ON peh.congregacao_cidade_id = cidade.id WHERE cidade.solicitante_id = :id';
					$sql .= ' ORDER BY cidade.estado ASC, cidade.cidade ASC, peh.id ASC';
					$abc = $this->pdo->prepare($sql);
					$abc->bindValue(":id", $_SESSION['id'], PDO::PARAM_INT);
					$abc->execute();
					
					if($abc->rowCount() > 0) {
						$y = 0;
						while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
							if($y > 0) {echo '<hr>';}
							
							$this->impressaoPEH($reg);
							$y++;
						}
					} else {
						exit('Não encontrado!');
					}
				}
				
			} else if($formtipo == 2) { // FAC
				exit('Seu nível de acesso não permite impressão desses dados.');
			}
		} else if($_SESSION['nivel'] == 10) { // ############################# NIVEL 10
			if($formtipo == 1) { // PEH
				// CHECA FILTRO: Individual, Cidade, TUDO;
				if($formqtd == 1) { // Individual
					
					if($form3 == '' || $form3 == 0) {exit('Erro no ultimo parametro.');}
					
					$sql = 'SELECT peh.*, cidade.cidade AS cong_cidade, cidade.estado AS cong_estado FROM peh LEFT JOIN cidade ON peh.congregacao_cidade_id = cidade.id WHERE peh.id = :id';
					$abc = $this->pdo->prepare($sql);
					$abc->bindValue(":id", $form3, PDO::PARAM_INT);
					$abc->execute();
					
					if($abc->rowCount() > 0) {
						$reg = $abc->fetch(PDO::FETCH_OBJ);
						$this->impressaoPEH($reg);
					} else {
						exit('Não encontrado!');
					}
				} else if ($formqtd == 2) { // Cidade
					
					if($form3 == '' || $form3 == 0) {exit('Erro no ultimo parametro.');}
					
					$sql = 'SELECT peh.*, cidade.cidade AS cong_cidade, cidade.estado AS cong_estado FROM peh LEFT JOIN cidade ON peh.congregacao_cidade_id = cidade.id WHERE peh.congregacao_cidade_id = :id';
					$sql .= ' ORDER BY cidade.estado ASC, cidade.cidade ASC, peh.id ASC';
					$abc = $this->pdo->prepare($sql);
					$abc->bindValue(":id", $form3, PDO::PARAM_INT);
					$abc->execute();
					
					if($abc->rowCount() > 0) {
						$y = 0;
						while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
							if($y > 0) {echo '<hr>';}
							
							$this->impressaoPEH($reg);
							$y++;
						}
					} else {
						exit('Não encontrado!');
					}
					
				} else if ($formqtd == 5) { // TUDO
					
					$sql = 'SELECT peh.*, cidade.cidade AS cong_cidade, cidade.estado AS cong_estado FROM peh LEFT JOIN cidade ON peh.congregacao_cidade_id = cidade.id WHERE cidade.resp_id = :id';
					$sql .= ' ORDER BY cidade.estado ASC, cidade.cidade ASC, peh.id ASC';
					$abc = $this->pdo->prepare($sql);
					$abc->bindValue(":id", $_SESSION['id'], PDO::PARAM_INT);
					$abc->execute();
					
					if($abc->rowCount() > 0) {
						$y = 0;
						while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
							if($y > 0) {echo '<hr>';}
							
							$this->impressaoPEH($reg);
							$y++;
						}
					} else {
						exit('Não encontrado!');
					}
					
				}
			} else if($formtipo == 2) { // FAC
				// CHECA FILTRO: Individual, Cidade, TUDO;
				if($formqtd == 1) { // Individual
					
				} else if($formqtd == 2) { // Cidade
					
				} else if($formqtd == 5) {
					
				}
			}
		} else if($_SESSION['nivel'] == 20) { // ############################# NIVEL 20
			if($formtipo == 1) { // PEH
				// CHECA FILTRO: Individual, Cidade, TUDO;
				if($formqtd == 1) { // Individual
					
					if($form3 == '' || $form3 == 0) {exit('Erro no ultimo parametro.');}
					
					$sql = 'SELECT peh.*, cidade.cidade AS cong_cidade, cidade.estado AS cong_estado FROM peh LEFT JOIN cidade ON peh.congregacao_cidade_id = cidade.id WHERE peh.id = :id';
					$abc = $this->pdo->prepare($sql);
					$abc->bindValue(":id", $form3, PDO::PARAM_INT);
					$abc->execute();
					
					if($abc->rowCount() > 0) {
						$reg = $abc->fetch(PDO::FETCH_OBJ);
						$this->impressaoPEH($reg);
					} else {
						exit('Não encontrado!');
					}
				} else if ($formqtd == 2) { // Cidade
					
					if($form3 == '' || $form3 == 0) {exit('Erro no ultimo parametro.');}
					
					$sql = 'SELECT peh.*, cidade.cidade AS cong_cidade, cidade.estado AS cong_estado FROM peh LEFT JOIN cidade ON peh.congregacao_cidade_id = cidade.id WHERE peh.congregacao_cidade_id = :id';
					$sql .= ' ORDER BY cidade.estado ASC, cidade.cidade ASC, peh.id ASC';
					$abc = $this->pdo->prepare($sql);
					$abc->bindValue(":id", $form3, PDO::PARAM_INT);
					$abc->execute();
					
					if($abc->rowCount() > 0) {
						$y = 0;
						while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
							if($y > 0) {echo '<hr>';}
							
							$this->impressaoPEH($reg);
							$y++;
						}
					} else {
						exit('Não encontrado!');
					}
				} else if ($formqtd == 5) { // TUDO
					
					$sql = 'SELECT peh.*, cidade.cidade AS cong_cidade, cidade.estado AS cong_estado FROM peh LEFT JOIN cidade ON peh.congregacao_cidade_id = cidade.id WHERE 1';
					$sql .= ' ORDER BY cidade.estado ASC, cidade.cidade ASC, peh.id ASC';
					$abc = $this->pdo->query($sql);
					
					if($abc->rowCount() > 0) {
						$y = 0;
						while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
							if($y > 0) {echo '<hr>';}
							
							$this->impressaoPEH($reg);
							$y++;
						}
					} else {
						exit('Não encontrado!');
					}
				}
			} else if($formtipo == 2) { // FAC
				// CHECA FILTRO: Individual, Cidade, TUDO;
				if($formqtd == 1) { // Individual
					if($form3 == '' || $form3 == 0) {exit('Erro no ultimo parametro.');}
					
					$sql = 'SELECT fac.*, cidade.cidade AS cidade_nome, cidade.estado FROM fac LEFT JOIN cidade ON fac.cidade = cidade.id WHERE fac.id = :id';
					$abc = $this->pdo->prepare($sql);
					$abc->bindValue(":id", $form3, PDO::PARAM_INT);
					$abc->execute();
					
					if($abc->rowCount() > 0) {
						$reg = $abc->fetch(PDO::FETCH_OBJ);
						$this->impressaoFAC($reg);
					}
					
				} else if($formqtd == 2) { // Cidade
					if($form3 == '' || $form3 == 0) {exit('Erro no ultimo parametro.');}if($form3 == '' || $form3 == 0) {exit('Erro no ultimo parametro.');}
					
					$sql = 'SELECT fac.*, cidade.cidade AS cidade_nome, cidade.estado FROM fac LEFT JOIN cidade ON fac.cidade = cidade.id WHERE cidade.id = :id ORDER BY fac.id ASC';
					$abc = $this->pdo->prepare($sql);
					$abc->bindValue(":id", $form3, PDO::PARAM_INT);
					$abc->execute();
					
					if($abc->rowCount() > 0) {
						$y = 0;
						while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
							if($y > 0) {echo '<hr>';}
							
							$this->impressaoFAC($reg);
							$y++;
						}
					}
						
				} else if($formqtd == 5) {
					
					$sql = 'SELECT fac.*, cidade.cidade AS cidade_nome, cidade.estado FROM fac LEFT JOIN cidade ON fac.cidade = cidade.id WHERE 1 ORDER BY cidade.estado ASC, cidade.cidade ASC, fac.id ASC';
					$abc = $this->pdo->query($sql);
					
					if($abc->rowCount() > 0) {
						$y = 0;
						while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
							if($y > 0) {echo '<hr>';}
							
							$this->impressaoFAC($reg);
							$y++;
						}
					}
					
				}
			}
		}
	}
	
	
	
	
	/*
	 * #########################################################################
	 *                            MÉTODOS PRIVATE
	 * #########################################################################
	 */
	private function impressaoFAC($reg)
	{
	
		echo <<<CSS
		
<style>
@page {
	size: A4;
	margin:0;
}

@media print {
	html, body {
		width: 210mm;
		height: 297mm;
		margin: 0;
		font-size: 12px;
	}
	div.page {
		width: 210mm;
		height:290mm;
		box-sizing: border-box;
		padding:7mm 5mm 7mm 10mm;
	}
	div.noprint {
		display:none;
	}
	dl {
	
		margin-bottom:0px;
	}
	dd {
		padding-left:10px;
	}
	h5 {
		text-align:left;
	}
	h4, h5 {
		margin: 0;
		margin-bottom: 7px;
	}
	table {
		font-size:12px;
	}
	.table {
		margin-bottom: 5px;
	}
	.tabela-margin {
		margin-bottom: 10px;
	}
}
.tabela-margin {
		margin-bottom: 10px;
}
.quartos {
	text-align: center;
}
</style>

<div class="noprint text-center" style="background: #eee; padding: 10px 0 0; box-sizing:border-box;">
	<h4><strong> Página gerada para impressão</strong></h4>
	<h5> Clique no botão abaixo para imprimir ou <i>Salvar em PDF</i>.</h5>
		<button class="btn btn-success" onclick="window.print()"><span class="glyphicon glyphicon-print"></span> Imprimir</button>
	<hr style="border: 1px #555 dashed">
</div>

CSS;
		
		echo <<<DADOS
		<div class="page">
			<h4 class="text-center"><strong>FORMULÁRIO DE ACOMODAÇÃO <small>(Nº.: $reg->id)</small></strong></h4><br>

DADOS;
		
		$x = 0;
		for($x=1; $x <= 4; $x++) {
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
					<div class="col-xs-12">
						<table class="table table-bordered table-condensed tabela-margin">
							<tbody class="quartos">
								<tr>
									<th rowspan="3" class="text-center" style="vertical-align:middle;">
										QUARTO $x
									</th>
									<th rowspan="2" class="text-center" style="vertical-align:middle;">Camas Solteiro</th>
									<th rowspan="2" class="text-center" style="vertical-align:middle;">Camas Casal</th>
									<th colspan="2" class="text-center" style="font-size: 12px;">Preço do quarto por dia</th>
								</tr>
								<tr>
									<th class="text-center" style="font-size: 12px;">Uma pessoa</th>
									<th class="text-center" style="font-size: 12px;">Duas ou mais</th>
								</tr>
								<tr>
									<td>$var1</td>
									<td>$var2</td>
									<td>R$ $var3</td>
									<td>R$ $var4</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				
				
DADOS;
			/*
			echo <<<DADOS
				
				<div class="row" class="quartos">
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
*/
		}
		
		
		
		echo <<<DADOS
			<table class="table table-condensed table-bordered">
				<tbody>
					<tr>
						<td colspan="3">
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
					
						</td>
					</tr>
					<tr>
						<td>
							<dl>
								<dt>Em que andar ficam os quartos?</dt>
								<dd>$andar</dd>
							</dl>
						</td>
						<td>
							<dl>
								<dt>Poderá prover condução?</dt>
								<dd>$transporte</dd>
							</dl>
						</td>
						<td>
							<dl>
								<dt>É o lar de Testemunhas de Jeová?</dt>
								<dd>$casa_tj</dd>
							</dl>
						</td>
					</tr>
					<tr>
						<td colspan="3">
							<dl>
								<dt>Observações:</dt>
								<dd>$reg->obs1</dd>
							</dl>
						</td>
					</tr>
				</tbody>
			</table>
					<hr>
DADOS;
		
		
		
		echo <<<DADOS
			<table class="table table-condensed table-bordered">
			<thead>
				<tr>
					<th colspan="2" class="text-center" style="width:50%; vertical-align: middle;">ENDEREÇO DO HOSPEDEIRO</th>
					<th class="text-center" style="width:50%; vertical-align: middle;">PUBLICADOR QUE INDICOU<br>
					<small style="font-size:75%; color: #777">(Publicador que conseguiu a hospedagem,<br>se não for o hospedeiro)</small></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<dl>
							<dt>Nome:</dt>
							<dd>$reg->nome</dd>
						</dl>
					</td>
					<td>
						<dl>
							<dt>Endereço:</dt>
							<dd>$reg->endereco</dd>
						</dl>
					</td>
					
					<td>
						<dl>
							<dt>Publicador:</dt>
							<dd>$reg->publicador_nome</dd>
						</dl>
					</td>
				</tr>
				<tr>
					<td>
						<dl>
							<dt>Telefone:</dt>
							<dd>$reg->telefone</dd>
						</dl>
					</td>
					<td>
						<dl>
							<dt>Cidade:</dt>
							<dd>$reg->cidade_nome/$reg->estado</dd>
						</dl>
					</td>
					
					<td>
						<dl>
							<dt>Telefone:</dt>
							<dd>$reg->publicador_tel</dd>
						</dl>
					</td>
				</tr>
			</tbody>
		</table>
			<hr>
									
DADOS;
		
		$abc = $this->pdo->query('SELECT cidade.cidade, cidade.estado FROM cidade WHERE cidade.id = '.$reg->cong_cidade);
		$lin = $abc->fetch(PDO::FETCH_OBJ);
		
		$exc = '';
		$boa = '';
		$raz = '';
		switch($reg->condicao) {
			case 'excelente': $exc = 'checked'; break;
			case 'boa': $boa = 'checked'; break;
			case 'razoavel': $raz = 'checked';break;
		}
		
		?>
		
		
		<?php
		echo <<<DADOS
			<table class="table table-condensed">
			<thead>
				<tr>
					<th colspan="3">SECRETÁRIO DA CONGREGAÇÃO</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
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
					</td>
					<td>
						<label>Condição do(s) quarto(s):</label><br>
						<label class="radio-inline"><input type="radio" name="condicao" disabled $exc>Excelente</label><br>
						<label class="radio-inline"><input type="radio" name="condicao" disabled $boa>Boa</label><br>
						<label class="radio-inline"><input type="radio" name="condicao" disabled $raz>Razoável</label>
						
					</td>
					<td>
						<dl>
							<dt>Observações:</dt>
							<dd>$reg->obs2</dd>
						</dl>
					</td>
				</tr>
			</tbody>
		</table>
		</div>
DADOS;
		
		
	
	}
		
	
	private function impressaoPEH($reg)
	{
		//var_dump($reg);
		echo <<<CSS

<style>
@page {
	size: A4;
	margin:0;
}

@media print {
	html, body {
		width: 210mm;
		height: 297mm;
		margin: 0;
	}
	div.page {
		width: 210mm;
		height:290mm;
		box-sizing: border-box;
		padding:7mm 5mm 7mm 10mm;
	}
	div.noprint {
		display:none;
	}
	dl {
		
		margin-bottom:8px;
	}
	dd {
		padding-left:10px;
	}
}

</style>


<div class="noprint text-center" style="background: #eee; padding: 10px 0 0; box-sizing:border-box;">
	<h4><strong> Página gerada para impressão</strong></h4>
	<h5> Clique no botão abaixo para imprimir ou <i>Salvar em PDF</i>.</h5>
		<button class="btn btn-success" onclick="window.print()"><span class="glyphicon glyphicon-print"></span> Imprimir</button>
	<hr style="border: 1px #555 dashed">
</div>

CSS;
		
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

				<div class="page">
					<h4 class="text-center"><strong>PEDIDO ESPECIAL DE HOSPEDAGEM <small>(Nº.: $reg->id)</small></strong></h4><br>
		
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
							<table class="table table-hover table-condensed table-striped" class="tabela-ocupante">
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
									<tr><td style="font-size: 12px">
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
				</div><!-- ./DIV.PAGE -->
HTML;
	}
	
}