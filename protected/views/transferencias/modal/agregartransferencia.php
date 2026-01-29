<!-- Modal -->
<div id="creartransferencia" class="modal fade " role="dialog">
   <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Alta de Transferencia</h4>
    </div>
    <div class="modal-body">
        <div class="form">
            <?php $form=$this->beginWidget('CActiveForm', array(
                'id'=>'agregarregistroform',
                'action'=>Yii::app()->createUrl('transferencias/index'),
                'enableClientValidation'=>true,
                'clientOptions' => array('validateOnSubmit'=>true, 'validateOnType'=>true),
                'htmlOptions' => array(
                    'enctype' => 'multipart/form-data',
                    ),
                    )); ?>

                    <div class="row">
                        <div class="col-md-12">
                            <p class="note">Llene los campos a continuación para dar de alta</p>
                            <?php echo $form->errorSummary($nuevatr); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-7">
                            <div class="row">
                                <div class="col-md-6">
                                    <?php echo $form->labelEx($nuevatr,'id_sucursal_origen'); ?>
                                    <?php echo $form->dropdownlist($nuevatr,'id_sucursal_origen',$sucursalesdropdown,array('empty'=>'-- Solicitante --','class'=>'form-control')); ?>
                                    <?php echo $form->error($nuevatr,'id_sucursal_origen'); ?>
                                </div>
                                <div class="col-md-6">
                                    <?php echo $form->labelEx($nuevatr,'id_sucursal_destino'); ?>
                                    <?php echo $form->dropdownlist($nuevatr,'id_sucursal_destino',$sucursalesdropdown,array('empty'=>'-- Solicitante --','class'=>'form-control')); ?>
                                    <?php echo $form->error($nuevatr,'id_sucursal_destino'); ?>
                                </div>
                                <div class="col-md-6">
                                    <?php echo $form->labelEx($nuevatr,'id_usuario_solicita'); ?>
                                    <?php echo $form->dropdownlist($nuevatr,'id_usuario_solicita',$usuariosdropdown,array('empty'=>'-- Solicitante --','class'=>'form-control','data-plugin'=>'select2')); ?>
                                    <?php echo $form->error($nuevatr,'id_usuario_solicita'); ?>
                                </div>
                                <div class="col-md-6">
                                     <?php echo $form->labelEx($nuevatr,'tipo'); ?>
                                    <div>
                                        <?php echo $form->radioButtonList($nuevatr,'tipo',$tipodetr,array('separator'=>' '));?>
                                        <?php echo $form->error($nuevatr,'tipo'); ?>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="col-md-12">
                                <?php echo $form->labelEx($nuevatr,'comentarios'); ?>
                                <?php echo $form->textarea($nuevatr,'comentarios',array('class'=>'form-control','rows'=>"5")); ?>
                                <?php echo $form->error($nuevatr,'comentarios'); ?>
                            </div>
                        </div>
                    </div>
                    <?php echo $form->hiddenField($nuevatr,'eliminado',array('value'=>0)); ?>
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <hr>
                            <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> Crear Transferencia</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cerrar</button>
                        </div>
                    </div>
                    <?php $this->endWidget(); ?>
                </div><!-- form -->
            </div>
        </div>
    </div>
</div>