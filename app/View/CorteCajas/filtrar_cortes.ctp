


<div class="row" id="todos_los_cortes">

	<div class="col s12 m9 l6">

		<ul class="collapsible" data-collapsible="accordion">

			<li>
				<?php $id_corte = 0; ?>
				<?php foreach ($cortes as $key => $corte): ?>
					<?php if ($id_corte != $corte["CorteCaja"]["cajero_id"]): ?>
						<?php $id_corte = $corte["CorteCaja"]["cajero_id"] ?>
						</li>
						<li>
							<div class="collapsible-header">
								<i class="material-icons">play_arrow</i>
								<?php echo
									$corte["Asociado"]["nombre"]." ".
									$corte["Asociado"]["a_paterno"]." ".
									$corte["Asociado"]["a_materno"]
								?>
							</div>
					<?php endif ?>
						
					<div class="collapsible-body">
						<?php echo $corte["CorteCaja"]["fecha_ap"]." - "; ?>
						<?php
							if ($corte["CorteCaja"]["fecha_cierre"])
								echo $corte["CorteCaja"]["fecha_cierre"];
							else
								echo "abierta";
						?>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<a href="/corte_cajas/ver/<?php echo $corte["CorteCaja"]["id"] ?>">
							<span class="icon-ver dash-icon"></span>
						</a>
					</div>

				<?php endforeach ?>
			</li>
			
		</ul>

	</div>

</div>