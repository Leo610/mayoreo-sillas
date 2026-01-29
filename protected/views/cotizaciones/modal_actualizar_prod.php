<div class="modal fade" id="proddescmodal" tabindex="-1" role="dialog" aria-labelledby="proddescmodalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="proddescmodalLabel">Actualizar prodcutos cotizacion</h4>
            </div>
            <div class="modal-body">
                <!-- <form class="form"> -->
                <?php $form = $this->beginWidget(
                    'CActiveForm',
                    array(

                        'action' => Yii::app()->createUrl('cotizaciones/actualizarproductoscot'),
                        'method' => 'post',
                        'enableClientValidation' => true,


                    )
                ); ?>
                <div class="row">
                    <?php echo $form->hiddenField($model, 'id_cotizacion_producto'); ?>
                    <div class="col-md-6">
                        <label for="">Cantidad</label>
                        <?php echo $form->textField($model, 'cotizacion_producto_cantidad', array('class' => 'form-control', 'id' => 'Cotizacionesproductos_cotizacion_producto_cantidad2')); ?>
                        <?php echo $form->error($model, 'cotizacion_producto_cantidad'); ?>
                    </div>
                    <div class="col-md-6">
                        <label for="">Precio</label>
                        <?php echo $form->textField($model, 'cotizacion_producto_unitario', array('class' => 'form-control', 'id' => 'Cotizacionesproductos_cotizacion_producto_unitario2')); ?>
                        <?php echo $form->error($model, 'cotizacion_producto_unitario'); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label for="">Color tapiceria</label>
                        <?php echo $form->textField($model, 'color_tapiceria', array('class' => 'form-control')); ?>
                        <?php echo $form->error($model, 'color_tapiceria'); ?>
                    </div>
                    <div class="col-md-6">
                        <label for="">Color estructura</label>
                        <?php echo $form->dropDownList($model, 'color', $colores, array('empty' => 'Color de Estructura', 'class' => 'form-control')); ?>
                        <?php echo $form->error($model, 'color'); ?>
                    </div>
                    <div class="col-md-6">
                        <label for="">T. Descuento</label>
                        <?php echo $form->dropDownList($model, 'tipo_descuetno', array('porcentaje' => 'Porcentaje', 'monto' => 'Monto x Producto'), array('class' => 'form-control', 'id' => 'Cotizacionesproductos_tipo_descuetno2')); ?>
                        <?php echo $form->error($model, 'tipo_descuetno'); ?>
                    </div>
                    <div class="col-md-6">
                        <label for="">Descuento</label>
                        <?php echo $form->textField($model, 'descuento', array('class' => 'form-control', 'id' => 'Cotizacionesproductos_descuento2')); ?>
                        <?php echo $form->error($model, 'descuento'); ?>
                    </div>


                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label for="">Descripcion</label>
                        <?php echo $form->textField($model, 'cotizacion_producto_descripcion', array('class' => 'form-control', 'id' => 'Cotizacionesproductos_cotizacion_producto_descripcion2')); ?>
                        <?php echo $form->error($model, 'cotizacion_producto_unitario'); ?>
                    </div>
                </div>
                <div class="row">

                    <div class="col-md-12">
                        <?php echo $form->labelEx($model, 'especificaciones_extras'); ?>
                        <?php echo $form->textField($model, 'especificaciones_extras', array('class' => 'form-control', 'id' => 'Cotizacionesproductos_especificaciones_extras2')); ?>
                        <?php echo $form->error($model, 'especificaciones_extras'); ?>
                    </div>
                </div>
                <div class="row buttons">
                    <div class="col-md-12 mt-md center">
                        <?php echo CHtml::submitButton('Actualizar', array('class' => 'btn btn-success')); ?>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
                <?php $this->endWidget(); ?>
                <!-- </form> -->
            </div>
        </div>
    </div>
</div>