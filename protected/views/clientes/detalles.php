<?php
/* @var $this ClientesController */
/* @var $model Clientes */


$this->pageTitle = 'Detalles del Cliente';
$this->breadcrumbs = array(
    'clientes' => Yii::app()->createUrl('clientes/admin'),
    'Detalles del Cliente',
);
$opcionmenu = 1;
?>


<?php include 'menu.php' ?>
<style>
    .ocultar {
        display: none;
    }
</style>


<script type="text/javascript">
    $(document).ready(function () {
        Actualizar(<?= $id ?>)
    });

    function direccionenvio(e) {
        // subir los inputs con la clase inputusa y a los otros colcarles la dirmex
        if (e.value == 2) {
            document.querySelectorAll('.dirmex').forEach(function (element) {
                element.classList.add('ocultar');
            });

            document.querySelectorAll('.inputusa').forEach(function (element) {
                element.classList.remove('ocultar');
            });

        } else {
            document.querySelectorAll('.dirmex').forEach(function (element) {
                element.classList.remove('ocultar');
            });

            document.querySelectorAll('.inputusa').forEach(function (element) {
                element.classList.add('ocultar');
            });
        }
    }

    function Actualizar(id) {
        var jqxhr = $.ajax({
            url: "<?php echo $this->createUrl("Clientes/Clientesdatos"); ?>",
            type: "POST",
            dataType: "json",
            timeout: (120 * 1000),
            data: {
                id: id,
            },
            success: function (Response, newValue) {
                console.log(Response);


                if (Response.requestresult == 'ok') {
                    if (Response.Datos.pais == 2) {
                        document.querySelectorAll('.dirmex').forEach(function (element) {
                            element.classList.add('ocultar');
                        });

                        document.querySelectorAll('.inputusa').forEach(function (element) {
                            element.classList.remove('ocultar');
                        });
                    }
                    // Si el resultado es correcto, agregamos los datos al form del modal
                    $("#Clientes_cliente_nombre").val(Response.Datos.cliente_nombre);
                    $("#Clientes_cliente_razonsocial").val(Response.Datos.cliente_razonsocial);
                    $("#Clientes_cliente_rfc").val(Response.Datos.cliente_rfc);
                    $("#Clientes_cliente_calle").val(Response.Datos.cliente_calle);
                    $("#Clientes_cliente_colonia").val(Response.Datos.cliente_colonia);
                    $("#Clientes_cliente_numerointerior").val(Response.Datos.cliente_numerointerior);
                    $("#Clientes_cliente_numeroexterior").val(Response.Datos.cliente_numeroexterior);
                    $("#cliente_codigopostal").val(Response.Datos.cliente_codigopostal);
                    $("#Clientes_id_lista_precio").val(Response.Datos.id_lista_precio);
                    GetColonias(Response.Datos.cliente_codigopostal);
                    if (Response.Datos.pais != 2) {

                        setTimeout(function () {
                            $("#Clientes_cliente_colonia").val(Response.Datos.cliente_colonia);
                            $("#Clientes_cliente_municipio").val(Response.Datos.cliente_municipio);
                            $("#Clientes_cliente_entidad").val(Response.Datos.cliente_entidad);
                        }, 1000);
                    }
                    $("#Clientes_cliente_municipio").val(Response.Datos.cliente_municipio);
                    $("#Clientes_cliente_entidad").val(Response.Datos.cliente_entidad);
                    $("#Clientes_cliente_pais").val(Response.Datos.cliente_pais);
                    $("#Clientes_cliente_email").val(Response.Datos.cliente_email);
                    $("#Clientes_cliente_telefono").val(Response.Datos.cliente_telefono);
                    $("#Clientes_id_cliente").val(Response.Datos.id_cliente);
                    $("#empresa").val(Response.nombree);
                    $("#Clientes_id_empresa").val(Response.Datos.id_empresa);
                    $("#Clientes_cliente_tipo").val(Response.Datos.cliente_tipo);
                    $("#Clientes_cliente_tipo_clasificacion").val(Response.Datos.cliente_tipo_clasificacion);
                    $("#Clientes_cliente_como_trabajarlo").val(Response.Datos.cliente_como_trabajarlo);
                    $("#Clientes_como_contacto").val(Response.Datos.como_contacto);
                    $("#Clientes_pais").val(Response.Datos.pais);
                    if (Response.Datos.pais == 2) {
                        setTimeout(() => {

                            $('#cpusa').val(Response.Datos.cliente_codigopostal);
                            $('#estusa').val(Response.Datos.cliente_entidad);
                            $('#munusa').val(Response.Datos.cliente_municipio);
                            $('#colusa').val(Response.Datos.cliente_colonia);
                        }, 1000);

                    }

                    //mostramos la imagen
                    if (Response.Datos.cliente_logo != '') {
                        $('#imagendiv').show();
                    }
                    $("#imagenpro").attr("src", "../images/clientes/" + Response.Datos.cliente_logo);
                    $("#imagenoriginal").val(Response.Datos.cliente_logo);
                    $("#formmodal").modal('show');
                } else {
                }
            },
            error: function (e) {
                $.notify('Ocurrio un error inesperado', 'error');
            }
        });
    }

    // function Actualizar(id) {
    //     var jqxhr = $.ajax({
    //         url: "<?php echo $this->createUrl("Clientes/Clientesdatos"); ?>",
    //         type: "POST",
    //         dataType: "json",
    //         timeout: (120 * 1000),
    //         data: {
    //             id: id,
    //         },
    //         success: function (Response, newValue) {
    //             console.log(Response);

    //             if (Response.requestresult == 'ok') {
    //                 if (Response.Datos.pais == 2) {
    //                     document.querySelectorAll('.dirmex').forEach(function (element) {
    //                         element.classList.add('ocultar');
    //                     });

    //                     document.querySelectorAll('.inputusa').forEach(function (element) {
    //                         element.classList.remove('ocultar');
    //                     });
    //                 }
    //                 // Si el resultado es correcto, agregamos los datos al form del modal
    //                 $("#Clientes_cliente_nombre").val(Response.Datos.cliente_nombre);
    //                 $("#Clientes_cliente_razonsocial").val(Response.Datos.cliente_razonsocial);
    //                 $("#Clientes_cliente_rfc").val(Response.Datos.cliente_rfc);
    //                 $("#Clientes_cliente_calle").val(Response.Datos.cliente_calle);
    //                 $("#Clientes_cliente_colonia").val(Response.Datos.cliente_colonia);
    //                 $("#Clientes_cliente_numerointerior").val(Response.Datos.cliente_numerointerior);
    //                 $("#Clientes_cliente_numeroexterior").val(Response.Datos.cliente_numeroexterior);
    //                 $("#cliente_codigopostal").val(Response.Datos.cliente_codigopostal);
    //                 $("#Clientes_id_lista_precio").val(Response.Datos.id_lista_precio);
    //                 GetColonias(Response.Datos.cliente_codigopostal);
    //                 if (Response.Datos.pais != 2) {

    //                     setTimeout(function () {
    //                         $("#Clientes_cliente_colonia").val(Response.Datos.cliente_colonia);
    //                         $("#Clientes_cliente_municipio").val(Response.Datos.cliente_municipio);
    //                         $("#Clientes_cliente_entidad").val(Response.Datos.cliente_entidad);
    //                     }, 1000);
    //                 }
    //                 // setTimeout(function () {
    //                 //     $("#Clientes_cliente_colonia").val(Response.Datos.cliente_colonia);
    //                 //     $("#Clientes_cliente_municipio").val(Response.Datos.cliente_municipio);
    //                 //     $("#Clientes_cliente_entidad").val(Response.Datos.cliente_entidad);
    //                 // }, 1000);
    //                 $("#Clientes_cliente_municipio").val(Response.Datos.cliente_municipio);
    //                 $("#Clientes_cliente_entidad").val(Response.Datos.cliente_entidad);
    //                 $("#Clientes_cliente_pais").val(Response.Datos.cliente_pais);
    //                 $("#Clientes_cliente_email").val(Response.Datos.cliente_email);
    //                 $("#Clientes_cliente_telefono").val(Response.Datos.cliente_telefono);
    //                 $("#Clientes_id_cliente").val(Response.Datos.id_cliente);
    //                 $("#empresa").val(Response.nombree);
    //                 $("#Clientes_id_empresa").val(Response.Datos.id_empresa);
    //                 $("#Clientes_cliente_tipo").val(Response.Datos.cliente_tipo);
    //                 $("#Clientes_cliente_tipo_clasificacion").val(Response.Datos.cliente_tipo_clasificacion);
    //                 $("#Clientes_cliente_como_trabajarlo").val(Response.Datos.cliente_como_trabajarlo);
    //                 $("#Clientes_como_contacto").val(Response.Datos.como_contacto);
    //                 $("#Clientes_pais").val(Response.Datos.pais);
    //                 if (Response.Datos.pais == 2) {
    //                     setTimeout(() => {

    //                         $('#cpusa').val(Response.Datos.cliente_codigopostal);
    //                         $('#estusa').val(Response.Datos.cliente_entidad);
    //                         $('#munusa').val(Response.Datos.cliente_municipio);
    //                         $('#colusa').val(Response.Datos.cliente_colonia);
    //                     }, 1000);

    //                 }

    //                 //mostramos la imagen
    //                 if (Response.Datos.cliente_logo != '') {
    //                     $('#imagendiv').show();
    //                 }
    //                 $("#imagenpro").attr("src", "../images/clientes/" + Response.Datos.cliente_logo);
    //                 $("#imagenoriginal").val(Response.Datos.cliente_logo);

    //                 $("#formmodal").modal('show');
    //             } else {
    //             }
    //         },
    //         error: function (e) {
    //             $.notify('Ocurrio un error inesperado', 'error');
    //         }
    //     });
    // }

    function GetColonias(codigopostal) {
        var jqxhr = $.ajax({
            url: "<?php echo $this->createUrl("administracion/Obtenercolonias"); ?>",
            type: "POST",
            dataType: "json",
            timeout: (120 * 1000),
            data: {
                codigopostal: codigopostal
            },
            success: function (Response, newValue) {
                if (Response.requestresult == 'ok') {
                    $.notify(Response.message, "success");

                    $('#Clientes_cliente_entidad').empty().append(Response.op_entidades);
                    $('#Clientes_cliente_municipio').empty().append(Response.op_municipios);
                    $('#Clientes_cliente_colonia').empty().append(Response.op_colonias);

                } else {
                    $.notify(Response.message, "error");
                }
            },
            error: function (e) {
                $.notify('Ocurrio un error inesperado', "error");
            }
        });
    }


