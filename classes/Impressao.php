<?php

class Impressao
{
	/* ############# VARIAVEIS */
	private $pdo = '';
	protected $css_peh =<<<DADOS



<style>
@import url('https://fonts.googleapis.com/css?family=Roboto+Slab');
@page {
	size: A4;
	margin:0;
}
@media screen {
    div.page {
        padding: 10px;
    }
}

@media print {
	html, body {
		width: 210mm;
		height: 297mm;
		margin: 0;
        padding:0;
	}
	div.page {
		width: 210mm;
		height:297mm;
        max-width: 210mm;
		max-height:298mm;
		box-sizing: border-box;
		padding:10mm 7mm 10mm 12mm;
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

DADOS;
	protected $css_fac =<<<DADOS

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
        padding:0;
	}
	div.page {
		width: 210mm;
		height:297mm;
        max-width: 210mm;
		max-height:298mm;
		box-sizing: border-box;
		padding:10mm 7mm 10mm 12mm;
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
DADOS;
	
	/* ############# METODOS */
	public function __construct()
	{
		
		include('conecta.php');
		$this->db_user = $db_username;
		$this->db_senha = $db_pass;
		$this->db_name = $db_name;
		$this->db_host = $db_host;
		$this->PDO();
		$this->gmtTimeZone = new DateTimeZone('America/Bahia');
		
		if(!isset($_SESSION)) {
		    session_start();
		}
	}
	
	private function PDO()
	{
	    // Conexão via PDO
	    $this->pdo = new PDO ( "mysql:host=".$this->db_host.";dbname=".$this->db_name, $this->db_user, $this->db_senha );
	    if (!$this->pdo) {
	        die ( 'Erro ao criar a conexão' );
	    }
	    
	    $this->pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
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
					    // Escreve FOLHA DE ESTILO da página PEH
					    echo $this->css_peh;
					    
						$reg = $abc->fetch(PDO::FETCH_OBJ);
						echo $this->impressaoPEH($reg);
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
						
						// Escreve FOLHA DE ESTILO da página PEH
						echo $this->css_peh;
						
						while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
							
							echo $this->impressaoPEH($reg);
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
						
						// Escreve FOLHA DE ESTILO da página PEH
						echo $this->css_peh;
						
						while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
							echo $this->impressaoPEH($reg);
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
					    // Escreve FOLHA DE ESTILO da página PEH
					    echo $this->css_peh;
					    
						$reg = $abc->fetch(PDO::FETCH_OBJ);
						echo $this->impressaoPEH($reg);
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
						
						// Escreve FOLHA DE ESTILO da página PEH
						echo $this->css_peh;
						
						while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
							
							echo $this->impressaoPEH($reg);
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
						
						// Escreve FOLHA DE ESTILO da página PEH
						echo $this->css_peh;
						
						while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
							
							echo $this->impressaoPEH($reg);
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
					    // Escreve FOLHA DE ESTILO da página PEH
					    echo $this->css_peh;
					    
						$reg = $abc->fetch(PDO::FETCH_OBJ);
						echo $this->impressaoPEH($reg);
						
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
						
						// Escreve FOLHA DE ESTILO da página PEH
						echo $this->css_peh;
						
						while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
							
							echo $this->impressaoPEH($reg);
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
						
						// Escreve FOLHA DE ESTILO da página PEH
						echo $this->css_peh;
						
						while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
							
							echo $this->impressaoPEH($reg);
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
					    // Escreve FOLHA DE ESTILO da página PEH
					    echo $this->css_fac;
					    
						$reg = $abc->fetch(PDO::FETCH_OBJ);
						echo $this->impressaoFAC($reg);
					}
					
				} else if($formqtd == 2) { // Cidade
					if($form3 == '' || $form3 == 0) {exit('Erro no ultimo parametro.');}if($form3 == '' || $form3 == 0) {exit('Erro no ultimo parametro.');}
					
					$sql = 'SELECT fac.*, cidade.cidade AS cidade_nome, cidade.estado FROM fac LEFT JOIN cidade ON fac.cidade = cidade.id WHERE cidade.id = :id ORDER BY fac.id ASC';
					$abc = $this->pdo->prepare($sql);
					$abc->bindValue(":id", $form3, PDO::PARAM_INT);
					$abc->execute();
					
					if($abc->rowCount() > 0) {
					    $y = 0;
					    
					    // Escreve FOLHA DE ESTILO da página PEH
					    echo $this->css_fac;
					    
						while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
							echo $this->impressaoFAC($reg);
							$y++;
						}
					}
						
				} else if($formqtd == 5) {
					
					$sql = 'SELECT fac.*, cidade.cidade AS cidade_nome, cidade.estado FROM fac LEFT JOIN cidade ON fac.cidade = cidade.id WHERE 1 ORDER BY cidade.estado ASC, cidade.cidade ASC, fac.id ASC';
					$abc = $this->pdo->query($sql);
					
					if($abc->rowCount() > 0) {
						$y = 0;
						
						// Escreve FOLHA DE ESTILO da página PEH
						echo $this->css_fac;
						
						while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
							echo $this->impressaoFAC($reg);
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
		$def = $this->pdo->query('SELECT * FROM fac WHERE id = '.$reg->id);
		$lin = $def->fetch(PDO::FETCH_ASSOC);
		$html = '';
		
		//Trata dados
		$x = 1;
		$quarto = '';
		
		/*
		while($x <= $reg->quartos_qtd) {
		    if($lin['quarto'.$x.'_sol_qtd'] != '' || $lin['quarto'.$x.'_cas_qtd'] != '') {
		        $c_s = '<h5><span class="badge badge-info">'.$lin['quarto'.$x.'_sol_qtd'].'</span></h5>'; // CAMAS DE SOLTEIRO
		        $c_c = '<h5><span class="badge badge-info">'.$lin['quarto'.$x.'_cas_qtd'].'</span></h5>'; // CAMAS DE CASAL
		        $v1 = '<h5><span class="badge badge-info">'.$lin['quarto'.$x.'_valor1'].'</span></h5>'; // VALOR 1
		        $v2 = '<h5><span class="badge badge-info">'.$lin['quarto'.$x.'_valor2'].'</span></h5>'; // VALOR 2
		        $quarto .=<<<DADOS
		        
		        
					<div class="row">
                        <div class="col-2">
                            <h6 class="text-right"><strong>QUARTO $x</strong></h6>
                        </div>
						<div class="col-5">
							<div class="card bg-light">
                                <div class="card-body">
    								<div class="row text-center">
                                        <div class="col-12"><strong>Quantidade de camas</strong></div>
    								</div>
    								<div class="row text-center">
    									<div class="col-6">
    										<strong>Solteiro</strong><br>$c_s
    									</div>
    									<div class="col-6">
    										<strong>Casal</strong><br>$c_c
    									</div>
    								</div>
                                </div>
							</div>
                        </div>
						<div class="col-5">
							<div class="card bg-light">
                                <div class="card-body">
    								<div class="row text-center">
    									<div class="col-12"><strong>Preço do quarto por dia</strong></div>
    								</div>
    								<div class="row text-center">
    									<div class="col-6">
    										<strong>Uma pessoa</strong><br>$v1
    									</div>
    									<div class="col-6">
    										<strong>Duas ou mais</strong><br>$v2
    									</div>
    								</div>
                                </div>
							</div>
						</div>
                    </div>
                    <br>
DADOS;
		    }
		    $c_s = 0;
		    $c_c = 0;
		    $v1 = 0;
		    $v2 = 0;
		    
		    $x++;
		}
		*/
		
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
		    
		    
		    $quarto .=<<<DADOS
		    
				<div class="row">
					<div class="col-12">
						<table class="table table-bordered table-sm">
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
		}
		
		
		
		// Seta CONG_CIDADE
		$def = $this->pdo->query('SELECT cidade FROM cidade WHERE id = '.$reg->cong_cidade);
		$lin = $def->fetch(PDO::FETCH_OBJ);
		
		
		if($reg->dias == 'todos') {
		    $dias_print =<<<DADOS
						<label class="checkbox-inline"><input type="checkbox" checked disabled> Domingo</label>
						<label class="checkbox-inline"><input type="checkbox" checked disabled> Segunda</label>
						<label class="checkbox-inline"><input type="checkbox" checked disabled> Terça</label>
						<label class="checkbox-inline"><input type="checkbox" checked disabled> Quarta</label>
						<label class="checkbox-inline"><input type="checkbox" checked disabled> Quinta</label>
						<label class="checkbox-inline"><input type="checkbox" checked disabled> Sexta</label>
						<label class="checkbox-inline"><input type="checkbox" checked disabled> Sábado</label>
						<label class="checkbox-inline"><input type="checkbox" checked disabled><strong> Todos</strong></label>
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
		    $dias_print =<<<DADOS
						<label class="checkbox-inline"><input type="checkbox" $dom disabled> Domingo</label>
						<label class="checkbox-inline"><input type="checkbox" $seg disabled> Segunda</label>
						<label class="checkbox-inline"><input type="checkbox" $ter disabled> Terça</label>
						<label class="checkbox-inline"><input type="checkbox" $qua disabled> Quarta</label>
						<label class="checkbox-inline"><input type="checkbox" $qui disabled> Quinta</label>
						<label class="checkbox-inline"><input type="checkbox" $sex disabled> Sexta</label>
						<label class="checkbox-inline"><input type="checkbox" $sab disabled> Sábado</label>
						<label class="checkbox-inline"><input type="checkbox" disabled><strong> Todos</strong></label>
DADOS;
		}
		
		if($reg->andar == 0) {
		    $andar = 'Térreo';
		} else {
		    $andar = $reg->andar.'º andar';
		}
		
		if($reg->transporte == false) {
		    $transporte = '<span class="badge badge-info">NÃO</span>';
		} else {
		    $transporte = '<span class="badge badge-info">SIM</span>';
		}
		
		if($reg->casa_tj == false) {
		    $casa_tj = '<span class="badge badge-info">NÃO</span>';
		} else {
		    $casa_tj = '<span class="badge badge-info">SIM</span>';
		}
		
		if($reg->obs1 == '') {
		    $reg->obs1 = '-';
		}
		if($reg->obs2 == '') {
		    $reg->obs2 = '-';
		}
		
		$exc = '';
		$boa = '';
		$raz = '';
		switch($reg->condicao) {
		    case 'excelente': $exc = 'checked'; break;
		    case 'boa': $boa = 'checked'; break;
		    case 'razoavel': $raz = 'checked';break;
		}
		
		
		$html.=<<<DADOS
		
		
            <div class="page">
                <h5 class="text-center"><strong>FORMULÁRIO DE ACOMODAÇÃO</strong> <small class="text-secondary">(Nº.: $reg->id)</small></h5>
                    <hr>
                
					$quarto
					
                    <table class="table table-bordered table-sm tabela-margin">
                        <tbody>
                            <tr>
                                <td>
                                    <div class="row">
                						<div class="col-12">
                                            <strong>Os quartos estão disponíveis nos dias:</strong><br>
                							$dias_print
                						</div>
                					</div>
                                </td>
                            </tr>
    					
                    </table>

                    <hr>
                    <table class="table table-bordered table-sm tabela-margin">
                        <tbody>
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
                                        <dd><i>"$reg->obs1"</i></dd>
                                    </dl>
                                </td>
                            </tr>
                    </table>

					<table class="table table-bordered table-sm tabela-margin">
                        <tbody>
                            <tr>
                                <td colspan="2" style="width:50%">
                                    <h5 class="text-center"><strong>ENDEREÇO DO HOSPEDEIRO</strong></h5>
                                </td>
                                <td>
                                    <h5 class="text-center"><strong>PUBLICADOR QUE INDICOU</strong><br>
                                    <small class="text-secondary"><small>(Publicador que conseguiu a hospedagem, se não for o hospedeiro)</small></small></h5>
                                </td>
                            </tr>
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
                                        <dd>$reg->cidade_nome</dd>
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
                    
                    <table class="table tabela-margin">
                        <thead>
                            <tr>
                                <th colspan="3">
                                    <h5><strong>SECRETÁRIO DA CONGREGAÇÃO</strong></h5>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="width:33.3333%">
                                    <dl>
                                        <dt>Nome da Congregação:</dt>
                                        <dd>$reg->cong_nome</dd>
                                        <dt>Cidade:</dt>
                                        <dd>$lin->cidade</dd>
                                        <dt>Nome do Secretário:</dt>
                                        <dd>$reg->cong_sec</dd>
                                        <dt>Telefone do Secretário:</dt>
                                        <dd>$reg->cong_tel</dd>
                                    </dl>
                                </td>
                                <td style="width:33.3333%">
                                    <dl>
                                        <dt>Condição do(s) quarto(s):</dt>
                                        <dd>
                                            <label class="radio-inline"><input type="radio" name="condicao" disabled $exc>Excelente</label>
            								<label class="radio-inline"><input type="radio" name="condicao" disabled $boa>Boa</label>
            								<label class="radio-inline"><input type="radio" name="condicao" disabled $raz>Razoável</label>
                                        </dd>
                                    </dl>
                                </td>
                                <td style="width:33.3333%">
                                    <dl>
                                        <dt>Observações:</dt>
                                        <dd><i>"$reg->obs2"</i></dd>
                                    </dl>
                                </td>
                            </tr>
                        </tbody>
                    </table>
				</div>
					
					
					
DADOS;
		
		
		return $html;
	}
	
