<?php
$this->pageTitle='Pagar orden de compra';

$this->pageDescription = '';
$this->breadcrumbs=array(
    $this->pageTitle
);

// menu de inventario
$this->renderpartial('//inventario/menu',array('op_menu'=>13));
?>

<div class="panel">
    <?php if(empty($ordencompra)){ ?>
    <form method="get">
        <div class="row">
            <div class="col-md-12">
                <div class="input-group">
                    <input type="number" id="numero_oc" name="numero_oc" class="form-control" placeholder="Ingrese numero de orden de compra">
                    <span class="input-group-btn">
                        <button class="btn btn-success" type="submit" name="buscar" value="1">Buscar orden de compra</button>
                    </span>
                </div>
            </div>
        </div>
        <br>
        <div class="table-responsive">
<table id="resultado" class="table table-sm table-hover datatable">
            <thead>
                <tr>
                    <th># OC</th>
                    <th>Fecha</th>
                    <th>Sucursal</th>
                    <th>Solicitante</th>
                    <th>Proveedor</th>
                    <th>Total</th>
                    <th>Total Pagado</th>
                    <th>Total Pendiente</th>
                    <th>Tipo</th>
                    <th>Estatus</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($ordenescompra as $rows){ ?>
                <tr>
                    <td><?=$rows['id']?></td>
                    <td><?=$rows['fecha_alta'];?></td>
                    <td><?=$rows['idSucursal']['nombre']?></td>
                    <td><?=$rows['idUsuariosolicita']['nombre']?></td>
                    <td><?=$rows['idProveedor']['nombre']?></td>
                    <td>$ <?=number_format($rows['total'],2)?></td>
                    <td>$ <?=number_format($rows['total_pagado'],2)?></td>
                    <td>$ <?=number_format($rows['total_pendiente'],2)?></td>
                    <td>
                        <?php if($rows['tipo_oc']==0)
                        {
                            echo '<span class="badge badge-default">NORMAL</span>';
                        }elseif($rows['tipo_oc']==1)
                        {
                            echo '<span class="badge badge-danger">URGENTE</span>';
                        }
                        ?>
                    </td>
                    <td>
                        <?php 
                        $estatus = $this->EstatusOC($rows['estatus']);
                        echo $estatus['badge']?>
                    </td>
                    <td>
                        <?php 
                        echo CHtml::link('<i class="fa fa-money" aria-hidden="true"></i> Pagar',yii::app()->createurl('ordenescompra/pagar/',array('numero_oc'=>$rows['id'],'buscar'=>1)), array(
                            'style' => 'margin-right:3px;',
                            'class'=>'btn btn-default btn-xs',
                        ));

                        //
                        echo '<a href="'.Yii::app()->createUrl('ordenescompra/pdf',array('id'=>$rows['id'])).'" target="_blank" class="btn btn-danger btn-xs"><i class="fa fa-print" aria-hidden="true"></i>  </a>';
                        ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
            </table>
            </div>
    </form>
    <?php }else{ 
            $this->opcionestitulo = '<a href="'.Yii::app()->createUrl('ordenescompra/pdf',array('id'=>$ordencompra['id'])).'" target="_blank" class="btn btn-default btn-sm"><i class="fa fa-print" aria-hidden="true"></i> Imprimir OC </a>';
        ?>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
            <table class="table table-sm table-hover ">
                <tr>
                    <td># Orden de Compra</td>
                    <td class="text-danger font-weight-bold"><?=$ordencompra['id']?></td>
                    <td>Sucursal</td>
                    <td class="font-weight-bold"><?=$ordencompra['idSucursal']['nombre']?></td>
                    <td>Usuario Solicita</td>
                    <td class="font-weight-bold"><?=$ordencompra['idUsuariosolicita']['nombre']?></td>
                    <td>Fecha</td>
                    <td class="font-weight-bold"><?=$ordencompra['fecha_alta']?></td>
                </tr>
                <tr>
                    <td>Proveedor</td>
                    <td class="font-weight-bold"><?=$ordencompra['idProveedor']['nombre']?></td>
                    <td>Generó</td>
                    <td class="font-weight-bold"><?=$ordencompra['idUsuariocrea']['nombre']?></td>
                    <td>Estatus</td>
                    <td class="font-weight-bold">
                        <?php 
                            $estatus = $this->EstatusOC($ordencompra['estatus']);
                            echo $estatus['badge']
                        ?>
                    </td>
                    <td>Prioridad</td>
                    <td class="font-weight-bold">
                        <?php if($ordencompra['tipo_oc']==0)
                        {
                            echo '<span class="badge badge-default">NORMAL</span>';
                        }elseif($ordencompra['tipo_oc']==1)
                        {
                            echo '<span class="badge badge-danger">URGENTE</span>';
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td class="font-weight-bold"></td>
                    <td>Total</td>
                    <td class=" font-weight-bold">$ <?=number_format($ordencompra['total'],2)?></td>
                    <td>Total pagado</td>
                    <td class="font-weight-bold">$ <?=number_format($ordencompra['total_pagado'],2)?></td>
                    <td>Total pendiente</td>
                    <td class="text-danger font-weight-bold">$ <?=number_format($ordencompra['total_pendiente'],2)?></td>
                    
                </tr>
                <tr>
                    <td>Comentarios</td>
                    <td class="font-weight-bold" colspan="7"><?=$ordencompra['comentarios']?></td>
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
                        <th>Recibido</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($conceptos as $rows){ ?>
                        <tr id="tr<?=$rows['id']?>">
                            <td><?=$rows['concepto']?></td>
                            <td><?=$rows['idProducto']['clave']?></td>
                            <td><?=$rows['idProducto']['idUm']['nombre']?></td>
                            <td>$ <?=number_format($rows['unitario']+$rows['iva'],2)?></td>
                            <td style="text-align: center;"><?=$rows['cantidad']?></td>
                            <td>$ <?=number_format($rows['total'],2)?></td>
                            <td id="cantidadrecibida<?=$rows['id']?>" style="text-align: center;"><?=$rows['cantidad_recibida']?></td>
                        </tr>
                    <?php } ?>
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
                        <td id="contenidosubtotal">$ <?=number_format($ordencompra['subtotal'],2)?></td>
                    </tr>
                    <tr>
                        <td>IVA</td>
                        <td id="contenidoiva">$ <?=number_format($ordencompra['iva'],2)?></td>
                    </tr>
                    <tr>
                        <td class="lead">Total</td>
                        <td class="lead font-weight-bold" id="contenidototal">$ <?=number_format($ordencompra['total'],2)?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        </div>
    </div>

    <div class="row" >
        <div class="col-md-9">
            <div class="table-responsive">
            <table id="tablapagos" class="table table-sm table-hover table-striped datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th># OC</th>
                        <th>Forma de pago</th>
                        <th>Monto</th>
                        <th>Usuario</th>
                        <th>Comentarios</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($pagos as $rows){ ?>
                        <tr>
                            <td><?=$rows['id']?></td>
                            <td><?=$rows['id_identificador']?></td>
                            <td><?=$rows['idFormapago']['nombre']?></td>
                            <td>$ <?=number_format($rows['monto'],2)?></td>
                            <td><?=$rows['idUsuario']['nombre']?></td>
                            <td><?=$rows['comentarios']?></td>
                            <td><?=$rows['fecha_alta']?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        </div>
        <?php if($ordencompra['total_pendiente']>0){ ?> 
        <div class="col-md-3">
            <div class="form">
                <?php $form=$this->beginWidget('CActiveForm', array(
                'id'=>'modeloparaegreo',
                'action'=>Yii::app()->createUrl('ordenescompra/agregarpago'),
                'enableClientValidation'=>true,
                'clientOptions' => array('validateOnSubmit'=>true, 'validateOnType'=>true),
                'htmlOptions' => array(
                    'enctype' => 'multipart/form-data',
                    ),
                    )); ?>

                    <div class="row">
                        <div class="col-md-12">
                            <p class="note">Llene los campos a continuación para agregar un pago a la orden de compra</p>
                            <?php echo $form->errorSummary($modeloparaegreo); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <?php echo $form->labelEx($modeloparaegreo,'id_forma_pago'); ?>
                            <?php echo $form->dropdownlist($modeloparaegreo,'id_forma_pago',$metodosdepago,array('empty'=>'-- Seleccione --','class'=>'form-control')); ?>
                            <?php echo $form->error($modeloparaegreo,'id_forma_pago'); ?>
                        </div>
                        <div class="col-md-12">
                            <?php echo $form->labelEx($modeloparaegreo,'monto'); ?>
                            <?php echo $form->numberfield($modeloparaegreo,'monto',array('class'=>'form-control','min'=>0,'step'=>'any')); ?>
                            <?php echo $form->error($modeloparaegreo,'monto'); ?>
                        </div>
                        <div class="col-md-12">
                            <?php echo $form->labelEx($modeloparaegreo,'comentarios'); ?>
                            <?php echo $form->textarea($modeloparaegreo,'comentarios',array('class'=>'form-control')); ?>
                            <?php echo $form->error($modeloparaegreo,'comentarios'); ?>
                        </div>
                    </div>
                    <?php echo $form->hiddenField($modeloparaegreo,'tipo',array('value'=>3)); ?>
                    <?php echo $form->hiddenField($modeloparaegreo,'id_identificador',array('value'=>$ordencompra['id'])); ?>
                    <?php echo $form->hiddenField($modeloparaegreo,'eliminado',array('value'=>0)); ?>
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <hr>    
                            <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> Agregar pago</button>
                        </div>
                    </div>
                    <?php $this->endWidget(); ?>
                </div><!-- form -->
            </div>
            <?php } ?>
        </div>
    <?php } ?>
</div>