<?php
$this->pageTitle='Transferencia # '.$transferencia['id'];
$this->pageDescription = '';
$this->breadcrumbs=array(
	'Transferencias '=>Yii::app()->createurl('transferencias/index'),
    $this->pageTitle
);
$this->opcionestitulo = '<a href="'.Yii::app()->createUrl('transferencias/pdf',array('id'=>$transferencia['id'])).'" target="_blank" class="btn btn-default btn-sm"><i class="fa fa-print" aria-hidden="true"></i> Imprimir TRX </a>';
// menu de inventario
$this->renderpartial('//inventario/menu',array('op_menu'=>5));
//
include 'modal/editarconcepto.php';
?>
<div class="panel">
    <div class="row">
    	<div class="col-md-12">
    		<table class="table table-sm table-hover table-responsive">
    			<tr>
    				<td># Transferencia</td>
    				<td class="text-danger font-weight-bold"><?=$transferencia['id']?></td>
                    <td>Sucursal de Origen</td>
                    <td class="font-weight-bold"><?=$transferencia['idSucursalorigen']['nombre']?></td>
                    <td>Sucursal de Destino</td>
                    <td class="font-weight-bold"><?=$transferencia['idSucursaldestino']['nombre']?></td>
    				
    				<td>Fecha</td>
    				<td class="font-weight-bold"><?=$transferencia['fecha_solicitud']?></td>
    			</tr>
    			<tr>
    				<td>Generó</td>
    				<td class="font-weight-bold"><?=$transferencia['idUsuariocrea']['Usuario_Nombre']?></td>
                    <td>Usuario Solicita</td>
                    <td class="font-weight-bold"><?=$transferencia['idUsuariosolicita']['Usuario_Nombre']?></td>
    				<td>Estatus</td>
    				<td class="font-weight-bold">
    					<?php 
							$estatus = $this->EstatusTR($transferencia['estatus']);
							echo $estatus['badge']
						?>
					</td>
    				<td>Prioridad</td>
    				<td class="font-weight-bold">
    					<?php if($transferencia['tipo']==0)
						{
							echo '<span class="badge badge-default">NORMAL</span>';
						}elseif($transferencia['tipo']==1)
						{
							echo '<span class="badge badge-danger">URGENTE</span>';
						}
						?>
    				</td>
    			</tr>
    			<tr>
    				<td>Comentarios</td>
    				<td class="font-weight-bold" colspan="7"><?=$transferencia['comentarios']?></td>
    			</tr>
                <?php if($transferencia['estatus']==1){ ?>
    			<tr id="agregarproductotr">
    				<td>Producto</td>
    				<td colspan="7">
                        <div class="row">
                            <div class="col-md-1">
                            	<input type="text" class="form-control" name="id_productoclave" id="id_productoclave" readonly="true">
                                <input type="hidden" class="form-control" name="id_producto_seleccionado" id="id_producto_seleccionado" readonly="true">
                            </div>
                            <div class="col-md-5">
                                <?php 
                                    $this->widget('zii.widgets.jui.CJuiAutoComplete',array(
                                      'name'=>'buscador',
                                      'id'=>'buscador',
                                      'source'=>$this->createUrl('administracion/buscadorproducto',array('tipo'=>1)),
                                        // Opciones javascript adicionales para el plugin
                                      'options'=>array(
                                          'minLength'=>'2',
                                           'autoFocus'=>true,
                                          'select'=>'js:function(event, ui) {
                                            $("#id_producto_seleccionado").val(ui.item.id_producto);
                                            $("#id_productoclave").val(ui.item.clave)
                                            Datosproducto(ui.item.id_producto);
                                            }',
                                          'focus'=>'js:function(event, ui) {
                                            $("#id_producto_seleccionado").val("");
                                              return false;
                                          }'
                                      ),
                                      'htmlOptions'=>array(
                                          'class'=>'form-control',
                                          'placeholder'=>'Buscar producto por nombre o clave'
                                      )
                                    ));
                                ?>
                            </div>
                            <div class="col-md-2">
                                <input type="number" min="0" class="form-control" id="unitarioproducto" placeholder="unitario" onchange="Obtenertotal(1);" >
                            </div>
                            <div class="col-md-2">
                                <input type="number" min="0" class="form-control" id="cantidadproducto" value="1" placeholder="cantidad" onchange="Obtenertotal(1);">
                            </div>
                            <div class="col-md-2">
                                <div class="input-group">
                                  <input type="number" id="totalproducto" class="form-control" disabled="true" placeholder="total">
                                  <span class="input-group-btn">
                                    <button class="btn btn-success" type="button" onclick="Agregarproducto()">Agregar</button>
                                  </span>
                                </div>
                            </div>
                        </div>
    				</td>
    			</tr>
                <?php } ?>
    		</table>
        </div>
    </div>
    <div class="row">
    	<div class="col-md-9">
    		<table id="listaprouctos" class="table table-sm table-hover table-responsive table-striped" style="font-size: 11px;">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Clave</th>
                        <th>Unitario</th>
                        <th>IVA</th>
                        <th>Subtotal</th>
                        <th>Cantidad</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
    	</div>
    	<div class="col-md-3">
            <table id="tabladetotales" class="table table-responsive table-hover table-sm">
                <thead>
                    <tr>
                        <th colspan="3" class="text-center">Totales</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>SubTotal</td>
                        <td id="contenidosubtotal"></td>
                    </tr>
                    <tr>
                        <td>IVA</td>
                        <td id="contenidoiva"></td>
                    </tr>
                    <tr>
                        <td class="lead">Total</td>
                        <td class="lead font-weight-bold" id="contenidototal"></td>
                    </tr>
                </tbody>
            </table>
    	</div>
        <div class="col-md-12 text-right">
            <?php if($transferencia['estatus']==1){ ?>
                <button type="button" class="btn btn-success btn-sm" onclick="Actualizarestatus(4)">
                    <i class="fa fa-check-circle" aria-hidden="true"></i>
                    Transferencia Terminada
                </button>
            <?php }
            if($transferencia['estatus']==1 || $transferencia['estatus']==2){ ?>
                <button type="button" class="btn btn-danger btn-sm" onclick="Actualizarestatus(9)">
                    <i class="fa fa-ban" aria-hidden="true"></i>
                    Cancelar
                </button>
            <?php } ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    
    setTimeout( function(){ 
        ObtenerdatosTR();
    }  , 500 );

    // funcion para actualizar el estatus de la tr 
    function Actualizarestatus(estatusnuevo)
    {
        /*
            1= transferencia abierta
            2= transferencia cerrada
            3= transferencia en curso
            4= transferencia recibida
            9= transferencia cancelada
        */
        if(confirm('Confirme actualización')==false)
        {
            return false;
        }
        // ajax para actualizar 
        var jqxhr = $.ajax({
            url: "<?php echo $this->createUrl("transferencias/actualizarestatus"); ?>",
            type: "POST",
            dataType : "json",
            timeout : (120 * 1000),
            data: {
                estatusnuevo:estatusnuevo,
                id_tr:<?=$transferencia['id']?>
            },
            success : function(Response) {
                if (Response.requestresult == 'ok') {
                    // toastr.success(Response.message, {timeOut:500})
                    $.notify(Response.message, "success");
                    location.reload(); // refrescamos la página
                    // similar behavior as clicking on a link
                    //window.location.href = "<?=Yii::app()->createUrl('transferencias/salida',array('buscar'=>1,'numero_tc'=>$transferencia['id']))?>";
                }else{
                    // toastr.warning(Response.message, {timeOut:500})
                    $.notify(Response.message, "warning");
                }
            },
            error: function(e){
                $.notify('Ocurrio un error inesperado', "warning");
            }
        });
    }
    // datos del producto
	function Datosproducto(idproducto)
	{
        // enviamos la petición ajaxx
        var jqxhr = $.ajax({
            url: "<?php echo $this->createUrl("transferencias/datosproducto"); ?>",
            type: "POST",
            dataType : "json",
            timeout : (120 * 1000),
            data: {
                idproducto:idproducto,
                id_sucursal:'<?=$transferencia['id_sucursal_origen']?>'
            },
            success : function(Response) {
                if (Response.requestresult == 'ok') {
                    console.log(Response);
                    $.notify(Response.message, "success");
                    /*toastr.success(Response.message, {timeOut:500})*/
                    $('#unitarioproducto').val(Response.precios.precio);
                    Obtenertotal(1);
                }else{
                    // toastr.warning(Response.message, {timeOut:500})
                    $.notify(Response.message, "warning");
                }
            },
            error: function(e){
                    // toastr.warning('Ocurrio un error inesperado', {timeOut:500})
                    $.notify('Ocurrio un error inesperado', "warning");
                }
        });
	}
    // datos de la tr
    function ObtenerdatosTR()
    {
        // enviamos la petición ajaxx
        var jqxhr = $.ajax({
            url: "<?php echo $this->createUrl("transferencias/datostr"); ?>",
            type: "POST",
            dataType : "json",
            timeout : (120 * 1000),
            data: {
                id:<?=$transferencia['id']?>,
            },
            success : function(Response) {
                if (Response.requestresult == 'ok') {
                    // toastr.success(Response.message,{timeOut:500})
                    $.notify(Response.message, "success");
                    // insertamos los valores
                    $('#contenidosubtotal').empty().append(Response.subtotalformat);
                    $('#contenidoiva').empty().append(Response.ivaformat);
                    $('#contenidototal').empty().append(Response.totalformat);
                    // insertamos los conceptos en la tabla
                    $('#listaprouctos > tbody').empty().append(Response.conceptostr);
                }else{
                    // toastr.warning(Response.message, {timeOut:500})
                    $.notify(Response.message, "warning");
                }
            },
            error: function(e){
                    // toastr.warning('Ocurrio un error inesperado', {timeOut:500})
                    $.notify('Ocurrio un error inesperado', "warning");
                }
        });
    }
    // obtenemos el total en base al unitario
    function Obtenertotal(tipo)
    {
        if(tipo==1)
        {
            var unitarioproducto = $('#unitarioproducto').val();
            var cantidadproducto = $('#cantidadproducto').val();
            if(unitarioproducto=='')
            {
                return false;
            }
            var totalproducto = unitarioproducto*cantidadproducto;
            $('#totalproducto').val(totalproducto.toFixed(2));
        }
        if(tipo==2)
        {
            var unitarioproducto = $('#editarpartida_unitario').val();
            var cantidadproducto = $('#editarpartida_cantidad').val();
            if(unitarioproducto=='')
            {
                return false;
            }
            var totalproducto = unitarioproducto*cantidadproducto;
            $('#editarpartida_total').val(totalproducto.toFixed(2));
        }
    }
    // funcion para agregar el producto a la transferencia
    function Agregarproducto()
    {
        var id_producto = $('#id_producto_seleccionado').val();
        var unitarioproducto = $('#unitarioproducto').val();
        var cantidadproducto = $('#cantidadproducto').val();
        if(unitarioproducto=='' || cantidadproducto=='' || id_producto=='' || cantidadproducto == 0)
        {
            //toastr.warning('Favor de llenar todos los campos', {timeOut:500})
            $.notify('Favor de llenar todos los campos', "warning");
            return false;
        }
        // enviamos la peticion para insertar el producto
        var jqxhr = $.ajax({
            url: "<?php echo $this->createUrl("transferencias/agregarproductotr"); ?>",
            type: "POST",
            dataType : "json",
            timeout : (120 * 1000),
            data: {
                id_producto:id_producto,
                unitarioproducto:unitarioproducto,
                cantidadproducto:cantidadproducto,
                id:<?=$transferencia['id']?>
            },
            success : function(Response) {
                if (Response.requestresult == 'ok') {
                    // limpiamos el campo
                    $('#buscador').val('');
                    // toastr.success(Response.message, {timeOut:500})
                    $.notify(Response.message, "success");
                    ObtenerdatosTR();
                    $('#id_producto_seleccionado').val('');
                    $('#unitarioproducto').val('');
                    $('#cantidadproducto').val(1);
                    $('#totalproducto').val('');
                    $('#id_productoclave').val('');
                }else{
                    // toastr.warning(Response.message, {timeOut:500})
                    $.notify(Response.message, "warning");
                }
            },
            error: function(e){
                    // toastr.warning('Ocurrio un error inesperado', {timeOut:500})
                    $.notify('Ocurrio un error inesperado', "warning");
                }
        });
    }
    // funcion para eliminar el producto de la tr
    function Eliminarpartida(id)
    {
        if(confirm('Confirme eliminación')==false)
        {
            return false;
        }
        // enviamos la peticion para insertar el producto
        var jqxhr = $.ajax({
            url: "<?php echo $this->createUrl("transferencias/eliminarpartida"); ?>",
            type: "POST",
            dataType : "json",
            timeout : (120 * 1000),
            data: {
                id:id,
                id_tr:<?=$transferencia['id']?>
            },
            success : function(Response) {
                if (Response.requestresult == 'ok') {
                    // limpiamos el campo
                    // toastr.success(Response.message, {timeOut:500})
                    $.notify(Response.message, "success");
                    // eliminamos el tr
                    $('#partida'+id).remove();
                    ObtenerdatosTR();
                }else{
                    // toastr.warning(Response.message, {timeOut:500})
                    $.notify(Response.message, "warning");
                }
            },
            error: function(e){
                    // toastr.warning('Ocurrio un error inesperado', {timeOut:500})
                    $.notify('Ocurrio un error inesperado', "warning");
                }
        });
    }
    // funcion para editar una partida
    function Datospartida(id)
    {
        // enviamos la peticion para insertar el producto
        var jqxhr = $.ajax({
            url: "<?php echo $this->createUrl("transferencias/datospartida"); ?>",
            type: "POST",
            dataType : "json",
            timeout : (120 * 1000),
            data: {
                id:id,
                id_tr:<?=$transferencia['id']?>
            },
            success : function(Response) {
                if (Response.requestresult == 'ok') {
                    // limpiamos el campo
                    // toastr.success(Response.message,{timeOut:500})
                    $.notify(Response.message, "success");
                    // asignamos los datos
                    var src = $('#editarpartida_img').data('src');
                    var img = src+'/'+$('#editarpartida_img').data('imgno');
                    if(Response.img!='')
                    {
                        var img = src+'/productos/'+Response.img;
                    }
                    $('#editarpartida_img').attr('src',img);
                    $('#editarpartida_nombre').val(Response.nombre);
                    $('#editarpartida_clave').val(Response.clave);
                    $('#editarpartida_cantidad').val(Response.concepto.cantidad);
                    $('#editarpartida_unitario').val(Response.concepto.unitario);
                    $('#id_transferencia').val(Response.concepto.id_transferencia);
                    $('#id_partida').val(Response.concepto.id);
                    $('#editarpartida_total').val(Response.concepto.cantidad*Response.concepto.unitario);
                    $('#id_producto').val(Response.concepto.id_producto);
                    // abrimos el modal de la partida
                    $('#editarpartidamodal').modal('show')
                }else{
                    $.notify(Response.message, "warning");
                    // toastr.warning(Response.message, {timeOut: 500})
                }
            },
            error: function(e){
                    // toastr.warning('Ocurrio un error inesperado', {timeOut: 500})
                    $.notify('Ocurrio un error inesperado', "warning");
                }
        });
    }



</script>