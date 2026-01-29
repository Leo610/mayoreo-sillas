<?php
/* @var $this RutasController */
/* @var $model Rutas */


$this->pageTitle=$Ruta->id.' '.$Ruta->nombre;
$this->breadcrumbs=array(
    'Rutas'=> Yii::app()->createUrl('administracion/rutas'),
    $this->pageTitle,
);

?>
<script type="text/javascript">
$( document ).ready(function() {
    // Funcion para ordenar la lista de resultados
    $('#lista').DataTable();   
    // Funcion para mostrar el modal
    $( ".abrirmodal" ).click(function() {
        var modal = $(this).data('idmodal');
        // le agrgamos el id del detalle
        var id = $(this).data('id');
        $('#RutasDetalles_id').val(id);
        if(modal=="#formmodaldetalle")
        {
             var jqxhr = $.ajax({
                url: "<?php echo $this->createUrl("Rutas_detalles/Datos"); ?>",
                type: "POST",
                dataType : "json",
                timeout : (120 * 1000),
                data: {
                    id: id,
                },
                success : function(Response, newValue) {
                    if (Response.requestresult == 'ok') {
                       // Si el resultado es correcto, agregamos los datos al form del modal
                       $("#RutasDetalles_resultado").val(Response.Datos.resultado);
                       $("#RutasDetalles_estatus").val(Response.Datos.estatus);
                       // y posteriormente mostramos el modal 
                    }else{
                        
                    }
                },
                error: function(e){
                      $.notify('Ocurrio un error inesperado','error');
                    }
                });
           
        }
        $(modal).modal('show');
    }); 
 });

function Actestatus(id,nuevovalor){
    // Verificamos que tengan datos
    if(id=='')
    {
        $.notify('Favor de verificar','error');
        return false;
    }
    if(nuevovalor=='')
    {
        $.notify('Favor de verificar','error');
        return false;   
    }

        var jqxhr = $.ajax({
        url: "<?php echo $this->createUrl("rutas/actualizarestatus"); ?>",
        type: "POST",
        dataType : "json",
        timeout : (120 * 1000),
        data: {
            id: id,
            nuevovalor: nuevovalor
        },
        success : function(Response, newValue) {
            if (Response.requestresult == 'ok') {
                $.notify(Response.message, "success");

            }else{
                 $.notify(Response.message, "error");    
            }
        },
        error: function(e){
                 $.notify("Ocurrio un error inesperado", "error");
            }
        });
    }


</script>
<?php 
$opmenu=6;
include 'menu/menu.php';
include 'modals/modal.creardetalle.php';
include 'modals/modal.editarrutadetalle.php'; 
?>

<div class="row">
    <div class="col-md-12">
        <h1 class="m-md"><?=$this->pageTitle?> | <button class="btn btn-success abrirmodal" data-idmodal="#formmodalcreardetalle" id="abrirmodal">Agregar detalle</button></h1>

        

        <table class="table  table-bordered  table-hover ">
            <tr>
                <td>Vendedor</td>
                <td style="color:#000; font-weight:bold;"><?=$Ruta['rl_vendedor']['Usuario_Nombre']?></td>
                <td>Desde</td>
                <td style="color:#000; font-weight:bold;"><?=$Ruta['fecha_desde']?></td>
                <td>Hasta</td>
                <td style="color:#000; font-weight:bold;"><?=$Ruta['fecha_hasta']?></td>
                <td>Estatus</td>
                <td>
                    <select id="estatus" class="form-control" 
                    onchange="Actestatus(<?=$Ruta->id?>,this.value);">
                        <option value="FINALIZADA" <?=($Ruta->estatus=="FINALIZADA")?'selected':'';?>>FINALIZADA</option>
                        <option value="INICIADA" <?=($Ruta->estatus=="INICIADA")?'selected':'';?>>INICIADA</option>
                        <option value="PROGRAMADA" <?=($Ruta->estatus=="PROGRAMADA")?'selected':'';?>>PROGRAMADA</option>
                    </select>
                    </td>
            </tr>
            <tr>
                <td>Comentarios</td>
                <td style="color:#000; font-weight:bold;" colspan="7"><?=$Ruta['comentarios']?></td>
            </tr>
        </table>
        <div class="table-responsive">
        <table id="lista" class="table  table-bordered  table-hover ">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Nombre</th>
                    <th>Fecha visita</th>
                    <th>hora visita</th>
                    <th>Fecha Alta</th>
                    <th>Estatus</th>
                    <th>Orden</th>
                    <th></th>            
                    
                </tr>
            </thead>
            <tbody>
                    <?php 
                    foreach ($Rutadetalle as $rows){ ?>
                <tr>
                    <td><?=$rows->rl_clientes->cliente_nombre?></td>
                    <td><?=$rows->nombre?></td>
                    <td><?=$rows->fecha_visita?></td>
                    <td><?=$rows->hora_visita?></td>
                    <td><?=$rows->fecha_alta?></td>
                    <td><?=$rows->estatus?></td>
                    <td><?=$rows->orden?></td>
                    <td>
                    
                   <button class="btn btn-success abrirmodal" data-idmodal="#formmodaldetalle" id="abrirmodal" data-id="<?=$rows->id?>">Detalle</button></h1>
                                
                   </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        </div>
    </div>
</div>