	private function impressaoPEH($reg)
	{
	    // Trata dados
	    // Remove campos vazios
	    foreach($reg as $key => $value) {
	        if($value == '') {
	            $reg->$key = '-';
	        }
	        
	    }
	    
	    if($reg->oc2_nome == '-') {
	        $reg->oc2_idade = '-';
	    }
	    if($reg->oc3_nome == '-') {
	        $reg->oc3_idade = '-';
	    }
	    if($reg->oc4_nome == '-') {
	        $reg->oc4_idade = '-';
	    }
	    
	    if($reg->check_in == '0000-00-00') {
	        $reg->check_in = '<h5><span class="badge badge-warning">A definir!</span></h5><br>';
	    } else {
	        $x = explode('-', $reg->check_in);
	        $reg->check_in = '<h5><span class="badge badge-info"><span class="glyphicon glyphicon-calendar"></span> '.$x[2].'/'.$x[1].'/'.$x[0].'</span></h5><br>';
	    }
	    if($reg->check_out == '0000-00-00') {
	        $reg->check_out = '<h5><span class="badge badge-warning">A definir!</span></h5><br>';
	    } else {
	        $x = explode('-', $reg->check_out);
	        $reg->check_out = '<h5><span class="badge badge-info"><span class="glyphicon glyphicon-calendar"></span> '.$x[2].'/'.$x[1].'/'.$x[0].'</span></h5><br>';
	    }
	    if($reg->congresso_cidade == '-') {
	        $reg->congresso_cidade = '<h5><span class="badge badge-warning">A definir!</span></h5><br>';
	    } else {
	        $reg->congresso_cidade .= '<br><br>';
	    }
	    
	    $reg->tipo_hospedagem = strtoupper($reg->tipo_hospedagem);
	    
	    
	    
	    
	    $html=<<<DADOS
	    
	    
                <div class="page">
					<h5 class="text-center"><strong>PEDIDO ESPECIAL DE HOSPEDAGEM</strong> <small class="text-secondary">(Nº.: $reg->id)</small></h5>
                    
                    
					<hr>
					
					<div class="row">
						<div class="col-6">
							<div class="row">
                                <div class="col-12">
                                    <strong>Nome:</strong><br>
                                    $reg->nome
        							<br><br>
        							
                                    <strong>Endereço:</strong><br>
                                    $reg->endereco
        							<br><br>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <strong>Cidade:</strong><br>
                                    $reg->cidade
        							<br><br>
                                </div>
                                <div class="col-4">
                                    <strong>Estado:</strong><br>
                                    $reg->estado
        							<br><br>
                                </div>
                                <div class="col-2">
                                    <strong>País:</strong><br>
                                    BRA
                                    <br><br>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <strong>Telefone Residencial:</strong><br>
                                    $reg->tel_res
        							<br><br>
                                </div>
                                <div class="col-6">
                                    <strong>Telefone Celular:</strong><br>
                                    $reg->tel_cel
        							<br><br>
                                </div>
                            </div>
							<div class="row">
                                <div class="col-12">
                                    <strong>E-mail:</strong><br>
                                    $reg->email
        							<br><br>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <strong>Congregação:</strong><br>
                                    $reg->congregacao
        							<br><br>
                                </div>
                                <div class="col-6">
                                    <strong>Cidade:</strong><br>
                                    $reg->cong_cidade/$reg->cong_estado
        							<br><br>
                                </div>
                            </div>
						</div>
						<div class="col-6">
							<div class="row">
                                <div class="col-12">
                                    <strong>Cidade do Congresso:</strong><br>
                                    $reg->congresso_cidade
                                    
                                    <strong>Primeira noite que precisará do quarto:</strong><br>
                                    $reg->check_in
                                    
                                    <strong>Última noite que precisará do quarto:</strong><br>
                                    $reg->check_out
                                    
                                    <strong>Tipo de acomodação:</strong><br>
                                    $reg->tipo_hospedagem
        							<br><br>
        							
                                    <strong>Quanto pode pagar pelo quarto, por noite (em reais):</strong><br>
                                    R$ $reg->pagamento
        							<br><br>
        							
                                    <strong>Terá transporte próprio enquanto estiver na cidade do congresso?</strong><br>
                                    $reg->transporte
        							<br><br>
                                </div>
                            </div>
						</div>
					</div>
					
					
					
					<table class="table table-hover table-striped">
						<tbody>
							<tr><td>
								<h5><small><strong>Ocupante 1</strong></small></h5>
								<div class="row">
									<div class="col-2">
                                        <strong>Nome:</strong><br>
                                        $reg->oc1_nome
									</div>
									<div class="col-2">
										<strong>Idade:</strong><br>
                                        $reg->oc1_idade
									</div>
									<div class="col-2">
										<strong>Sexo:</strong><br>
                                        $reg->oc1_sexo
									</div>
									<div class="col-2">
										<strong>Parentesco:</strong><br>
                                        $reg->oc1_parente
									</div>
									<div class="col-2">
										<strong>Etnia:</strong><br>
                                        $reg->oc1_etnia
									</div>
									<div class="col-2">
										<strong>Privilégio(s):</strong><br>
                                        $reg->oc1_privilegio
									</div>
								</div>
							</td></tr>
							<tr><td>
								<h5><small><strong>Ocupante 2</strong></small></h5>
								<div class="row">
									<div class="col-2">
                                        <strong>Nome:</strong><br>
                                        $reg->oc2_nome
									</div>
									<div class="col-2">
										<strong>Idade:</strong><br>
                                        $reg->oc2_idade
									</div>
									<div class="col-2">
										<strong>Sexo:</strong><br>
                                        $reg->oc2_sexo
									</div>
									<div class="col-2">
										<strong>Parentesco:</strong><br>
                                        $reg->oc2_parente
									</div>
									<div class="col-2">
										<strong>Etnia:</strong><br>
                                        $reg->oc2_etnia
									</div>
									<div class="col-2">
										<strong>Privilégio(s):</strong><br>
                                        $reg->oc2_privilegio
									</div>
								</div>
							</td></tr>
							<tr><td>
								<h5><small><strong>Ocupante 3</strong></small></h5>
								<div class="row">
									<div class="col-2">
                                        <strong>Nome:</strong><br>
                                        $reg->oc3_nome
									</div>
									<div class="col-2">
										<strong>Idade:</strong><br>
                                        $reg->oc3_idade
									</div>
									<div class="col-2">
										<strong>Sexo:</strong><br>
                                        $reg->oc3_sexo
									</div>
									<div class="col-2">
										<strong>Parentesco:</strong><br>
                                        $reg->oc3_parente
									</div>
									<div class="col-2">
										<strong>Etnia:</strong><br>
                                        $reg->oc3_etnia
									</div>
									<div class="col-2">
										<strong>Privilégio(s):</strong><br>
                                        $reg->oc3_privilegio
									</div>
								</div>
							</td></tr>
							<tr><td>
								<h5><small><strong>Ocupante 4</strong></small></h5>
								<div class="row">
									<div class="col-2">
                                        <strong>Nome:</strong><br>
                                        $reg->oc4_nome
									</div>
									<div class="col-2">
										<strong>Idade:</strong><br>
                                        $reg->oc4_idade
									</div>
									<div class="col-2">
										<strong>Sexo:</strong><br>
                                        $reg->oc4_sexo
									</div>
									<div class="col-2">
										<strong>Parentesco:</strong><br>
                                        $reg->oc4_parente
									</div>
									<div class="col-2">
										<strong>Etnia:</strong><br>
                                        $reg->oc4_etnia
									</div>
									<div class="col-2">
										<strong>Privilégio(s):</strong><br>
                                        $reg->oc4_privilegio
									</div>
								</div>
							</td></tr>
						</tbody>
					</table>
					
					<!--
					
					<br>
					<div class="card bg-secondary text-white">
                        <div class="card-body">
                            <div class="row">
                                <h5><strong>SECRETÁRIO DA CONGREGAÇÃO</strong></h5>
                                <div class="col-12">
            						<strong>Motivo:</strong><br>
                                    "$reg->motivo"
                                    <br><br>
                                </div>
                                <div class="col-4">
            						<strong>Nome do Secretário:</strong><br>
                                    $reg->secretario_nome
                                    <br><br>
                                </div>
                                <div class="col-4">
            						<strong>Telefone do Secretário:</strong><br>
                                    $reg->secretario_tel
                                    <br><br>
                                </div>
                                <div class="col-4">
            						<strong>E-mail do Secretário:</strong><br>
                                    $reg->secretario_email
                                    <br><br>
                                </div>
                            </div>
                        </div>
					</div>
					-->
                </div><!-- ./DIV.PAGE -->
					
DADOS;
	    
	    
	    return $html;
	}
}