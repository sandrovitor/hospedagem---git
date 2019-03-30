<?php 
if(isset($_POST['nome']) && $_POST['nome'] <> '') {
	
	//verifica se todo os campos foram enviados.
	if($_POST['nome'] <> '' && $_POST['numero'] <> '' && $_POST['circuito'] <> '' && $_POST['cidade'] <> '' && $_POST['estado'] <> '')
	{
		// Procura no banco de a congregação já foi criada
		$abc = $pdo->prepare('SELECT * FROM congregacao WHERE nome LIKE :nome');
		$abc->bindValue(":nome", $_POST['nome'], PDO::PARAM_STR);
		$abc->execute();
		
		if($abc->rowCount() == 0) {
			// #############################################
			$abc = $pdo->prepare('INSERT INTO congregacao (id, nome, numero, circuito, cidade, estado) VALUES (NULL, :nome, :numero, :circuito, :cidade, :estado)');
			$abc->bindValue(":nome", $_POST['nome'], PDO::PARAM_STR);
			$abc->bindValue(":numero", $_POST['numero'], PDO::PARAM_STR);
			$abc->bindValue(":circuito", $_POST['circuito'], PDO::PARAM_STR);
			$abc->bindValue(":cidade", $_POST['cidade'], PDO::PARAM_STR);
			$abc->bindValue(":estado", $_POST['estado'], PDO::PARAM_STR);
			try {
				$abc->execute();
				echo<<<DADOS
<div class="alert alert-success">
	<strong>Sucesso!</strong> A congregação <i>$_POST[nome]</i> foi inserida no banco de dados do sistema.
</div>
DADOS;
			} catch(PDOException $e) {
				echo<<<DADOS
<div class="alert alert-warning">
	<strong>Aconteceu um erro!</strong> <i>$e->getMessage()</i>		
</div>
DADOS;
			}
		} else {
			echo 'Congregação já existe';
			
		}
	}
}
?>

<h4><strong><span class="glyphicon glyphicon-plus"></span> NOVA CONGREGAÇÃO</strong></h4><hr>

<div class="row">
	<div class="col-xs-12 col-sm-6 col-md-4">
		<form id="form1" action="#" method="post">
			<div class="form-group">
				<label for="form1_nome">Nome da Congregação:</label>
				<input type="text" id="form1_nome" name="nome" class="form-control">
				<span class="help-block"><strong>Exemplo:</strong> <i>'Central'</i>, <i>'LS Norte do Sul'</i> (sem aspas).</span>
			</div>
			<div class="form-group">
				<label for="form1_numero">Número da Congregação:</label>
				<input type="text" id="form1_numero" name="numero" class="form-control" maxlength="7">
			</div>
			<div class="form-group">
				<label for="form1_circuito">Circuito:</label>
				<input type="text" id="form1_circuito" name="circuito" class="form-control">
				<span class="help-block"><strong>Exemplo:</strong> <i>LS-03</i>, <i>BA-99</i>...</span>
			</div>
			<div class="form-group row">
				<div class="col-xs-7">
					<label for="form1_cidade">Cidade:</label>
					<input type="text" id="form1_cidade" name="cidade" class="form-control">
				</div>
				<div class="col-xs-5">
					<label for="form1_estado">Estado:</label>
					<select id="form1_estado" name="estado" class="form-control">
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
			</div>
			<div class="form-group">
				<button type="submit" class="btn btn-success"><strong>Criar</strong></button>
				<button type="reset" class="btn btn-danger"><strong>Resetar</strong></button>
			</div>
		</form>
	</div>
	<div class="col-xs-12 col-sm-6 col-md-4">
		<div class="panel panel-default">
			<?php 
				$abc = $pdo->query('SELECT congregacao.*, cidade.cidade, cidade.estado FROM congregacao LEFT JOIN cidade ON congregacao.cidade_id = cidade.id WHERE 1 ORDER BY cidade.estado ASC, cidade.cidade ASC, congregacao.nome ASC');
			?>
			<div class="panel-heading">
				<h5><strong>LISTA DE CONGREGAÇÕES</strong> <span class="badge pull-right"><?php echo $abc->rowCount();?></span></h5>
			</div>
			<div class="panel-body" style="max-height:300">
				<?php 
				if($abc->rowCount() > 0) {
					echo <<<DADOS
				<table class="table table-condensed table-hover">
					<thead>
						<tr>
							<th>Nome</th>
							<th>Número</th>
							<th>Circuito</th>
							<th>Lugar</th>
						</tr>
					</thead>
					<tbody>
DADOS;
					
					while($reg = $abc->fetch(PDO::FETCH_OBJ)) {
						echo <<<DADOS

						<tr>
							<th>$reg->nome</th>
							<td>$reg->numero</td>
							<td>$reg->circuito</td>
							<td>$reg->cidade/$reg->estado</td>
						</tr>
DADOS;
					}
					echo <<<DADOS

					</tbody>
				</table>
DADOS;
				} else {
					echo '<i>Ainda não há congregações no sistema...</i>';
				}
				?>
			</div>
		</div>
	</div>
</div>