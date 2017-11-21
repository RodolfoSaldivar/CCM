


<div class="row center">
<h5>
	<b>Se ha enviado un mensaje a su cuenta de correo electrónico.</b>
	<br><br>
	Le agradeceremos leer las instrucciones completas en dicho mensaje, para finalizar su compra y recoger su paquete de libros y cuadernos.
	<br><br>
	Agradecemos su preferencia. <br><br>
</h5>

<a href="/hijos/seleccionar_hijo" class="waves-effect waves-light btn">
	Comprar para otro hijo
</a>

<a href="/asociados/logout" class="waves-effect waves-light btn">
	Cerrar Sesión
</a>
</div>



<?php $this->Html->script('no_pagina_anterior', array('inline' => false)); ?>

<?php $this->Html->scriptStart(array('inline' => false)); ?>

	$("#menu_sub_1").addClass("menu-pasado");
	$("#menu_sub_2").addClass("menu-pasado");
	$("#menu_sub_3").addClass("menu-pasado");
	$("#menu_sub_4").addClass("menu-pasado");
	$("#menu_sub_5").addClass("menu-pasado");
	$("#menu_sub_6").removeClass("href_desactivado");
	$("#carrito_de_compra").addClass("hide");
	$("#menu_back").addClass("hide");

<?php $this->Html->scriptEnd(); ?>
