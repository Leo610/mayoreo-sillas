<?php
/* @var $this MonedasController */
/* @var $model Monedas */


$this->pageTitle='Administración de Monedas';
$this->breadcrumbs=array(
  'Modulos'=> Yii::app()->createUrl('administracion/modulos'),
	'Acciones',
);

?>
<script type="text/javascript">
$( document ).ready(function() {

    // Funcion para mostrar el modal
    $( "#abrirmodal" ).click(function() {
        $("#formmodal").modal('show');
        $('#Monedas-form')[0].reset();
    });

    // Funcion para ordenar la lista de resultados
    $('#lista').DataTable();
   
 });

    function Actualizar(id){
        var jqxhr = $.ajax({
        url: "<?php echo $this->createUrl("Monedas/Datos"); ?>",
        type: "POST",
        dataType : "json",
        timeout : (120 * 1000),
        data: {
            id: id,
        },
        success : function(Response, newValue) {
            if (Response.requestresult == 'ok') {
               // Si el resultado es correcto, agregamos los datos al form del modal
               $("#Monedas_id_moneda").val(Response.Datos.id_moneda);
               $("#Monedas_moneda_nombre").val(Response.Datos.moneda_nombre);
               $("#Monedas_moneda_abreviacion").val(Response.Datos.moneda_abreviacion);
               $("#Monedas_costo_compra").val(Response.Datos.costo_compra);
               $("#Monedas_costo_venta").val(Response.Datos.costo_venta);
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
<?php include 'modal/_form.php'; ?>

<div class="row">
    <div class="col-md-12">
        <h1>Administración Monedas | <a href="#" class="btn btn-success" id="abrirmodal">
            Agregar Monedas
        </a></h1>
        
        <hr>
        <div class="table-responsive">
        <table id="lista" class="table  table-bordered  table-hover ">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Abreviatura</th>
                    <th>Costo compra</th>
                    <th>Costo venta</th>
                    <th>Acciones</th>  
                </tr>
            </thead>
            <tbody>
            		<?php 
            		foreach ($lista as $rows){ ?>
                <tr>
                    <td><?=$rows['id_moneda']?></td>
                    <td><?=$rows['moneda_nombre']?></td>
                    <td><?=$rows['moneda_abreviacion']?></td>
                    <td>$<?=number_format($rows['costo_compra'],2)?></td>
                    <td>$<?=number_format($rows['costo_venta'],2)?></td>
                   	<td class="">
                        <?php 
                            echo CHtml::link('<i class="fa fa-pencil fa-lg"></i> Editar', "javascript:;", array(
                                'style' => 'cursor: pointer;',
                                "onclick" => "Actualizar(".$rows['id_moneda']."); return false;"
                            ));

                            if($rows['default']==0)
                            {
                   	       echo CHtml::link('<br><i class="fa fa-trash fa-lg"></i> Eliminar', array('Monedas/delete', 'id'=>$rows['id_moneda']),
    						  array(
    						    'submit'=>array('Monedas/delete', 'id'=>$rows['id_moneda']),
    						    'class' => 'delete','confirm'=>'Seguro que lo deseas eliminar?'
    						  )
    						);
                           }
    						?>
                   </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        </div>
    </div>
</div>