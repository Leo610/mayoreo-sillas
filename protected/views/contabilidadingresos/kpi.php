<?php
/* @var $this ClientesController */
/* @var $model Clientes */


$this->pageTitle = 'KPI ingresos';
$this->breadcrumbs = array(
    'Productos' => Yii::app()->createUrl('contabilidadingresos/index'),
    'KPI ingresos',
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
        <h1>KPI Ingresos</h1>

        <div class="row">
            <div class="col-md-12">
                <form method="post" action="<?php echo $this->createUrl("contabilidadingresos/kpiingresos"); ?>">
                    <div class="col-md-3">
                        <?php
                        $this->widget(
                            'zii.widgets.jui.CJuiAutoComplete',
                            array(
                                'name' => 'producto_nombre',
                                'source' => $this->createUrl('contabilidadingresos/buscarusuario'),
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
                                'value' => $usuario['Usuario_Nombre'],
                                'htmlOptions' => array(
                                    'class' => 'form-control',
                                    'placeholder' => 'Buscar Usuario'
                                )
                            )
                        );
                        ?>
                    </div>
                    <input type="hidden" name="id_usuario" id="id_usuario">

                    <div class="col-md-2">
                        <?php echo CHtml::submitButton('Buscar', array('class' => 'btn btn-success btn-ml')); ?>
                    </div>

                </form>
            </div>
        </div>

        <?php
        if (!empty($ingresos)) { ?>
            <br>
            <br>
            <div class="row">
                <div class="col-md-12" style="margin-left:12px;">
                    <p style="font-size: 16px;">Ingresos del Usuario
                        <b>
                            <?= $usuario['Usuario_Nombre'] ?>
                        </b>
                    </p>
                </div>
            </div>
            <br>
            <div class="row" style="display: flex; justify-content: space-between; align-items: center;">
                <div class="col-md-4 cuadros">
                    <i class="glyphicon glyphicon-signal" style="font-size: 35px; color: #0057a6;"></i><br>
                    <b style="font-size:28px">
                        <?= $ingresos[0]['cantidad_proyectos'] ?>
                    </b><br>
                    <p style="color: #0057a6; margin-bottom: 0; font-size: 18px;"><b>Pedidos vendidos</b></p>

                </div>
                <div class="col-md-4 cuadros">

                    <i class="fa fa-money" style="font-size: 35px; color: #47a447;"></i><br>
                    <b style="font-size:28px">$

                        <?= number_format($ingresos[0]['proyectos_vendidos'], 2) ?>
                    </b><br>
                    <p style="color: #47a447; margin-bottom: 0; font-size: 18px;"><b>Total de pedidos vendidos</b></p>

                </div>
            </div>
        <?php } ?>
    </div>
</div>