<?php
/* @var $this AdministracionController */
$this->pageTitle=$Datos->proveedor_razonsocial;

$this->breadcrumbs=array(
	'Administracion'=>array('/administracion'),
	'Proveedores'=>array('/proveedores/admin'),
	$Datos->proveedor_razonsocial
);
?>
<script type="text/javascript">
$(document).ready(function() {
    // Funcion para ordenar la lista de resultados, lo ordena por la columna 4, ya que empieza 0,1,2,3,4 y muestra  5 resultados, solamente	
    $('#listaordenescompra').DataTable( {
        "order": [[ 5, "desc" ]],
         "iDisplayLength": 5
    } );
});
		/*
		*	METODO PARA OBTENER LOS DATOS DEL PROVEEDOR, PARA EDITAR
		*	POR DANIEL VILLARREAL EL 9 DE FEBRERO DEL 2016
		*/
		 function Actualizar(id){
        var jqxhr = $.ajax({
        url: "<?php echo $this->createUrl("Proveedores/datos"); ?>",
        type: "POST",
        dataType : "json",
        timeout : (120 * 1000),
        data: {
            id: id,
        },
        success : function(Response, newValue) {
            if (Response.requestresult == 'ok') {
               // Si el resultado es correcto, agregamos los datos al form del modal
               $("#Proveedores_proveedor_razonsocial").val(Response.Datos.proveedor_razonsocial);
               $("#Proveedores_proveedor_rfc").val(Response.Datos.proveedor_rfc);
               $("#Proveedores_proveedor_calle").val(Response.Datos.proveedor_calle);
               $("#Proveedores_proveedor_colonia").val(Response.Datos.proveedor_colonia);
               $("#Proveedores_proveedor_numerointerior").val(Response.Datos.proveedor_numerointerior);
               $("#Proveedores_proveedor_numeroexterior").val(Response.Datos.proveedor_numeroexterior);
               $("#Proveedores_proveedor_codigopostal").val(Response.Datos.proveedor_codigopostal);
               $("#Proveedores_proveedor_municipio").val(Response.Datos.proveedor_municipio);
               $("#Proveedores_proveedor_entidad").val(Response.Datos.proveedor_entidad);
               $("#Proveedores_proveedor pais").val(Response.Datos.proveedor_pais);
               $("#Proveedores_proveedor_nombre").val(Response.Datos.proveedor_nombre);
               $("#Proveedores_proveedor_email").val(Response.Datos.proveedor_email);
               $("#Proveedores_proveedor_telefono").val(Response.Datos.proveedor_telefono);
               $("#Proveedores_id_proveedor").val(Response.Datos.id_proveedor);
               // y posteriormente mostramos el modal 
               $("#formmodal").modal('show');

            }else{
                
            }
        },
        error: function(e){
              $.notify('Ocurrio un error inesperado','error');
            }
        });
    }
</script>
<?php 
// Incluimos el modal de proveedores, de otra vista
Yii::app()->controller->renderFile(Yii::app()->basePath.'/views/proveedores/modal/_form.php',
		array(
			'model'=>$model
		));
?>
<div class="row">
	<div class="col-md-3">
		<fieldset>
			<legend>Datos</legend>
		<p class="mb-none">
			Nombre:<br><strong><?=$Datos->proveedor_nombre; ?></strong><br>
			Teléfono:<br><strong><?=$Datos->proveedor_telefono; ?></strong><br>
			Email:<br><strong><?=$Datos->proveedor_email; ?></strong><br>
			Razón Social:<br><strong><?=$Datos->proveedor_razonsocial; ?></strong><br>
			RFC:<br><strong><?=$Datos->proveedor_rfc; ?></strong><br>
			Calle:<br><strong><?=$Datos->proveedor_calle; ?></strong><br>
			Colonia:<br><strong><?=$Datos->proveedor_colonia; ?></strong><br>
			Número Interior:<br><strong><?=$Datos->proveedor_numeroexterior; ?> <?=$Datos->proveedor_numerointerior; ?></strong><br>
			Código Postal:<br><strong><?=$Datos->proveedor_codigopostal; ?></strong><br>
			Municipio:<br><strong><?=$Datos->proveedor_municipio; ?></strong><br>
			Entidad:<br><strong><?=$Datos->proveedor_entidad; ?></strong><br>
			País:<br><strong><?=$Datos->proveedor_pais; ?></strong><br>
		</p>
		<?php
			echo CHtml::link('<i class="fa fa-pencil fa-lg"></i> Editar', "javascript:;", array(
	          'style' => 'cursor: pointer;',
	          "onclick" => "Actualizar(".$Datos->id_proveedor."); return false;",
	          "class"=>'btn btn-primary mt-none btn-sm pull-right'
	    ));
		?>
		</fieldset>
	</div>
	<div class="col-md-9">
    <div class="col-md-12">
  		<fieldset>
				<legend style="margin-bottom:10px;">Lista de Ordenes de Compra</legend>
				<div class="table-responsive">
	      <table id="listaordenescompra" class="table  table-bordered table-hover">
	          <thead>
	              <tr>
	                  <th>Num</th>
	                  <th>Proveedor</th> 
	                  <th>Estatus</th> 
	                  <th>Total</th> 
	                  <th>Usuario</th>
	                  <th>Fecha Alta</th>
	                  <th></th>
	              </tr>
	          </thead>
	          <tbody>
	          		<?php 
	          		foreach ($ListaOC as $rows){ ?>
	              <tr>
	              		<td><?=$rows->id_orden_de_compra?></td>
	              		<td><?=$rows->rl_Proveedor->proveedor_rfc?></td>
	              		<td><?=$rows->ordendecompra_estatus?></td>
	                  <td>$ <?=$rows->ordendecompra_total?></td>
	                  <td><?=$rows->rl_usuarios->Usuario_Nombre?></td>
	                  <td><?=$rows->ordendecompra_fecha_alta?></td>
	                  <td>
	                  	<?php 
                   	    echo CHtml::link('<i class="fa fa-search"></i> Ver',array('ordenesdecompra/ver/'.$rows->id_orden_de_compra),array('class'=>"btn btn-secondary"));
    									?> - 
    									<?php 
                   	    echo CHtml::link('<i class="fa fa-file-pdf-o"></i>
 Ver',array('ordenesdecompra/pdf/'.$rows->id_orden_de_compra),array('class'=>"btn btn-danger",'target'=>'_blank'));
    									?>
	                  </td>
	             </tr>
	             <?php } ?>
	         </tbody>
	      </table>
	      </div>
    	</fieldset>
    </div>
  </div>
