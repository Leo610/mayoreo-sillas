<?php
$this->pageTitle='Recibir transferencia';

$this->pageDescription = '';
$this->breadcrumbs=array(
    $this->pageTitle
);

// menu de inventario
$this->renderpartial('//inventario/menu',array('op_menu'=>12));
?>
<div class="panel">
    <?php if(empty($transferencia)){ ?>
    <form method="get">
        <div class="row">
            <div class="col-md-12">
                <div class="input-group">
                    <input type="number" id="numero_tc" name="numero_tc" class="form-control" placeholder="Ingrese numero de transferencia">
                    <span class="input-group-btn">
                        <button class="btn btn-success" type="submit" name="buscar" value="1">Buscar Transferencia</button>
                    </span>
                </div>
            </div>
        </div>
        <br>
        <div class="table-responsive">
<table id="resultado" class="table table-sm table-hover datatable">
            <thead>
                <tr>
                    <th># TR</th>
                    <th>Fecha</th>
                    <th>Sucursal Origen</th>
                    <th>Sucursal Destino</th>
                    <th>Solicitante</th>
                    <th>Tipo</th>
                    <th>Estatus</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($transferencias as $rows){ ?>
                <tr>
                    <td><a href="<?php echo yii::app()->createurl('transferencias/recibir/',array('buscar'=>true,'numero_tc'=>$rows['id']))?>"><?=$rows['id']?></a></td>
                    <td><?=$rows['fecha_solicitud'];?></td>
                    <td><?=$rows['idSucursalorigen']['nombre']?></td>
                    <td><?=$rows['idSucursaldestino']['nombre']?></td>
                    <td><?=$rows['idUsuariosolicita']['nombre']?></td>
                    <td>
                        <?php if($rows['tipo']==0)
                        {
                            echo '<span class="badge badge-default">NORMAL</span>';
                        }elseif($rows['tipo']==1)
                        {
                            echo '<span class="badge badge-danger">URGENTE</span>';
                        }
                        ?>
                    </td>
                    <td>
                        <?php 
                        $estatus = $this->EstatusTR($rows['estatus']);
                        echo $estatus['badge']?>
                    </td>
                    <td>
                        <?php 
                        echo CHtml::link('Recibir',yii::app()->createurl('transferencias/recibir/',array('buscar'=>true,'numero_tc'=>$rows['id'])), array(
                            'style' => 'margin-right:3px;',
                            'class'=>'btn btn-default btn-xs',
                        ));
                       echo '<a href="'.Yii::app()->createUrl('transferencias/pdf',array('id'=>$rows['id'])).'" target="_blank" class="btn btn-danger btn-xs"><i class="fa fa-print" aria-hidden="true"></i></a>';
                        ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>


    </form>
    <?php }else{ 
            $this->opcionestitulo = '<a href="'.Yii::app()->createUrl('transferencias/pdf',array('id'=>$transferencia['id'])).'" target="_blank" class="btn btn-default btn-sm"><i class="fa fa-print" aria-hidden="true"></i> Imprimir TRX </a>';
        ?>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
            <table class="table table-sm table-hover ">
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
                    <td class="font-weight-bold"><?=$transferencia['idUsuariocrea']['nombre']?></td>
                    <td>Usuario Solicita</td>
                    <td class="font-weight-bold"><?=$transferencia['idUsuariosolicita']['nombre']?></td>
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
            </table>
        </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-9">
           <div class="table-responsive"> 
            <table id="listaprouctos" class="table table-sm table-hover table-striped" style="font-size: 11px;">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Clave</th>
                        <th>U.M.</th>
                        <th>Subtotal</th>
                        <th>Cantidad</th>
                        <th>Total</th>
                        <th style="display: none;">Recibido</th>
                        <th style="display: none;">Por recibir</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <?php foreach($conceptos as $rows){ ?>
                            <tr id="tr<?=$rows['id']?>">
                                <td><?=$rows['idProducto']['nombre']?></td>
                                <td><?=$rows['idProducto']['clave']?></td>
                                <td><?=$rows['idProducto']['idUm']['nombre']?></td>
                                <td>$ <?=number_format($rows['unitario']+$rows['iva'],2)?></td>
                                <td style="text-align: center;"><?=$rows['cantidad']?></td>
                                <td>$ <?=number_format($rows['total'],2)?></td>
                                <td id="cantidadrecibida<?=$rows['id']?>" style="text-align: center;display: none;"><?=$rows['cantidad_recibida']?></td>
                                <td id="botones<?=$rows['id']?>" style="display: none;">
                                    <?php if($rows['cantidad_pendiente']>0 && $transferencia['estatus']==3){ ?>
                                    <div class="input-group">
                                        <input type="number" class="form-control form-control-sm" value="<?=$rows['cantidad_pendiente']?>" style="width: 90px;" id="partida<?=$rows['id']?>" data-id="<?=$rows['id']?>">
                                        <span class="input-group-btn">
                                        <button type="button" class="btn btn-success btn-sm" onclick="Recibirpartida('#partida<?=$rows['id']?>')">OK</button>
                                        </span>
                                    </div>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php if($rows['idProducto']['control_por_serie']==1){
                                // obtenemos la serie y el lote de los movimientos de salida
                                // tenemos que ir a movimientos y luego a detalle
                                $movimientos = SucursalesMovimientos::model()->findAll(array(
                                    'condition'=>'tipo = 2 and tipo_identificador = 21 and id_identificador = :id_transferencia and id_partida = :id_partida',
                                    'params'=>array(':id_transferencia'=>$transferencia['id'],':id_partida'=>$rows['id'])
                                ));
                                if(!empty($movimientos)){
                                    foreach ($movimientos as $mov) {?>
                                    <tr>
                                        <td colspan="8">
                                            <b>Series lote: </b> 
                                            <?php  foreach ($mov['idMovseries'] as $serie) {?>
                                                <?=$serie['idSerie']['serie']?> - <?=$serie['idSerie']['lote']?>,
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    </tr>
                </tbody>
            </table>
        </div>
        </div>
        <div class="col-md-3">
            <div class="table-responsive">
            <table id="tabladetotales" class="table table-hover table-sm">
                <thead>
                    <tr>
                        <th colspan="3" class="text-center">Totales</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>SubTotal</td>
                        <td id="contenidosubtotal">$ <?=number_format($transferencia['subtotal'],2)?></td>
                    </tr>
                    <tr>
                        <td>IVA</td>
                        <td id="contenidoiva">$ <?=number_format($transferencia['iva'],2)?></td>
                    </tr>
                    <tr>
                        <td class="lead">Total</td>
                        <td class="lead font-weight-bold" id="contenidototal">$ <?=number_format($transferencia['total'],2)?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        </div>
        <div class="col-md-12 text-right">
            <?php if($transferencia['estatus']==3){ ?>
                <button type="button" class="btn btn-success btn-sm" onclick="Actualizarestatus(4)">
                    <i class="fa fa-check-circle" aria-hidden="true"></i>
                    Recibir transferencia
                </button>
            <?php }?>
        </div>
    </div>
    <script type="text/javascript">
    function Recibirpartida(input)
    {
        if(confirm('Favor de confirmar')==false)
        {
            return false;
        }
        var valor = $(input).val();
        var id = $(input).data('id');
        // revisamos que no sea 0
        if(valor == '' || valor == 0 || id == '')
        {
             toastr.warning('Ingrese el valor', {timeOut: 500})
        }
        // peticion ajax para recibir oc
        var jqxhr = $.ajax({
            url: "<?php echo $this->createUrl("inventario/recibirpartidatr"); ?>",
            type: "POST",
            dataType : "json",
            timeout : (120 * 1000),
            data: {
                cantidadarecibir:valor,
                id:id,
                id_tr:<?=$transferencia['id']?>
            },
            success : function(Response) {
                if (Response.requestresult == 'ok') {
                    // limpiamos el campo
                    toastr.success(Response.message,{timeOut:500})
                    // si la cantidad pendiente es = 0 ocultamos el form
                    if(Response.cantidadpendiente==0)
                    {
                        $('#botones'+id).empty();
                    }else{
                        // cantidad value
                        $('#partida'+id).val(Response.cantidadpendiente)
                    }
                    $('#cantidadrecibida'+id).empty().append(Response.cantidadrecibida);

                    
                }else{
                    toastr.warning(Response.message, {timeOut: 500})
                }
            },
            error: function(e){
                    toastr.warning('Ocurrio un error inesperado', {timeOut: 500})
                }
        });
    }
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
                    toastr.success(Response.message, {timeOut:500})
                    location.reload();
                }else{
                    toastr.warning(Response.message, {timeOut:500})
                }
            },
            error: function(e){
                    toastr.warning('Ocurrio un error inesperado', {timeOut:500})
                }
        });
    }
</script>
    <?php } ?>
</div>