</script>
<div class="container-fluid">
    <div class="row">
        <div class="form">

            <?php $form = $this->beginWidget(
                'CActiveForm',
                array(
                    'id' => 'Clientes-form',
                    'action' => Yii::app()->createUrl('Clientes/Createorupdate'),
                    'enableClientValidation' => true,
                    'clientOptions' => array('validateOnSubmit' => true, 'validateOnType' => true),
                    'htmlOptions' => array('enctype' => 'multipart/form-data'),
                )
            ); ?>

            <div class="row">
                <h2 style="padding-left: 15px; margin-bottom: 0px; ">Cliente

                    <?= $Datos['cliente_nombre'] ?>

                </h2>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-2" style="color: black; text-align: center;">Total vendido acumulado<br>
                    <b>
                        <?= '$' . number_format($proy[0]['vendido'], 2) ?>
                    </b>
                </div>
                <div class="col-md-2" style="color:green; text-align: center;">Total ingresado acumulado<br>
                    <b>
                        <?= '$' . number_format($proy[0]['acumulado'], 2) ?>
                    </b>
                </div>
                <div class="col-md-2" style="color:red; text-align: center;">Total pendiente acumulado<br>
                    <b>
                        <?= '$' . number_format($proy[0]['pendiente'], 2) ?>
                    </b>
                </div>
                <div class="col-md-2" style="color: black; text-align: center;">Total vendido año actual<br>
                    <b>
                        <?= '$' . number_format($proy2[0]['vendido'], 2) ?>
                    </b>
                </div>
                <div class="col-md-2" style="color:green; text-align: center;">total ingresado año actual<br>
                    <b>
                        <?= '$' . number_format($proy2[0]['acumulado'], 2) ?>
                    </b>
                </div>
                <div class="col-md-2" style="color:red; text-align: center;">total pendiente año actual<br>
                    <b>
                        <?= '$' . number_format($proy2[0]['pendiente'], 2) ?>
                    </b>
                </div>

            </div>
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <?php echo $form->labelEx($model, 'pais'); ?>
                    <?php echo $form->dropDownList($model, 'pais', [1 => 'México', 2 => 'USA'], array('class' => 'form-control', 'onchange' => 'direccionenvio(this)')); ?>
                    <?php echo $form->error($model, 'pais'); ?>
                </div>
                <div class="col-md-12">
                    <?php echo $form->labelEx($model, 'cliente_nombre'); ?>
                    <?php echo $form->textField($model, 'cliente_nombre', array('class' => 'form-control')); ?>
                    <?php echo $form->error($model, 'cliente_nombre'); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <?php echo $form->labelEx($model, 'cliente_email'); ?>
                    <?php echo $form->textField($model, 'cliente_email', array('class' => 'form-control')); ?>
                    <?php echo $form->error($model, 'cliente_email'); ?>
                </div>
                <div class="col-md-4">
                    <?php echo $form->labelEx($model, 'cliente_telefono'); ?>
                    <?php echo $form->textField($model, 'cliente_telefono', array('class' => 'form-control')); ?>
                    <?php echo $form->error($model, 'cliente_telefono'); ?>
                </div>
                <div class="col-md-4">
                    <?php echo $form->labelEx($model, 'id_lista_precio'); ?>
                    <?php echo $form->dropDownList($model, 'id_lista_precio', $arraylistaprecios, array('empty' => '-- Seleccione --', 'class' => 'form-control')); ?>
                    <?php echo $form->error($model, 'id_lista_precio'); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <label for="">Empresa</label>
                    <?php
                    $this->widget(
                        'zii.widgets.jui.CJuiAutoComplete',
                        array(
                            'name' => 'empresa',
                            'source' => $this->createUrl('clientes/empresa', ['format' => 'json', 'id' => true]),
                            // Opciones javascript adicionales para el plugin
                            'options' => array(
                                'minLength' => '3',
                                'select' => 'js:function(event, ui) {
                                    
                                    $("#Clientes_id_empresa").val(ui.item.id);	
                                }',
                                'change' => 'js:function(event, ui) {
                                    if (ui.item == null) {
                                        $("#Clientes_id_empresa").val("");	
                                            }
                                        }',
                                'focus' => 'js:function(event, ui) {
											return false;
										}'
                            ),
                            'htmlOptions' => array(
                                'class' => 'form-control',
                                'value' => 'aca',
                            )
                        )
                    );
                    ?>
                </div>
                <?php echo $form->hiddenField($model, 'id_empresa'); ?>
                <div class="col-md-4 dirmex">
                    <label>Código Postal</label>
                    <?php

                    $this->widget(
                        'zii.widgets.jui.CJuiAutoComplete',
                        array(
                            'name' => 'cliente_codigopostal',
                            'source' => $this->createUrl('administracion/ObtenerCP'),
                            // Opciones javascript adicionales para el plugin
                            'options' => array(
                                'minLength' => '3',
                                'select' => 'js:function(event, ui) {
                  $("#cliente_codigopostal").val(ui.item.value);
                  GetColonias(ui.item.id);
                 }',
                                'focus' => 'js:function(event, ui) {
                  return false;
              }'
                            ),
                            'htmlOptions' => array(
                                'class' => 'form-control',
                                'placeholder' => 'Ingrese el código postal'
                            )
                        )
                    );
                    ?>
                </div>
                <div class="col-md-4 inputusa ocultar">
                    <?php echo $form->labelEx($model, 'cliente_codigopostal'); ?>
                    <?php echo $form->textField($model, 'cliente_codigopostal', array('class' => 'form-control', 'placeholder' => 'Introduzca el codigo postal', 'id' => 'cpusa')); ?>
                    <?php echo $form->error($model, 'cliente_codigopostal'); ?>
                </div>
                <div class="col-md-4">
                    <?php echo $form->labelEx($model, 'cliente_pais'); ?>
                    <?php echo $form->textField($model, 'cliente_pais', array('class' => 'form-control', 'value' => 'México')); ?>
                    <?php echo $form->error($model, 'cliente_pais'); ?>
                </div>
                <div class="col-md-4 dirmex">
                    <?php echo $form->labelEx($model, 'cliente_entidad'); ?>
                    <?php echo $form->dropDownList($model, 'cliente_entidad', array(), array('class' => 'form-control')); ?>
                    <?php echo $form->error($model, 'cliente_entidad'); ?>
                </div>
                <div class="col-md-4 inputusa ocultar">
                    <?php echo $form->labelEx($model, 'cliente_entidad'); ?>
                    <?php echo $form->textField($model, 'cliente_entidad', array('class' => 'form-control', 'placeholder' => 'Introduzca el estado', 'id' => 'estusa', 'name' => 'estusa')); ?>
                    <?php echo $form->error($model, 'cliente_entidad'); ?>
                </div>

                <div class="col-md-4 dirmex">
                    <?php echo $form->labelEx($model, 'cliente_municipio'); ?>
                    <?php echo $form->dropDownList($model, 'cliente_municipio', array(), array('class' => 'form-control')); ?>
                    <?php echo $form->error($model, 'cliente_municipio'); ?>
                </div>
                <div class="col-md-4 inputusa ocultar">
                    <?php echo $form->labelEx($model, 'cliente_municipio'); ?>
                    <?php echo $form->textField($model, 'cliente_municipio', array('class' => 'form-control', 'placeholder' => 'Introduzca el municipio', 'id' => 'munusa', 'name' => 'munusa')); ?>
                    <?php echo $form->error($model, 'cliente_municipio'); ?>
                </div>
                <div class="col-md-4 dirmex">
                    <?php echo $form->labelEx($model, 'cliente_colonia'); ?>
                    <?php echo $form->textField($model, 'cliente_colonia', array('class' => 'form-control')); ?>
                    <!-- <?//php // echo $form->dropDownList($model, 'cliente_colonia', array(), array('class' => 'form-control'));     ?> -->
                    <?php echo $form->error($model, 'cliente_colonia'); ?>
                </div>
                <div class="col-md-4 inputusa ocultar">
                    <?php echo $form->labelEx($model, 'cliente_colonia'); ?>
                    <?php echo $form->textField($model, 'cliente_colonia', array('class' => 'form-control', 'placeholder' => 'Introduzca la colonia', 'id' => 'colusa', 'name' => 'colusa')); ?>
                    <?php echo $form->error($model, 'cliente_colonia'); ?>
                </div>
                <div class="col-md-4">
                    <?php echo $form->labelEx($model, 'cliente_calle'); ?>
                    <?php echo $form->textField($model, 'cliente_calle', array('class' => 'form-control')); ?>
                    <?php echo $form->error($model, 'cliente_calle'); ?>
                </div>

                <div class="col-md-4">
                    <?php echo $form->labelEx($model, 'cliente_numeroexterior'); ?>
                    <?php echo $form->textField($model, 'cliente_numeroexterior', array('class' => 'form-control')); ?>
                    <?php echo $form->error($model, 'cliente_numeroexterior'); ?>
                </div>
                <div class="col-md-4">
                    <?php echo $form->labelEx($model, 'cliente_numerointerior'); ?>
                    <?php echo $form->textField($model, 'cliente_numerointerior', array('class' => 'form-control')); ?>
                    <?php echo $form->error($model, 'cliente_numerointerior'); ?>
                </div>

                <!-- <div class="col-md-4">
                    <? //php echo $form->labelEx($model, 'cliente_tipo_clasificacion');       ?>
                    <? //php echo $form->dropDownList($model, 'cliente_tipo_clasificacion', $ListaClasificacion, array('empty' => '-- Seleccione --', 'class' => 'form-control'));       ?>
                    <? //php echo $form->error($model, 'cliente_tipo_clasificacion');       ?>
                </div>
                <div class="col-md-4">
                    <? //php echo $form->labelEx($model, 'cliente_como_trabajarlo');       ?>
                    <? //php echo $form->dropDownList($model, 'cliente_como_trabajarlo', $ListaComoTrabajarlo, array('empty' => '-- Seleccione --', 'class' => 'form-control'));       ?>
                    <? //php echo $form->error($model, 'cliente_como_trabajarlo');       ?>
                </div> -->
                <div class="col-md-4">
                    <?php echo $form->labelEx($model, 'cliente_tipo'); ?>
                    <?php echo $form->dropDownList($model, 'cliente_tipo', $listatipo, array('empty' => '-- Seleccione --', 'class' => 'form-control')); ?>
                    <?php echo $form->error($model, 'cliente_tipo'); ?>
                </div>
                <div class="col-md-4">
                    <?php echo $form->labelEx($model, 'como_contacto'); ?>
                    <?php echo $form->dropDownList($model, 'como_contacto', array(
                        'Facebook' => 'Facebook',
                        'Instagram' => 'Instagram',
                        'WhatsApp' => 'WhatsApp',
                        'Llamada a celular' => 'Llamada a celular',
                        'Llamada a telefonos de oficina' => 'Llamada a telefonos de oficina',
                        'Correo electronico' => 'Correo electronico',
                        'TikTok' => 'TikTok',
                        'Google business' => 'Google business',
                    ), array('empty' => '-- Seleccione --', 'class' => 'form-control')); ?>
                    <?php echo $form->error($model, 'como_contacto'); ?>
                </div>
            </div>

            <?php echo $form->hiddenField($model, 'id_cliente'); ?>


            <div class="row buttons">
                <div class="col-md-12 mt-md center">
                    <?php echo CHtml::submitButton('Guardar', array('class' => 'btn btn-success')); ?>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <?php
                    echo CHtml::link(
                        '<br><br><i class="fa fa-trash fa-lg"></i> Eliminar',
                        array('clientes/delete', 'id' => $id),
                        array(
                            'submit' => array('clientes/delete', 'id' => $id),
                            'class' => 'delete',
                            'confirm' => 'Seguro que lo deseas eliminar?'
                        )
                    );
                    ?>
                </div>
            </div>
            <?php $this->endWidget(); ?>
        </div><!-- form -->
    </div>
</div>