<?php include_once('app/conecta.php');?>
<h4><strong><span class="glyphicon glyphicon-search"></span> LISTAR USUÁRIOS <small>(RELATÓRIO GERAL)</small></strong></h4><hr>


<div class="row">
	<div class="col-xs-12">
		<h5><span class="glyphicon glyphicon-chevron-right"></span> <strong>SOLICITANTES DE HOSPEDAGEM</strong></h5>
		<table class="table table-condensed table-bordered">
			<thead>
				<tr>
					<th>Nome</th>
					<th>Usuario</th>
					<th>Tel. Residencial</th>
					<th>Tel. Celular</th>
					<th>E-mail</th>
					<th>Qtd. de Pedidos</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$abc = $pdo->query('SELECT * FROM login WHERE nivel = 1');
				if($abc->rowCount() > 0) {
					while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
						$def = $pdo->query('SELECT peh.id FROM peh LEFT JOIN cidade ON peh.congregacao_cidade_id = cidade.id WHERE cidade.solicitante_id = '.$reg->id);
						$pedidos = $def->rowCount();
						echo <<<DADOS

				<tr>
					<td>$reg->nome $reg->sobrenome</td>
					<td>$reg->usuario</td>
					<td>$reg->tel_res</td>
					<td>$reg->tel_cel</td>
					<td>$reg->email</td>
					<td><span class="badge">$pedidos</span></td>
				</tr>
DADOS;
					}
				} else {
					echo '<tr><td>Sem usuários nessa categoria</td></tr>';
				}
				?>
			</tbody>
		</table>
		
		<hr>
		
		<h5><span class="glyphicon glyphicon-chevron-right"></span> <strong>RESPONSÁVEL DA HOSPEDAGEM</strong></h5>
		<table class="table table-condensed table-bordered">
			<thead>
				<tr>
					<th rowspan="2">Nome</th>
					<th rowspan="2">Usuario</th>
					<th rowspan="2">Tel. Residencial</th>
					<th rowspan="2">Tel. Celular</th>
					<th rowspan="2">E-mail</th>
					<th colspan="2" class="text-center"><span class="glyphicon glyphicon-lamp"></span> Acomodações</th>
					<th colspan="3" class="text-center"><span class="glyphicon glyphicon-inbox"></span> Pedidos</th>
				</tr>
				<tr>
					<th>Total</th>
					<th>Livre</th>
					<th>Total</th>
					<th>Atendidos</th>
					<th>Pendentes</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$abc = $pdo->query('SELECT * FROM login WHERE nivel = 10');
				if($abc->rowCount() > 0) {
					while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
						$def = $pdo->query('SELECT fac.id FROM fac LEFT JOIN cidade ON fac.cidade = cidade.id WHERE cidade.resp_id = '.$reg->id);
						$acomod_total = $def->rowCount();
						
						$def = $pdo->query('SELECT peh.id FROM peh LEFT JOIN cidade ON peh.congregacao_cidade_id = cidade.id WHERE cidade.resp_id = '.$reg->id);
						$pedidos_total = $def->rowCount();
						
						$def = $pdo->query('SELECT peh.id FROM peh LEFT JOIN fac ON peh.fac_id = fac.id LEFT JOIN cidade ON fac.cidade = cidade.id WHERE peh.fac_id <> 0 AND cidade.resp_id = '.$reg->id);
						$pedidos_atend = $def->rowCount();
						
						$def = $pdo->query('SELECT peh.id FROM peh LEFT JOIN cidade ON peh.congregacao_cidade_id = cidade.id WHERE peh.fac_id = 0 AND cidade.resp_id = '.$reg->id);
						$pedidos_pend = $def->rowCount();
						
						$acomod_livre = $acomod_total - $pedidos_atend;
						
						/*
						 * ######### TRATA VARIAVEIS
						 */
						// Acomodação total
						if($acomod_total == 0) {
							$acomod_total = '<span title="Não há acomodações no sistema!" data-toggle="tooltip" class="label label-danger">'.$acomod_total.'</span>';
						} else if($acomod_total < $pedidos_total) {
							$acomod_total = '<span title="Número de acomodações não poder inferior ao número de pedidos" data-toggle="tooltip" class="label label-warning">'.$acomod_total.'</span>';
						} else {
							$acomod_total = '<span class="label label-success">'.$acomod_total.'</span>';
						}
						
						// Acomodação livre
						if(($acomod_livre == 0 && $pedidos_pend > 0) || ($acomod_livre < $pedidos_pend)) {
							$acomod_livre = '<span title="Ainda há pedidos pendentes. Providenciar acomodações!" data-toggle="tooltip" class="label label-danger">'.$acomod_livre.'</span>';
						} else if($acomod_livre == 0 && $pedidos_pend == 0) {
							$acomod_livre = '<span class="label label-success">'.$acomod_livre.'</span>';
						} else if($acomod_livre > 0 && $pedidos_pend > 0) {
							$acomod_livre = '<span title="Atenda todos os pedidos!" data-toggle="tooltip" class="label label-warning">'.$acomod_livre.'</span>';
						} else {
							$acomod_livre = '<span class="label label-success">'.$acomod_livre.'</span>';
						}
						
						// Pedidos atendidos
						if($pedidos_atend == 0 && $pedidos_total > 0) {
							$pedidos_atend = '<span title="Comece a atender os pedidos agora mesmo!" data-toggle="tooltip" class="label label-danger">'.$pedidos_atend.'</span>';
						} else if($pedidos_atend < $pedidos_total) {
							$pedidos_atend = '<span title="Ainda faltam pedidos para atender" data-toggle="tooltip" class="label label-warning">'.$pedidos_atend.'</span>';
						} else {
							$pedidos_atend = '<span class="label label-success">'.$pedidos_atend.'</span>';
						}
						
						// Pedidos total
						if($pedidos_total == 0) {
							$pedidos_total = '<span class="label label-default">'.$pedidos_total.'</span>';
						} else {
							$pedidos_total = '<span class="label label-primary">'.$pedidos_total.'</span>';
						}
						
						// Pedidos pendentes
						if($pedidos_pend == 0) {
							$pedidos_pend = '<span title="" data-toggle="tooltip" class="label label-success">'.$pedidos_pend.'</span>';
						} else {
							$pedidos_pend = '<span title="Ainda faltam pedidos para atender" data-toggle="tooltip" class="label label-warning">'.$pedidos_pend.'</span>';
						}
						
						echo <<<DADOS

				<tr>
					<td>$reg->nome $reg->sobrenome</td>
					<td>$reg->usuario</td>
					<td>$reg->tel_res</td>
					<td>$reg->tel_cel</td>
					<td>$reg->email</td>
					<td class="text-center">$acomod_total</td>
					<td class="text-center">$acomod_livre</td>
					<td class="text-center">$pedidos_total</td>
					<td class="text-center">$pedidos_atend</td>
					<td class="text-center">$pedidos_pend</td>
				</tr>
DADOS;
					}
				} else {
					echo '<tr><td>Sem usuários nessa categoria</td></tr>';
				}
				?>
			</tbody>
		</table>
		<table class="table table-condensed text-center">
			<tbody>
				<tr class="bg-info">
					<th>Legenda:</th>
					<td><span class="label label-success">0</span> <br> <i><strong>"TUDO OK"</strong></i></td>
					<td><span class="label label-warning">0</span> <br> <i><strong>"ALERTA, DÊ ATENÇÃO"</strong></i></td>
					<td><span class="label label-danger">0</span> <br> <i><strong>"PERIGO, HÁ ALGO ERRADO"</strong></i></td>
					<td><span class="label label-info">0</span>
					<span class="label label-primary">0</span>
					<span class="label label-default">0</span> <br> <i><strong>"INFORMAÇÃO"</strong></i></td>
				<tr>
			</tbody>
		</table>
		
		<hr>
		
		<h5><span class="glyphicon glyphicon-chevron-right"></span> <strong>ADMINISTRADORES</strong></h5>
		<table class="table table-condensed table-bordered">
			<thead>
				<tr>
					<th>Nome</th>
					<th>Usuario</th>
					<th>Tel. Residencial</th>
					<th>Tel. Celular</th>
					<th>E-mail</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$abc = $pdo->query('SELECT * FROM login WHERE nivel = 20');
				if($abc->rowCount() > 0) {
					while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
						echo <<<DADOS

				<tr>
					<td>$reg->nome $reg->sobrenome</td>
					<td>$reg->usuario</td>
					<td>$reg->tel_res</td>
					<td>$reg->tel_cel</td>
					<td>$reg->email</td>
				</tr>
DADOS;
					}
				} else {
					echo '<tr><td>Sem usuários nessa categoria</td></tr>';
				}
				?>
			</tbody>
		</table>
	</div>
</div>