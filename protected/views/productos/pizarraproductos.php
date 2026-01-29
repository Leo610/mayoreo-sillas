<?php
/* @var $this AdministracionController */

$this->pageTitle = 'Pizarra de fabricación';
$this->breadcrumbs = array(

    'Pizarra de Fabricacion',
);
$readonly = ($this->VerificarAcceso(11, Yii::app()->user->id)) == 1 ? false : true;
?>
<script type="text/javascript">
    $(document).on('ready', function() {
        $("#pedidosselect2").select2({
            // dropdownParent: $("#modalagregaregreso"),
            theme: 'bootstrap',
            placeholder: 'Todos los pedidos',
            ajax: {
                url: "<?php echo $this->createUrl('productos/Traerpedidos'); ?>",
                dataType: 'json',
                method: 'POST',
                data: (params) => {
                    return {
                        q: params.term,
                        type: 'public'
                    }
                },
                processResults: (data, params) => {
                    const results = [{
                        id: '0',
                        text: 'Todos los Pedidos',
                    }];
                    results.push(...data.map(item => ({
                        id: item.id,
                        text: item.label,
                    })));
                    return {
                        results: results,
                    }
                },
            },
        });
        //   document.querySelector('.stopa').addEventListener('click', fucntion(e){
        //     e.stopPropagation();
        //   });




        // funcion para poner fecha de entrega
        $(".fechaentrega").change(function() {
            var fecha = $(this).val();
            var id = $(this).data("id");

            var jqxhr = $.ajax({
                url: "<?php echo $this->createUrl('productos/actualizarfecha'); ?>",
                type: "POST",
                dataType: "json",
                timeout: (120 * 1000),
                data: {
                    fecha: fecha,
                    id: id
                },
                success: function(Response) {
                    if (Response.requestresult == 'ok') {
                        $.notify(Response.message, "success");
                        setTimeout(function() {
                            location.reload();
                        }, 500);
                    } else {
                        $.notify(Response.message, "error");
                    }
                },
                error: function(e) {
                    $.notify(Response.message, "error");
                }
            });
        });
        // funcion para traer los prodcutos de esa bodega 
        $(".bodegapizarra").change(function() {
            var nombre = $(this).val();
            var id = "<?= Yii::app()->request->getQuery('id') ?>";

            window.location.href = "<?php echo $this->createUrl('productos/Pizarraproductos'); ?>" + "?id=" + id + "&nombre=" + nombre;
        });
        // funcion para traer los pedidos de esa bodega 



        $(".pedidopizarra").change(function() {
            var id = $(this).val();

            window.location.href = "<?php echo $this->createUrl('productos/Pizarraproductos'); ?>" + "?id=" + id;
        });

        /*OPORTUNIDADES NORMALES*/
        $(".contenidoetapa").sortable({
            connectWith: ".contenidoetapa",
            handle: ".divoportunidad",
            items: ".itemsamover",


            start: function(event, ui) {
                /*Columna Inicial*/
                //alert(event);
            },

            receive: function(event, ui) {
                /*Columna Final*/
                var id_producto = ui.item.attr('idop');
                var id_etapa = $(this).data('idetapa');
                var jqxhr = $.ajax({
                    url: "<?php echo $this->createUrl('productos/actualizaretapa'); ?>",
                    type: "POST",
                    dataType: "json",
                    timeout: (120 * 1000),
                    data: {
                        campo: 'id_etapa',
                        valor: id_etapa,
                        id: id_producto,
                    },
                    success: function(Response, newValue) {
                        if (Response.requestresult == 'ok') {
                            $.notify(Response.message, "success");
                            setTimeout(function() {
                                location.reload();
                            }, 500);
                        } else {
                            $.notify(Response.message, "error");
                        }
                    },
                    error: function(e) {
                        $.notify(Response.message, "error");
                    }
                });
            }
        });
        // script para quitar el color verde despues de 24 hrs
        // traemos los elementos con la clase pedido
        var elementosNuevos = document.querySelectorAll('.divoportunidad');
        elementosNuevos.forEach(function(e) {
            // obtenemos la fecha de creacion
            var fechaCreacion = new Date(e.getAttribute('data-creacion'));

            // calculamos la fecha de creacion y la actual
            var diferencia = new Date() - fechaCreacion;
            // Si han pasado más de 24 horas (86400000 milisegundos), quita la clase "nuevo"
            if (diferencia >= 0 && diferencia < 86400000) {
                e.classList.add('pedido');
            }
            if (diferencia >= 86400000) {
                e.classList.remove('pedido');
            }
        });

        // script para el rojo
        // obtenemos la fecha actual

        var fechaActual = new Date();
        // ajustamos la fecha al primer dia de la semana (lunes)
        fechaActual.setDate(fechaActual.getDate() - (fechaActual.getDay() + 6) % 7);
        // calculamos la fecha al final de la semana (domingo)
        var fechafinSemana = new Date(fechaActual);
        fechafinSemana.setDate(fechafinSemana.getDate() + 6);
        //  convertimos las fechas a milisegundo para facilitar la comparacion
        var inicioSemana = fechaActual.getTime();
        var finSemana = fechafinSemana.getTime();
        // obtenemos el div que tiene el valor de la fecha del prodcuto
        var cuadro = document.querySelectorAll('.divoportunidad');
        // recorremos este div y verificamos si la fecha esta en la semana
        var fechaaa = new Date();
        var fecha2 = fechaaa.getTime();
        cuadro.forEach(function(e) {
            var fecha = new Date(e.getAttribute('data-entrega')).getTime();

            if (fecha >= inicioSemana && fecha <= finSemana) {
                e.classList.add('fecha');
            } else {
                e.classList.remove('fecha');
            }
            if (fecha <= fecha2) {
                e.classList.remove('fecha');
            }
        });
        // script para si recibio un cambio colocarlo en azul 
        // creamos un ajax para revisar si hay cambios 

        // Buscar();

        var divcuadro = document.querySelectorAll('.divoportunidad');
        // var divcuadro = document.querySelectorAll('.divoportunidad');
        divcuadro.forEach(function(e) {
            var idop = e.getAttribute('data-opid');
            var jqxhr = $.ajax({
                url: "<?php echo $this->createUrl('productos/buscarcambios'); ?>",
                type: "POST",
                dataType: "json",
                timeout: (120 * 1000),
                data: {
                    id: idop,
                },
                success: function(Response, newValue) {
                    if (Response.requestresult == 'ok') {
                        // $.notify(Response.mensaje, "success");
                        e.classList.add('cambio');
                        var feclaR = Response.fecha;
                        var FechaAlta = feclaR.getTime();
                    } else {
                        // $.notify(Response.mensaje, "error");
                    }
                },
                error: function(e) {
                    $.notify(Response.mensaje, "error");
                }
            });
        });
    });
