<?php
/* @var $this ClientesController */
/* @var $model Clientes */


$this->pageTitle = 'KPI ventas productos';
$this->breadcrumbs = array(
    'Productos' => Yii::app()->createUrl('productos/admin'),
    'KPI Ventas productos',
);

?>
<script type="text/javascript">
    $(document).ready(function () {

    });

</script>



<style>
    .cuadros {
        color: black;
        height: 250px;
        /* background: #0057a6; */
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        /* width: 80%; */
        border-radius: 15px 15px 15px 15px;
        box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.3);
    }
</style>

<div class="row">
    <div class="col-md-12">
        <h1>KPI Venta de Productos</h1>

        <div class="row">
            <div class="col-md-12">
                <form method="post" action="<?php echo $this->createUrl("productos/kpi"); ?>">
                    <div class="col-md-3">
                        <?php
                        $this->widget(
                            'zii.widgets.jui.CJuiAutoComplete',
                            array(
                                'name' => 'producto_nombre',
                                'source' => $this->createUrl('productos/Productosajax'),
                                // Opciones javascript adicionales para el plugin
                                'options' => array(
                                    'minLength' => '3',
                                    'select' => 'js:function(event, ui) {
                                            $("#id_producto").val(ui.item.id);
                                            
                 	            }',
                                    'focus' => 'js:function(event, ui) {
                                 return false;
                                }'
                                ),
                                'value' => $producto_nombre,
                                'htmlOptions' => array(
                                    'class' => 'form-control',
                                    'placeholder' => 'Buscar producto'
                                )
                            )
                        );
                        ?>
                    </div>
                    <div class="col-md-3">
                        <?php
                        $this->widget(
                            'zii.widgets.jui.CJuiAutoComplete',
                            array(
                                'name' => 'vendedor',
                                'source' => $this->createUrl('productos/buscarusuario'),
                                // Opciones javascript adicionales para el plugin
                                'options' => array(
                                    'minLength' => '3',
                                    'select' => 'js:function(event, ui) {
                                        console.log(ui);
                                            $("#id_usuario").val(ui.item.id);
                                            
                 	            }',
                                    'focus' => 'js:function(event, ui) {
                                 return false;
                                }'
                                ),
                                // 'value' => $usuario['Usuario_Nombre'],
                                'htmlOptions' => array(
                                    'class' => 'form-control',
                                    'placeholder' => 'Vendedor'
                                )
                            )
                        );
                        ?>
                    </div>
                    <div class="col-md-3">
                        <?php $this->widget(
                            'zii.widgets.jui.CJuiDatePicker',
                            array(
                                'name' => 'fechainicio',
                                'language' => 'es',
                                'htmlOptions' => array(
                                    'readonly' => "readonly",
                                    'class' => 'form-control'
                                ),
                                'options' => array(
                                    'dateFormat' => 'yy-mm-dd',
                                ),
                                'value' => $fechainicio
                            )
                        ); ?>
                    </div>
                    <div class="col-md-3">
                        <?php $this->widget(
                            'zii.widgets.jui.CJuiDatePicker',
                            array(
                                'name' => 'fechafin',
                                'language' => 'es',
                                'htmlOptions' => array(
                                    'readonly' => 'readonly',
                                    'class' => 'form-control'
                                ),
                                'options' => array(
                                    'dateFormat' => 'yy-mm-dd',

                                ),
                                'value' => $fechafin
                            )
                        ); ?>
                    </div>
                    <input type="hidden" name="id_usuario" id="id_usuario">
                    <input type="hidden" value="<?= $id_producto ?>" name="id_producto" id="id_producto">
                    <!-- <div class="col-md-3">
                            <? //php $this->widget(
                            //  'zii.widgets.jui.CJuiDatePicker',
                            //  array(
                            //      'name' => 'fechainicio',
                            //      'language' => 'es',
                            //      'htmlOptions' => array(
                            //          'readonly' => "readonly",
                            //          'class' => 'form-control'
                            //      ),
                            //      'options' => array(
                            //          'dateFormat' => 'yy-mm-dd',
                            //      ),
                            //      'value' => $fechainicio
                            //  )
                            //); ?>
                        </div> -->
                    <!-- <div class="col-md-3">
                            <? //php $this->widget(
                            //     'zii.widgets.jui.CJuiDatePicker',
                            //     array(
                            //         'name' => 'fechafin',
                            //         'language' => 'es',
                            //         'htmlOptions' => array(
                            //             'readonly' => 'readonly',
                            //             'class' => 'form-control'
                            //         ),
                            //         'options' => array(
                            //             'dateFormat' => 'yy-mm-dd',
                            
                            //         ),
                            //         'value' => $fechafin
                            //     )
                            // ); ?>
                        </div> -->
                    <div class="col-md-2">
                        <?php echo CHtml::submitButton('Buscar', array('class' => 'btn btn-success btn-ml')); ?>
                    </div>

                </form>
            </div>
        </div>

        <?php
        if (!empty($total) && !empty($cantidad)) { ?>
            <br>
            <br>
            <div class="row">
                <div class="col-md-12" style="margin-left:12px;">
                    <p style="font-size: 16px;">Datos del producto
                        <b>
                            <?= $producto_nombre ?>
                        </b>
                    </p>
                </div>
            </div>
            <br>
            <div class="row" style="display: flex; justify-content: space-between; align-items: center;">
                <div class="col-md-4 cuadros">
                    <i class="glyphicon glyphicon-signal" style="font-size: 35px; color: #0057a6;"></i><br>
                    <b style="font-size:28px">
                        <?= (!empty($total)) ? $cantidad : '' ?>
                    </b><br>
                    <p style="color: #0057a6; margin-bottom: 0; font-size: 18px;"><b>Productos vendidos</b></p>

                </div>
                <div class="col-md-4 cuadros">

                    <i class="fa fa-money" style="font-size: 35px; color: #47a447;"></i><br>
                    <b style="font-size:28px">$

                        <?= number_format($total, 2) ?>
                    </b><br>
                    <p style="color: #47a447; margin-bottom: 0; font-size: 18px;"><b>Total de productos vendidos</b></p>

                </div>
            </div>
        <?php } ?>
    </div>
</div>