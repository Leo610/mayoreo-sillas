<!-- Modal -->
<div id="agregarregistromodal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Agregar sucursal</h4>
            </div>
            <div class="modal-body">
                <div class="form">
                    <?php $form = $this->beginWidget('CActiveForm', array(
                        'id' => 'agregarregistroform',
                        'action' => Yii::app()->createUrl('sucursales/agregar'),
                        'enableClientValidation' => true,
                        'clientOptions' => array('validateOnSubmit' => true, 'validateOnType' => true),
                        'htmlOptions' => array(
                            'enctype' => 'multipart/form-data',
                        ),
                    )
                    ); ?>

                    <div class="row">
                        <div class="col-md-12">
                            <?php echo $form->errorSummary($model); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <?php echo $form->labelEx($model, 'nombre'); ?>
                            <?php echo $form->textField($model, 'nombre', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'nombre'); ?>
                        </div>
                        <div class="col-md-4">
                            <?php echo $form->labelEx($model, 'direccion'); ?>
                            <?php echo $form->textField($model, 'direccion', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'direccion'); ?>
                        </div>
                        <div class="col-md-4">
                            <?php echo $form->labelEx($model, 'colonia'); ?>
                            <?php echo $form->textField($model, 'colonia', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'colonia'); ?>
                        </div>
                        <div class="col-md-3">
                            <?php echo $form->labelEx($model, 'codigo_postal'); ?>
                            <?php echo $form->textField($model, 'codigo_postal', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'codigo_postal'); ?>
                        </div>
                        <div class="col-md-3">
                            <?php echo $form->labelEx($model, 'estado'); ?>
                            <?php echo $form->dropdownlist($model, 'estado', $entidades, array('empty' => '-- Seleccione estado  --', 'class' => 'form-control actualizarmunicipios', 'data-campo' => 'agregar')); ?>
                            <?php echo $form->error($model, 'estado'); ?>
                        </div>
                        <div class="col-md-3">
                            <?php echo $form->labelEx($model, 'ciudad'); ?>
                            <?php echo $form->dropdownlist($model, 'ciudad', array(), array('empty' => '-- Seleccione --', 'class' => 'form-control ciudades')); ?>
                            <?php echo $form->error($model, 'ciudad'); ?>
                        </div>
                        <div class="col-md-3">
                            <?php echo $form->labelEx($model, 'logotipo'); ?>
                            <?php echo $form->filefield($model, 'logotipo', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'logotipo'); ?>
                        </div>
                        <div class="col-md-3">
                            <?php echo $form->labelEx($model, 'tel1'); ?>
                            <?php echo $form->textField($model, 'tel1', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'tel1'); ?>
                        </div>
                        <div class="col-md-3">
                            <?php echo $form->labelEx($model, 'tel2'); ?>
                            <?php echo $form->textField($model, 'tel2', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'tel2'); ?>
                        </div>
                        <div class="col-md-3">
                            <?php echo $form->labelEx($model, 'web'); ?>
                            <?php echo $form->textField($model, 'web', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'web'); ?>
                        </div>
                        <div class="col-md-3">
                            <?php echo $form->labelEx($model, 'correo'); ?>
                            <?php echo $form->textField($model, 'correo', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'correo'); ?>
                        </div>
                        <div class="col-md-3">
                            <?php echo $form->labelEx($model, 'correo_compras'); ?>
                            <?php echo $form->textField($model, 'correo_compras', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'correo_compras'); ?>
                        </div>
                        <div class="col-md-3">
                            <?php echo $form->labelEx($model, 'rfc'); ?>
                            <?php echo $form->textField($model, 'rfc', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'rfc'); ?>
                        </div>
                        <div class="col-md-3">
                            <?php echo $form->labelEx($model, 'minimo_caja'); ?>
                            <?php echo $form->textField($model, 'minimo_caja', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'minimo_caja'); ?>
                        </div>
                        <div class="col-md-3">
                            <?php echo $form->labelEx($model, 'aviso_monto_retiro'); ?>
                            <?php echo $form->textField($model, 'aviso_monto_retiro', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'aviso_monto_retiro'); ?>
                        </div>
                        <div class="col-md-2">
                            <?php echo $form->labelEx($model, 'porcentaje_iva'); ?>
                            <?php echo $form->textField($model, 'porcentaje_iva', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'porcentaje_iva'); ?>
                        </div>
                        <div class="col-md-5">
                            <?php echo $form->labelEx($model, 'facturacion_regimen_fiscal'); ?>
                            <?php echo $form->dropdownlist($model, 'facturacion_regimen_fiscal', $listaregimenes, array('empty' => '-- Seleccione   --', 'class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'facturacion_regimen_fiscal'); ?>
                        </div>
                        <div class="col-md-5">
                            <?php echo $form->labelEx($model, 'nombre_fiscal'); ?>
                            <?php echo $form->textField($model, 'nombre_fiscal', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'nombre_fiscal'); ?>
                        </div>
                        <div class="col-md-5">
                            <?php echo $form->labelEx($model, 'bodega_principal'); ?>
                            <?php echo $form->dropdownlist($model, 'bodega_principal', array('empty' => '-- Seleccione Tipo  --', '0' => 'No', '1' => 'Si'), array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'bodega_principal'); ?>
                        </div>
                        <div class="col-md-5">
                            <?php echo $form->labelEx($model, 'plazo_cancelar_ticket'); ?>
                            <?php echo $form->textField($model, 'plazo_cancelar_ticket', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'plazo_cancelar_ticket'); ?>
                        </div>
                        <div class="col-md-12">
                            <?php echo $form->labelEx($model, 'datos_bancarios'); ?>
                            <?php echo $form->textarea($model, 'datos_bancarios', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'datos_bancarios'); ?>
                        </div>
                        <div class="col-md-12">
                            <?php echo $form->labelEx($model, 'ticket_texto'); ?>
                            <?php echo $form->textarea($model, 'ticket_texto', array('class' => 'form-control')); ?>
                            <?php echo $form->error($model, 'ticket_texto'); ?>
                        </div>


                        <!-- DATOS DE LA SUCURSAL -->
                        <div class="col-md-12 mt-2 pr-0 pl-0 d-none">
                            <div id="accordion" role="tablist" aria-multiselectable="true" style="width: 100%">
                                <div class="card mb-0">
                                    <div class="card-header" role="tab" id="headingOne">
                                        <h5 class="mb-0 mt-0">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"
                                                aria-expanded="true" aria-controls="collapseOne"
                                                style="display: block;">
                                                Configuracion de correo
                                            </a>
                                        </h5>
                                    </div>
                                    <div id="collapseOne" class="collapse " role="tabpanel"
                                        aria-labelledby="headingOne">
                                        <div class="card-block pt-0 pb-0">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <?php echo $form->labelEx($model, 'email_smtp'); ?>
                                                    <?php echo $form->textField($model, 'email_smtp', array('class' => 'form-control')); ?>
                                                    <?php echo $form->error($model, 'email_smtp'); ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <?php echo $form->labelEx($model, 'puerto_smtp'); ?>
                                                    <?php echo $form->textField($model, 'puerto_smtp', array('class' => 'form-control')); ?>
                                                    <?php echo $form->error($model, 'puerto_smtp'); ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <?php echo $form->labelEx($model, 'servidor_smtp'); ?>
                                                    <?php echo $form->textField($model, 'servidor_smtp', array('class' => 'form-control')); ?>
                                                    <?php echo $form->error($model, 'servidor_smtp'); ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <?php echo $form->labelEx($model, 'password_smtp'); ?>
                                                    <?php echo $form->textField($model, 'password_smtp', array('class' => 'form-control')); ?>
                                                    <?php echo $form->error($model, 'password_smtp'); ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <?php echo $form->labelEx($model, 'seguridad_smtp'); ?>
                                                    <?php echo $form->textField($model, 'seguridad_smtp', array('class' => 'form-control')); ?>
                                                    <?php echo $form->error($model, 'seguridad_smtp'); ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <?php echo $form->labelEx($model, 'activar_smtp'); ?>
                                                    <?php echo $form->dropdownlist($model, 'activar_smtp', array('0' => 'NO ACTIVO', '1' => 'ACTIVO'), array('class' => 'form-control')); ?>
                                                    <?php echo $form->error($model, 'activar_smtp'); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- DATOS DEL EMPLEADO TERMINA -->
                    </div>
                    <?php echo $form->hiddenField($model, 'pais', array('value' => 1)); ?>
                    <?php echo $form->hiddenField($model, 'estatus', array('value' => 1)); ?>
                    <?php echo $form->hiddenField($model, 'eliminado', array('value' => 0)); ?>
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <hr>
                            <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i>
                                Guardar</button>
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