</script>

<style>
    .pedido {
        background: #0afb3a;
    }

    .fecha {
        background: red;
    }

    .cambio {
        background: #42a5f5;
    }
</style>


<div class="row">
    <div class="col-md-12 oportunidades">
        <h1 class="mt-md mb-md">
            <?= $this->pageTitle ?>
        </h1>


        <div class="row">

            <div class=" col-12 col-md-2">
                <select class="bodegapizarra form-control">
                    <option value="">Todas las bodegas</option>
                    <?php foreach ($bodegas as $bodega) { ?>
                        <?php if (isset($_GET['nombre'])) { ?>
                            <option value="<?= $bodega['nombre'] ?>" <?= ($bodega['nombre'] == $_GET['nombre']) ? 'selected' : '' ?>>
                                <?= $bodega['nombre'] ?>
                            </option>
                        <?php } else { ?>
                            <option value="<?= $bodega['nombre'] ?>">
                                <?= $bodega['nombre'] ?>
                            </option>
                        <?php } ?>
                    <?php } ?>
                </select>
            </div>

            <div class=" col-12 col-md-2">
                <select class="form-control pedidopizarra" id="pedidosselect2">
                    <?php
                    if (!empty($_GET['id'])) { ?>

                        <option value="<?= $pedido['id_proyecto'] ?>">
                            #<?= $pedido['id_proyecto'] ?> - <?= $pedido['proyecto_nombre'] ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class=" col-12 col-md-8 text-right">
                <p>
                    <?php
                    if (isset($_GET['id']) || isset($_GET['nombre'])) {
                        $id = isset($_GET['id']) ? $_GET['id'] : 0;
                        $nombre = isset($_GET['nombre']) ? $_GET['nombre'] : '';
                        echo CHtml::link('Generar Reporte', array('productos/Reportefabricacion?id=' . $_GET['id'] . '&nombre=' . $nombre . ''), array('class' => "btn btn-primary"));
                    } else {

                        echo CHtml::link('Generar Reporte', array('productos/Reportefabricacion'), array('class' => "btn btn-primary"));
                    }
                    ?>
                </p>
            </div>
        </div>
        <?php
        // foreach para obtener las etapas
        $cantidadetapas = count($Etapas);
        $widthdiv = 100 / $cantidadetapas - 2;
        foreach ($Etapas as $rows) { ?>
            <div class="divetapas" style="width:<?= $widthdiv ?>%;">
                <div class="tituloetapa">
                    <p class="mb-none tituloetapa">
                        <?= $rows->nombre ?>
                    </p>
                </div>
                <div class="contenidoetapa" data-idetapa="<?= $rows->id_catalogo_recurrente ?>" id="<?= $rows->id_catalogo_recurrente ?>">
                    <!-- obtenemos los prodcutos de cada etapa -->
                    <?php
                    ?>
                    <?php foreach ($productosPedidos as $productos) { ?>
                        <?php if ($productos->id_etapa == $rows->id_catalogo_recurrente) { ?>
                            <div class="itemsamover" idop="<?= $productos->id_proyectos_productos ?>">
                                <div data-opid="<?= $productos->id_proyectos_productos ?>" data-entrega="<?= $productos['fecha_de_entrega'] ?>" class="divoportunidad " data-creacion="<?= $productos['fecha_alta'] ?>" id_oportunidad="<?= $productos->id_etapa ?>" data-idoportunidad="<?= $productos->id_etapa ?>" id="<?= $productos->id_proyectos_productos ?>">
                                    <a href="<?php echo $this->createUrl('proyectos/detalle') . '/' . $productos->id_proyecto; ?>" title="<?= $productos['rl_producto']['producto_nombre'] ?>" idop="<?= $productos->id_proyectos_productos ?>">
                                        <small>
                                            <?php if ($productos->id_producto != '' && $productos->rl_producto->producto_nombre != '') {
                                                echo $productos->rl_producto->producto_nombre;
                                            } ?>
                                            <?php if ($productos->color != '') {
                                                echo $productos->color;
                                            } ?>
                                        </small>
                                        <p class="mb-none" style="color: black;">
                                            pedido #
                                            <b>
                                                <?= $productos->id_proyecto ?>
                                            </b>, <b>Cantidad
                                                <?= $productos->proyectos_productos_cantidad ?>
                                            </b>
                                            <?php
                                            // Verificamos si el usuario en sesion no es el de la oportunidad mostramos de quien es
                                            if ($productos->bodega != '') {
                                                echo '<br>Bodega:  <strong>' . $productos->bodega . '</strong>';
                                            }
                                            ?>
                                        </p>
                                    </a>
                                    <?php if (!$readonly) { ?>
                                        <div>
                                            <p class="mb-none" style="color: black;">Fecha de entrega</p>
                                            <?php $this->widget(
                                                'zii.widgets.jui.CJuiDatePicker',
                                                array(
                                                    'name' => 'fecha_de_entrega',
                                                    'language' => 'es',
                                                    'htmlOptions' => array(
                                                        'readonly' => "readonly",
                                                        'class' => 'fechaentrega form-control stopa',
                                                        'data-id' => $productos['id_proyectos_productos'],
                                                        'id' => 'fecha_entrega_' . $productos['id_proyectos_productos'] . ''
                                                    ),
                                                    'options' => array(
                                                        'dateFormat' => 'yy-mm-dd',
                                                    ),
                                                    'value' => $productos['fecha_de_entrega']
                                                )
                                            ); ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } ?>


                </div>

            </div>
        <?php }
        ?>
    </div>
</div>