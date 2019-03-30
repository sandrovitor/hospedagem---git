
			<div class="panel panel-default">
				<div class="panel-heading">
					<strong>Informações Gerais</strong>
				</div>
				<div id="table1">
					<div class="panel-body">
						<h5 class="text-center"><strong>VISÃO GERAL</strong></h5>
						<table class="table table-condensed table-bordered table-responsive">
							<tbody>
								<tr>
									<th>Número de Cidades</th>
									<td class="text-center"><span class="badge"><?php 
									$abc = $pdo->query('SELECT * FROM cidade WHERE 1');
									echo $abc->rowCount();
									?></span>
									</td>
								</tr>
								<tr>
									<th>Pedidos de Hospedagem</th>
									<td class="text-center"><span class="badge"><?php 
									$abc = $pdo->query('SELECT * FROM peh WHERE 1');
									echo $abc->rowCount();
									?></span>
									</td>
								</tr>
							</tbody>
						</table>
						
						<hr>
						<h5 class="text-center"><strong>USUÁRIOS</strong></h5>
						<table class="table table-condensed table-bordered table-responsive">
							<tbody>
								<tr>
									<th>Solicitantes de Hospedagem</th>
									<td class="text-center"><span class="badge"><?php 
									$abc = $pdo->query('SELECT * FROM login WHERE nivel = 1');
									echo $abc->rowCount();
									?></span>
									</td>
								</tr>
								<tr>
									<th>Responsáveis da Hospedagem</th>
									<td class="text-center"><span class="badge"><?php 
									$abc = $pdo->query('SELECT * FROM login WHERE nivel = 10');
									echo $abc->rowCount();
									?></span>
									</td>
								</tr>
								<tr>
									<th>Administradores</th>
									<td class="text-center"><span class="badge"><?php 
									$abc = $pdo->query('SELECT * FROM login WHERE nivel = 20');
									echo $abc->rowCount();
									?></span>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>