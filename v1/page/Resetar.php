<?php
/* AJUDAS */
$funcao1 =<<<DADOS
Ao preencher o PEH (Pedido Especial de Hospedagem), o solicitante informa quando os hóspedes chegaram e
quando eles irão partir. Essa data é importante para o anfitrião se programar bem ao receber os seus hóspedes.<br>
No entanto, em casos em que o PEH é reaproveitado de outro evento, pode acontecer destas datas não serem atualizadas.
<br><br>
Este item informa ao sistema para apagar estas datas e forçar ao solicitante inserí-las quando revisar o PEH.
DADOS;
$funcao1_titulo =<<<DADOS
<strong>Primeira e última noite</strong>
DADOS;

$funcao2 =<<<DADOS
Em casos de mudança de salão de assembleias para o evento, apague esta opção para que os solicitantes informem
novamente a cidade onde o evento acontecerá.
DADOS;
$funcao2_titulo =<<<DADOS
<strong>Cidade do Congresso</strong>
DADOS;


/* FIM AJUDAS */
?>
<h4><strong><span class="glyphicon glyphicon-refresh"></span> REDEFINIR</strong></h4><hr>
<div class="row">
	<div class="col-xs-12" id="mensagem_retorno">
	
	</div>
</div>

<div class="row">
	<div class="col-xs-12 col-sm-6 col-md-4">
		<div class="panel panel-primary">
			<div class="panel-body">
				<strong>Atenção:</strong>Nenhum formulário será apagado!
        		<hr>
        		<form>
        			<div class="checkbox">
                    	<label><input type="checkbox" id="funcao1" value="1" checked="checked"><strong>Desvincular PEH e FAC</strong></label>
                    </div>
                    <div class="checkbox">
                    	<label><input type="checkbox" id="funcao2" value="1" checked="checked"><strong>Colocar todos PEH em revisão</strong></label>
                    </div>
                    <div class="checkbox">
                    	<label><input type="checkbox" id="funcao3" value="1" checked="checked"><strong>Colocar todos FAC em revisão</strong></label>
                    </div>
                    Adicional:
                    <div class="checkbox">
                    	<label><input type="checkbox" id="funcao4" value="1" checked="checked"><strong>Apagar as datas da primeira e última noite do PEH</strong></label>
                    	<a href="javascript:void(0)" data-toggle="popover" data-trigger="focus" title="<?php echo $funcao1_titulo;?>" data-content="<?php echo $funcao1;?>">Entenda isso</a>
                    </div>
                    <div class="checkbox">
                    	<label><input type="checkbox" id="funcao5" value="1" checked="checked"><strong>Apagar o campo "Cidade do Congresso" do PEH</strong></label>
                    	<a href="javascript:void(0)" data-toggle="popover" data-trigger="focus" title="<?php echo $funcao2_titulo;?>" data-content="<?php echo $funcao2;?>">Entenda isso</a>
                    </div>
                    <div class="form-group">
                    	<button type="button" class="btn btn-primary" onclick="admReseta()"><span class="glyphicon glyphicon-ok"></span> Confirmar operação</button>
                    </div>
        		</form>
        		<small>
        		*<strong>PEH:</strong> Pedido Especial de Hospedagem<br>
        		*<strong>FAC:</strong> Formulário de Acomodação
        		</small>
			</div>
		</div>
	</div>
</div>