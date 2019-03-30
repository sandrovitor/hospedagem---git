<?php
class Sistema
{
    // BANCO DE DADOS
    private $pdo;
    private $db_user;
    private $db_senha;
    private $db_name;
    private $db_host;
    
    // CONSTANTES
    const PED_TOTAL = 'TOTAL'; // Todos os pedidos no sistema.
    const PED_DESATIVADO = 'DESAT'; // Somente os pedidos em revisão no sistema (aparecem como desativados).
    const PED_ATIVO = 'ATIVO'; // Somente os pedidos ativos no sistema, atendidos ou não.
    const PED_ATENDIDO = 'ATEND'; // Somente os pedidos ativos e que já foram atendidos.
    const PED_PENDENTE = 'PENDE'; // Somente os pedidos ativos e pendentes de atendimento.
    
    const ACO_TOTAL = 'TOTAL'; // Todos as acomodações no sistema.
    const ACO_DESATIVADO = 'DESAT'; // Somente as acomodações em revisão no sistema (aparecem como desativados).
    const ACO_ATIVO = 'ATIVO'; // Somente as acomodações ativas no sistema, atendidas ou não.
    const ACO_ATENDIDO = 'ATEND'; // Somente as acomodações ativas e que já foram atendidas.
    const ACO_PENDENTE = 'PENDE'; // Somente as acomodações ativas e pendentes de atendimento.
    
    // DATA E HORA
    public $gmtTimeZone;
    
    // INFOS GERAIS
    
    
    
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

    // FUNÇÕES GERAIS
    public function getTotalPedidos($tipo = 'TOTAL')
    {
        /*
         * O $tipo pode ser
         *      TOTAL = Todos os pedidos no sistema.
         *      DESAT = Somente os pedidos em revisão no sistema (aparecem como desativados).
         *      ATIVO = Somente os pedidos ativos no sistema, atendidos ou não.
         *      ATEND = Somente os pedidos ativos e que já foram atendidos.
         *      PENDE = Somente os pedidos ativos e pendentes de atendimento.
         * 
         * 
         * quando em branco, assumirá valor de "TOTAL"
         */
        
        switch($tipo) {
            case 'TOTAL':
                $abc = $this->pdo->query('SELECT * FROM peh WHERE 1');
                break;
                
            case 'DESAT':
                $abc = $this->pdo->query('SELECT * FROM peh WHERE `revisar` = 1');
                break;
                
            case 'ATIVO':
                $abc = $this->pdo->query('SELECT * FROM peh WHERE `revisar` = 0');
                break;
                
            case 'ATEND':
                $abc = $this->pdo->query('SELECT * FROM peh WHERE `revisar` = 0 AND `fac_id` <> 0');
                break;
                
            case 'PENDE':
                $abc = $this->pdo->query('SELECT * FROM peh WHERE `revisar` = 0 AND `fac_id` = 0');
                break;
                
            default:
                $abc = $this->pdo->query('SELECT * FROM peh WHERE 1');
                break;
        }
        
        return $abc->rowCount();
    }
    
    public function getParcialPedidos()
    {
        if($_SESSION['nivel'] == 20) {
            $x = 'Não aplicável à administrador.';
        } else if($_SESSION['nivel'] == 10) {
            $abc = $this->pdo->query('SELECT peh.id FROM peh LEFT JOIN cidade ON peh.congregacao_cidade_id = cidade.id WHERE cidade.resp_id = '.$_SESSION['id'].' AND hospedeiro = 0');
            $x = $abc->rowCount();
        } else {
            $abc = $this->pdo->query('SELECT peh.id FROM peh LEFT JOIN cidade ON peh.congregacao_cidade_id = cidade.id WHERE cidade.solicitante_id = '.$_SESSION['id'].' AND hospedeiro = 0');
            $x = $abc->rowCount();
        }
        
        return $x;
    }
    
    public function getTotalAcomod($tipo = 'TOTAL')
    {
        /*
         * O $tipo pode ser
         *      TOTAL = Todos as acomodações no sistema.
         *      DESAT = Somente as acomodações em revisão no sistema (aparecem como desativados).
         *      ATIVO = Somente as acomodações ativas no sistema, atendidas ou não.
         *      ATEND = Somente as acomodações ativas e que já foram atendidas.
         *      PENDE = Somente as acomodações ativas e pendentes de atendimento.
         *
         *
         * quando em branco, assumirá valor de "TOTAL"
         */
        
        switch($tipo) {
            case 'TOTAL':
                $abc = $this->pdo->query('SELECT * FROM fac WHERE 1');
                $x = $abc->rowCount();
                break;
                
            case 'DESAT':
                $abc = $this->pdo->query('SELECT * FROM fac WHERE `revisar` = 1');
                $x = $abc->rowCount();
                break;
                
            case 'ATIVO':
                $abc = $this->pdo->query('SELECT * FROM fac WHERE `revisar` = 0');
                $x = $abc->rowCount();
                break;
                
            case 'ATEND':
                $abc = $this->pdo->query('SELECT fac.id FROM fac LEFT JOIN peh ON fac.id = peh.fac_id WHERE `fac`.`revisar` = 0 AND `peh`.`revisar` = 0');
                $x = $abc->rowCount();
                break;
                
            case 'PENDE':
                $abc = $this->pdo->query('SELECT * FROM fac WHERE 1');
                $y = $abc->rowCount(); // TOTAL
                
                $abc = $this->pdo->query('SELECT fac.id FROM fac LEFT JOIN peh ON fac.id = peh.fac_id WHERE `fac`.`revisar` = 0 AND `peh`.`revisar` = 0');
                $z = $abc->rowCount(); // ATENDIDOS
                
                $x = $y-$z; // TOTAL - ATENDIDOS = PENDENTES
                break;
                
            default:
                $abc = $this->pdo->query('SELECT * FROM fac WHERE 1');
                $x = $abc->rowCount();
                break;
        }
        
        return $x;
    }
    
    public function getParcialAcomod()
    {
        if($_SESSION['nivel'] == 20) {
            $abc = $this->pdo->query('SELECT fac.id FROM fac LEFT JOIN cidade ON fac.cidade = cidade.id WHERE cidade.resp_id = '.$_SESSION['id'].' AND hospedeiro = 1');
            $x = $abc->rowCount();
        } else if($_SESSION['nivel'] == 10) {
            $abc = $this->pdo->query('SELECT fac.id FROM fac LEFT JOIN cidade ON fac.cidade = cidade.id WHERE cidade.resp_id = '.$_SESSION['id'].' AND hospedeiro = 1');
            $x = $abc->rowCount();
        } else {
            $abc = $this->pdo->query('SELECT resp_id FROM cidade WHERE hospedeiro = 0 AND solicitante_id = '.$_SESSION['id']);
            $reg = $abc->fetch(PDO::FETCH_OBJ);
            $resp_id = $reg->resp_id;
            
            if($resp_id != 0) {
                $abc = $this->pdo->query('SELECT fac.id FROM fac LEFT JOIN cidade ON fac.cidade = cidade.id WHERE cidade.resp_id = '.$resp_id.' AND hospedeiro = 1');
                $x = $abc->rowCount();
            } else {
                $x = '<span data-toggle="tooltip" title="Indefinido. Aguardando designação.">IND</span>';
            }
            unset($reg, $resp_id);
            
        }
        
        return $x;
    }
    
    public function getRelatorioSimplesPedidos($so_conteudo = TRUE)
    {
        $html = '';
        if($so_conteudo == FALSE) {
            $html.=<<<DADOS

<table class="table">
	<caption>RELATÓRIO SIMPLIFICADO</caption>
	<tbody>
DADOS;
        }
        
        if($_SESSION['nivel'] == 20) {
            $abc = $this->pdo->query('SELECT id, cidade, estado FROM cidade WHERE hospedeiro = 0 ORDER BY cidade ASC');
        } else if($_SESSION['nivel'] == 10) {
            $abc = $this->pdo->query('SELECT id, cidade, estado FROM cidade WHERE hospedeiro = 0 AND resp_id = '.$_SESSION['id'].' ORDER BY cidade ASC');
        } else {
            $abc = $this->pdo->query('SELECT id, cidade, estado FROM cidade WHERE hospedeiro = 0 AND solicitante_id = '.$_SESSION['id'].' ORDER BY cidade ASC');
        }
        
        //$abc = $this->pdo->query('SELECT id, cidade, estado FROM cidade WHERE hospedeiro = 0 ORDER BY cidade ASC');
        
        $total_pedidos = 0;
        $total_atendidos = 0;
        
        if($abc->rowCount() > 0) {
            while($lin = $abc->fetch(PDO::FETCH_OBJ)) {
                // Consulta numero de pedidos e etc
                $def = $this->pdo->query('SELECT oc1_nome, oc2_nome, oc3_nome, oc4_nome FROM peh WHERE congregacao_cidade_id = '.$lin->id);
                $pedidos = $def->rowCount();
                
                $def = $this->pdo->query('SELECT peh.id FROM peh WHERE congregacao_cidade_id = '.$lin->id.' AND revisar = 1');
                $revisando = $def->rowCount();
                
                
                $def = $this->pdo->query('SELECT peh.id FROM peh WHERE congregacao_cidade_id = '.$lin->id.' AND fac_id <> 0');
                $atendidos = $def->rowCount();
                
                // Incrementa total
                $total_pedidos += $pedidos;
                $total_atendidos += $atendidos;
                
                
                
                
                // TRATAMENTO DE DADOS
                if($pedidos == 0) {
                    $pedidos_str = '<span class="badge badge-pill badge-dark">'.$pedidos.'</span>';
                } else {
                    $pedidos_str = '<span class="badge badge-pill badge-primary">'.$pedidos.'</span>';
                }
                
                
                if($revisando == 0) { 
                    $revisando_str = '<span class="badge badge-pill badge-dark">'.$revisando.'</span>';
                } else if($revisando > 0 && $revisando < $pedidos) {
                    $revisando_str = '<span class="badge badge-pill badge-warning">'.$revisando.'</span>';
                } else {
                    $revisando_str = '<span class="badge badge-pill badge-danger">'.$revisando.'</span>';
                }
                
                if($atendidos == 0) {
                    $atendidos_str = '<span class="badge badge-pill badge-danger">'.$atendidos.'</span>';
                } else if($atendidos > 0 && $atendidos < $pedidos) {
                    $atendidos_str = '<span class="badge badge-pill badge-warning">'.$atendidos.'</span>';
                } else {
                    $atendidos_str = '<span class="badge badge-pill badge-success">'.$atendidos.'</span>';
                }
                
                
                $html.=<<<DADOS
                
		<tr>
			<th rowspan="3"><span class="glyphicon glyphicon-map-marker"></span> $lin->cidade / $lin->estado</th>
			<td>Pedidos</td>
			<td>$pedidos_str</td>
		</tr>
		<tr>
			<td>Revisando</td>
			<td>$revisando_str</td>
		</tr>
		<tr>
			<td>Atendidos</td>
			<td>$atendidos_str</td>
		</tr>
DADOS;
            }
                
            $html.=<<<DADOS
            
		<tr class="bg-dark text-white">
			<th rowspan="2" class="text-center">TOTAL</th>
			<td>Pedidos</td>
			<td><span class="badge badge-pill badge-light">$total_pedidos</span></td>
		</tr>
		<tr class="bg-dark text-white">
			<td>Atendidos</td>
			<td><span class="badge badge-pill badge-light">$total_atendidos</span></td>
		</tr>
DADOS;
            
        } else {
            $html.=<<<DADOS

        <tr><td>Nada encontrado</td></tr>
DADOS;
        }
        
       
		if($so_conteudo == FALSE) {
		  $html.=<<<DADOS
		
	</tbody>
</table>
DADOS;
		}
        
        return $html;
    }
    
    public function getRelatorioSimplesAcomod()
    {
        $html = '';
        
        
    }
   
    public function getConsultaListaPEH()
    {
        $html = '';
        // Abre tabela
        $html .=<<<DADOS

                                <table class="table table-sm">

DADOS;
        
        if($_SESSION['nivel'] == 1) { // Solicitante de hospedagem
            $abc = $this->pdo->query('SELECT cidade.cidade AS cong_cidade, cidade.estado AS cong_estado, peh.* FROM peh LEFT JOIN cidade ON peh.congregacao_cidade_id = cidade.id WHERE cidade.solicitante_id = '.$_SESSION['id'].' ORDER BY cidade.cidade ASC, peh.id ASC');
            
        } else if ($_SESSION['nivel'] == 10) { // Responsável pela hospedagem
            $abc = $this->pdo->query('SELECT cidade.cidade AS cong_cidade, cidade.estado AS cong_estado, peh.* FROM peh LEFT JOIN cidade ON peh.congregacao_cidade_id = cidade.id WHERE cidade.resp_id = '.$_SESSION['id'].' ORDER BY cidade.cidade ASC, peh.id ASC');
            
        } else if ($_SESSION['nivel'] == 20) { // Administrador
            $abc = $this->pdo->query('SELECT cidade.cidade AS cong_cidade, cidade.estado AS cong_estado, peh.* FROM peh LEFT JOIN cidade ON peh.congregacao_cidade_id = cidade.id WHERE 1 ORDER BY cidade.cidade ASC, peh.id ASC');
            
        }
        
        
        if($abc->rowCount() > 0) {
            $cidade = '';
            while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
                // Verifica a localidade
                if($cidade == '' || $cidade != $reg->cong_cidade.'/'.$reg->cong_estado) {
                    $cidade = $reg->cong_cidade.'/'.$reg->cong_estado;
                    $html .=<<<DADOS
                    
    								<tr>
    									<th colspan="3" class="text-center bg-info"><span class="glyphicon glyphicon-map-marker"></span> $cidade</th>
    								</tr>
DADOS;
                }
                
                $info = '';
                if($reg->fac_id == 0) {
                    // PENDENTE
                    $info .= '<span class="badge badge-warning" data-toggle="tooltip" title="PENDENTE!"><span class="glyphicon glyphicon-question-sign"></span> PEN</span>';
                } else {
                    // ATENDIDO
                    $info .= '<span class="badge badge-success" data-toggle="tooltip" title="OK!"><span class="glyphicon glyphicon-ok"></span> OK !</span>';
                }
                
                if($reg->revisar == TRUE) {
                    // EM REVISÃO
                    $info .= '<br><span id="peh_rev-'.$reg->id.'" class="badge badge-danger" data-toggle="tooltip" title="REVISAR!"><span class="glyphicon glyphicon-alert"></span> REV</span>';
                } else {
                    // SEM REVISAR
                    $info .= '<br><span id="peh_rev-'.$reg->id.'" class="badge badge-secondary" data-toggle="tooltip" title="TUDO OK"><span class="glyphicon glyphicon-alert"></span> REV</span>';
                }
                
                $bot = '';
                $token = md5($reg->id.session_id());
                if($_SESSION['nivel'] == 1 || $_SESSION['nivel'] == 20) {
                    $bot .= '<a href="editar.php?tipo=peh&pehid='.$reg->id.'&token='.$token.'" target="_blank" class="btn btn-sm btn-success" title="Editar" data-toggle="tooltip"><span class="glyphicon glyphicon-edit"></span></a> ';
                    $bot .= <<<DADOS
 <button class="btn btn-sm btn-danger" title="Apagar pedido" data-toggle="tooltip" onclick="apagaPEH($reg->id, '$token')"><span class="glyphicon glyphicon-erase"></span></button>
DADOS;
                }
                if(($_SESSION['nivel'] == 10 || $_SESSION['nivel'] == 20)) {
                    if($reg->revisar == 0) {
                        $dis = '';
                    } else if($reg->revisar == 1) {
                        $dis = 'disabled';
                    }
                    $bot .= <<<DADOS
 <button onclick="revisarForms('peh', $reg->id, '$token', this)" class="btn btn-sm btn-warning" title="Pedir revisão" data-toggle="tooltip" $dis><span class="glyphicon glyphicon-comment"></span></button>
DADOS;
                } else { // Se for nivel 1
                    // Verifica se já foi atendido
                    if($reg->fac_id != 0) {
                        // Já foi atendido
                        $bot .= ' <button onclick="carAcomod('.$reg->fac_id.')" class="btn btn-sm btn-info" title="Informação da Acomodação" data-toggle="tooltip"><span class="glyphicon glyphicon-lamp"></span></button>';
                    } else {
                        // Não foi atendido
                        $bot .= ' <button class="btn btn-sm btn-info" title="Ainda sem acomodação" data-toggle="tooltip" disabled><span class="glyphicon glyphicon-lamp"></span></button>';
                    }
                    
                }
                // Escreve linha.
                $html .=<<<DADOS
                
    								<tr>
    									<td><a href="javascript:void(0)" onclick="pehCarrega($reg->id,0)" data-toggle="collapse" data-target="#panel1">Pedido - Nº.: $reg->id</a></td>
    									<td>$info</td>
    									<td>$bot</td>
    								</tr>
DADOS;
            }
        } else {
            $html .=<<<DADOS
            
    								<tr>
    									<th colspan="3">Nada encontrado.</th>
    								</tr>
DADOS;
        }
        
        // Fecha tabela
        $html .=<<<DADOS
        
    							</table>
DADOS;
        
        return $html;
    }
    
    public function getConsultaListaFAC()
    {
        $html = '';
        // Abre tabela
        $html .=<<<DADOS
        
                                <table class="table table-sm">
                                
DADOS;
        
        if ($_SESSION['nivel'] == 10) { // Responsável pela hospedagem
            $abc = $this->pdo->query('SELECT fac.*, cidade.cidade AS cong_cidade, cidade.estado AS cong_estado, peh.id AS peh_id FROM fac LEFT JOIN cidade ON fac.cidade = cidade.id LEFT JOIN peh ON fac.id = peh.fac_id WHERE cidade.hospedeiro = 1 AND cidade.resp_id = '.$_SESSION['id'].' ORDER BY cidade.cidade ASC, fac.id ASC');
            
        } else if ($_SESSION['nivel'] == 20) { // Administrador
            $abc = $this->pdo->query('SELECT fac.*, cidade.cidade AS cong_cidade, cidade.estado AS cong_estado, peh.id AS peh_id FROM fac LEFT JOIN cidade ON fac.cidade = cidade.id LEFT JOIN peh ON fac.id = peh.fac_id WHERE cidade.hospedeiro = 1 ORDER BY cidade.cidade ASC, fac.id ASC');
            
        }
        
        if($abc->rowCount() > 0) {
            $cidade = '';
            while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
                // Verifica a localidade
                if($cidade == '' || $cidade != $reg->cong_cidade.'/'.$reg->cong_estado) {
                    $cidade = $reg->cong_cidade.'/'.$reg->cong_estado;
                    $html .=<<<DADOS
                    
    								<tr>
    									<th colspan="3" class="text-center bg-info"><span class="glyphicon glyphicon-map-marker"></span> $cidade</th>
    								</tr>
DADOS;
                }
                    
                
                $info = '';
                $token = md5($reg->id.session_id());
                
                // Verifica se tá ocupado
                if($reg->peh_id > 0) { // OCUPADO
                    $info .= '<span class="badge badge-success" data-toggle="tooltip" title="Acomodação já em vinculada!"><span class="glyphicon glyphicon-ok"></span> JÁ !</span>';
                } else { // LIVRE
                    $info .= '<span class="badge badge-warning" data-toggle="tooltip" title="ACOMODAÇÃO LIVRE!"><span class="glyphicon glyphicon-question-sign"></span> LIV</span>';
                }
                
                if($reg->revisar == TRUE) {
                    // EM REVISÃO
                    $info .= '<br><span id="fac_rev-'.$reg->id.'" class="badge badge-danger" data-toggle="tooltip" title="REVISAR!"><span class="glyphicon glyphicon-alert"></span> REV</span>';
                } else {
                    // SEM REVISAR
                    $info .= '<br><span id="fac_rev-'.$reg->id.'" class="badge badge-secondary" data-toggle="tooltip" title="TUDO OK"><span class="glyphicon glyphicon-alert"></span> REV</span>';
                }
                
                $bot = '';
                
                $bot .= '<a href="editar.php?tipo=fac&facid='.$reg->id.'&token='.$token.'" target="_blank" class="btn btn-sm btn-success" title="Editar" data-toggle="tooltip"><span class="glyphicon glyphicon-edit"></span></a> ';
                $bot .= <<<DADOS
														<button class="btn btn-sm btn-danger" title="Apagar acomodação" data-toggle="tooltip" onclick="apagaFAC($reg->id, '$token')"><span class="glyphicon glyphicon-erase"></span></button>
DADOS;
                
                if($_SESSION['nivel'] == 20) {
                    if($reg->revisar == 0) {
                        $dis = '';
                    } else if($reg->revisar == 1) {
                        $dis = 'disabled';
                    }
                    $bot .= <<<DADOS
	<button onclick="revisarForms('fac', $reg->id, '$token', this)" class="btn btn-sm btn-warning" title="Pedir revisão" data-toggle="tooltip" $dis><span class="glyphicon glyphicon-comment"></span></button>
DADOS;
                }
                    
                
                
                // Escreve linha.
                $html .=<<<DADOS
                
    								<tr>
    									<td><a href="javascript:void(0)" onclick="facCarrega($reg->id,0)" data-toggle="collapse" data-target="#panel1">Acomod. Nº.: $reg->id</a></td>
    									<td>$info</td>
    									<td>$bot</td>
    								</tr>
DADOS;
                
                
            }
        } else {
            $html .=<<<DADOS
            
    								<tr>
    									<th colspan="3">Nada encontrado.</th>
    								</tr>
DADOS;
        }
        
        
        $html .=<<<DADOS
        
                                </table>
