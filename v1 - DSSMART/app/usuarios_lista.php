						<table class="table table-hover table-condensed table-responsive table-bordered">
							<thead>
								<tr>
									<th>Nome</th>
									<th>Usuario</th>
									<th>Nível de Acesso</th>
									<th>Tel. Residencial</th>
									<th>Tel. Celular</th>
									<th>E-mail</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								include_once('app/conecta.php');
								$abc = $pdo->query('SELECT * FROM login ORDER BY nivel ASC, nome ASC, sobrenome ASC');
								$nivel = '-';
								
								if($abc->rowCount() == 0) {
									echo '<tr><td colspan="5">Ainda sem usuários</td></tr>';
								} else {
									while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
										switch($reg->nivel) {
											case 1:
												$nivel = 'Solicitante';
												break;
												
											case 10:
												$nivel = 'Responsável da Hospedagem';
												break;
												
											case 20:
												$nivel = 'Administrador';
												break;
										}
										echo <<<DADOS

								<tr>
									<td>$reg->nome $reg->sobrenome</td>
									<td>$reg->usuario</td>
									<td>$nivel</td>
									<td>$reg->tel_res</td>
									<td>$reg->tel_cel</td>
									<td>$reg->email</td>
								</tr>
DADOS;
									}
								}
								?>
							</tbody>
						</table>