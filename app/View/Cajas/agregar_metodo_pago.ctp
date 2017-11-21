


<div class="row margin_nada" id="row_<?php echo $cont ?>">
	<div class="margin_nada input-field col s4 un_select">
		<select id="forma_pago_<?php echo $cont ?>" name="data[Pago][<?php echo $cont ?>][forma_pago]" onchange="removerRequerido(<?php echo $cont ?>)">
			<option value="nada" disabled selected>Forma de Pago</option>
			<option value="efectivo">Efectivo</option>
			<option value="banco">Deposito Banco</option>
			<option value="cheque">Cheque</option>
			<option value="tarjeta">Tarjeta</option>
		</select>
		<label><label id="forma_pago_<?php echo $cont ?>-error" class="validation_label" for="forma_pago_<?php echo $cont ?>">*Requerido</label></label>
	</div>
	<div class="margin_nada input-field col s4">
		<input id="importe_<?php echo $cont ?>" class="un_importe" type="number" name="data[Pago][<?php echo $cont ?>][importe]">
  		<label for="importe_<?php echo $cont ?>">
  			Importe
          	<label id="importe_<?php echo $cont ?>-error" class="error validation_label" for="importe_<?php echo $cont ?>"></label>
  		</label>
	</div>
	<div class="margin_nada input-field col s4">
		<input id="referencia_<?php echo $cont ?>" type="text" name="data[Pago][<?php echo $cont ?>][referencia]">
  		<label for="referencia_<?php echo $cont ?>">
  			Referencia
          	<label id="referencia_<?php echo $cont ?>-error" class="error validation_label" for="referencia_<?php echo $cont ?>"></label>
  		</label>
	</div>
</div>