DADOS;
        
        return $html;
    }

    public function getPEH($fid, $links)
    {
        if($fid != 0) {
            $abc = $this->pdo->query('SELECT peh.*, cidade.cidade AS cong_cidade, cidade.estado AS cong_estado FROM `peh` LEFT JOIN cidade ON peh.congregacao_cidade_id = cidade.id WHERE peh.id = '.(int)$fid);
        } else {
            return 'Acesso negado!';
            exit();
        }
        
        if($abc->rowCount() == 0) {
            return 'PEH não encontrado.';
            exit();
        }
        
        $reg = $abc->fetch(PDO::FETCH_OBJ);
        //var_dump($reg);
        
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
        
        
        if($reg->revisar == 1 && ($_SESSION['nivel'] == 1 || $_SESSION['nivel'] == 20)) {
            $token = md5($reg->id.session_id());
            $revisar = '&nbsp; <span class="badge badge-danger" data-toggle="tooltip" title="É PRECISO REVISAR ESSE FORMULÁRIO"><span class="glyphicon glyphicon-alert"></span> REV</span> <a href="editar.php?tipo=peh&pehid='.$reg->id.'&token='.$token.'" target="_blank" class="btn btn-sm btn-success" title="Editar" data-toggle="tooltip"><span class="glyphicon glyphicon-edit"></span></a>';
        } else {
            $revisar = '';
        }
        
        // Se já estiver vinculado com uma acomodação.
        if($reg->fac_id != 0) {
            // Verifica se escreve na página com LINKS
            if($links == 0) { // SEM LINK
                $fac_info = <<<DADOS
					<div class="text-center"><kbd><span class="glyphicon glyphicon-ok"></span> JÁ VINCULADO A ACOMODAÇÃO >>> <div class="badge badge-info" style="font-size:11px; letter-spacing: 1px;">ID: $reg->fac_id</div></kbd></div>
					<hr>
DADOS;
            } else if($links == 1) { // COM LINK PARA FORMULARIO DE ACOMODAÇÃO [pagina de gerenciamento]
                $fac_info = <<<DADOS
					<div class="text-center"><kbd><span class="glyphicon glyphicon-ok"></span> JÁ VINCULADO A ACOMODAÇÃO >>> <div class="badge badge-info" style="font-size:11px; letter-spacing: 1px;">ID: <a data-toggle="popover" data-trigger="click focus hover" data-placement="top" data-content="<a class='btn btn-primary text-white' onclick='facCarrega($reg->fac_id,1); zeraFacSelect();'>Visualizar Acomodação <span class='glyphicon glyphicon-arrow-right'></span></a>">$reg->fac_id</a></div></kbd></div>
					<hr>
DADOS;
                }
        } else {
            $fac_info = '';
        }
        
        $html=<<<DADOS
        
                    
					<h5 class="text-center"><strong>PEDIDO ESPECIAL DE HOSPEDAGEM</strong> <small class="text-secondary">(Nº.: $reg->id)</small> 
                    $revisar</h5>

                    $fac_info                    
					<hr>
					
					<div class="row">
						<div class="col-12 col-md-6">
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
                                <div class="col-12 col-md-6">
                                    <strong>Cidade:</strong><br>
                                    $reg->cidade
        							<br><br>
                                </div>
                                <div class="col-6 col-md-4">
                                    <strong>Estado:</strong><br>
                                    $reg->estado
        							<br><br>
                                </div>
                                <div class="col-6 col-md-2">
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
						<div class="col-12 col-md-6">
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
									<div class="col-12 col-md-2">
                                        <strong>Nome:</strong><br>
                                        $reg->oc1_nome
									</div>
									<div class="col-6 col-md-2">
										<strong>Idade:</strong><br>
                                        $reg->oc1_idade
									</div>
									<div class="col-6 col-md-2">
										<strong>Sexo:</strong><br>
                                        $reg->oc1_sexo
									</div>
									<div class="col-12 col-md-2">
										<strong>Parentesco:</strong><br>
                                        $reg->oc1_parente
									</div>
									<div class="col-6 col-md-2">
										<strong>Etnia:</strong><br>
                                        $reg->oc1_etnia
									</div>
									<div class="col-6 col-md-2">
										<strong>Privilégio(s):</strong><br>
                                        $reg->oc1_privilegio
									</div>
								</div>
							</td></tr>
							<tr><td>
								<h5><small><strong>Ocupante 2</strong></small></h5>
								<div class="row">
									<div class="col-12 col-md-2">
                                        <strong>Nome:</strong><br>
                                        $reg->oc2_nome
									</div>
									<div class="col-6 col-md-2">
										<strong>Idade:</strong><br>
                                        $reg->oc2_idade
									</div>
									<div class="col-6 col-md-2">
										<strong>Sexo:</strong><br>
                                        $reg->oc2_sexo
									</div>
									<div class="col-12 col-md-2">
										<strong>Parentesco:</strong><br>
                                        $reg->oc2_parente
									</div>
									<div class="col-6 col-md-2">
										<strong>Etnia:</strong><br>
                                        $reg->oc2_etnia
									</div>
									<div class="col-6 col-md-2">
										<strong>Privilégio(s):</strong><br>
                                        $reg->oc2_privilegio
									</div>
								</div>
							</td></tr>
							<tr><td>
								<h5><small><strong>Ocupante 3</strong></small></h5>
								<div class="row">
									<div class="col-12 col-md-2">
                                        <strong>Nome:</strong><br>
                                        $reg->oc3_nome
									</div>
									<div class="col-6 col-md-2">
										<strong>Idade:</strong><br>
                                        $reg->oc3_idade
									</div>
									<div class="col-6 col-md-2">
										<strong>Sexo:</strong><br>
                                        $reg->oc3_sexo
									</div>
									<div class="col-12 col-md-2">
										<strong>Parentesco:</strong><br>
                                        $reg->oc3_parente
									</div>
									<div class="col-6 col-md-2">
										<strong>Etnia:</strong><br>
                                        $reg->oc3_etnia
									</div>
									<div class="col-6 col-md-2">
										<strong>Privilégio(s):</strong><br>
                                        $reg->oc3_privilegio
									</div>
								</div>
							</td></tr>
							<tr><td>
								<h5><small><strong>Ocupante 4</strong></small></h5>
								<div class="row">
									<div class="col-12 col-md-2">
                                        <strong>Nome:</strong><br>
                                        $reg->oc4_nome
									</div>
									<div class="col-6 col-md-2">
										<strong>Idade:</strong><br>
                                        $reg->oc4_idade
									</div>
									<div class="col-6 col-md-2">
										<strong>Sexo:</strong><br>
                                        $reg->oc4_sexo
									</div>
									<div class="col-12 col-md-2">
										<strong>Parentesco:</strong><br>
                                        $reg->oc4_parente
									</div>
									<div class="col-6 col-md-2">
										<strong>Etnia:</strong><br>
                                        $reg->oc4_etnia
									</div>
									<div class="col-6 col-md-2">
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
                                <div class="col-12 col-md-12">
            						<strong>Motivo:</strong><br>
                                    "$reg->motivo"
                                    <br><br>
                                </div>
                                <div class="col-12 col-md-4">
            						<strong>Nome do Secretário:</strong><br>
                                    $reg->secretario_nome
                                    <br><br>
                                </div>
                                <div class="col-12 col-md-4">
            						<strong>Telefone do Secretário:</strong><br>
                                    $reg->secretario_tel
                                    <br><br>
                                </div>
                                <div class="col-12 col-md-4">
            						<strong>E-mail do Secretário:</strong><br>
                                    $reg->secretario_email
                                    <br><br>
                                </div>
                            </div>
                        </div>
					</div>
					-->
					
DADOS;
        
        
        return $html;
    }
    
    public function getFAC($fid, $links)
    {
        if($fid != 0) {
            $abc = $this->pdo->query('SELECT fac.*, cidade.cidade AS cidade_nome, cidade.estado FROM fac LEFT JOIN cidade ON fac.cidade = cidade.id WHERE fac.id = '.(int)$fid);
            $def = $this->pdo->query('SELECT fac.*, cidade.cidade AS cidade_nome, cidade.estado FROM fac LEFT JOIN cidade ON fac.cidade = cidade.id WHERE fac.id = '.(int)$fid);
        } else {
            return 'Acesso negado!';
            exit();
        }
        
        $html = '';
        
        if($abc->rowCount() == 0) {
            return 'FAC não encontrado.';
            exit();
        }
        
        $reg = $abc->fetch(PDO::FETCH_OBJ); // OBJETO
        $lin = $def->fetch(PDO::FETCH_ASSOC); // ARRAY
        
        //var_dump($reg, $lin);
        //exit();
        
        
        //Trata dados
        $x = 1;
        $quarto = '';
        while($x <= $reg->quartos_qtd) {
            if($lin['quarto'.$x.'_sol_qtd'] != '' || $lin['quarto'.$x.'_cas_qtd'] != '') {
                $c_s = '<h5><span class="badge badge-info">'.$lin['quarto'.$x.'_sol_qtd'].'</span></h5>'; // CAMAS DE SOLTEIRO
                $c_c = '<h5><span class="badge badge-info">'.$lin['quarto'.$x.'_cas_qtd'].'</span></h5>'; // CAMAS DE CASAL
                $v1 = '<h5><span class="badge badge-info">'.$lin['quarto'.$x.'_valor1'].'</span></h5>'; // VALOR 1 
                $v2 = '<h5><span class="badge badge-info">'.$lin['quarto'.$x.'_valor2'].'</span></h5>'; // VALOR 2
                $quarto .=<<<DADOS


					<div class="row">
                        <div class="col-12 col-md-2">
                            <h6 class="float-right"><strong>QUARTO $x</strong></h6>
                        </div>
						<div class="col-6 col-sm-5">
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
						<div class="col-6 col-sm-5">
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
        
        
        // Pesquisa se o formulário já atendeu algum pedido de hospedagem.
        $def = $this->pdo->query('SELECT peh.id FROM peh WHERE peh.fac_id = '.$fid);
        $info_head = '';
        if($def->rowCount() > 0) {
            $fac_id = '';
            while($lin = $def->fetch(PDO::FETCH_OBJ)) {
                $fac_id .= $lin->id.', ';
            }
            $fac_id = substr($fac_id, 0, -2);
            
            if($links == 0) { // SEM LINK
                $info_head =<<<DADOS
				<h6 class="text-center"><kbd><span class="glyphicon glyphicon-ok"></span> ATENDENDO PEDIDO(s) DE HOSPEDAGEM >>> <div class="badge badge-info" style="font-size:0.8rem; letter-spacing: 1px;">ID: $fac_id</div></kbd></h6>
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
                
                
                $info_head =<<<DADOS
				<h6 class="text-center"><kbd><span class="glyphicon glyphicon-ok"></span> ATENDENDO PEDIDO(s) DE HOSPEDAGEM >>> <div class="badge badge-info" style="font-size:0.8rem; letter-spacing: 1px;">ID: $link</div></kbd></h6>
				<hr>
DADOS;
                unset($peh_id, $fac_id, $link);
                }
        }
        
        
        // Seta CONG_CIDADE
        $def = $this->pdo->query('SELECT cidade FROM cidade WHERE id = '.$reg->cong_cidade);
        $lin = $def->fetch(PDO::FETCH_OBJ);
        
        // Revisar
        if($reg->revisar == 1 && ($_SESSION['nivel'] == 10 || $_SESSION['nivel'] == 20)) {
            $token = md5($reg->id.session_id());
            $revisar = '&nbsp; <span class="badge badge-danger" data-toggle="tooltip" title="É PRECISO REVISAR ESSE FORMULÁRIO"><span class="glyphicon glyphicon-alert"></span> REV</span> <a href="editar.php?tipo=fac&facid='.$reg->id.'&token='.$token.'" target="_blank" class="btn btn-sm btn-success" title="Editar" data-toggle="tooltip"><span class="glyphicon glyphicon-edit"></span></a>';
        } else {
            $revisar = '';
        }
        
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
                
                
                <h5 class="text-center"><strong>FORMULÁRIO DE ACOMODAÇÃO</strong> <small class="text-secondary">(Nº.: $reg->id)</small> $revisar</h5>
                $info_head
                    
				    
					$quarto
					
                    <hr>
					<div class="row">
						<div class="col-12 col-md-10 offset-md-2">
                            <strong>Os quartos estão disponíveis nos dias:</strong><br>
							$dias_print
						</div>
					</div>
					<div class="row">
						<div class="col-12 col-md-3 offset-md-2">
							<strong>Em que andar ficam os quartos?</strong><br>$andar
						</div>
						<div class="col-12 col-md-3">
							<strong>Poderá prover condução?</strong><br>$transporte
						</div>
						<div class="col-12 col-sm-4">
							<strong>É o lar de Testemunhas de Jeová?</strong><br>$casa_tj
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<strong>Observações:</strong><br>"$reg->obs1"
							</div>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-sm-6">
							<h6 class="text-center"><strong>ENDEREÇO DO HOSPEDEIRO</strong></h6>
							<div class="row">
                                <div class="col-12">
                                    <strong>Nome:</strong><br>$reg->nome<br><br>
                                </div>
                            </div>
							<div class="row">
                                <div class="col-12">
                                    <strong>Endereço:</strong><br>$reg->endereco<br><br>
                                </div>
                            </div>
							<div class="row">
                                <div class="col-6">
                                    <strong>Telefone:</strong><br>$reg->telefone<br><br>
                                </div>
                                <div class="col-6">
                                    <strong>Cidade:</strong><br>$reg->cidade_nome<br><br>
                                </div>
                            </div><br>
                            <h6 class="text-center"><strong>PUBLICADOR QUE INDICOU</strong></h6>
							<div class="row">
                                <div class="col-6">
                                    <strong>Publicador:</strong><br>$reg->publicador_nome<br><br>
                                </div>
                                <div class="col-6">
                                    <strong>Telefone:</strong><br>$reg->publicador_tel<br><br>
                                </div>
                            </div>
						</div>
						<div class="col-sm-6">
							<div class="card bg-primary text-white">
                                <div class="card-body">
            						<h6 class="text-center"><strong>SECRETÁRIO DA CONGREGAÇÃO</strong></h6>
                					<div class="row">
                						<div class="col-sm-6">
                							<strong>Nome da Congregação:</strong><br>$reg->cong_nome<br><br>
                                            <strong>Cidade:</strong><br>$lin->cidade<br><br>
                						</div>
                						<div class="col-sm-6">
                							<strong>Nome do Secretário:</strong><br>$reg->cong_sec<br><br>
                                            <strong>Telefone do Secretário:</strong><br>$reg->cong_tel<br><br>
                						</div>
                					</div>
                					<div class="row">
                						<div class="col-sm-12">
                							<strong>Condição do(s) quarto(s):</strong><br>
                                            <label class="radio-inline"><input type="radio" name="condicao" disabled $exc>Excelente</label>
            								<label class="radio-inline"><input type="radio" name="condicao" disabled $boa>Boa</label>
            								<label class="radio-inline"><input type="radio" name="condicao" disabled $raz>Razoável</label><br>
                                            <strong>Observações:</strong><br>"$reg->obs2"<br>
                						</div>
                					</div>
                                </div>
        					</div>
						</div>
					</div>
					
					
					
					
DADOS;
        
        
        return $html;
        
    }
    
    public function getPEHQtd()
    {
        if($_SESSION['nivel'] == 1) { // Solicitante de hospedagem
            $def = $this->pdo->query('SELECT DISTINCT peh.id FROM peh LEFT JOIN cidade ON peh.congregacao_cidade_id = cidade.id WHERE cidade.solicitante_id = '.$_SESSION['id']);
            
        } else if ($_SESSION['nivel'] == 10) { // Responsável pela hospedagem
            $def = $this->pdo->query('SELECT DISTINCT peh.id FROM peh LEFT JOIN cidade ON peh.congregacao_cidade_id = cidade.id WHERE cidade.resp_id = '.$_SESSION['id']);
            
        } else if ($_SESSION['nivel'] == 20) { // Administrador
            $def = $this->pdo->query('SELECT DISTINCT peh.id FROM peh LEFT JOIN cidade ON peh.congregacao_cidade_id = cidade.id WHERE 1');
            
        }
        
        //TOTAL DE PEDIDOS
        return $def->rowCount();
    }
    
    public function getFACQtd()
    {
        if ($_SESSION['nivel'] == 10) { // Responsável pela hospedagem
            $abc = $this->pdo->query('SELECT DISTINCT fac.id FROM fac LEFT JOIN cidade ON fac.cidade = cidade.id WHERE cidade.hospedeiro = 1 AND cidade.resp_id = '.$_SESSION['id']);
            
        } else if ($_SESSION['nivel'] == 20) { // Administrador
            $abc = $this->pdo->query('SELECT DISTINCT fac.id FROM fac LEFT JOIN cidade ON fac.cidade = cidade.id WHERE cidade.hospedeiro = 1');
            
        }
        
        // TOTAL DE ACOMODAÇÕES
        return $abc->rowCount();
    }
    
    public function getPEHRevisarQtd()
    {
        if($_SESSION['nivel'] == 1) { // Solicitante de hospedagem
            $def = $this->pdo->query('SELECT DISTINCT peh.id FROM peh LEFT JOIN cidade ON peh.congregacao_cidade_id = cidade.id WHERE peh.revisar = 1 AND cidade.solicitante_id = '.$_SESSION['id']);
            
        } else if ($_SESSION['nivel'] == 10) { // Responsável pela hospedagem
            $def = $this->pdo->query('SELECT DISTINCT peh.id FROM peh LEFT JOIN cidade ON peh.congregacao_cidade_id = cidade.id WHERE peh.revisar = 1 AND cidade.resp_id = '.$_SESSION['id']);
            
        } else if ($_SESSION['nivel'] == 20) { // Administrador
            $def = $this->pdo->query('SELECT DISTINCT peh.id FROM peh LEFT JOIN cidade ON peh.congregacao_cidade_id = cidade.id WHERE peh.revisar = 1');
            
        }
        
        // PEDIDOS A REVISAR
        if($def->rowCount() > 0) {
            return '<span id="panel_peh_revisar" class="badge badge-danger" title="PEDIDOS ESPERANDO REVISÃO!" data-toggle="tooltip"><span class="glyphicon glyphicon-alert"></span> '.$def->rowCount().'</span>';
        } else {
            return '<span id="panel_peh_revisar" class="badge badge-success" title="PEDIDOS ESPERANDO REVISÃO!" data-toggle="tooltip">0</span>';
        }
        
    }
    
    public function getFACRevisarQtd()
    {
        if ($_SESSION['nivel'] == 10) { // Responsável pela hospedagem
            $abc = $this->pdo->query('SELECT DISTINCT fac.id FROM fac LEFT JOIN cidade ON fac.cidade = cidade.id WHERE fac.revisar = 1 AND cidade.hospedeiro = 1 AND cidade.resp_id = '.$_SESSION['id']);
            
        } else if ($_SESSION['nivel'] == 20) { // Administrador
            $abc = $this->pdo->query('SELECT DISTINCT fac.id FROM fac LEFT JOIN cidade ON fac.cidade = cidade.id WHERE fac.revisar = 1 AND cidade.hospedeiro = 1');
            
        }
        
        // PEDIDOS A REVISAR
        if($abc->rowCount() > 0) {
            return '<span id="panel_fac_revisar" class="badge badge-danger" title="FORMULÁRIOS ESPERANDO REVISÃO!" data-toggle="tooltip"><span class="glyphicon glyphicon-alert"></span> '.$abc->rowCount().'</span>';
        } else {
            return '<span id="panel_fac_revisar" class="badge badge-success" title="FORMULÁRIOS ESPERANDO REVISÃO!" data-toggle="tooltip">0</span>';
        }
        
    }
    
    public function getG_PEHSelect()
    {
        $html = '';
        
        if($_SESSION['nivel'] == 10) {
            $def = $this->pdo->query('SELECT DISTINCT peh.congregacao_cidade_id, cidade.cidade, cidade.estado FROM peh LEFT JOIN cidade ON peh.congregacao_cidade_id = cidade.id WHERE cidade.resp_id = '.$_SESSION['id'].' ORDER BY cidade.estado ASC, cidade.cidade ASC');
            
        } else if($_SESSION['nivel'] == 20) {
            $def = $this->pdo->query('SELECT DISTINCT peh.congregacao_cidade_id, cidade.cidade, cidade.estado FROM peh LEFT JOIN cidade ON peh.congregacao_cidade_id = cidade.id WHERE 1 ORDER BY cidade.estado ASC, cidade.cidade ASC');
            
        }
        
        $html .=<<<DADOS
        
						<option value="0" data-fac="0">Escolha um pedido de hospedagem:</option>
DADOS;
        
        
        $cidade = ''; $estado = '';
        if($def->rowCount() > 0) {
            while($lin = $def->fetch(PDO::FETCH_OBJ)) {
                if($cidade == '') {
                    $html .= '<optgroup label="'.$lin->cidade.'/'.$lin->estado.'">
';
                    $cidade = $lin->cidade;
                    $estado = $lin->estado;
                } else if($cidade <> $lin->cidade) {
                    $html .= '</optgroup>
													<optgroup label="'.$lin->cidade.'/'.$lin->estado.'">';
                    $cidade = $lin->cidade;
                    $estado = $lin->estado;
                }
                
                $abc = $this->pdo->query('SELECT peh.id, peh.fac_id, peh.revisar FROM peh WHERE peh.revisar = 0 AND congregacao_cidade_id = '.$lin->congregacao_cidade_id.' ORDER BY id ASC');
                if($abc->rowCount() > 0) {
                    while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
                        $token = md5($reg->id.session_id());
                        if($reg->fac_id == 0) {
                            $html .= '<option value="'.$reg->id.'" data-fac="'.$reg->fac_id.'" data-revisar="'.$reg->revisar.'" data-token="'.$token.'">Pedido - Nº '.$reg->id.'</option>';
                        } else {
                            $html .= '<option value="'.$reg->id.'" data-fac="'.$reg->fac_id.'" data-revisar="'.$reg->revisar.'">Pedido - Nº '.$reg->id.' &nbsp; [OK!]</option>';
                        }
                    }
                } else {
                    $html .= '<option value="" disabled>Sem Pedidos</option>';
                }
            }
            $html .= '</optgroup>';
        }
        
        
        
        return $html;
    }
    
    public function getG_FACSelect()
    {
        $html = '';
        
        if($_SESSION['nivel'] == 10) {
            $def = $this->pdo->query('SELECT DISTINCT fac.cidade AS cidade_id, cidade.cidade, cidade.estado FROM fac LEFT JOIN cidade ON fac.cidade = cidade.id WHERE cidade.hospedeiro = 1 AND cidade.resp_id = '.$_SESSION['id'].' ORDER BY cidade.estado ASC, cidade.cidade ASC');
            
        } else if($_SESSION['nivel'] == 20) {
            $def = $this->pdo->query('SELECT DISTINCT fac.cidade AS cidade_id, cidade.cidade, cidade.estado FROM fac LEFT JOIN cidade ON fac.cidade = cidade.id WHERE cidade.hospedeiro = 1 ORDER BY cidade.estado ASC, cidade.cidade ASC');
            
        }
        
        $html .=<<<DADOS
        
						<option value="0">Escolha uma acomodação:</option>
DADOS;
        
        $cidade = ''; $estado = '';
        if($def->rowCount() > 0) {
            while($lin = $def->fetch(PDO::FETCH_OBJ)) {
                if($cidade == '') {
                    $html .= '<optgroup label="'.$lin->cidade.'/'.$lin->estado.'">
';
                    $cidade = $lin->cidade;
                    $estado = $lin->estado;
                } else if($cidade <> $lin->cidade) {
                    $html .= '</optgroup>
													<optgroup label="'.$lin->cidade.'/'.$lin->estado.'">';
                    $cidade = $lin->cidade;
                    $estado = $lin->estado;
                }
                
                $vinculado = '';
                
                $abc = $this->pdo->query('SELECT fac.id, fac.revisar FROM fac WHERE fac.revisar = 0 AND cidade = '.$lin->cidade_id);
                if($abc->rowCount() > 0) {
                    while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
                        $token = md5($reg->id.session_id());
                        $xyz = $this->pdo->query('SELECT peh.id FROM peh WHERE peh.fac_id = '.$reg->id);
                        if($xyz->rowCount() > 0) {
                            $vinculado = ' &nbsp; [OK!]';
                        } else {
                            $vinculado = '';
                        }
                        
                        $html .= '<option value="'.$reg->id.'" data-revisar="'.$reg->revisar.'" data-token="'.$token.'">Acomodação - Nº '.$reg->id.$vinculado.'</option>';
                    }
                } else {
                    $html .= '<option value="" disabled>Sem Pedidos</option>';
                }
            }
            $html .= '</optgroup>';
        }
        unset($cidade, $estado, $abc, $def, $xyz, $vinculado, $reg, $lin);
        
        
        return $html;
    }
    
    public function getAtendimento($id, $link = true)
    {
        $html = '';
        
        try {
            // procura id do PEH no Banco de dados
            $abc = $this->pdo->prepare('SELECT peh.*, cidade.cidade as cong_cidade, cidade.estado as cong_estado FROM peh LEFT JOIN cidade ON peh.congregacao_cidade_id = cidade.id WHERE peh.id = :id');
            $abc->bindValue(':id', $id, PDO::PARAM_INT);
            $abc->execute();
            
            if($abc->rowCount() == 0) {
                $html .= 'Esse PEH não foi encontrado. Atualize a página, pois os dados podem ter sido atualizados nos últimos minutos.';
            } else {
                $reg = $abc->fetch(PDO::FETCH_OBJ);
                
                
                $html .=<<<DADOS
                
                <table class="table table-bordered" style="font-size:14px;">
                    <tbody>
                        <tr>
                            <td><strong>Pedido nº.:</strong> $reg->id</td>
                            <td><strong>Congregação:</strong> $reg->congregacao</td>
                            <td><strong>Cidade:</strong> $reg->cong_cidade/$reg->cong_estado</td>
                        </tr>
                        <tr>
                            <td colspan="3"><strong>Ocupantes:</strong> </td>
                        </tr>
DADOS;
                
                if($reg->oc1_nome != '') {
                    $html.=<<<DADOS

                        <tr>
                            <td>$reg->oc1_nome</td>
                            <td><strong>Idade:</strong> $reg->oc1_idade</td>
                            <td><strong>Privilégio:</strong> $reg->oc1_privilegio</td>
                        </tr>
DADOS;
                }
                if($reg->oc2_nome != '') {
                    $html.=<<<DADOS
                    
                        <tr>
                            <td>$reg->oc2_nome</td>
                            <td><strong>Idade:</strong> $reg->oc2_idade</td>
                            <td><strong>Privilégio:</strong> $reg->oc2_privilegio</td>
                        </tr>
DADOS;
                }
                
                if($reg->oc3_nome != '') {
                    $html.=<<<DADOS
                    
                        <tr>
                            <td>$reg->oc3_nome</td>
                            <td><strong>Idade:</strong> $reg->oc3_idade</td>
                            <td><strong>Privilégio:</strong> $reg->oc3_privilegio</td>
                        </tr>
DADOS;
                }
                
                if($reg->oc4_nome != '') {
                    $html.=<<<DADOS
                    
                        <tr>
                            <td>$reg->oc4_nome</td>
                            <td><strong>Idade:</strong> $reg->oc4_idade</td>
                            <td><strong>Privilégio:</strong> $reg->oc4_privilegio</td>
                        </tr>
DADOS;
                }
                $html.=<<<DADOS
                
                    </tbody>
                </table>
                <br>
DADOS;
                
                /*
                 * ########################## ACOMODAÇÃO
                 */
                
                // Verifica se foi atendido
                if($reg->fac_id == 0) {
                    // Não foi atendido
                    
                    $html .=<<<DADOS
                    
                <table class="table table-bordered" style="font-size:14px">
                    <tbody>
                        <tr>
                            <td><strong>Acomodação nº.:</strong> <span class="badge badge-warning">EM ABERTO!</span></td>
                        </tr>
                    </tbody>
                </table>
                <br>
DADOS;
                } else {
                    // Foi atendido
                    //Pesquisa FAC
                    
                    $def = $this->pdo->query('SELECT fac.* FROM fac WHERE fac.id = '.$reg->fac_id);
                    $lin = $def->fetch(PDO::FETCH_OBJ);
                    
                    //Trata dados
                    if($lin->casa_tj == true) {
                        $casa_tj = '<span class="badge badge-success">SIM</span>';
                    } else {
                        $casa_tj = '<span class="badge badge-light">NÃO</span>';
                    }
                    
                    $lin->condicao = strtoupper($lin->condicao);
                    
                    
                    
                    // CAMAS
                    $cama_sol = $lin->quarto1_sol_qtd + $lin->quarto2_sol_qtd + $lin->quarto3_sol_qtd + $lin->quarto4_sol_qtd;
                    $cama_cas = $lin->quarto1_cas_qtd + $lin->quarto2_cas_qtd + $lin->quarto3_cas_qtd + $lin->quarto4_cas_qtd;
                    $camas = $cama_cas + $cama_sol;
                    
                    $html .=<<<DADOS
                    
                <table class="table table-bordered" style="font-size:14px">
                    <tbody>
                        <tr>
                            <td colspan="3"><strong>Acomodação nº.:</strong> $lin->id</td>
                        </tr>
                        <tr>
                            <td><strong>Hospedeiro:</strong> $lin->nome</td>
                            <td><strong>Endereço:</strong> $lin->endereco</td>
                            <td><strong>Telefone:</strong> $lin->telefone</td>
                        </tr>
                        <tr>
                            <td><strong>Qtd de camas:</strong> $camas</td>
                            <td><strong>Lar de Testemunha de Jeová?</strong> $casa_tj</td>
                            <td><strong>Condição dos quartos:</strong> $lin->condicao</td>
                        </tr>
                        <tr>
                            <td colspan="2"><strong>Publicador que indicou:</strong> $lin->publicador_nome</td>
                            <td><strong>Telefone:</strong> $lin->publicador_tel</td>
                        </tr>
                    </tbody>
                </table>
                <br>
DADOS;
                }
                    
                
                /*
                 * ######################### RESPONSÁVEL PELA ACOMODAÇÃO
                 */
                $def = $this->pdo->query('SELECT cidade.*, login.nome, login.sobrenome, login.tel_res, login.tel_cel, login.email FROM cidade LEFT JOIN login ON cidade.resp_id = login.id WHERE cidade.id = '.$reg->congregacao_cidade_id);
                $lin = $def->fetch(PDO::FETCH_OBJ);
                
                if($lin->tel_res != '' || $lin->tel_cel != '') {
                    // Trata telefone
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
                
                $html .=<<<DADOS
                
                <table class="table table-bordered" style="font-size:14px">
                    <tbody>
                        <tr>
                            <td colspan="3"><strong>RESPONSÁVEL PELA ACOMODAÇÃO</strong></td>
                        </tr>
                        <tr>
                            <td><strong>Nome:</strong> $lin->nome $lin->sobrenome</td>
                            <td><strong>Telefone(s)</strong> $telefone</td>
                            <td><strong>E-mail:</strong> $lin->email</td>
                        </tr>
                    </tbody>
                </table>
                <br>
DADOS;
                
                if($link == true) {
                    /*
                     * ###################### MAIS INFORMAÇÕES
                     * 
                     */
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
                    
                    
                    
                    
                    $html .=<<<DADOS
                    
                    <table class="table table-bordered" style="font-size:14px">
                        <tbody>
                            <tr>
                                <td style="vertical-align: middle;"><strong>Compartilhar:</strong> <a href="$link" target="_blank">$link</a></td>
    							<td> <a data-toggle="modal" data-target="#modal02"><img class="img-responsive" src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=$link_qr"></a> </td>
                            </tr>
                        </tbody>
                    </table>
                    <div id="modal02" class="modal fade" role="dialog">
    					<div class="modal-dialog modal-lg">
    						<!-- CONTEUDO DO MODAL -->
    						<div class="modal-content">
    							<div class="modal-header">
    								<h4 class="modal-title"><strong><span class="glyphicon glyphicon-qrcode"></span> QRCODE AMPLIADO</strong></h4>
    								<button type="button" class="close" onclick="$('#modal02').modal('hide');">&times;</button>
    							</div>
    							<div class="modal-body" id="qr_modal" style="text-align:center">
    								<img class="img-responsive" src="https://api.qrserver.com/v1/create-qr-code/?size=400x400&data=$link_qr" style="margin:0 auto">
    							</div>
    							<div class="modal-footer">
    								<button type="button" class="btn btn-default" onclick="$('#modal02').modal('hide');">Fechar</button>
    							</div>
    						</div>
    					</div>
    				</div>
DADOS;
                }
            }
        } catch(PDOException $e) {
            $html.= 'Erro: '.$e->getMessage();
        }
        
        return $html;
    }
    
    public function getAtendimentoExt()
    {
        $html = '';
        /*
         * LINK PARA ACESSO EXTERNO!
         *
         * VALIDATION = Código criptografado em MD5. O código é: 'hospedagem' + codigo do pedido de hospedagem (enviado no TARGET) + 'ls3'.
         * TARGET = Número do pedido de hospedagem em encriptação BASE64.
         *
         * Em QR Codes, o link vai ficar um pouco diferente. O padrão é: TARGET + 'qrcode' + VALIDATION.
         * Isso permite que várias variáveis viajem juntas na mesma QueryString.
         * 
         * TRABALHA COM O GET!
         */
        
        
        // Validation e TARGET setados
        if(isset($_GET['validation']) && isset($_GET['target'])) {
            //Decodifica TARGET
            $target = base64_decode($_GET['target']);
            if($target == FALSE) {
                // Aborta!
                $html.= '<h4><strong>Acesso não autorizado!</strong></h4>';
            } else {
                // Verifica se o código de validação é legítimo.
                $validation = md5('hospedagem'.$target.'ls3');
                if($validation == $_GET['validation']) { // AUTORIZADO!!
                    // Resgata código do PEH, enviado no TARGET
                    $id = $target;
                    
                    $html .= $this->getAtendimento($id, false);
                    
                    
                    // Verifica se foi acessado via QR Code
                    if(isset($_GET['validation']) && !isset($_GET['target']))  {
                        // Verifica se foi o validation tem o termo 'qrcode';
                        $y = strpos($_GET['validation'], 'qrcode');
                        if($y != FALSE) {
                            $y = explode('-qrcode-', $_GET['validation']);
                            $_GET['target'] = $y[0];
                            $_GET['validation'] = $y[1];
                            
                            $html.=<<<DADOS
                            
                        <div style="width:100%; margin:0; text-align:center; font-weight:bold; color: #aaa; font-family: 'Trebuchet MS', 'Lucida Sans', sans-serif;">
                            <span class="glyphicon glyphicon-qrcode"></span> Acessado via QR-Code.
						</div>
DADOS;
                        }
                        
                    } 
                    
                    
                    
                } else {
                    // Não autorizado. Aborta
                    $html.= '<h4><strong>Acesso não autorizado!</strong></h4>';
                }
            }
            
        } else {
            // Aborta!
            $html .= '<h4><strong>Acesso não autorizado!</strong></h4>';
        }
        
        return $html;
    }
    
    public function setPEHNovo()
    {
        /*
        var_dump($_POST);
        exit();
        */
        // ########################################
                
        
        // Se não for enviado o tipo de acomodação, seta como 'casa'
        if(!isset($_POST['tipo_hospedagem']) || $_POST['tipo_hospedagem'] == '') {
            $_POST['tipo_hospedagem'] = 'casa';
        }
        
        // Se não for enviado transporte, seta como 'NÃO'
        if(!isset($_POST['transporte']) || $_POST['transporte'] == '') {
            $_POST['transporte'] = 'NÃO';
        }
        
        
        $query = "INSERT INTO `peh` (`id`, `nome`, `endereco`, `cidade`, `estado`, `tel_res`, `tel_cel`, `email`, `congregacao`, `congregacao_cidade_id`, `congresso_cidade`, `check_in`, `check_out`, `tipo_hospedagem`, `pagamento`, `transporte`, `oc1_nome`, `oc1_idade`, `oc1_sexo`, `oc1_parente`, `oc1_etnia`, `oc1_privilegio`, `oc2_nome`, `oc2_idade`, `oc2_sexo`, `oc2_parente`, `oc2_etnia`, `oc2_privilegio`, `oc3_nome`, `oc3_idade`, `oc3_sexo`, `oc3_parente`, `oc3_etnia`, `oc3_privilegio`, `oc4_nome`, `oc4_idade`, `oc4_sexo`, `oc4_parente`, `oc4_etnia`, `oc4_privilegio`, `motivo`, `secretario_nome`, `secretario_tel`, `secretario_email`, `data`) VALUES ";
        $query .= "(NULL, :nome, :endereco, :cidade, :estado, :tel_res, :tel_cel, :email, :congregacao, :congregacao_cidade_id, :congresso_cidade, :check_in, :check_out, :tipo_hospedagem, :pagamento, :transporte, :oc1_nome, :oc1_idade, :oc1_sexo, :oc1_parente, :oc1_etnia, :oc1_privilegio, :oc2_nome, :oc2_idade, :oc2_sexo, :oc2_parente, :oc2_etnia, :oc2_privilegio, :oc3_nome, :oc3_idade, :oc3_sexo, :oc3_parente, :oc3_etnia, :oc3_privilegio, :oc4_nome, :oc4_idade, :oc4_sexo, :oc4_parente, :oc4_etnia, :oc4_privilegio, :motivo, :secretario_nome, :secretario_tel, :secretario_email, :data)";
        
        
        
        try {
            $abc = $this->pdo->prepare($query);
            $abc->bindValue(':nome', $_POST['nome'], PDO::PARAM_STR);
            $abc->bindValue(':endereco', $_POST['endereco'], PDO::PARAM_STR);
            $abc->bindValue(':cidade', $_POST['cidade'], PDO::PARAM_STR);
            $abc->bindValue(':estado', $_POST['estado'], PDO::PARAM_STR);
            $abc->bindValue(':tel_res', $_POST['tel_res'], PDO::PARAM_STR);
            $abc->bindValue(':tel_cel', $_POST['tel_cel'], PDO::PARAM_STR);
            $abc->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
            $abc->bindValue(':congregacao', $_POST['congregacao'], PDO::PARAM_STR);
            $abc->bindValue(':congregacao_cidade_id', $_POST['congregacao_cidade_id'], PDO::PARAM_STR);
            $abc->bindValue(':congresso_cidade', $_POST['congresso_cidade'], PDO::PARAM_STR);
            $abc->bindValue(':check_in', $_POST['check_in'], PDO::PARAM_STR);
            $abc->bindValue(':check_out', $_POST['check_out'], PDO::PARAM_STR);
            $abc->bindValue(':tipo_hospedagem', $_POST['tipo_hospedagem'], PDO::PARAM_STR);
            $abc->bindValue(':pagamento', $_POST['pagamento'], PDO::PARAM_INT);
            $abc->bindValue(':transporte', $_POST['transporte'], PDO::PARAM_STR);
            
            $abc->bindValue(':oc1_nome', $_POST['oc1_nome'], PDO::PARAM_STR);
            $abc->bindValue(':oc1_idade', $_POST['oc1_idade'], PDO::PARAM_INT);
            $abc->bindValue(':oc1_sexo', $_POST['oc1_sexo'], PDO::PARAM_STR);
            $abc->bindValue(':oc1_parente', $_POST['oc1_parente'], PDO::PARAM_STR);
            $abc->bindValue(':oc1_etnia', $_POST['oc1_etnia'], PDO::PARAM_STR);
            $abc->bindValue(':oc1_privilegio', $_POST['oc1_privilegio'], PDO::PARAM_STR);
            
            // Se não houver nome no Ocupante 2, não cadastra nada
            if($_POST['oc2_nome'] == '') {
                $abc->bindValue(':oc2_nome', '', PDO::PARAM_STR);
                $abc->bindValue(':oc2_idade', 0, PDO::PARAM_INT);
                $abc->bindValue(':oc2_sexo', '', PDO::PARAM_STR);
                $abc->bindValue(':oc2_parente', '', PDO::PARAM_STR);
                $abc->bindValue(':oc2_etnia', '', PDO::PARAM_STR);
                $abc->bindValue(':oc2_privilegio', '', PDO::PARAM_STR);
            } else {
                $abc->bindValue(':oc2_nome', $_POST['oc2_nome'], PDO::PARAM_STR);
                $abc->bindValue(':oc2_idade', $_POST['oc2_idade'], PDO::PARAM_INT);
                $abc->bindValue(':oc2_sexo', $_POST['oc2_sexo'], PDO::PARAM_STR);
                $abc->bindValue(':oc2_parente', $_POST['oc2_parente'], PDO::PARAM_STR);
                $abc->bindValue(':oc2_etnia', $_POST['oc2_etnia'], PDO::PARAM_STR);
                $abc->bindValue(':oc2_privilegio', $_POST['oc2_privilegio'], PDO::PARAM_STR);
            }
            
            // Se não houver nome no Ocupante 3, não cadastra nada
            if($_POST['oc3_nome'] == '') {
                $abc->bindValue(':oc3_nome', '', PDO::PARAM_STR);
                $abc->bindValue(':oc3_idade', 0, PDO::PARAM_INT);
                $abc->bindValue(':oc3_sexo', '', PDO::PARAM_STR);
                $abc->bindValue(':oc3_parente', '', PDO::PARAM_STR);
                $abc->bindValue(':oc3_etnia', '', PDO::PARAM_STR);
                $abc->bindValue(':oc3_privilegio', '', PDO::PARAM_STR);
            } else {
                $abc->bindValue(':oc3_nome', $_POST['oc3_nome'], PDO::PARAM_STR);
                $abc->bindValue(':oc3_idade', $_POST['oc3_idade'], PDO::PARAM_INT);
                $abc->bindValue(':oc3_sexo', $_POST['oc3_sexo'], PDO::PARAM_STR);
                $abc->bindValue(':oc3_parente', $_POST['oc3_parente'], PDO::PARAM_STR);
                $abc->bindValue(':oc3_etnia', $_POST['oc3_etnia'], PDO::PARAM_STR);
                $abc->bindValue(':oc3_privilegio', $_POST['oc3_privilegio'], PDO::PARAM_STR);
            }
            
            // Se não houver nome no Ocupante 4, não cadastra nada
            if($_POST['oc4_nome'] == '') {
                $abc->bindValue(':oc4_nome', '', PDO::PARAM_STR);
                $abc->bindValue(':oc4_idade', 0, PDO::PARAM_INT);
                $abc->bindValue(':oc4_sexo', '', PDO::PARAM_STR);
                $abc->bindValue(':oc4_parente', '', PDO::PARAM_STR);
                $abc->bindValue(':oc4_etnia', '', PDO::PARAM_STR);
                $abc->bindValue(':oc4_privilegio', '', PDO::PARAM_STR);
            } else {
                $abc->bindValue(':oc4_nome', $_POST['oc4_nome'], PDO::PARAM_STR);
                $abc->bindValue(':oc4_idade', $_POST['oc4_idade'], PDO::PARAM_INT);
                $abc->bindValue(':oc4_sexo', $_POST['oc4_sexo'], PDO::PARAM_STR);
                $abc->bindValue(':oc4_parente', $_POST['oc4_parente'], PDO::PARAM_STR);
                $abc->bindValue(':oc4_etnia', $_POST['oc4_etnia'], PDO::PARAM_STR);
                $abc->bindValue(':oc4_privilegio', $_POST['oc4_privilegio'], PDO::PARAM_STR);
            }
            
            
            $abc->bindValue(':motivo', $_POST['motivo'], PDO::PARAM_STR);
            $abc->bindValue(':secretario_nome', $_POST['secretario_nome'], PDO::PARAM_STR);
            $abc->bindValue(':secretario_tel', $_POST['secretario_tel'], PDO::PARAM_STR);
            $abc->bindValue(':secretario_email', $_POST['secretario_email'], PDO::PARAM_STR);
            $abc->bindValue(':data', date('Y:m:d H:i:s'), PDO::PARAM_STR);
            
            
            $abc->execute();
            
            return 'success';
        } catch(PDOException $e) {
            return '<strong>Abortado!</strong> Erro: '.$e->getMessage();
        }
        
    }
    
    public function setFACNovo()
    {
        /*
        var_dump($_POST);
        exit();
        */
        // ########################################
        
        
        
        // Conta quantidade de quartos com cama.
        $conta_quartos = 0; $x = 1;
        while(isset($_POST['quarto'.$x])) {
            if($_POST['quarto'.$x] == 'yes') {
                // Confirma se a quantidade de camas foi informada.
                if($_POST['quarto'.$x.'_sol_qtd'] > 0 || $_POST['quarto'.$x.'_cas_qtd'] > 0) {
                    $conta_quartos++;
                }
            }
            $x++;
        }
        
        
        $quarto = ''; $x = 1;
        
        // Re-organiza os quartos
        while(isset($_POST['quarto'.$x])) {
            // Verifica se tem camas
            if($_POST['quarto'.$x.'_sol_qtd'] > 0 || $_POST['quarto'.$x.'_cas_qtd'] > 0) {// TEM CAMAS
                // Verifica se é o primeiro quarto
                if($x-1 > 0) { // NÃO É O PRIMEIRO QUARTO.
                    // Verifica se existe um quarto sem camas
                    $id = array_search(false, $quarto);
                    
                    if($id == false) { // QUARTOS ANTERIORES TEM CAMAS
                        // Atualiza status como OK
                        $quarto[$x] = 'OK';
                    } else { // QUARTO ANTERIOR NÃO TEM CAMAS
                        // Transfere todos as camas do quarto atual, para o quarto anterior vazio.
                        $_POST['quarto'.($id).'_sol_qtd'] = $_POST['quarto'.$x.'_sol_qtd'];
                        $_POST['quarto'.($id).'_cas_qtd'] = $_POST['quarto'.$x.'_cas_qtd'];
                        $_POST['quarto'.($id).'_valor1'] = $_POST['quarto'.$x.'_valor1'];
                        $_POST['quarto'.($id).'_valor2'] = $_POST['quarto'.$x.'_valor2'];
                        
                        /// Zera informações do quarto atual
                        $_POST['quarto'.$x.'_sol_qtd'] = '';
                        $_POST['quarto'.$x.'_cas_qtd'] = '';
                        $_POST['quarto'.$x.'_valor1'] = '';
                        $_POST['quarto'.$x.'_valor2'] = '';
                        
                        // Atualiza status do quarto anterior vazio.
                        $quarto[$id] = 'OK';
                        // Atualiza status do quarto atual.
                        $quarto[$x] = false;
                        unset($id);
                    }
                } else { // PRIMEIRO QUARTO
                    $quarto[$x] = 'OK';
                }
            } else { // NÃO TEM CAMAS
                $quarto[$x] = false;
            }
            $x++;
        }
        
        
        
        
        
        // Conta quantidade de dias.
        if(array_search('todos', $_POST['dias']) != FALSE) {
            $dias = 'todos';
        } else {
            $dias = implode(';',$_POST['dias']);
        }
        
        
        $query = "INSERT INTO `fac` (`id`, `quartos_qtd`, `quarto1_sol_qtd`, `quarto1_cas_qtd`, `quarto1_valor1`, `quarto1_valor2`, `quarto2_sol_qtd`, `quarto2_cas_qtd`, `quarto2_valor1`, `quarto2_valor2`, `quarto3_sol_qtd`, `quarto3_cas_qtd`, `quarto3_valor1`, `quarto3_valor2`, `quarto4_sol_qtd`, `quarto4_cas_qtd`, `quarto4_valor1`, `quarto4_valor2`, `dias`, `andar`, `transporte`, `casa_tj`, `obs1`, `nome`, `endereco`, `cidade`, `telefone`, `publicador_nome`, `publicador_tel`, `cong_nome`, `cong_cidade`, `cong_sec`, `cong_tel`, `condicao`, `obs2`) VALUES ";
        $query .= "(NULL, :quartos_qtd, :quarto1_sol_qtd, :quarto1_cas_qtd, :quarto1_valor1, :quarto1_valor2, :quarto2_sol_qtd, :quarto2_cas_qtd, :quarto2_valor1, :quarto2_valor2, :quarto3_sol_qtd, :quarto3_cas_qtd, :quarto3_valor1, :quarto3_valor2, :quarto4_sol_qtd, :quarto4_cas_qtd, :quarto4_valor1, :quarto4_valor2, :dias, :andar, :transporte, :casa_tj, :obs1, :nome, :endereco, :cidade, :telefone, :publicador_nome, :publicador_tel, :cong_nome, :cong_cidade, :cong_sec, :cong_tel, :condicao, :obs2)";
        
        
        try {
            $abc = $this->pdo->prepare($query);
            
            $abc->bindValue(':quartos_qtd', $conta_quartos, PDO::PARAM_INT);
            $abc->bindValue(':quarto1_sol_qtd', $_POST['quarto1_sol_qtd'], PDO::PARAM_INT);
            $abc->bindValue(':quarto1_cas_qtd', $_POST['quarto1_cas_qtd'], PDO::PARAM_INT);
            $abc->bindValue(':quarto1_valor1', $_POST['quarto1_valor1'], PDO::PARAM_INT);
            $abc->bindValue(':quarto1_valor2', $_POST['quarto1_valor2'], PDO::PARAM_INT);
            
            $abc->bindValue(':quarto2_sol_qtd', $_POST['quarto2_sol_qtd'], PDO::PARAM_INT);
            $abc->bindValue(':quarto2_cas_qtd', $_POST['quarto2_cas_qtd'], PDO::PARAM_INT);
            $abc->bindValue(':quarto2_valor1', $_POST['quarto2_valor1'], PDO::PARAM_INT);
            $abc->bindValue(':quarto2_valor2', $_POST['quarto2_valor2'], PDO::PARAM_INT);
            
            $abc->bindValue(':quarto3_sol_qtd', $_POST['quarto3_sol_qtd'], PDO::PARAM_INT);
            $abc->bindValue(':quarto3_cas_qtd', $_POST['quarto3_cas_qtd'], PDO::PARAM_INT);
            $abc->bindValue(':quarto3_valor1', $_POST['quarto3_valor1'], PDO::PARAM_INT);
            $abc->bindValue(':quarto3_valor2', $_POST['quarto3_valor2'], PDO::PARAM_INT);
            
            $abc->bindValue(':quarto4_sol_qtd', $_POST['quarto4_sol_qtd'], PDO::PARAM_INT);
            $abc->bindValue(':quarto4_cas_qtd', $_POST['quarto4_cas_qtd'], PDO::PARAM_INT);
            $abc->bindValue(':quarto4_valor1', $_POST['quarto4_valor1'], PDO::PARAM_INT);
            $abc->bindValue(':quarto4_valor2', $_POST['quarto4_valor2'], PDO::PARAM_INT);
            
            
            $abc->bindValue(':dias', $dias, PDO::PARAM_STR);
            $abc->bindValue(':andar', $_POST['andar'], PDO::PARAM_INT);
            $abc->bindValue(':transporte', $_POST['transporte'], PDO::PARAM_BOOL);
            $abc->bindValue(':casa_tj', $_POST['casa_tj'], PDO::PARAM_BOOL);
            $abc->bindValue(':obs1', addslashes($_POST['obs1']), PDO::PARAM_STR);
            $abc->bindValue(':nome', addslashes($_POST['nome']), PDO::PARAM_STR);
            $abc->bindValue(':endereco', addslashes($_POST['endereco']), PDO::PARAM_STR);
            $abc->bindValue(':cidade', $_POST['cidade'], PDO::PARAM_INT);
            $abc->bindValue(':telefone', $_POST['telefone'], PDO::PARAM_STR);
            $abc->bindValue(':publicador_nome', addslashes($_POST['publicador_nome']), PDO::PARAM_STR);
            $abc->bindValue(':publicador_tel', addslashes($_POST['publicador_tel']), PDO::PARAM_STR);
            $abc->bindValue(':cong_nome', addslashes($_POST['cong_nome']), PDO::PARAM_STR);
            $abc->bindValue(':cong_cidade', $_POST['cong_cidade'], PDO::PARAM_INT);
            $abc->bindValue(':cong_sec', addslashes($_POST['cong_sec']), PDO::PARAM_STR);
            $abc->bindValue(':cong_tel', $_POST['cong_tel'], PDO::PARAM_STR);
            $abc->bindValue(':condicao', $_POST['condicao'], PDO::PARAM_STR);
            $abc->bindValue(':obs2', addslashes($_POST['obs2']), PDO::PARAM_STR);
            
            
            $abc->execute();
            
            return 'success';
        } catch(PDOException $e) {
            return '<strong>Abortado!</strong> Erro: '.$e->getMessage();
        }
        
    }
    
    public function setPEHEdit()
    {
        //var_dump($_POST);
        if(!isset($_POST['pehid']) || $_POST['pehid'] == 0 || $_POST['pehid'] == '') {
            exit('Há dados faltando!');
        }
        
        
        try {
            $query = 'UPDATE `peh` SET `nome` = :nome, `endereco` = :endereco, `cidade` = :cidade, `estado` = :estado, `tel_res` = :tel_res, `tel_cel` = :tel_cel, `email` = :email, `congregacao` = :congregacao, `congregacao_cidade_id` = :congregacao_cidade_id, `congresso_cidade` = :congresso_cidade, `check_in` = :check_in, `check_out` = :check_out, `tipo_hospedagem` = :tipo_hospedagem, `pagamento` = :pagamento, `transporte` = :transporte, `oc1_nome` = :oc1_nome, `oc1_idade` = :oc1_idade, `oc1_sexo` = :oc1_sexo, `oc1_parente` = :oc1_parente, `oc1_etnia` = :oc1_etnia, `oc1_privilegio` = :oc1_privilegio, `oc2_nome` = :oc2_nome, `oc2_idade` = :oc2_idade, `oc2_sexo` = :oc2_sexo, `oc2_parente` = :oc2_parente, `oc2_etnia` = :oc2_etnia, `oc2_privilegio` = :oc2_privilegio, `oc3_nome` = :oc3_nome, `oc3_idade` = :oc3_idade, `oc3_sexo` = :oc3_sexo, `oc3_parente` = :oc3_parente, `oc3_etnia` = :oc3_etnia, `oc3_privilegio` = :oc3_privilegio, `oc4_nome` = :oc4_nome, `oc4_idade` = :oc4_idade, `oc4_sexo` = :oc4_sexo, `oc4_parente` = :oc4_parente, `oc4_etnia` = :oc4_etnia, `oc4_privilegio` = :oc4_privilegio, `motivo` = :motivo, `secretario_nome` = :secretario_nome, `secretario_tel` = :secretario_tel, `secretario_email` = :secretario_email, `data` = :data,`revisar` = 0 WHERE id = :fid';
            $abc = $this->pdo->prepare($query);
            
            $abc->bindValue(':nome', $_POST['nome'], PDO::PARAM_STR);
            $abc->bindValue(':endereco', $_POST['endereco'], PDO::PARAM_STR);
            $abc->bindValue(':cidade', $_POST['cidade'], PDO::PARAM_STR);
            $abc->bindValue(':estado', $_POST['estado'], PDO::PARAM_STR);
            $abc->bindValue(':tel_res', $_POST['tel_res'], PDO::PARAM_STR);
            $abc->bindValue(':tel_cel', $_POST['tel_cel'], PDO::PARAM_STR);
            $abc->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
            $abc->bindValue(':congregacao', $_POST['congregacao'], PDO::PARAM_STR);
            $abc->bindValue(':congregacao_cidade_id', $_POST['congregacao_cidade_id'], PDO::PARAM_STR);
            $abc->bindValue(':congresso_cidade', $_POST['congresso_cidade'], PDO::PARAM_STR);
            $abc->bindValue(':check_in', $_POST['check_in'], PDO::PARAM_STR);
            $abc->bindValue(':check_out', $_POST['check_out'], PDO::PARAM_STR);
            $abc->bindValue(':tipo_hospedagem', $_POST['tipo_hospedagem'], PDO::PARAM_STR);
            $abc->bindValue(':pagamento', $_POST['pagamento'], PDO::PARAM_INT);
            $abc->bindValue(':transporte', $_POST['transporte'], PDO::PARAM_STR);
            
            $abc->bindValue(':oc1_nome', $_POST['oc1_nome'], PDO::PARAM_STR);
            $abc->bindValue(':oc1_idade', $_POST['oc1_idade'], PDO::PARAM_INT);
            $abc->bindValue(':oc1_sexo', $_POST['oc1_sexo'], PDO::PARAM_STR);
            $abc->bindValue(':oc1_parente', $_POST['oc1_parente'], PDO::PARAM_STR);
            $abc->bindValue(':oc1_etnia', $_POST['oc1_etnia'], PDO::PARAM_STR);
            $abc->bindValue(':oc1_privilegio', $_POST['oc1_privilegio'], PDO::PARAM_STR);
            
            // Se não houver nome no Ocupante 2, não cadastra nada
            if($_POST['oc2_nome'] == '') {
                $abc->bindValue(':oc2_nome', '', PDO::PARAM_STR);
                $abc->bindValue(':oc2_idade', 0, PDO::PARAM_INT);
                $abc->bindValue(':oc2_sexo', '', PDO::PARAM_STR);
                $abc->bindValue(':oc2_parente', '', PDO::PARAM_STR);
                $abc->bindValue(':oc2_etnia', '', PDO::PARAM_STR);
                $abc->bindValue(':oc2_privilegio', '', PDO::PARAM_STR);
            } else {
                $abc->bindValue(':oc2_nome', $_POST['oc2_nome'], PDO::PARAM_STR);
                $abc->bindValue(':oc2_idade', $_POST['oc2_idade'], PDO::PARAM_INT);
                $abc->bindValue(':oc2_sexo', $_POST['oc2_sexo'], PDO::PARAM_STR);
                $abc->bindValue(':oc2_parente', $_POST['oc2_parente'], PDO::PARAM_STR);
                $abc->bindValue(':oc2_etnia', $_POST['oc2_etnia'], PDO::PARAM_STR);
                $abc->bindValue(':oc2_privilegio', $_POST['oc2_privilegio'], PDO::PARAM_STR);
            }
            
            // Se não houver nome no Ocupante 3, não cadastra nada
            if($_POST['oc3_nome'] == '') {
                $abc->bindValue(':oc3_nome', '', PDO::PARAM_STR);
                $abc->bindValue(':oc3_idade', 0, PDO::PARAM_INT);
                $abc->bindValue(':oc3_sexo', '', PDO::PARAM_STR);
                $abc->bindValue(':oc3_parente', '', PDO::PARAM_STR);
                $abc->bindValue(':oc3_etnia', '', PDO::PARAM_STR);
                $abc->bindValue(':oc3_privilegio', '', PDO::PARAM_STR);
            } else {
                $abc->bindValue(':oc3_nome', $_POST['oc3_nome'], PDO::PARAM_STR);
                $abc->bindValue(':oc3_idade', $_POST['oc3_idade'], PDO::PARAM_INT);
                $abc->bindValue(':oc3_sexo', $_POST['oc3_sexo'], PDO::PARAM_STR);
                $abc->bindValue(':oc3_parente', $_POST['oc3_parente'], PDO::PARAM_STR);
                $abc->bindValue(':oc3_etnia', $_POST['oc3_etnia'], PDO::PARAM_STR);
                $abc->bindValue(':oc3_privilegio', $_POST['oc3_privilegio'], PDO::PARAM_STR);
            }
            
            // Se não houver nome no Ocupante 4, não cadastra nada
            if($_POST['oc4_nome'] == '') {
                $abc->bindValue(':oc4_nome', '', PDO::PARAM_STR);
                $abc->bindValue(':oc4_idade', 0, PDO::PARAM_INT);
                $abc->bindValue(':oc4_sexo', '', PDO::PARAM_STR);
                $abc->bindValue(':oc4_parente', '', PDO::PARAM_STR);
                $abc->bindValue(':oc4_etnia', '', PDO::PARAM_STR);
                $abc->bindValue(':oc4_privilegio', '', PDO::PARAM_STR);
            } else {
                $abc->bindValue(':oc4_nome', $_POST['oc4_nome'], PDO::PARAM_STR);
                $abc->bindValue(':oc4_idade', $_POST['oc4_idade'], PDO::PARAM_INT);
                $abc->bindValue(':oc4_sexo', $_POST['oc4_sexo'], PDO::PARAM_STR);
                $abc->bindValue(':oc4_parente', $_POST['oc4_parente'], PDO::PARAM_STR);
                $abc->bindValue(':oc4_etnia', $_POST['oc4_etnia'], PDO::PARAM_STR);
                $abc->bindValue(':oc4_privilegio', $_POST['oc4_privilegio'], PDO::PARAM_STR);
            }
            
            
            $abc->bindValue(':motivo', $_POST['motivo'], PDO::PARAM_STR);
            $abc->bindValue(':secretario_nome', $_POST['secretario_nome'], PDO::PARAM_STR);
            $abc->bindValue(':secretario_tel', $_POST['secretario_tel'], PDO::PARAM_STR);
            $abc->bindValue(':secretario_email', $_POST['secretario_email'], PDO::PARAM_STR);
            $abc->bindValue(':data', date('Y:m:d H:i:s'), PDO::PARAM_STR);
            
            $abc->bindValue(':fid', $_POST['pehid'], PDO::PARAM_INT);
            
            $abc->execute();
            return 'success';
            
        } catch(PDOException $e) {
            return '<strong>Abortado!</strong> Erro: '.$e->getMessage();
        }
    }
    
    public function setFACEdit()
    {
        //var_dump($_POST);
        if(!isset($_POST['facid']) || $_POST['facid'] == 0 || $_POST['facid'] == '') {
            exit('Há dados faltando!');
        }
        
        try {
            // Conta quantidade de quartos.
            $conta_quartos = 0; $x = 1;
            while(isset($_POST['quarto'.$x])) {
                if($_POST['quarto'.$x] == 'yes') {
                    // Confirma se a quantidade de camas foi informada.
                    if($_POST['quarto'.$x.'_sol_qtd'] > 0 || $_POST['quarto'.$x.'_cas_qtd'] > 0) {
                        $conta_quartos++;
                    }
                }
                $x++;
            }
            
            
            $quarto = ''; $x = 1;
            
            // Re-organiza os quartos
            while(isset($_POST['quarto'.$x])) {
                // Verifica se tem camas
                if($_POST['quarto'.$x.'_sol_qtd'] > 0 || $_POST['quarto'.$x.'_cas_qtd'] > 0) {// TEM CAMAS
                    // Verifica se é o primeiro quarto
                    if($x-1 > 0) { // NÃO É O PRIMEIRO QUARTO.
                        // Verifica se existe um quarto sem camas
                        $id = array_search(false, $quarto);
                        
                        if($id == false) { // QUARTOS ANTERIORES TEM CAMAS
                            // Atualiza status como OK
                            $quarto[$x] = 'OK';
                        } else { // QUARTO ANTERIOR NÃO TEM CAMAS
                            // Transfere todos as camas do quarto atual, para o quarto anterior vazio.
                            $_POST['quarto'.($id).'_sol_qtd'] = $_POST['quarto'.$x.'_sol_qtd'];
                            $_POST['quarto'.($id).'_cas_qtd'] = $_POST['quarto'.$x.'_cas_qtd'];
                            $_POST['quarto'.($id).'_valor1'] = $_POST['quarto'.$x.'_valor1'];
                            $_POST['quarto'.($id).'_valor2'] = $_POST['quarto'.$x.'_valor2'];
                            
                            /// Zera informações do quarto atual
                            $_POST['quarto'.$x.'_sol_qtd'] = '';
                            $_POST['quarto'.$x.'_cas_qtd'] = '';
                            $_POST['quarto'.$x.'_valor1'] = '';
                            $_POST['quarto'.$x.'_valor2'] = '';
                            
                            // Atualiza status do quarto anterior vazio.
                            $quarto[$id] = 'OK';
                            // Atualiza status do quarto atual.
                            $quarto[$x] = false;
                            unset($id);
                        }
                    } else { // PRIMEIRO QUARTO
                        $quarto[$x] = 'OK';
                    }
                } else { // NÃO TEM CAMAS
                    $quarto[$x] = false;
                }
                $x++;
            }
            
            
            // Varre variáveis dos quartos
            for($x=1; $x <= 4; $x++) {
                if($_POST['quarto'.$x.'_sol_qtd'] == '') {
                    $_POST['quarto'.$x.'_sol_qtd'] = 0;
                }
                if($_POST['quarto'.$x.'_cas_qtd'] == '') {
                    $_POST['quarto'.$x.'_cas_qtd'] = 0;
                }
                if($_POST['quarto'.$x.'_valor1'] == '') {
                    $_POST['quarto'.$x.'_valor1'] = 0;
                }
                if($_POST['quarto'.$x.'_valor2'] == '') {
                    $_POST['quarto'.$x.'_valor2'] = 0;
                }
            }
            
            
            // Conta quantidade de dias.
            if(array_search('todos', $_POST['dias']) != FALSE) {
                $dias = 'todos';
            } else {
                $dias = implode(';',$_POST['dias']);
            }
            
            
            $query = 'UPDATE `fac` SET `quartos_qtd` = :quartos_qtd, `quarto1_sol_qtd` = :quarto1_sol_qtd, `quarto1_cas_qtd` = :quarto1_cas_qtd, `quarto1_valor1` = :quarto1_valor1, `quarto1_valor2` = :quarto1_valor2, `quarto2_sol_qtd` = :quarto2_sol_qtd, `quarto2_cas_qtd` = :quarto2_cas_qtd, `quarto2_valor1` = :quarto2_valor1, `quarto2_valor2` = :quarto2_valor2, `quarto3_sol_qtd` = :quarto3_sol_qtd, `quarto3_cas_qtd` = :quarto3_cas_qtd, `quarto3_valor1` = :quarto3_valor1, `quarto3_valor2` = :quarto3_valor2, `quarto4_sol_qtd` = :quarto4_sol_qtd, `quarto4_cas_qtd` = :quarto4_cas_qtd, `quarto4_valor1` = :quarto4_valor1, `quarto4_valor2` = :quarto4_valor2, `dias` = :dias, `andar` = :andar, `transporte` = :transporte, `casa_tj` = :casa_tj, `obs1` = :obs1, `nome` = :nome, `endereco` = :endereco, `telefone` = :telefone, `cidade` = :cidade, `publicador_nome` = :publicador_nome, `publicador_tel` = :publicador_tel, `cong_nome` = :cong_nome, `cong_cidade` = :cong_cidade, `cong_sec` = :cong_sec, `cong_tel` = :cong_tel, `condicao` = :condicao, `obs2` = :obs2, `revisar` = 0 WHERE `id` = :fid';
            $abc = $this->pdo->prepare($query);
            
            
            $abc->bindValue(':quartos_qtd', $conta_quartos, PDO::PARAM_INT);
            $abc->bindValue(':quarto1_sol_qtd', (int)$_POST['quarto1_sol_qtd'], PDO::PARAM_INT);
            $abc->bindValue(':quarto1_cas_qtd', (int)$_POST['quarto1_cas_qtd'], PDO::PARAM_INT);
            $abc->bindValue(':quarto1_valor1', (int)$_POST['quarto1_valor1'], PDO::PARAM_INT);
            $abc->bindValue(':quarto1_valor2', (int)$_POST['quarto1_valor2'], PDO::PARAM_INT);
            
            $abc->bindValue(':quarto2_sol_qtd', $_POST['quarto2_sol_qtd'], PDO::PARAM_INT);
            $abc->bindValue(':quarto2_cas_qtd', $_POST['quarto2_cas_qtd'], PDO::PARAM_INT);
            $abc->bindValue(':quarto2_valor1', $_POST['quarto2_valor1'], PDO::PARAM_INT);
            $abc->bindValue(':quarto2_valor2', $_POST['quarto2_valor2'], PDO::PARAM_INT);
            
            $abc->bindValue(':quarto3_sol_qtd', $_POST['quarto3_sol_qtd'], PDO::PARAM_INT);
            $abc->bindValue(':quarto3_cas_qtd', $_POST['quarto3_cas_qtd'], PDO::PARAM_INT);
            $abc->bindValue(':quarto3_valor1', $_POST['quarto3_valor1'], PDO::PARAM_INT);
            $abc->bindValue(':quarto3_valor2', $_POST['quarto3_valor2'], PDO::PARAM_INT);
            
            $abc->bindValue(':quarto4_sol_qtd', $_POST['quarto4_sol_qtd'], PDO::PARAM_INT);
            $abc->bindValue(':quarto4_cas_qtd', $_POST['quarto4_cas_qtd'], PDO::PARAM_INT);
            $abc->bindValue(':quarto4_valor1', $_POST['quarto4_valor1'], PDO::PARAM_INT);
            $abc->bindValue(':quarto4_valor2', $_POST['quarto4_valor2'], PDO::PARAM_INT);
            
            $abc->bindValue(':dias', $dias, PDO::PARAM_STR);
            $abc->bindValue(':andar', $_POST['andar'], PDO::PARAM_INT);
            $abc->bindValue(':transporte', $_POST['transporte'], PDO::PARAM_BOOL);
            $abc->bindValue(':casa_tj', $_POST['casa_tj'], PDO::PARAM_BOOL);
            $abc->bindValue(':obs1', addslashes($_POST['obs1']), PDO::PARAM_STR);
            $abc->bindValue(':nome', addslashes($_POST['nome']), PDO::PARAM_STR);
            $abc->bindValue(':endereco', addslashes($_POST['endereco']), PDO::PARAM_STR);
            $abc->bindValue(':telefone', $_POST['telefone'], PDO::PARAM_STR);
            $abc->bindValue(':cidade', $_POST['cidade'], PDO::PARAM_INT);
            $abc->bindValue(':publicador_nome', addslashes($_POST['publicador_nome']), PDO::PARAM_STR);
            $abc->bindValue(':publicador_tel', addslashes($_POST['publicador_tel']), PDO::PARAM_STR);
            $abc->bindValue(':cong_nome', addslashes($_POST['cong_nome']), PDO::PARAM_STR);
            $abc->bindValue(':cong_cidade', $_POST['cong_cidade'], PDO::PARAM_INT);
            $abc->bindValue(':cong_sec', addslashes($_POST['cong_sec']), PDO::PARAM_STR);
            $abc->bindValue(':cong_tel', $_POST['cong_tel'], PDO::PARAM_STR);
            $abc->bindValue(':condicao', $_POST['condicao'], PDO::PARAM_STR);
            $abc->bindValue(':obs2', addslashes($_POST['obs2']), PDO::PARAM_STR);
            
            $abc->bindValue(':fid', (int)$_POST['facid'], PDO::PARAM_INT);
            
            
            $abc->execute();
            return 'success';
        } catch(PDOException $e) {
            return '<strong>Abortado!</strong> Erro: '.$e->getMessage();
        }
    }

    public function setVinculo($pehid, $facid)
    {
        if((int)$pehid == 0 || (int)$facid == 0) { // Não aceita valores nulos e que não sejam números
            return 'Dados enviados estão incorretos!';
            exit();
        }
        
        // Procura PEH
        $abc = $this->pdo->prepare('SELECT * FROM peh WHERE id = :id');
        $abc->bindValue(":id", $pehid, PDO::PARAM_INT);
        $abc->execute();
        
        if($abc->rowCount() == 0) { // Nada encontrado, encerra.
            return 'Pedido de hospedagem não encontrado.';
        } else { // Pedido encontrado
            $peh = $abc->fetch(PDO::FETCH_OBJ); // ############### LINHA DO PEH!
            
            // Procura FAC
            $abc = $this->pdo->prepare('SELECT * FROM fac WHERE id = :id');
            $abc->bindValue(":id", $facid, PDO::PARAM_INT);
            $abc->execute();
            
            if($abc->rowCount() == 0) { // Nada encontrado, encerra.
                return 'Formulário de acomodação não encontrado';
            } else { // Acomodação encontrada
                $fac = $abc->fetch(PDO::FETCH_OBJ); // ################ LINHA DO FAC!
                
                
                // VERIFICO SE O PEDIDO DE HOSPEDAGEM JÁ FOI VINCULADO A UMA ACOMODAÇÃO.
                if($peh->fac_id == 0) {
                    // PEDIDO EM ABERTO. INICIA PROCESSO DE VINCULAÇÃO.
                    
                    // Antes, verifica se a acomodação já etá vinculada a outra hospedagem.
                    $abc = $this->pdo->prepare('SELECT peh.id FROM peh WHERE fac_id = :id');
                    $abc->bindValue(":id", $facid, PDO::PARAM_INT);
                    $abc->execute();
                    
                    if($abc->rowCount() == 0) { // Não há vinculos! Continua vinculação!
                        try {
                            $abc = $this->pdo->prepare('UPDATE peh SET fac_id = :facid WHERE id = :pehid');
                            $abc->bindValue(":facid", $facid, PDO::PARAM_INT);
                            $abc->bindValue(":pehid", $pehid, PDO::PARAM_INT);
                            
                            $abc->execute();
                            return 'success';
                        } catch(PDOException $e) {
                            return 'Abortado! Erro: '.$e->getMessage();
                        }
                    } else { // Há vinculos! Para!!
                        return 'Acomodação vinculada com outra hospedagem.';
                    }
                } else {
                    // PEDIDO NÃO ESTÁ EM ABERTO
                    return 'Pedido de hospedagem vinculado a outra acomodação. Não é possivel continuar...';
                }
            }
        }
    }
    
    public function setDesvinculo($pehid, $facid)
    {
        if((int)$pehid == 0 || (int)$facid == 0) { // Não aceita valores nulos e que não sejam números
            return 'Dados enviados estão incorretos!';
            exit();
        }
        
        // Procura PEH
        $abc = $this->pdo->prepare('SELECT * FROM peh WHERE id = :id');
        $abc->bindValue(":id", $pehid, PDO::PARAM_INT);
        $abc->execute();
        
        if($abc->rowCount() == 0) { // Nada encontrado, encerra.
            return 'Pedido de hospedagem não encontrado.';
        } else { // Pedido encontrado
            $peh = $abc->fetch(PDO::FETCH_OBJ); // ############### LINHA DO PEH!
            
            // Procura FAC
            $abc = $this->pdo->prepare('SELECT * FROM fac WHERE id = :id');
            $abc->bindValue(":id", $facid, PDO::PARAM_INT);
            $abc->execute();
            
            if($abc->rowCount() == 0) { // Nada encontrado, encerra.
                return 'Formulário de acomodação não encontrado';
            } else { // Acomodação encontrada
                $fac = $abc->fetch(PDO::FETCH_OBJ); // ################ LINHA DO FAC!
                
                
                // VERIFICO SE O PEDIDO DE HOSPEDAGEM JÁ FOI VINCULADO A ESTAACOMODAÇÃO.
                if($peh->fac_id == $fac->id) {
                    // PEDIDO JÁ FOI VINCULADO. ENTÃO, INICIA PROCESSO DE DESVINCULAÇÃO.
                    
                    try {
                        $abc = $this->pdo->prepare('UPDATE peh SET fac_id = 0 WHERE id = :pehid');
                        $abc->bindValue(":pehid", $pehid, PDO::PARAM_INT);
                        
                        $abc->execute();
                        return 'success';
                    } catch(PDOException $e) {
                        return 'Abortado! Erro: '.$e->getMessage();
                    }
                    
                } else {
                    // PEDIDOS NÃO ESTÃO VINCULADOS
                    return 'Pedido de Hospedagem e a Acomodação não estão vinculados.';
                }
            }
        }
        
    }
    
    public function setDesvinculo_Sozinho($tipo, $id)
    {
        if($tipo == 'peh') {
            $abc = $this->pdo->prepare('UPDATE `peh` SET `fac_id` = 0 WHERE `id` = :id');
            $abc->bindValue(':id', $id, PDO::PARAM_INT);
            $abc->execute();
            return 'success';
        } else if($tipo == 'fac') {
            $abc = $this->pdo->prepare('UPDATE `peh` SET `fac_id` = 0 WHERE `fac_id` = :id');
            $abc->bindValue(':id', $id, PDO::PARAM_INT);
            $abc->execute();
            return 'success';
        } else {
            return 'Parâmetros incorretos...';
        }
    }

    public function setRevisar($tipo, $id, $token = '')
    {
        if($tipo == 'peh') {
            $token_novo = md5($id.session_id());
            if($token == $token_novo) {
                $abc = $this->pdo->prepare('UPDATE `peh` SET `revisar` = 1 WHERE `id` = :id');
                $abc->bindValue(':id', $id, PDO::PARAM_INT);
                $abc->execute();
                return 'success';
            } else {
                return 'Erro 403: Token de confirmação inválido. Tente novamente, ou contate desenvolvedor.';
            }
        } else if($tipo == 'fac') {
            $abc = $this->pdo->prepare('UPDATE `fac` SET `revisar` = 1 WHERE `id` = :id');
            $abc->bindValue(':id', $id, PDO::PARAM_INT);
            $abc->execute();
            return 'success';
        } else {
            return 'Erro 404: Operação não pode ser concluída, pois o resultado foi inesperado!';
        }
    }
    
    public function setApagar($tipo, $id, $token)
    {
        // verifica o tipo do formulário que vai ser apagado
        if($tipo == 'PEH') {
            // Tipo PEH. Somente nivel 1 e 20 podem excluir esse formulário.
            if($_SESSION['nivel'] == 1 || $_SESSION['nivel'] == 20) {
                try {
                    // Pesquisa formulário no BD
                    $sql = 'SELECT id FROM peh WHERE id = :id';
                    $abc = $this->pdo->prepare($sql);
                    $abc->bindValue(':id', $id, PDO::PARAM_INT);
                    $abc->execute();
                    
                    // Checa se algo foi encontrado
                    if($abc->rowCount() > 0) {
                        $reg = $abc->fetch(PDO::FETCH_OBJ);
                        
                        // calcula o token desse registro
                        $token_novo = md5($reg->id.session_id());
                        
                        // Verifica o token
                        if($token == $token_novo) {
                            // Autorizado. Exclui registro
                            $sql = 'DELETE FROM peh WHERE id = :id';
                            $abc = $this->pdo->prepare($sql);
                            $abc->bindValue(':id', $id, PDO::PARAM_INT);
                            $abc->execute();
                            
                            return 'success';
                            
                        } else {
                            // Token incorreto
                            return 'Erro 403: chave de autorização para essa operação foi recusada!';
                        }
                        
                    } else {
                        return 'Erro 404: Desculpa, mas esse PEH não foi encontrado. Talvez já tenha sido excluído.'; 
                    }
                    
                    
                    
                } catch(PDOException $e) {
                    return 'Abortado! Erro: '.$e->getMessage();
                }
                
            } else {
                return 'Erro 403: Você não tem permissão para fazer isso!';
            }
        } else if($tipo == 'FAC') {
            // Tipo FAC. Somente nivel 10 e 20 podem excluir esse formulário.
            if($_SESSION['nivel'] == 10 || $_SESSION['nivel'] == 20) {
                try {
                    // Pesquisa formulário no BD
                    $sql = 'SELECT id FROM fac WHERE id = :id';
                    $abc = $this->pdo->prepare($sql);
                    $abc->bindValue(':id', $id, PDO::PARAM_INT);
                    $abc->execute();
                    
                    // Checa se algo foi encontrado
                    if($abc->rowCount() > 0) {
                        $reg = $abc->fetch(PDO::FETCH_OBJ);
                        
                        // calcula o token desse registro
                        $token_novo = md5($reg->id.session_id());
                        
                        // Verifica o token
                        if($token == $token_novo) {
                            // Autorizado. Exclui registro
                            $sql = 'DELETE FROM fac WHERE id = :id';
                            $abc = $this->pdo->prepare($sql);
                            $abc->bindValue(':id', $id, PDO::PARAM_INT);
                            $abc->execute();
                            
                            return 'success';
                            
                        } else {
                            // Token incorreto
                            return 'Erro 403: chave de autorização para essa operação foi recusada!';
                        }
                        
                    } else {
                        return 'Erro 404: Desculpa, mas esse FAC não foi encontrado. Talvez já tenha sido excluído.';
                    }
                    
                } catch(PDOException $e) {
                    return 'Abortado! Erro: '.$e->getMessage();
                }
            } else {
                return 'Erro 403: Você não tem permissão para fazer isso!';
            }
        } else {
            return 'Tipo de formulário inválido.';
        }
    }
    
    public function setPerfilSalva()
    {
        if(!isset($_POST['nome']) || !isset($_POST['sobrenome']) || !isset($_POST['id']) || $_POST['nome'] == '' || $_POST['sobrenome'] == '' || $_POST['id'] == '') {
            return 'Desculpa, mas não foi possivel fazer essa operação.';
        } else {
            // Checa se enviou número de telefone
            if($_POST['tel_cel'] != '' || $_POST['tel_res'] != '') {
                // Enviou ao menos um
                $abc = $this->pdo->prepare('UPDATE login SET nome = :nome, sobrenome = :sobrenome, email = :email, tel_res = :tel_res, tel_cel = :tel_cel WHERE id = :id');
                try {
                    $abc->bindValue(':nome', $_POST['nome'], PDO::PARAM_STR);
                    $abc->bindValue(':sobrenome', $_POST['sobrenome'], PDO::PARAM_STR);
                    $abc->bindValue(':email', addslashes($_POST['email']), PDO::PARAM_STR);
                    $abc->bindValue(':tel_res', $_POST['tel_res'], PDO::PARAM_STR);
                    $abc->bindValue(':tel_cel', $_POST['tel_cel'], PDO::PARAM_STR);
                    $abc->bindValue(':id', $_POST['id'], PDO::PARAM_STR);
                    
                    $abc->execute();
                    return 'success';
                } catch(PDOException $e) {
                    return 'Erro: '.$e->getMessage();
                }
            } else {
                // Não enviou nenhum
                return 'Você não informou um número de telefone para contato.';
            }
        }
    }
    
    public function setMudaSenha()
    {
        if(!isset($_POST['senha_atual']) || !isset($_POST['senha1']) || !isset($_POST['senha2']) || $_POST['senha_atual'] == '' || $_POST['senha1'] == '' || $_POST['senha2'] == '') {
            return 'Houve um erro. Necessário confirmar senha atual e informar a senha nova duas vezes.';
        } else {
            if($_POST['senha1'] == $_POST['senha2']) {
                $abc = $this->pdo->query('SELECT senha FROM login WHERE id = '.$_SESSION['id']);
                $reg = $abc->fetch(PDO::FETCH_OBJ);
                if($reg->senha == hash('sha256', $_POST['senha_atual'])) {
                    $abc = $this->pdo->prepare('UPDATE login SET senha = :senha WHERE id = :id');
                    try{
                        $abc->bindValue(':senha', hash("sha256", $_POST['senha1']), PDO::PARAM_STR);
                        $abc->bindValue(':id', $_SESSION['id'], PDO::PARAM_INT);
                        $abc->execute();
                        
                        return 'success';
                    } catch(PDOException $e) {
                        return 'Erro: '.$e->getMessage();
                    }
                } else {
                    return 'A senha atual está errada!';
                }
            } else {
                return 'Senhas não são iguais.';
            }
        }
    }
    
    
    
    
    
    // PÁGINAS
    public function pageNovoPEH()
    {
        $html = '';
        
        $hoje = new DateTime();
        
        if((int)$hoje->format('n') >= 6 && (int)$hoje->format('n') < 10) { // Se o mes atual for entre junho e outubro, o motivo é Congresso Regional de LS
            $motivo_peh = 'Congresso Regional de LS';
        } else { // Motivo é Assembleia de LS
            $motivo_peh = 'Assembleia de LS';
        }
        
        unset($hoje);
        
        
        // Resgata cookies
        if(isset($_COOKIE['congresso_cidade']) && $_COOKIE['congresso_cidade'] != '' ) {
            $congresso_cidade = $_COOKIE['congresso_cidade'];
            $congresso_cidade_status = 'has-autocomplete';
        } else {
            $congresso_cidade = '';
            $congresso_cidade_status = '';
        }
        
        if(isset($_COOKIE['congregacao']) && $_COOKIE['congregacao'] != '' ) {
            $congregacao = $_COOKIE['congregacao'];
            $congregacao_status = 'has-autocomplete';
        } else {
            $congregacao = '';
            $congregacao_status = '';
        }
        
        if(isset($_COOKIE['secretario_nome']) && $_COOKIE['secretario_nome'] != '' ) {
            $secretario_nome = $_COOKIE['secretario_nome'];
            $secretario_nome_status = 'has-autocomplete';
        } else {
            $secretario_nome = '';
            $secretario_nome_status = '';
        }
        
        if(isset($_COOKIE['secretario_tel']) && $_COOKIE['secretario_tel'] != '' ) {
            $secretario_tel = $_COOKIE['secretario_tel'];
            $secretario_tel_status = 'has-autocomplete';
        } else {
            $secretario_tel = '';
            $secretario_tel_status = '';
        }
        
        if(isset($_COOKIE['secretario_email']) && $_COOKIE['secretario_email'] != '' ) {
            $secretario_email = $_COOKIE['secretario_email'];
            $secretario_email_status = 'has-autocomplete';
        } else {
            $secretario_email = '';
            $secretario_email_status = '';
        }
        
        // Fim de resgate dos cookies
        
        
        if($_SESSION['nivel'] == 20 || $_SESSION['nivel'] == 1) {
            if($_SESSION['nivel'] == 20) { // Se for administrador, exibe todas as cidades
                $abc = $this->pdo->query('SELECT * FROM cidade WHERE hospedeiro = 0 ORDER BY cidade ASC');
            } else { // Só exibe as cidades que o usuário é solicitante
                $abc = $this->pdo->query('SELECT * FROM cidade WHERE hospedeiro = 0 AND solicitante_id = '.$_SESSION['id'].' ORDER BY cidade ASC');
            }
            $cong_cidade = '';
            if($abc->rowCount() > 0) {
                while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
                    $cong_cidade.= <<<DADOS
                    
										<option value="$reg->id">$reg->cidade/$reg->estado</option>
DADOS;
                }
            }
            
            $html.=<<<DADOS

                <form id="peh_form" action="#" method="POST" onsubmit="validatePEH(); return false;"><br>
					<h3 class="text-center"><strong>PEDIDO ESPECIAL DE HOSPEDAGEM</strong>
                    <span class="text-danger" data-toggle="tooltip" title="Itens marcados com asterisco são obrigatórios!">*</span></h3>
					
					<h4><strong>PUBLICADOR</strong></h4>
					<hr>
					<div class="row">
						<div class="col-12 col-md-6">
							<div class="form-group">
								<label for="nome">Nome:</label>
								<input type="text" name="nome" id="nome" class="form-control" autofocus required="required" onchange="$('oc1_nome').val($(this).val());">
								<small class="text-secondary">O mesmo que no campo ocupante 1 abaixo</small>
							</div>
							
							<div class="form-group">
								<label for="endereco">Endereço:</label>
								<input type="text" name="endereco" id="endereco" class="form-control" required="required">
							</div>
							
							<div class="form-group row">
								<div class="col-6">
									<label for="cidade">Cidade:</label>
									<input type="text" name="cidade" id="cidade" class="form-control" required="required">
								</div>
								<div class="col-3">
									<label for="estado">Estado:</label>
									<select name="estado" id="estado" class="form-control">
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
								<div class="col-3">
									<label for="pais">País:</label>
									<select disabled name="pais" id="pais" class="form-control">
										<option value="BRA">Brasil</option>
									</select>
								</div>
							</div>
							
							<div class="form-group row">
								<div class="col-6">
									<label for="tel_res">Telefone Residencial:</label>
									<input type="number" name="tel_res" id="tel_res" class="form-control">
									<small class="text-secondary">Formato: (xx) xxxx-xxxx [Só números]</small>
								</div>
								<div class="col-6">
									<label for="tel_cel">Telefone Celular:</label>
									<input type="number" name="tel_cel" id="tel_cel" class="form-control">
									<small class="text-secondary">Formato: (xx) x xxxx-xxxx [Só números]</small>
								</div>
							</div>
							
							<div class="form-group">
								<label for="email">E-mail:</label>
								<input type="email" name="email" id="email" class="form-control">
							</div>
							
							<div class="form-group row">
								<div class="col-6">
									<label for="congregacao">Congregação:</label>
                                    <span class="glyphicon glyphicon-question-sign" style="cursor:pointer;" title="Auto-preenchimento inteligente" data-content="Os campos com fundo verde são campos que foram salvos em cookies no seu computador, possibilitando o auto-preenchimento automático. Sempre que alterar um desses campos, a informação ficará salva para o próximo pedido.<br><br> Se o campo abaixo ainda não ficou verde, assim que prenchê-lo e concluir o envio, os dados serão salvos para o próximo uso." data-trigger="hover" data-toggle="popover"></span>
									<input type="text" name="congregacao" id="congregacao" class="form-control $congregacao_status" required="required" value="$congregacao">
								</div>
								<div class="col-6">
									<label for="congregacao_cidade_id">Cidade:</label>
									<select id="congregacao_cidade_id" name="congregacao_cidade_id" class="form-control">
									   $cong_cidade
									</select>
								</div>
							</div>
						</div>
						<div class="col-12 col-md-6">
							<div class="form-group">
								<label for="congresso_cidade">Cidade do congresso:</label> 
                                <span class="glyphicon glyphicon-question-sign" style="cursor:pointer;" title="Auto-preenchimento inteligente" data-content="Os campos com fundo verde são campos que foram salvos em cookies no seu computador, possibilitando o auto-preenchimento automático. Sempre que alterar um desses campos, a informação ficará salva para o próximo pedido.<br><br> Se o campo abaixo ainda não ficou verde, assim que prenchê-lo e concluir o envio, os dados serão salvos para o próximo uso." data-trigger="hover" data-toggle="popover"></span>
								<input type="text" name="congresso_cidade" id="congresso_cidade" class="form-control $congresso_cidade_status" required="required" value="$congresso_cidade">
							</div>
							
							<div class="form-group">
								<label for="check_in">Primeira noite que precisará do quarto:</label>
								<input type="date" name="check_in" id="check_in" class="form-control" required="required">
							</div>
							
							<div class="form-group">
								<label for="check_out">Última noite que precisará do quarto:</label>
								<input type="date" name="check_out" id="check_out" class="form-control" required="required">
							</div>
							
							<div class="form-group">
								<label for="tipo_hospedagem">Tipo de acomodação:</label>
								<div class="radio" id="tipo_hospedagem">
									<label><input type="radio" name="tipo_hospedagem"  onclick="$('#tipo_hospedagem').val($(this).val())" value="casa"> Casa particular</label>
									<label><input type="radio" name="tipo_hospedagem" onclick="$('#tipo_hospedagem').val($(this).val())" value="hotel"> Hotel</label>
								</div>
							</div>
							
							<div class="form-group">
								<label for="pagamento">Quanto pode pagar por esse quarto, <span style="font-weight:bold; text-decoration: underline;">por noite (em reais)?</span></label>
								<input type="number" name="pagamento" id="pagamento" class="form-control">
								<small class="text-secondary">Veja a seção "Quartos de Hotel" no fim do formulário</small>
							</div>
							
							<div class="form-group">
								<label for="transporte">Terá transporte próprio enquanto estiver na cidade do congresso?</label>
								<div class="radio" id="transporte">
									<label><input type="radio" name="transporte" onclick="$('#transporte').val($(this).val())" value="SIM"> Sim</label>
									<label><input type="radio" name="transporte" onclick="$('#transporte').val($(this).val())" value="NÃO"> Não</label>
								</div>
							</div>
						</div>
					</div>
					
					<br>
					<h4><strong>OCUPANTES DO QUARTO <small>(É recomendado o máximo de 2 pessoas por casa.)</small></strong></h4>
					
					<table class="table table-hover table-striped">
						<tbody>
							<tr><td>
								<h5><strong>Ocupante 1</strong></h5>
								<div class="form-group row">
									<div class="col-sm-3">
										<label for="oc1_nome">Nome:</label>
										<input type="text" name="oc1_nome" id="oc1_nome" class="form-control" required="required">
									</div>
									<div class="col-sm-1">
										<label for="oc1_idade">Idade:</label>
										<input type="number" name="oc1_idade" id="oc1_idade" class="form-control" required="required">
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
										<input type="text" name="oc1_parente" id="oc1_parente" class="form-control" required="required">
									</div>
									<div class="col-sm-2">
										<label for="oc1_etnia">Etnia:</label>
										<input type="text" name="oc1_etnia" id="oc1_etnia" class="form-control">
									</div>
									<div class="col-sm-2">
										<label for="oc1_privilegio" style="font-size: 80%">Publicador, pioneiro, etc...</label>
										<input type="text" name="oc1_privilegio" id="oc1_privilegio" class="form-control" required="required">
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
					<div class="card bg-secondary text-white">
                        <div class="card-body">
    						<h4><strong>SECRETÁRIO DA CONGREGAÇÃO</strong></h4>
    						<div class="form-group">
    							<label for="motivo">Explique por que esta é uma necessidade especial: </label>
                                <span class="glyphicon glyphicon-question-sign" style="cursor:pointer;" title="Auto-preenchimento" data-content="O campo abaixo conta com o auto-preenchimento com base na data que você está preenchendo o formulário: se for entre junho e setembro, o motivo será o Congresso Regional; nas demais datas, o motivo será a Assembleia." data-trigger="hover" data-toggle="popover"></span>
    							<textarea rows="2" class="form-control has-autocomplete" name="motivo" id="motivo" required="required">$motivo_peh</textarea>
    						</div>
    						
    						<div class="form-group row">
    							<div class="col-12 col-md-4">
    								<label for="secretario_nome">Nome do Secretário:</label>
                                    <span class="glyphicon glyphicon-question-sign" style="cursor:pointer;" title="Auto-preenchimento inteligente" data-content="Os campos com fundo verde são campos que foram salvos em cookies no seu computador, possibilitando o auto-preenchimento automático. Sempre que alterar um desses campos, a informação ficará salva para o próximo pedido.<br><br> Se o campo abaixo ainda não ficou verde, assim que prenchê-lo e concluir o envio, os dados serão salvos para o próximo uso." data-trigger="hover" data-toggle="popover"></span>
    								<input type="text" name="secretario_nome" id="secretario_nome" class="form-control $secretario_nome_status" required="required" value="$secretario_nome">
    							</div>
    							<div class="col-12 col-md-4">
    								<label for="secretario_tel">Telefone do Secretário:</label>
                                    <span class="glyphicon glyphicon-question-sign" style="cursor:pointer;" title="Auto-preenchimento inteligente" data-content="Os campos com fundo verde são campos que foram salvos em cookies no seu computador, possibilitando o auto-preenchimento automático. Sempre que alterar um desses campos, a informação ficará salva para o próximo pedido.<br><br> Se o campo abaixo ainda não ficou verde, assim que prenchê-lo e concluir o envio, os dados serão salvos para o próximo uso." data-trigger="hover" data-toggle="popover"></span>
    								<input type="number" name="secretario_tel" id="secretario_tel" class="form-control $secretario_tel_status" required="required" value="$secretario_tel">
    							</div>
    							<div class="col-12 col-md-4">
    								<label for="secretario_email">E-mail do Secretário:</label>
                                    <span class="glyphicon glyphicon-question-sign" style="cursor:pointer;" title="Auto-preenchimento inteligente" data-content="Os campos com fundo verde são campos que foram salvos em cookies no seu computador, possibilitando o auto-preenchimento automático. Sempre que alterar um desses campos, a informação ficará salva para o próximo pedido.<br><br> Se o campo abaixo ainda não ficou verde, assim que prenchê-lo e concluir o envio, os dados serão salvos para o próximo uso." data-trigger="hover" data-toggle="popover"></span>
    								<input type="text" name="secretario_email" id="secretario_email" class="form-control $secretario_email" value="$secretario_email_status">
    							</div>
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
                        <input type="hidden" id="tipo_hospedagem" value="">
                        <input type="hidden" id="transporte" value="">
						<button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-send"></span> <strong>Enviar pedido</strong></button>
					</div>
					
				</form>
DADOS;
        } else {
            $html.= 'Acesso negado! Seu nível de acesso não permite acesso à este formulário.';
        }
            
        
        
        return $html;
    }
    
    public function pageNovoFAC()
    {
        $html = '';
        
        if($_SESSION['nivel'] == 20 || $_SESSION['nivel'] == 10) {
            if($_SESSION['nivel'] == 20) {
                $abc = $this->pdo->query('SELECT * FROM cidade WHERE hospedeiro = 1');
            } else {
                $abc = $this->pdo->query('SELECT * FROM cidade WHERE hospedeiro = 1 AND resp_id = '.$_SESSION['id']);
            }
            $cidade = '';
            if($abc->rowCount() > 0) {
                while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
                    $cidade.= <<<DADOS
										<option value="$reg->id">$reg->cidade/$reg->estado</option>
										
DADOS;
                }
            }
            
            // Resgata cookies
            if(isset($_COOKIE['cong_nome']) && $_COOKIE['cong_nome'] != '' ) {
                $cong_nome = $_COOKIE['cong_nome'];
                $cong_nome_status = 'has-autocomplete';
            } else {
                $cong_nome = '';
                $cong_nome_status = '';
            }
            
            if(isset($_COOKIE['cong_sec']) && $_COOKIE['cong_sec'] != '' ) {
                $cong_sec = $_COOKIE['cong_sec'];
                $cong_sec_status = 'has-autocomplete';
            } else {
                $cong_sec = '';
                $cong_sec_status = '';
            }
            
            if(isset($_COOKIE['cong_tel']) && $_COOKIE['cong_tel'] != '' ) {
                $cong_tel = $_COOKIE['cong_tel'];
                $cong_tel_status = 'has-autocomplete';
            } else {
                $cong_tel = '';
                $cong_tel_status = '';
            }
            // Fim do resgate de cookies
            
            
            $html.=<<<DADOS
                
                <br>
                <h3 class="text-center"><strong>FORMULÁRIO DE ACOMODAÇÃO</strong><br></h3>
			
				<!--<div class="alert alert-danger visible-sm visible-xs">
					<strong>Sinal vermelho!</strong><br>
					Esse formulário não pode ser preenchido/exibido em dispositivos móveis. <i><strong>Utilize um computador ou equipamento de tela maior.</strong></i>
				</div>-->
				<form action="#" method="post" class="" onsubmit="validateFAC(); return false;">
					<div class="row">
						<div class="col-sm-10 offset-sm-2">
							<div class="alert alert-info">
								<span class="glyphicon glyphicon-info-sign"></span> Se a acomodação possui diversos quartos com diversas camas, talvez seja melhor cadastrar cada quarto como uma acomodação diferente.<br>
								Essa prática visa organizar da melhor forma os recursos disponíveis, facilitar a organização e a compartimentalização de informações.
							</div>
						</div>
					</div>
					
					
					<div class="form-group row" id="quarto1">
						<div class="col-12 col-sm-2 hidden-xs hidden-sm">
							<h5 class="float-right" style="text-align:right;"><strong>QUARTO 1</strong><br>
							<small class="text-primary" style="cursor:pointer;font-size:0.7rem"><a onclick="mostraQuarto()">(Adicionar outro quarto)</a></small></h5>
						</div>
                        <div class="col-12 col-sm-2 hidden-md hidden-lg hidden-xl">
							<h5 style="text-align:center"><strong>QUARTO 1</strong><br>
							<small class="text-primary" style="cursor:pointer;font-size:0.7rem"><a onclick="mostraQuarto()">(Adicionar outro quarto)</a></small></h5>
						</div>
						<div class="col-6 col-sm-5">
							<div class="card bg-secondary text-white">
                                <div class="card-body">
    								<div class="row text-center">
                                        <div class="col-12"><label>Quantidade de camas</label></div>
    								</div>
    								<div class="row">
    									<div class="col-6">
    										<input type="number" name="quarto1_sol_qtd" id="quarto1_sol_qtd" class="form-control" placeholder="Solteiro">
    									</div>
    									<div class="col-6">
    										<input type="number" name="quarto1_cas_qtd" id="quarto1_cas_qtd" class="form-control" placeholder="Casal">
    									</div>
    								</div>
                                </div>
							</div>
						</div>
						<div class="col-6 col-sm-5">
							<div class="card bg-secondary text-white">
                                <div class="card-body">
    								<div class="row text-center">
    									<div class="col-12"><label>Preço do quarto por dia</label></div>
    								</div>
    								<div class="row">
    									<div class="col-6">
    										<input type="number" name="quarto1_valor1" id="quarto1_valor1" class="form-control" placeholder="Um no quarto">
    									</div>
    									<div class="col-6">
    										<input type="number" name="quarto1_valor2" id="quarto1_valor2" class="form-control" placeholder="Dois ou mais no quarto">
    									</div>
    								</div>
                                </div>
							</div>
						</div>
                        
					</div>
					<div class="form-group row" id="quarto2" style="display:none">
						<div class="col-12 col-sm-2 hidden-xs hidden-sm">
							<h5 class="float-right" style="text-align:right;"><strong>QUARTO 2</strong><br>
							<small class="text-primary" style="cursor:pointer;font-size:0.7rem"><a onclick="mostraQuarto()">(Adicionar outro quarto)</a></small></h5>
						</div>
                        <div class="col-12 col-sm-2 hidden-md hidden-lg hidden-xl hidden-xl">
							<h5 style="text-align:center"><strong>QUARTO 2</strong><br>
							<small class="text-primary" style="cursor:pointer;font-size:0.7rem"><a onclick="mostraQuarto()">(Adicionar outro quarto)</a></small></h5>
						</div>
						<div class="col-6 col-sm-5">
							<div class="card bg-secondary text-white">
                                <div class="card-body">
    								<div class="row text-center">
    									<div class="col-12"><label>Quantidade de camas</label></div>
    								</div>
    								<div class="row">
    									<div class="col-6">
    										<input type="number" name="quarto2_sol_qtd" id="quarto2_sol_qtd" class="form-control"  placeholder="Solteiro">
    									</div>
    									<div class="col-6">
    										<input type="number" name="quarto2_cas_qtd" id="quarto2_cas_qtd" class="form-control"  placeholder="Casal">
    									</div>
    								</div>
                                </div>
							</div>
						</div>
						<div class="col-6 col-sm-5">
							<div class="card bg-secondary text-white">
                                <div class="card-body">
    								<div class="row text-center">
    									<div class="col-12"><label>Preço do quarto por dia</label></div>
    								</div>
    								<div class="row">
    									<div class="col-6">
    										<input type="number" name="quarto2_valor1" id="quarto2_valor1" class="form-control" placeholder="Um no quarto">
    									</div>
    									<div class="col-6">
    										<input type="number" name="quarto2_valor2" id="quarto2_valor2" class="form-control" placeholder="Dois ou mais no quarto">
    									</div>
    								</div>
                                </div>
							</div>
						</div>
                        
					</div>
					<div class="form-group row" id="quarto3" style="display:none">
						<div class="col-12 col-sm-2 hidden-xs hidden-sm">
							<h5 class="float-right" style="text-align:right;"><strong>QUARTO 3</strong><br>
							<small class="text-primary" style="cursor:pointer;font-size:0.7rem"><a onclick="mostraQuarto()">(Adicionar outro quarto)</a></small></h5>
						</div>
                        <div class="col-12 col-sm-2 hidden-md hidden-lg hidden-xl">
							<h5 style="text-align:center"><strong>QUARTO 3</strong><br>
							<small class="text-primary" style="cursor:pointer;font-size:0.7rem"><a onclick="mostraQuarto()">(Adicionar outro quarto)</a></small></h5>
						</div>
						<div class="col-6 col-sm-5">
							<div class="card bg-secondary text-white">
                                <div class="card-body">
    								<div class="row text-center">
    									<div class="col-12"><label>Quantidade de camas</label></div>
    								</div>
    								<div class="row">
    									<div class="col-6">
    										<input type="number" name="quarto3_sol_qtd" id="quarto3_sol_qtd"  class="form-control"  placeholder="Solteiro">
    									</div>
    									<div class="col-6">
    										<input type="number" name="quarto3_cas_qtd" id="quarto3_cas_qtd" class="form-control"  placeholder="Casal">
    									</div>
    								</div>
                                </div>
							</div>
						</div>
						<div class="col-6 col-sm-5">
							<div class="card bg-secondary text-white">
                                <div class="card-body">
    								<div class="row text-center">
    									<div class="col-12"><label>Preço do quarto por dia</label></div>
    								</div>
    								<div class="row">
    									<div class="col-6">
    										<input type="number" name="quarto3_valor1" id="quarto3_valor1" class="form-control" placeholder="Um no quarto">
    									</div>
    									<div class="col-6">
    										<input type="number" name="quarto3_valor2" id="quarto3_valor2" class="form-control" placeholder="Dois ou mais no quarto">
    									</div>
    								</div>
                                </div>
							</div>
						</div>
                        
					</div>
					<div class="form-group row" id="quarto4" style="display:none">
						<div class="col-12 col-sm-2 hidden-xs hidden-sm">
							<h5 class="float-right " style="text-align:right;"><strong>QUARTO 4</strong></h5>
						</div>
                        <div class="col-12 col-sm-2 hidden-md hidden-lg hidden-xl">
							<h5 style="text-align:center;"><strong>QUARTO 4</strong></h5>
						</div>
						<div class="col-6 col-sm-5">
							<div class="card bg-secondary text-white">
                                <div class="card-body">
    								<div class="row text-center">
    									<div class="col-12"><label>Quantidade de camas</label></div>
    								</div>
    								<div class="row">
    									<div class="col-6">
    										<input type="number" name="quarto4_sol_qtd" id="quarto4_sol_qtd"  class="form-control"  placeholder="Solteiro">
    									</div>
    									<div class="col-6">
    										<input type="number" name="quarto4_cas_qtd" id="quarto4_cas_qtd" class="form-control"  placeholder="Casal">
    									</div>
    								</div>
                                </div>
							</div>
						</div>
						<div class="col-6 col-sm-5">
							<div class="card bg-secondary text-white">
                                <div class="card-body">
    								<div class="row text-center">
    									<div class="col-12"><label>Preço do quarto por dia</label></div>
    								</div>
    								<div class="row">
    									<div class="col-6">
    										<input type="number" name="quarto4_valor1" id="quarto4_valor1" class="form-control" placeholder="Um no quarto">
    									</div>
    									<div class="col-6">
    										<input type="number" name="quarto4_valor2" id="quarto4_valor2" class="form-control" placeholder="Dois ou mais no quarto">
    									</div>
    								</div>
                                </div>
							</div>
						</div>
					</div>
					
                    <hr>
					<div class="row">
						<div class="col-sm-10 offset-sm-2">
							<div class="form-group">
								<label>Os quartos estão disponíveis nos dias:</label><br>
								<label class="checkbox-inline"><input type="checkbox" name="dias[]" value="1">Domingo</label> &nbsp; &nbsp;
								<label class="checkbox-inline"><input type="checkbox" name="dias[]" value="2">Segunda</label> &nbsp; &nbsp;
								<label class="checkbox-inline"><input type="checkbox" name="dias[]" value="3">Terça</label> &nbsp; &nbsp;
								<label class="checkbox-inline"><input type="checkbox" name="dias[]" value="4">Quarta</label> &nbsp; &nbsp;
								<label class="checkbox-inline"><input type="checkbox" name="dias[]" value="5">Quinta</label> &nbsp; &nbsp;
								<label class="checkbox-inline"><input type="checkbox" name="dias[]" value="6">Sexta</label> &nbsp; &nbsp;
								<label class="checkbox-inline"><input type="checkbox" name="dias[]" value="7">Sábado</label> &nbsp; &nbsp;
								<label class="checkbox-inline"><input type="checkbox" name="dias[]" value="todos"><strong>Todos</strong></label>
								
							</div>
						</div>
					</div>
					<div class="form-group row">
						<div class="col-sm-3 offset-sm-2">
							<label>Em que andar ficam os quartos?<br> <small class="text-secondary">(Assuma térreo como 0)</small></label>
							<input type="number" name="andar" id="andar" value="0" class="form-control">
						</div>
						<div class="col-sm-3">
							<label>Poderá prover condução?</label>
							<select name="transporte" id="transporte" class="form-control">
								<option value="1">Sim</option>
								<option value="0">Não</option>
							</select>
						</div>
						<div class="col-sm-4">
							<label>É o lar de Testemunhas de Jeová?</label>
							<select name="casa_tj" id="casa_tj" class="form-control">
								<option value="1">Sim</option>
								<option value="0">Não</option>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<label>Observações:</label>
								<textarea rows="2" name="obs1" id="obs1" class="form-control"></textarea>
							</div>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-sm-6">
							<h5 CLASS="text-center"><strong>ENDEREÇO DO HOSPEDEIRO</strong></h5>
							<div class="form-group">
								<label>Nome:</label>
								<input type="text" name="nome" id="nome" class="form-control" required="required">
							</div>
							<div class="form-group">
								<label>Endereço <small class="text-secondary">(incluir bairro)</small>:</label>
								<input type="text" name="endereco" id="endereco" class="form-control" required="required">
							</div>
							<div class="row">
								<div class="col-sm-6">
									<label>Telefone:</label>
									<input type="text" name="telefone" id="telefone" class="form-control" required="required">
								</div>
								<div class="col-sm-6">
									<label>Cidade:</label>
									<select name="cidade" id="cidade" class="form-control">
										$cidade
									</select>
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<h5 CLASS="text-center"><strong>PUBLICADOR QUE INDICOU</strong><br>
							<small class="text-secondary">(Publicador que conseguiu a hospedagem, se não for o hospedeiro)</small></h5>
							<div class="form-group">
								<label>Publicador:</label>
								<input type="text" name="publicador_nome" id="publicador_nome" class="form-control">
							</div>
							<div class="form-group">
								<label>Telefone</label>
								<input type="text" name="publicador_tel" id="publicador_tel" class="form-control">
							</div>
						</div>
					</div>
					
					<hr>
					<div class="card bg-secondary text-white">
                        <div class="card-body">
    						<h4><strong>SECRETÁRIO DA CONGREGAÇÃO</strong></h4>
    						<div class="form-group row">
    							<div class="col-sm-4">
    								<label>Nome da Congregação:</label>
                                    <span class="glyphicon glyphicon-question-sign" style="cursor:pointer;" title="Auto-preenchimento inteligente" data-content="Os campos com fundo verde são campos que foram salvos em cookies no seu computador, possibilitando o auto-preenchimento automático. Sempre que alterar um desses campos, a informação ficará salva para o próximo pedido.<br><br> Se o campo abaixo ainda não ficou verde, assim que prenchê-lo e concluir o envio, os dados serão salvos para o próximo uso." data-trigger="hover" data-toggle="popover"></span>
    								<input type="text" name="cong_nome" id="cong_nome" class="form-control $cong_nome_status" value="$cong_nome">
    								<br>
    								<label>Cidade:</label>
    								<select name="cong_cidade" id="cong_cidade" class="form-control">
    									$cidade
    								</select>
    							</div>
    							<div class="col-sm-4">
    								<label>Nome do Secretário:</label>
                                    <span class="glyphicon glyphicon-question-sign" style="cursor:pointer;" title="Auto-preenchimento inteligente" data-content="Os campos com fundo verde são campos que foram salvos em cookies no seu computador, possibilitando o auto-preenchimento automático. Sempre que alterar um desses campos, a informação ficará salva para o próximo pedido.<br><br> Se o campo abaixo ainda não ficou verde, assim que prenchê-lo e concluir o envio, os dados serão salvos para o próximo uso." data-trigger="hover" data-toggle="popover"></span>
    								<input type="text" name="cong_sec" id="cong_sec" class="form-control $cong_sec_status" required="required" value="$cong_sec">
    								<br>
    								<label>Telefone do Secretário:</label>
                                    <span class="glyphicon glyphicon-question-sign" style="cursor:pointer;" title="Auto-preenchimento inteligente" data-content="Os campos com fundo verde são campos que foram salvos em cookies no seu computador, possibilitando o auto-preenchimento automático. Sempre que alterar um desses campos, a informação ficará salva para o próximo pedido.<br><br> Se o campo abaixo ainda não ficou verde, assim que prenchê-lo e concluir o envio, os dados serão salvos para o próximo uso." data-trigger="hover" data-toggle="popover"></span>
    								<input type="text" name="cong_tel" id="cong_tel" class="form-control $cong_tel_status" required="required" value="$cong_tel">
    							</div>
    							<div class="col-sm-4">
    								<label>Condição do(s) quarto(s):</label><br>
    								<label class="radio-inline"><input type="radio" name="condicao" onclick="$('#condicao').val($(this).val());" value="excelente">Excelente</label>
    								<label class="radio-inline"><input type="radio" name="condicao" onclick="$('#condicao').val($(this).val());" value="boa">Boa</label>
    								<label class="radio-inline"><input type="radio" name="condicao" onclick="$('#condicao').val($(this).val());" value="razoavel">Razoável</label>
    								<br>
    								<label>Observações</label>
    								<textarea rows="2" name="obs2" id="obs2" class="form-control"></textarea>
    							</div>
    						</div>
                        </div>
					</div>

					<br>
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<input type="hidden" name="formulario_tipo" value="FAC">
								<input type="hidden" name="quarto1" id="quarto1_ocupado" value="yes">
								<input type="hidden" name="quarto2" id="quarto2_ocupado" value="not">
								<input type="hidden" name="quarto3" id="quarto3_ocupado" value="not">
								<input type="hidden" name="quarto4" id="quarto4_ocupado" value="not">
                                <input type="hidden" id="condicao" value="">
                                <input type="hidden" id="dias_individuais" value="">
								<button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-send"></span> Enviar</button>
							</div>
						</div>
					</div>
					
					
				</form>
DADOS;
        } else {
            $html.= 'Acesso negado! Seu nível de acesso não permite acesso à este formulário.';
        }
        
        return $html;
    }
    
    public function pageEditaPEH($pehid = 0, $token = '')
    {
        // Verifica se o FACID e o TOKEN foram enviados
        if($pehid == 0 || $pehid == '' || $token == '') {
            exit('<h5><strong>Erro 403:</strong> Parâmetros errados. Não é possível continuar...</h5>');
        }
        
        // Verifica o TOKEN: MD5( PEHID + session_id() );
        $token_calc = md5((int)$_GET['pehid'].session_id());
        if($token != $token_calc) {
            // TOKEN DIFERENTE
            exit('<h5><strong>Erro 403:</strong> Sua chave de acesso é inválida/expirou.</h5>');
        }
        unset($token_calc);
        
        
        $html = '';
        
        $abc = $this->pdo->prepare('SELECT peh.*, cidade.cidade AS cong_cidade, cidade.estado AS cong_estado FROM `peh` LEFT JOIN cidade ON peh.congregacao_cidade_id = cidade.id WHERE peh.id = :pehid');
        $abc->bindValue(":pehid", $pehid, PDO::PARAM_INT);
        $abc->execute();
        
        if($abc->rowCount() == 0) {
            exit('<h5><strong>Erro 404:</strong> Não encontrado!</h5>');
        }
        
        $reg = $abc->fetch(PDO::FETCH_OBJ);
        
        
        // Tratamento de dados
        // Congregação cidade
        if($_SESSION['nivel'] == 20 || $_SESSION['nivel'] == 1) {
            if($_SESSION['nivel'] == 20) { // Se for administrador, exibe todas as cidades
                $def = $this->pdo->query('SELECT * FROM cidade WHERE hospedeiro = 0 ORDER BY cidade ASC');
            } else { // Só exibe as cidades que o usuário é solicitante
                $def = $this->pdo->query('SELECT * FROM cidade WHERE hospedeiro = 0 AND solicitante_id = '.$_SESSION['id'].' ORDER BY cidade ASC');
            }
            $cong_cidade = '';
            if($def->rowCount() > 0) {
                while($lin = $def->fetch(PDO::FETCH_OBJ)) {
                    if($reg->congregacao_cidade_id == $lin->id) {
                        $cong_cidade.= <<<DADOS
                        
										<option value="$lin->id" selected>$lin->cidade/$lin->estado</option>
DADOS;
                    } else {
                        $cong_cidade.= <<<DADOS
                        
										<option value="$lin->id">$lin->cidade/$lin->estado</option>
DADOS;
                    }
                    
                }
            }
            
            unset($lin);
            
            // ESTADO
            $e = ''; $x = 1;
            while($x<=27) {
                $e[$x] = '';
                $x++;
            }
            
            switch($reg->estado) {
                case 'AC': $e[1] = 'selected'; break;
                case 'AL': $e[2] = 'selected'; break;
                case 'AP': $e[3] = 'selected'; break;
                case 'AM': $e[4] = 'selected'; break;
                case 'BA': $e[5] = 'selected'; break;
                case 'CE': $e[6] = 'selected'; break;
                case 'DF': $e[7] = 'selected'; break;
                case 'ES': $e[8] = 'selected'; break;
                case 'GO': $e[9] = 'selected'; break;
                case 'MA': $e[10] = 'selected'; break;
                case 'MT': $e[11] = 'selected'; break;
                case 'MS': $e[12] = 'selected'; break;
                case 'MG': $e[13] = 'selected'; break;
                case 'PA': $e[14] ='selected'; break;
                case 'PB': $e[15] = 'selected'; break;
                case 'PR': $e[16] = 'selected'; break;
                case 'PE': $e[17] = 'selected'; break;
                case 'PI': $e[18] = 'selected'; break;
                case 'RJ': $e[19] = 'selected'; break;
                case 'RN': $e[20] = 'selected'; break;
                case 'RS': $e[21] = 'selected'; break;
                case 'RO': $e[22] = 'selected'; break;
                case 'RR': $e[23] = 'selected'; break;
                case 'SC': $e[24] = 'selected'; break;
                case 'SP': $e[25] = 'selected'; break;
                case 'SE': $e[26] = 'selected'; break;
                case 'TO': $e[27] = 'selected'; break;
            }
            
            
            // Tipo Hospedagem
            $casa = $hotel = '';
            if($reg->tipo_hospedagem == 'casa'){
                $casa = 'checked';
            } else if($reg->tipo_hospedagem == 'hotel'){
                $hotal = 'checked';
            }
            
            // Transporte
            $trans_sim = $trans_nao = '';
            if($reg->transporte == 'SIM') {
                $trans_sim = 'checked';
            } else {
                $trans_nao = 'checked';
            }
            
            
            
            
            $html.=<<<DADOS
            
                <form id="peh_form" action="#" method="POST" onsubmit="validatePEH(); return false;"><br>
					<h3 class="text-center"><strong>PEDIDO ESPECIAL DE HOSPEDAGEM</strong>
                    <span class="text-danger" data-toggle="tooltip" title="Itens marcados com asterisco são obrigatórios!">*</span></h3>
                    
					<h4><strong>PUBLICADOR</strong></h4>
					<hr>
					<div class="row">
						<div class="col-12 col-md-6">
							<div class="form-group">
								<label for="nome">Nome:</label>
								<input type="text" name="nome" id="nome" class="form-control" autofocus required="required" onchange="$('oc1_nome').val($(this).val());" value="$reg->nome">
								<small class="text-secondary">O mesmo que no campo ocupante 1 abaixo</small>
							</div>
							
							<div class="form-group">
								<label for="endereco">Endereço:</label>
								<input type="text" name="endereco" id="endereco" class="form-control" required="required" value="$reg->endereco">
							</div>
							
							<div class="form-group row">
								<div class="col-6">
									<label for="cidade">Cidade:</label>
									<input type="text" name="cidade" id="cidade" class="form-control" required="required" value="$reg->cidade">
								</div>
								<div class="col-3">
									<label for="estado">Estado:</label>
									<select name="estado" id="estado" class="form-control" value="$reg->estado">
										<option value="AC" $e[1]>Acre</option>
										<option value="AL" $e[2]>Alagoas</option>
										<option value="AP" $e[3]>Amapá</option>
										<option value="AM" $e[4]>Amazonas</option>
										<option value="BA" $e[5]>Bahia</option>
										<option value="CE" $e[6]>Ceará</option>
										<option value="DF" $e[7]>Distrito Federal</option>
										<option value="ES" $e[8]>Espírito Santo</option>
										<option value="GO" $e[9]>Goiás</option>
										<option value="MA" $e[10]>Maranhão</option>
										<option value="MT" $e[11]>Mato Grosso</option>
										<option value="MS" $e[12]>Mato Grosso do Sul</option>
										<option value="MG" $e[13]>Minas Gerais</option>
										<option value="PA" $e[14]>Pará</option>
										<option value="PB" $e[15]>Paraíba</option>
										<option value="PR" $e[16]>Paraná</option>
										<option value="PE" $e[17]>Pernambuco</option>
										<option value="PI" $e[18]>Piauí</option>
										<option value="RJ" $e[19]>Rio de Janeiro</option>
										<option value="RN" $e[20]>Rio Grande do Norte</option>
										<option value="RS" $e[21]>Rio Grande do Sul</option>
										<option value="RO" $e[22]>Rondônia</option>
										<option value="RR" $e[23]>Roraima</option>
										<option value="SC" $e[24]>Santa Catarina</option>
										<option value="SP" $e[25]>São Paulo</option>
										<option value="SE" $e[26]>Sergipe</option>
										<option value="TO" $e[27]>Tocantins</option>
									</select>
								</div>
								<div class="col-3">
									<label for="pais">País:</label>
									<select disabled name="pais" id="pais" class="form-control">
										<option value="BRA">Brasil</option>
									</select>
								</div>
							</div>
							
							<div class="form-group row">
								<div class="col-6">
									<label for="tel_res">Telefone Residencial:</label>
									<input type="number" name="tel_res" id="tel_res" class="form-control" value="$reg->tel_res">
									<small class="text-secondary">Formato: (xx) xxxx-xxxx [Só números]</small>
								</div>
								<div class="col-6">
									<label for="tel_cel">Telefone Celular:</label>
									<input type="number" name="tel_cel" id="tel_cel" class="form-control" value="$reg->tel_cel">
									<small class="text-secondary">Formato: (xx) x xxxx-xxxx [Só números]</small>
								</div>
							</div>
							
							<div class="form-group">
								<label for="email">E-mail:</label>
								<input type="email" name="email" id="email" class="form-control" value="$reg->email">
							</div>
							
							<div class="form-group row">
								<div class="col-6">
									<label for="congregacao">Congregação:</label>
									<input type="text" name="congregacao" id="congregacao" class="form-control" required="required" value="$reg->congregacao">
								</div>
								<div class="col-6">
									<label for="congregacao_cidade_id">Cidade:</label>
									<select id="congregacao_cidade_id" name="congregacao_cidade_id" class="form-control">
									   $cong_cidade
									</select>
								</div>
							</div>
						</div>
						<div class="col-12 col-md-6">
							<div class="form-group">
								<label for="congresso_cidade">Cidade do congresso:</label>
								<input type="text" name="congresso_cidade" id="congresso_cidade" class="form-control" required="required" value="$reg->congresso_cidade">
							</div>
							
							<div class="form-group">
								<label for="check_in">Primeira noite que precisará do quarto:</label>
								<input type="date" name="check_in" id="check_in" class="form-control" required="required" value="$reg->check_in">
							</div>
							
							<div class="form-group">
								<label for="check_out">Última noite que precisará do quarto:</label>
								<input type="date" name="check_out" id="check_out" class="form-control" required="required" value="$reg->check_out">
							</div>
							
							<div class="form-group">
								<label for="tipo_hospedagem">Tipo de acomodação:</label>
								<div class="radio" id="tipo_hospedagem">
									<label><input type="radio" name="tipo_hospedagem" $casa onclick="$('#tipo_hospedagem').val($(this).val())" value="casa"> Casa particular</label>
									<label><input type="radio" name="tipo_hospedagem" $hotel onclick="$('#tipo_hospedagem').val($(this).val())" value="hotel"> Hotel</label>
								</div>
							</div>
							
							<div class="form-group">
								<label for="pagamento">Quanto pode pagar por esse quarto, <span style="font-weight:bold; text-decoration: underline;">por noite (em reais)?</span></label>
								<input type="number" name="pagamento" id="pagamento" class="form-control" value="$reg->pagamento">
								<small class="text-secondary">Veja a seção "Quartos de Hotel" no fim do formulário</small>
							</div>
							
							<div class="form-group">
								<label for="transporte">Terá transporte próprio enquanto estiver na cidade do congresso?</label>
								<div class="radio" id="transporte">
									<label><input type="radio" name="transporte" $trans_sim onclick="$('#transporte').val($(this).val())" value="SIM"> Sim</label>
									<label><input type="radio" name="transporte" $trans_nao onclick="$('#transporte').val($(this).val())" value="NÃO"> Não</label>
								</div>
							</div>
						</div>
					</div>
					
					<br>
					<h4><strong>OCUPANTES DO QUARTO <small>(É recomendado o máximo de 2 pessoas por casa.)</small></strong></h4>
					
					<table class="table table-hover table-striped">
						<tbody>
							<tr><td>
								<h5><strong>Ocupante 1</strong></h5>
								<div class="form-group row">
									<div class="col-sm-3">
										<label for="oc1_nome">Nome:</label>
										<input type="text" name="oc1_nome" id="oc1_nome" class="form-control" required="required" value="$reg->oc1_nome">
									</div>
									<div class="col-sm-1">
										<label for="oc1_idade">Idade:</label>
										<input type="number" name="oc1_idade" id="oc1_idade" class="form-control" required="required" value="$reg->oc1_idade">
									</div>
									<div class="col-sm-1">
										<label for="oc1_sexo">Sexo:</label>
										<select name="oc1_sexo" id="oc1_sexo" class="form-control" value="$reg->oc1_sexo">
											<option value="M">Masculino</option>
											<option value="F">Feminino</option>
										</select>
									</div>
									<div class="col-sm-3">
										<label for="oc1_parente">Parentesco entre os ocupantes:</label>
										<input type="text" name="oc1_parente" id="oc1_parente" class="form-control" required="required" value="$reg->oc1_parente">
									</div>
									<div class="col-sm-2">
										<label for="oc1_etnia">Etnia:</label>
										<input type="text" name="oc1_etnia" id="oc1_etnia" class="form-control" value="$reg->oc1_etnia">
									</div>
									<div class="col-sm-2">
										<label for="oc1_privilegio" style="font-size: 80%">Publicador, pioneiro, etc...</label>
										<input type="text" name="oc1_privilegio" id="oc1_privilegio" class="form-control" required="required" value="$reg->oc1_privilegio">
									</div>
								</div>
							</td></tr>
							<tr><td>
								<h5><strong>Ocupante 2</strong></h5>
								<div class="form-group row">
									<div class="col-sm-3">
										<label for="oc2_nome">Nome:</label>
										<input type="text" name="oc2_nome" id="oc2_nome" class="form-control" value="$reg->oc2_nome">
									</div>
									<div class="col-sm-1">
										<label for="oc2_idade">Idade:</label>
										<input type="number" name="oc2_idade" id="oc2_idade" class="form-control" value="$reg->oc2_idade">
									</div>
									<div class="col-sm-1">
										<label for="oc2_sexo">Sexo:</label>
										<select name="oc2_sexo" id="oc2_sexo" class="form-control" value="$reg->oc2_sexo">
											<option value="M">Masculino</option>
											<option value="F">Feminino</option>
										</select>
									</div>
									<div class="col-sm-3">
										<label for="oc2_parente">Parentesco entre os ocupantes:</label>
										<input type="text" name="oc2_parente" id="oc2_parente" class="form-control" value="$reg->oc2_parente">
									</div>
									<div class="col-sm-2">
										<label for="oc2_etnia">Etnia:</label>
										<input type="text" name="oc2_etnia" id="oc2_etnia" class="form-control" value="$reg->oc2_etnia">
									</div>
									<div class="col-sm-2">
										<label for="oc2_privilegio" style="font-size: 80%">Publicador, pioneiro, etc...</label>
										<input type="text" name="oc2_privilegio" id="oc2_privilegio" class="form-control" value="$reg->oc2_privilegio">
									</div>
								</div>
							</td></tr>
							<tr><td>
								<h5><strong>Ocupante 3</strong></h5>
								<div class="form-group row">
									<div class="col-sm-3">
										<label for="oc3_nome">Nome:</label>
										<input type="text" name="oc3_nome" id="oc3_nome" class="form-control" value="$reg->oc3_nome">
									</div>
									<div class="col-sm-1">
										<label for="oc3_idade">Idade:</label>
										<input type="number" name="oc3_idade" id="oc3_idade" class="form-control" value="$reg->oc3_idade">
									</div>
									<div class="col-sm-1">
										<label for="oc3_sexo">Sexo:</label>
										<select name="oc3_sexo" id="oc3_sexo" class="form-control" value="$reg->oc3_sexo">
											<option value="M">Masculino</option>
											<option value="F">Feminino</option>
										</select>
									</div>
									<div class="col-sm-3">
										<label for="oc3_parente">Parentesco entre os ocupantes:</label>
										<input type="text" name="oc3_parente" id="oc3_parente" class="form-control" value="$reg->oc3_parente">
									</div>
									<div class="col-sm-2">
										<label for="oc3_etnia">Etnia:</label>
										<input type="text" name="oc3_etnia" id="oc3_etnia" class="form-control" value="$reg->oc3_etnia">
									</div>
									<div class="col-sm-2">
										<label for="oc3_privilegio" style="font-size: 80%">Publicador, pioneiro, etc...</label>
										<input type="text" name="oc3_privilegio" id="oc3_privilegio" class="form-control" value="$reg->oc3_privilegio">
									</div>
								</div>
							</td></tr>
							<tr><td>
								<h5><strong>Ocupante 4</strong></h5>
								<div class="form-group row">
									<div class="col-sm-3">
										<label for="oc4_nome">Nome:</label>
										<input type="text" name="oc4_nome" id="oc4_nome" class="form-control" value="$reg->oc4_nome">
									</div>
									<div class="col-sm-1">
										<label for="oc4_idade">Idade:</label>
										<input type="number" name="oc4_idade" id="oc4_idade" class="form-control" value="$reg->oc4_idade">
									</div>
									<div class="col-sm-1">
										<label for="oc4_sexo">Sexo:</label>
										<select name="oc4_sexo" id="oc4_sexo" class="form-control" value="$reg->oc4_sexo">
											<option value="M">Masculino</option>
											<option value="F">Feminino</option>
										</select>
									</div>
									<div class="col-sm-3">
										<label for="oc4_parente">Parentesco entre os ocupantes:</label>
										<input type="text" name="oc4_parente" id="oc4_parente" class="form-control" value="$reg->oc4_parente">
									</div>
									<div class="col-sm-2">
										<label for="oc4_etnia">Etnia:</label>
										<input type="text" name="oc4_etnia" id="oc4_etnia" class="form-control" value="$reg->oc4_etnia">
									</div>
									<div class="col-sm-2">
										<label for="oc4_privilegio" style="font-size: 80%">Publicador, pioneiro, etc...</label>
										<input type="text" name="oc4_privilegio" id="oc4_privilegio" class="form-control" value="$reg->oc4_privilegio">
									</div>
								</div>
							</td></tr>
						</tbody>
					</table>
					
					
					
					
					<br>
					
					
					<br>
					
					
					<br>
					
					
					<br>
					<div class="card bg-secondary text-white">
                        <div class="card-body">
    						<h4><strong>SECRETÁRIO DA CONGREGAÇÃO</strong></h4>
    						<div class="form-group">
    							<label for="motivo">Explique por que esta é uma necessidade especial:</label>
    							<textarea rows="2" class="form-control" name="motivo" id="motivo" required="required">$reg->motivo</textarea>
    						</div>
    						
    						<div class="form-group row">
    							<div class="col-12 col-md-4">
    								<label for="secretario_nome">Nome do Secretário:</label>
    								<input type="text" name="secretario_nome" id="secretario_nome" class="form-control" required="required" value="$reg->secretario_nome">
    							</div>
    							<div class="col-12 col-md-4">
    								<label for="secretario_tel">Telefone do Secretário:</label>
    								<input type="number" name="secretario_tel" id="secretario_tel" class="form-control" required="required" value="$reg->secretario_tel">
    							</div>
    							<div class="col-12 col-md-4">
    								<label for="secretario_email">E-mail do Secretário:</label>
    								<input type="text" name="secretario_email" id="secretario_email" class="form-control" value="$reg->secretario_email">
    							</div>
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
                        <input type="hidden" name="pehid" id="pehid" value="$reg->id">
                        <input type="hidden" id="formulario_action" value="edit">
                        <input type="hidden" id="tipo_hospedagem" value="">
                        <input type="hidden" id="transporte" value="$reg->transporte">
						<button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-send"></span> <strong>Enviar pedido</strong></button>
					</div>
					
				</form>
DADOS;
        } else {
            $html.= 'Acesso negado! Seu nível de acesso não permite acesso à este formulário.';
        }
        
        
        
        return $html;
    }
    
    public function pageEditaFAC($facid = 0, $token = '')
    {
        // Verifica se o FACID e o TOKEN foram enviados
        if($facid == 0 || $facid == '' || $token == '') {
            exit('<h5><strong>Erro 403:</strong> Parâmetros errados. Não é possível continuar...</h5>');
        }
        
        // Verifica o TOKEN: MD5( FACID + session_id() );
        $token_calc = md5((int)$_GET['facid'].session_id());
        if($token != $token_calc) {
            // TOKEN DIFERENTE
            exit('<h5><strong>Erro 403:</strong> Sua chave de acesso é inválida/expirou.</h5>');
        }
        unset($token_calc);
        
        
        
        $html = '';
        
        
        // Retorna dados do FAC
        $abc = $this->pdo->prepare('SELECT fac.*, cidade.cidade AS cidade_nome, cidade.estado FROM fac LEFT JOIN cidade ON fac.cidade = cidade.id WHERE fac.id = :facid');
        $abc->bindValue(':facid', $facid, PDO::PARAM_INT);
        $abc->execute();
        
        if($abc->rowCount() == 0) {
            exit('<h5><strong>Erro 404:</strong> Não encontrado!</h5>');
        }
        
        $reg = $abc->fetch(PDO::FETCH_OBJ);
        
        // Tratamento dos dados
        // CIDADE
        if($_SESSION['nivel'] == 20 || $_SESSION['nivel'] == 10) {
            if($_SESSION['nivel'] == 20) {
                $def = $this->pdo->query('SELECT * FROM cidade WHERE hospedeiro = 1');
            } else {
                $def = $this->pdo->query('SELECT * FROM cidade WHERE hospedeiro = 1 AND resp_id = '.$_SESSION['id']);
            }
            $cidade1 = ''; $cidade2 = '';
            if($def->rowCount() > 0) {
                while($lin = $def->fetch(PDO::FETCH_OBJ)) {
                    if($reg->cidade == $lin->id) {
                        $cidade1.= <<<DADOS
										<option value="$lin->id" selected>$lin->cidade/$lin->estado</option>
										
DADOS;
                    } else {
                        $cidade1.= <<<DADOS
										<option value="$lin->id">$lin->cidade/$lin->estado</option>
										
DADOS;
                    }
                        
                    if($reg->cong_cidade == $lin->id) {
                        $cidade2.= <<<DADOS
										<option value="$lin->id" selected>$lin->cidade/$lin->estado</option>
										
DADOS;
                    } else{
                        $cidade2.= <<<DADOS
										<option value="$lin->id">$lin->cidade/$lin->estado</option>
										
DADOS;
                    }
                    
                }
            }
            
            // Casa TJ e Transporte
            $casatj_sim = $casatj_nao = $trans_sim = $trans_nao = '';
            if($reg->casa_tj == 'SIM') {
                $casatj_sim = 'selected';
            } else {
                $casatj_nao = 'selected';
            }
            if($reg->transporte == 'SIM') {
                $trans_sim = 'selected';
            } else {
                $trans_nao = 'selected';
            }
            
            
            if($reg->quarto1_sol_qtd == '0') {$reg->quarto1_sol_qtd = ''; }
            if($reg->quarto1_cas_qtd == '0') {$reg->quarto1_cas_qtd = ''; }
            if($reg->quarto1_valor1 == '0') {$reg->quarto1_valor1 = ''; }
            if($reg->quarto1_valor2 == '0') {$reg->quarto1_valor2 = ''; }
            
            if($reg->quarto2_sol_qtd == '0') {$reg->quarto2_sol_qtd = ''; }
            if($reg->quarto2_cas_qtd == '0') {$reg->quarto2_cas_qtd = ''; }
            if($reg->quarto2_valor1 == '0') {$reg->quarto2_valor1 = ''; }
            if($reg->quarto2_valor2 == '0') {$reg->quarto2_valor2 = ''; }
            
            if($reg->quarto3_sol_qtd == '0') {$reg->quarto3_sol_qtd = ''; }
            if($reg->quarto3_cas_qtd == '0') {$reg->quarto3_cas_qtd = ''; }
            if($reg->quarto3_valor1 == '0') {$reg->quarto3_valor1 = ''; }
            if($reg->quarto3_valor2 == '0') {$reg->quarto3_valor2 = ''; }
            
            if($reg->quarto4_sol_qtd == '0') {$reg->quarto4_sol_qtd = ''; }
            if($reg->quarto4_cas_qtd == '0') {$reg->quarto4_cas_qtd = ''; }
            if($reg->quarto4_valor1 == '0') {$reg->quarto4_valor1 = ''; }
            if($reg->quarto4_valor2 == '0') {$reg->quarto4_valor2 = ''; }
            
            // DIAS
            $todos = $dom = $seg = $ter = $qua = $qui = $sex = $sab = '';
            if($reg->dias == 'todos') {
                $todos = 'checked';
            } else {
                $dias = explode(';', $reg->dias);
                foreach($dias as $dia) {
                    switch($dia) {
                        case '1': $dom = 'checked'; break;
                        case '2': $seg = 'checked'; break;
                        case '3': $ter = 'checked'; break;
                        case '4': $qua = 'checked'; break;
                        case '5': $qui = 'checked'; break;
                        case '6': $sex = 'checked'; break;
                        case '7': $sab = 'checked'; break;
                        
                    }
                }
            }
            
            // CONDIÇÃO
            $raz = $boa = $exc = '';
            switch($reg->condicao) {
                case 'razoavel': $raz = 'checked'; break;
                case 'boa': $boa = 'checked'; break;
                case 'excelente': $exc = 'checked'; break;
            }
            
            
            
            
            $html.=<<<DADOS
            
                <br>
                <h3 class="text-center"><strong>FORMULÁRIO DE ACOMODAÇÃO <small style="font-size:1rem">[ID: $reg->id] <span class="badge badge-info"><span class="glyphicon glyphicon-edit"></span> EDIÇÃO</span></small></strong></h3>
                
				<div class="alert alert-danger visible-sm visible-xs">
					<strong>Sinal vermelho!</strong><br>
					Esse formulário não pode ser preenchido/exibido em dispositivos móveis. <i><strong>Utilize um computador ou equipamento de tela maior.</strong></i>
				</div>
				<form action="#" method="post" class="hidden-xs hidden-sm" onsubmit="validateFAC(); return false;">
					<div class="row">
						<div class="col-sm-10 offset-sm-2">
							<div class="alert alert-info">
								<span class="glyphicon glyphicon-info-sign"></span> Se a acomodação possui diversos quartos com diversas camas, talvez seja melhor cadastrar cada quarto como uma acomodação diferente.<br>
								Essa prática visa organizar da melhor forma os recursos disponíveis, facilitar a organização e a compartimentalização de informações.
							</div>
						</div>
					</div>
					
					
					<div class="form-group row" id="quarto1">
						<div class="col-12 col-sm-2">
							<h5 class="float-right hidden-xs" style="text-align:right;"><strong>QUARTO 1</strong><br>
							<small class="text-primary" style="cursor:pointer;font-size:0.7rem"></small></h5>
						</div>
						<div class="col-6 col-sm-5">
							<div class="card bg-secondary text-white">
                                <div class="card-body">
    								<div class="row text-center">
                                        <div class="col-12"><label>Quantidade de camas</label></div>
    								</div>
    								<div class="row">
    									<div class="col-6">
    										<input type="number" name="quarto1_sol_qtd" id="quarto1_sol_qtd" class="form-control" placeholder="Solteiro" value="$reg->quarto1_sol_qtd">
    									</div>
    									<div class="col-6">
    										<input type="number" name="quarto1_cas_qtd" id="quarto1_cas_qtd" class="form-control" placeholder="Casal" value="$reg->quarto1_cas_qtd">
    									</div>
    								</div>
                                </div>
							</div>
						</div>
						<div class="col-6 col-sm-5">
							<div class="card bg-secondary text-white">
                                <div class="card-body">
    								<div class="row text-center">
    									<div class="col-12"><label>Preço do quarto por dia</label></div>
    								</div>
    								<div class="row">
    									<div class="col-6">
    										<input type="number" name="quarto1_valor1" id="quarto1_valor1" class="form-control" placeholder="Um no quarto" value="$reg->quarto1_valor1">
    									</div>
    									<div class="col-6">
    										<input type="number" name="quarto1_valor2" id="quarto1_valor2" class="form-control" placeholder="Dois ou mais no quarto" value="$reg->quarto1_valor2">
    									</div>
    								</div>
                                </div>
							</div>
						</div>
						
					</div>
					<div class="form-group row" id="quarto2">
						<div class="col-12 col-sm-2">
							<h5 class="float-right hidden-xs" style="text-align:right;"><strong>QUARTO 2</strong><br>
							<small class="text-primary" style="cursor:pointer;font-size:0.7rem"></small></h5>
						</div>
						<div class="col-6 col-sm-5">
							<div class="card bg-secondary text-white">
                                <div class="card-body">
    								<div class="row text-center">
    									<div class="col-12"><label>Quantidade de camas</label></div>
    								</div>
    								<div class="row">
    									<div class="col-6">
    										<input type="number" name="quarto2_sol_qtd" id="quarto2_sol_qtd" class="form-control"  placeholder="Solteiro" value="$reg->quarto2_sol_qtd">
    									</div>
    									<div class="col-6">
    										<input type="number" name="quarto2_cas_qtd" id="quarto2_cas_qtd" class="form-control"  placeholder="Casal" value="$reg->quarto2_cas_qtd">
    									</div>
    								</div>
                                </div>
							</div>
						</div>
						<div class="col-6 col-sm-5">
							<div class="card bg-secondary text-white">
                                <div class="card-body">
    								<div class="row text-center">
    									<div class="col-12"><label>Preço do quarto por dia</label></div>
    								</div>
    								<div class="row">
    									<div class="col-6">
    										<input type="number" name="quarto2_valor1" id="quarto2_valor1" class="form-control" placeholder="Um no quarto" value="$reg->quarto2_valor1">
    									</div>
    									<div class="col-6">
    										<input type="number" name="quarto2_valor2" id="quarto2_valor2" class="form-control" placeholder="Dois ou mais no quarto" value="$reg->quarto2_valor2">
    									</div>
    								</div>
                                </div>
							</div>
						</div>
					</div>
					<div class="form-group row" id="quarto3">
						<div class="col-12 col-sm-2">
							<h5 class="float-right hidden-xs" style="text-align:right;"><strong>QUARTO 3</strong><br>
							<small class="text-primary" style="cursor:pointer;font-size:0.7rem"></small></h5>
						</div>
						<div class="col-6 col-sm-5">
							<div class="card bg-secondary text-white">
                                <div class="card-body">
    								<div class="row text-center">
    									<div class="col-12"><label>Quantidade de camas</label></div>
    								</div>
    								<div class="row">
    									<div class="col-6">
    										<input type="number" name="quarto3_sol_qtd" id="quarto3_sol_qtd"  class="form-control"  placeholder="Solteiro" value="$reg->quarto3_sol_qtd">
    									</div>
    									<div class="col-6">
    										<input type="number" name="quarto3_cas_qtd" id="quarto3_cas_qtd" class="form-control"  placeholder="Casal" value="$reg->quarto3_cas_qtd">
    									</div>
    								</div>
                                </div>
							</div>
						</div>
						<div class="col-6 col-sm-5">
							<div class="card bg-secondary text-white">
                                <div class="card-body">
    								<div class="row text-center">
    									<div class="col-12"><label>Preço do quarto por dia</label></div>
    								</div>
    								<div class="row">
    									<div class="col-6">
    										<input type="number" name="quarto3_valor1" id="quarto3_valor1" class="form-control" placeholder="Um no quarto" value="$reg->quarto3_valor1">
    									</div>
    									<div class="col-6">
    										<input type="number" name="quarto3_valor2" id="quarto3_valor2" class="form-control" placeholder="Dois ou mais no quarto" value="$reg->quarto3_valor2">
    									</div>
    								</div>
                                </div>
							</div>
						</div>
					</div>
					<div class="form-group row" id="quarto4">
						<div class="col-12 col-sm-2">
							<h5 class="float-right hidden-xs" style="text-align:right;"><strong>QUARTO 4</strong></h5>
						</div>
						<div class="col-6 col-sm-5">
							<div class="card bg-secondary text-white">
                                <div class="card-body">
    								<div class="row text-center">
    									<div class="col-12"><label>Quantidade de camas</label></div>
    								</div>
    								<div class="row">
    									<div class="col-6">
    										<input type="number" name="quarto4_sol_qtd" id="quarto4_sol_qtd"  class="form-control"  placeholder="Solteiro" value="$reg->quarto4_sol_qtd">
    									</div>
    									<div class="col-6">
    										<input type="number" name="quarto4_cas_qtd" id="quarto4_cas_qtd" class="form-control"  placeholder="Casal" value="$reg->quarto4_cas_qtd">
    									</div>
    								</div>
                                </div>
							</div>
						</div>
						<div class="col-6 col-sm-5">
							<div class="card bg-secondary text-white">
                                <div class="card-body">
    								<div class="row text-center">
    									<div class="col-12"><label>Preço do quarto por dia</label></div>
    								</div>
    								<div class="row">
    									<div class="col-6">
    										<input type="number" name="quarto4_valor1" id="quarto4_valor1" class="form-control" placeholder="Um no quarto" value="$reg->quarto4_valor1">
    									</div>
    									<div class="col-6">
    										<input type="number" name="quarto4_valor2" id="quarto4_valor2" class="form-control" placeholder="Dois ou mais no quarto" value="$reg->quarto4_valor2">
    									</div>
    								</div>
                                </div>
							</div>
						</div>
					</div>
					
                    <hr>
					<div class="row">
						<div class="col-sm-10 offset-sm-2">
							<div class="form-group">
								<label>Os quartos estão disponíveis nos dias:</label>
								<label class="checkbox-inline"><input type="checkbox" name="dias[]" $dom value="1">Domingo</label>
								<label class="checkbox-inline"><input type="checkbox" name="dias[]" $seg value="2">Segunda</label>
								<label class="checkbox-inline"><input type="checkbox" name="dias[]" $ter value="3">Terça</label>
								<label class="checkbox-inline"><input type="checkbox" name="dias[]" $qua value="4">Quarta</label>
								<label class="checkbox-inline"><input type="checkbox" name="dias[]" $qui value="5">Quinta</label>
								<label class="checkbox-inline"><input type="checkbox" name="dias[]" $sex value="6">Sexta</label>
								<label class="checkbox-inline"><input type="checkbox" name="dias[]" $sab value="7">Sábado</label>
								<label class="checkbox-inline"><input type="checkbox" name="dias[]" $todos value="todos"><strong>Todos</strong></label>
								
							</div>
						</div>
					</div>
					<div class="form-group row">
						<div class="col-sm-3 offset-sm-2">
							<label>Em que andar ficam os quartos?<br> <small class="text-secondary">(Assuma térreo como 0)</small></label>
							<input type="number" name="andar" id="andar" value="$reg->andar" class="form-control">
						</div>
						<div class="col-sm-3">
							<label>Poderá prover condução?</label>
							<select name="transporte" id="transporte" class="form-control">
								<option value="1" $trans_sim>Sim</option>
								<option value="0" $trans_nao>Não</option>
							</select>
						</div>
						<div class="col-sm-4">
							<label>É o lar de Testemunhas de Jeová?</label>
							<select name="casa_tj" id="casa_tj" class="form-control">
								<option value="1" $casatj_sim>Sim</option>
								<option value="0" $casatj_nao>Não</option>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<label>Observações:</label>
								<textarea rows="2" name="obs1" id="obs1" class="form-control">$reg->obs1</textarea>
							</div>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-sm-6">
							<h5 CLASS="text-center"><strong>ENDEREÇO DO HOSPEDEIRO</strong></h5>
							<div class="form-group">
								<label>Nome:</label>
								<input type="text" name="nome" id="nome" class="form-control" required="required" value="$reg->nome">
							</div>
							<div class="form-group">
								<label>Endereço <small class="text-secondary">(incluir bairro)</small>:</label>
								<input type="text" name="endereco" id="endereco" class="form-control" required="required" value="$reg->endereco">
							</div>
							<div class="row">
								<div class="col-sm-6">
									<label>Telefone:</label>
									<input type="text" name="telefone" id="telefone" class="form-control" required="required" value="$reg->telefone">
								</div>
								<div class="col-sm-6">
									<label>Cidade:</label>
									<select name="cidade" id="cidade" class="form-control" value="$reg->cidade">
										$cidade1
									</select>
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<h5 CLASS="text-center"><strong>PUBLICADOR QUE INDICOU</strong><br>
							<small class="text-secondary">(Publicador que conseguiu a hospedagem, se não for o hospedeiro)</small></h5>
							<div class="form-group">
								<label>Publicador:</label>
								<input type="text" name="publicador_nome" id="publicador_nome" class="form-control" value="$reg->publicador_nome">
							</div>
							<div class="form-group">
								<label>Telefone</label>
								<input type="text" name="publicador_tel" id="publicador_tel" class="form-control" value="$reg->publicador_tel">
							</div>
						</div>
					</div>
					
					<hr>
					<div class="card bg-secondary text-white">
                        <div class="card-body">
    						<h4><strong>SECRETÁRIO DA CONGREGAÇÃO</strong></h4>
    						<div class="form-group row">
    							<div class="col-sm-4">
    								<label>Nome da Congregação:</label>
    								<input type="text" name="cong_nome" id="cong_nome" class="form-control" value="$reg->cong_nome">
    								<br>
    								<label>Cidade:</label>
    								<select name="cong_cidade" id="cong_cidade" class="form-control" value="$reg->cong_cidade">
    									$cidade2
    								</select>
    							</div>
    							<div class="col-sm-4">
    								<label>Nome do Secretário:</label>
    								<input type="text" name="cong_sec" id="cong_sec" class="form-control" required="required" value="$reg->cong_sec">
    								<br>
    								<label>Telefone do Secretário:</label>
    								<input type="text" name="cong_tel" id="cong_tel" class="form-control" required="required" value="$reg->cong_tel">
    							</div>
    							<div class="col-sm-4">
    								<label>Condição do(s) quarto(s):</label><br>
    								<label class="radio-inline"><input type="radio" name="condicao" onclick="$('#condicao').val($(this).val());" $exc value="excelente">Excelente</label>
    								<label class="radio-inline"><input type="radio" name="condicao" onclick="$('#condicao').val($(this).val());" $boa value="boa">Boa</label>
    								<label class="radio-inline"><input type="radio" name="condicao" onclick="$('#condicao').val($(this).val());" $raz value="razoavel">Razoável</label>
    								<br>
    								<label>Observações</label>
    								<textarea rows="2" name="obs2" id="obs2" class="form-control">$reg->obs2</textarea>
    							</div>
    						</div>
                        </div>
					</div>
					
					<br>
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<input type="hidden" name="formulario_tipo" value="FAC">
                                <input type="hidden" name="facid" id="facid" value="$reg->id">
                                <input type="hidden" id="formulario_action" value="edit">
								<input type="hidden" name="quarto1" id="quarto1_ocupado" value="yes">
								<input type="hidden" name="quarto2" id="quarto2_ocupado" value="yes">
								<input type="hidden" name="quarto3" id="quarto3_ocupado" value="yes">
								<input type="hidden" name="quarto4" id="quarto4_ocupado" value="yes">
                                <input type="hidden" id="condicao" value="$reg->condicao">
                                <input type="hidden" id="dias_individuais" value="">
								<button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-save"></span> Salvar alteração</button>
							</div>
						</div>
					</div>
					
					
				</form>
DADOS;
        } else {
            $html.= 'Acesso negado! Seu nível de acesso não permite acesso à este formulário.';
        }
        
        return $html;
    }
    
    public function pageAtendimento()
    {
        // RESUMO: Retorna uma tabela com todos os nomes dos PEH, suas cidades e estados, e situação da hospedagem
        
        // Consulta todos PEH
        $abc= $this->pdo->query('SELECT peh.id, peh.oc1_nome, peh.oc2_nome, peh.oc3_nome, peh.oc4_nome, cidade.cidade, cidade.estado, peh.fac_id FROM peh LEFT JOIN cidade ON peh.congregacao_cidade_id = cidade.id WHERE peh.revisar = 0');
        if($abc->rowCount() == 0) {
            return 'Desculpa... mas não encontrei nada no banco de dados.';
        } else {
            // Pega todos os registros e lança em um array
            $x = 0; // Controle do array
            $pessoas = ''; // ARRAY
            
            while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
                $pessoas[$x]['nome'] = $reg->oc1_nome;
                $pessoas[$x]['peh'] = $reg->id;
                $pessoas[$x]['cidade'] = $reg->cidade;
                $pessoas[$x]['estado'] = $reg->estado;
                $pessoas[$x]['fac'] = $reg->fac_id;
                $x++;
                
                // Verifica se há algo nos outros campos
                if($reg->oc2_nome != '') {
                    $pessoas[$x]['nome'] = $reg->oc2_nome;
                    $pessoas[$x]['peh'] = $reg->id;
                    $pessoas[$x]['cidade'] = $reg->cidade;
                    $pessoas[$x]['estado'] = $reg->estado;
                    $pessoas[$x]['fac'] = $reg->fac_id;
                    $x++;
                }
                if($reg->oc3_nome != '') {
                    $pessoas[$x]['nome'] = $reg->oc3_nome;
                    $pessoas[$x]['peh'] = $reg->id;
                    $pessoas[$x]['cidade'] = $reg->cidade;
                    $pessoas[$x]['estado'] = $reg->estado;
                    $pessoas[$x]['fac'] = $reg->fac_id;
                    $x++;
                }
                if($reg->oc4_nome != '') {
                    $pessoas[$x]['nome'] = $reg->oc4_nome;
                    $pessoas[$x]['peh'] = $reg->id;
                    $pessoas[$x]['cidade'] = $reg->cidade;
                    $pessoas[$x]['estado'] = $reg->estado;
                    $pessoas[$x]['fac'] = $reg->fac_id;
                    $x++;
                }
                
            }
            
            // Arruma array por ordem alfabética
            sort($pessoas);
            $total_pessoas = $x;
            
            // escreve tabela
            
            $html =<<<DADOS

        <table id="atendimento_tabela" class="table table-bordered table-sm table-hover table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Nome</th>
                    <th># PEH</th>
                    <th>Cidade</th>
                    <th>Estado</th>
                    <th>Atendido (?)</th>
                </tr>
            </thead>
            <tbody>
DADOS;
            
            // Varre array para escrever linhas da tabela
            foreach($pessoas as $x) {
                if($x['fac'] == 0) {
                    $info = '<span class="badge badge-danger">Não</span>';
                } else {
                    $info = '<span class="badge badge-success">Sim</span>';
                }
                
                $html.=<<<DADOS

                <tr>
                    <td><a href="javascript:void(0)" onclick="atendCarrega($x[peh])">$x[nome]</a></td>
                    <td class="text-center">$x[peh]</td>
                    <td>$x[cidade]</td>
                    <td>$x[estado]</td>
                    <td>$info</td>
                </tr>
DADOS;
            }
            
            
            
            $html.=<<<DADOS

            </tbody>
        </table>
        <div id="atendimento_frame_msg"></div>
DADOS;
            
            return $html;
        }
    }
    
    public function pageMeuPerfil()
    {
        $html = '';
        
        
        $abc = $this->pdo->query('SELECT * FROM login WHERE id = '.$_SESSION['id']);
        if($abc->rowCount() > 0) {
            $reg = $abc->fetch(PDO::FETCH_OBJ);
            
            
            
            /// Atualiza sessão antes de editar os dados
            $_SESSION['id'] = $reg->id;
            $_SESSION['nome'] = $reg->nome;
            $_SESSION['sobrenome'] = $reg->sobrenome;
            $_SESSION['usuario'] = $reg->usuario;
            $_SESSION['nivel'] = $reg->nivel;
            $_SESSION['tel_res'] = $reg->tel_res;
            $_SESSION['tel_cel'] = $reg->tel_cel;
            $_SESSION['email'] = $reg->email;
            
            // Trata dados
            $nivel = '';
            switch($reg->nivel) {
                case '1':
                    $nivel = 'Solicitante de Hospedagem';
                    break;
                    
                case '10':
                    $nivel = 'Responsável da Hospedagem';
                    break;
                    
                case '20':
                    $nivel = 'Administrador';
                    break;
            }
            
            $tel_res = $tel_cel = '';
            if($reg->tel_res == '') {
                $tel_res = '-';
            } else {
                $tel_res = '('.substr($reg->tel_res, 0, 2).') '.substr($reg->tel_res, 2, 4).'-'.substr($reg->tel_res, 6);
            }
            
            if($reg->tel_cel == '') {
                $tel_cel = '-';
            } else {
                $tel_cel = $telefone = '('.substr($reg->tel_cel, 0, 2).') '.substr($reg->tel_cel, 2, 1).' '.substr($reg->tel_cel, 3, 4).'-'.substr($reg->tel_cel, 7);
            }
            
            
            
            
            $html .=<<<DADOS

            <dl>
                <dt>Nome:</dt>
                <dd class="dl-offset">$reg->nome $reg->sobrenome</dd>

                <dt>Usuario:</dt>
                <dd class="dl-offset">$reg->usuario</dd>

                <dt>Nível de acesso:</dt>
                <dd class="dl-offset">$nivel </dd>

                <dt>Tel. Residencial:</dt>
                <dd class="dl-offset">$tel_res</dd>

                <dt>Tel. Celular:</dt>
                <dd class="dl-offset">$tel_cel</dd>

                <dt>E-mail:</dt>
                <dd class="dl-offset">$reg->email</dd>

DADOS;

            if($_SESSION['nivel'] == 1) {
                $abc = $this->pdo->query('SELECT * FROM `cidade` WHERE `solicitante_id` = '.$_SESSION['id']);
                if($abc->rowCount() > 0) {
                    $dd = '';
                    while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
                        $dd .= '<span class="badge badge-dark"> '.$reg->cidade.'/'.$reg->estado.' </span> ';
                    }
                } else {
                    $dd = ' >>> SEM DESIGNAÇÕES <<< ';
                }
                $html.=<<<DADOS
                
                <dt>Cidade(s) designada(s):</dt>
                <dd class="dl-offset">$dd</dd>
DADOS;
            } else if($_SESSION['nivel'] == 10) {
                // Cidade visitante
                $abc = $this->pdo->query('SELECT * FROM `cidade` WHERE `hospedeiro` = 0 && `resp_id` = '.$_SESSION['id']);
                if($abc->rowCount() > 0) {
                    $dd = '';
                    while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
                        $dd .= '<span class="badge badge-dark"> '.$reg->cidade.'/'.$reg->estado.' </span> ';
                    }
                } else {
                    $dd = ' >>> SEM DESIGNAÇÕES <<< ';
                }
                $html.=<<<DADOS
                
                <dt>Cidade(s) visitante(s):</dt>
                <dd class="dl-offset">$dd</dd>
DADOS;
                
                
                // Cidade com hospedagem
                $abc = $this->pdo->query('SELECT * FROM `cidade` WHERE `hospedeiro` = 1 && `resp_id` = '.$_SESSION['id']);
                if($abc->rowCount() > 0) {
                    $dd = '';
                    while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
                        $dd .= '<span class="badge badge-dark"> '.$reg->cidade.'/'.$reg->estado.' </span> ';
                    }
                } else {
                    $dd = ' >>> SEM DESIGNAÇÕES <<< ';
                }
                $html.=<<<DADOS
                
                <dt>Cidade(s) com hospedagem:</dt>
                <dd class="dl-offset">$dd</dd>
DADOS;
            }
            
            
            
            $html.=<<<DADOS

            </dl>
DADOS;
            
            // Considerações finais
            if($_SESSION['nivel'] == 1) {
                $html.=<<<DADOS
                
            
            <div class="alert alert-info"><strong>Verifique sempre a suas designações!</strong> Como solicitante, sua designação nunca vai mudar. Mas verifique sempre que notar algum problema ao fazer operações dentro do sistema.</div>
DADOS;
            } else if($_SESSION['nivel'] == 10) {
                $html.=<<<DADOS
                
            
            <div class="alert alert-info"><strong>Verifique sempre as suas designações!</strong> Caso em algum dos campos apareça a informação <i>">>> SEM DESIGNAÇÕES <<<"</i>, informe ao administrador.</div>
DADOS;
            }
            
        } else {
            $html .= 'Aconteceu algum problema do lado de cá. Seu registro não foi encontrado... Por favor, deslogue e entre novamente no sistema.';
        }
        
        return $html;
    }
    
}