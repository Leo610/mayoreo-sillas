<!-- Modal -->
<div id="crearordenescompra" class="modal fade " role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Alta de Orden de Compra</h4>
            </div>
            <div class="modal-body">
                <div class="form">
                    <?php $form = $this->beginWidget('CActiveForm', array(
                        'id' => 'agregarregistroform',
                        'action' => Yii::app()->createUrl('ordenescompra/index'),
                        'enableClientValidation' => true,
                        'clientOptions' => array('validateOnSubmit' => true, 'validateOnType' => true),
                        'htmlOptions' => array(
                            'enctype' => 'multipart/form-data',
                        ),
                    )
                    ); ?>

                    <div class="row">
                        <div class="col-md-12">
                            <p class="note">Llene los campos a continuación para dar de alta</p>
                            <?php echo $form->errorSummary($nuevaoc); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-7">
                            <div class="row">
                                <div class="col-md-6">
                                    <?php echo $form->labelEx($nuevaoc, 'id_sucursal'); ?>
                                    <?php echo $form->dropdownlist($nuevaoc, 'id_sucursal', $sucursalesdropdown, array('empty' => '-- Solicitante --', 'class' => 'form-control')); ?>
                                    <?php echo $form->error($nuevaoc, 'id_sucursal'); ?>
                                </div>

                                <div class="col-md-6">
                                    <?php echo $form->labelEx($nuevaoc, 'id_usuario_solicita'); ?>
                                    <?php echo $form->dropdownlist($nuevaoc, 'id_usuario_solicita', $usuariosdropdown, array('empty' => '-- Solicitante --', 'class' => 'form-control', 'data-plugin' => 'select2')); ?>
                                    <?php echo $form->error($nuevaoc, 'id_usuario_solicita'); ?>
                                </div>
                                <div class="col-md-12">
                                    <?php echo $form->labelEx($nuevaoc, 'id_proveedor'); ?>
                                    <?php echo $form->dropdownlist($nuevaoc, 'id_proveedor', $proveedoresdropdown, array('empty' => '-- Solicitante --', 'class' => 'form-control', 'data-plugin' => 'select2')); ?>
                                    <?php echo $form->error($nuevaoc, 'id_proveedor'); ?>
                                </div>
                                <div class="col-md-6 d-none">
                                    <?php echo $form->labelEx($nuevaoc, 'tipo_oc'); ?><br>
                                    <?php echo $form->radioButtonList($nuevaoc, 'tipo_oc', $tipodeoc, array('separator' => ' ')); ?>
                                    <?php echo $form->error($nuevaoc, 'tipo_oc'); ?>
                                </div>
                                <div class="col-md-6">
                                    <label>Incluir productos minimos</label><br>
                                    <label><input type="radio" name="productosminimos" value="0"
                                            checked="true">No</label>
                                    <label><input type="radio" name="productosminimos" value="1">Si</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="col-md-12">
                                <?php echo $form->labelEx($nuevaoc, 'comentarios'); ?>
                                <?php echo $form->textarea($nuevaoc, 'comentarios', array('class' => 'form-control', 'rows' => "5")); ?>
                                <?php echo $form->error($nuevaoc, 'comentarios'); ?>
                            </div>
                        </div>
                    </div>
                    <?php echo $form->hiddenField($nuevaoc, 'eliminado', array('value' => 0)); ?>
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <hr>
                            <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> Crear Orden
                                de Compra</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal"><i
                                    class="fa fa-times-circle"></i> Cerrar</button>
                        </div>
                    </div>
                    <?php $this->endWidget(); ?>
                </div><!-- form -->
            </div>
        </div>
    </div>
</div>