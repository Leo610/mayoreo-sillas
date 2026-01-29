<?php
/* @var $this CotizacionesController */
$this->pageTitle = 'Lista de cotizaciones';

$this->breadcrumbs = array(
  'Lista Cotizaciones',
);
$cot = 'Lista de cotizaciones';
if (isset($_GET['id_cliente']) && !empty(trim($_GET['id_cliente']))) {
  $this->renderpartial('//clientes/menu', array('opcionmenu' => 2));
  $cliente = Clientes::model()->find('id_cliente =' . $_GET['id_cliente']);
  $cot = 'Cotizaciones de ' . $cliente['cliente_nombre'];
}
?>
<script type="text/javascript">
  $(document).ready(function() {
    // Funcion para ordenar la lista de resultados
    $('#listacotizaciones').DataTable({
      dom: 'lBfrtip',
      buttons: [{
        extend: 'csv',
        title: 'Exportar',
        text: 'Exportar a EXCEL',
        exportOptions: {
          columns: ':not(.noExport)'
        }
      }, ],
      order: [
        [0, 'desc']
      ], // Ordenar por la primera columna en orden ascendente
      pageLength: 100,
      lengthMenu: [10, 25, 50, 100],
    });
  });
</script>
<div class="row">
  <div class="col-md-8">
    <h1>
      <?= $cot ?> | <a href="<?php echo Yii::app()->createUrl('/administracion/crearcotizaciones/') ?>"
        class="btn btn-success ">
        <i class="fa fa-list-ol"></i> Crear cotización
      </a>
    </h1>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <fieldset>
      <legend>Filtros </legend>
      <form method="GET">

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

        <div class="col-md-2">
          <?php echo CHtml::submitButton('Filtro', array('class' => 'btn btn-success btn-ml')); ?>
        </div>

      </form>
    </fieldset>
  </div>
</div>

<div id="scroll-container-fixedcot"
  style="overflow-x: scroll;height:50px;position: fixed;bottom: 1%; left: 0;z-index: 100;width: 100%; padding:0 10px ;">
  <div id="scroll-elem-fixedcot" style=" height:100%; padding: 10px">
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="table-responsive tablescrolcot">
      <table id="listacotizaciones" class="table  table-bordered  table-hover ">
        <thead>
          <tr>
            <th>Num</th>
            <th>Cliente</th>
            <th>Subtotal</th>
            <th>Iva</th>
            <th>Total</th>
            <th>Agente</th>
            <th>Envio de Cot</th>
            <th>Fecha Alta</th>
            <th class="noExport"></th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($listacotizaciones as $rows) {
            // $subtotal = ($rows->cotizacion_total) / 1.16;
            // $iva = ($rows->cotizacion_total / 1.16) * .16;
            if ($rows['sumar_iva'] == 1) {
              $subtotal = ($rows->cotizacion_total) / 1.16;
              $iva = ($rows->cotizacion_total / 1.16) * .16;
            } else {
              $subtotal = $rows->cotizacion_total;
              $iva = 0;
            }
            $total = $rows->cotizacion_total;
          ?>
            <tr>
              <td>
                <?= $rows->id_cotizacion ?>
              </td>
              <td>
                <?= $rows->rl_clientes['cliente_nombre'] ?>
              </td>
              <td>$
                <?= number_format($subtotal, 2) ?>
              </td>
              <td>$
                <?= number_format($iva, 2) ?>
              </td>
              <td>$
                <?= number_format($total, 2) ?>
              </td>
              <td>
                <?= $rows->rl_usuarios->Usuario_Nombre ?>
              </td>
              <td>
                <?php
                echo CHtml::link('Enviar Cotización', array('cotizaciones/enviarcotizacion/' . $rows->id_cotizacion), array('class' => "btn btn-success btn-xs"));

                ?>
              </td>
              <td>
                <?= $rows->cotizacion_fecha_alta ?>
              </td>
              <td class="noExport">
                <?php
                if ($rows->cotizacion_estatus == 2) {
                  echo '<p>La cotizacion fue cancelada </p>';
                } else {
                  if ($rows->cotizacion_estatus == 0) {
                    echo CHtml::link('Terminar cotización', array('cotizaciones/Actualizarcotizacion/' . $rows->id_cotizacion), array('class' => "btn btn-success"));
                    echo ' ' . CHtml::link('<span class="glyphicon glyphicon-trash"></span>', array('cotizaciones/cancelar/?lista=1&id=' . $rows->id_cotizacion), array('class' => "btn btn-danger", 'confirm' => 'Seguro que deseas cancelar?'));
                  } else {
                ?>
                    <?php
                    if ($rows->pedido != 1) {
                      echo CHtml::link('<i class="fa fa-search"></i> Ver', array('cotizaciones/Actualizarcotizacion/' . $rows->id_cotizacion), array('class' => "btn btn-secondary"));
                    }
                    ?>
                    <?php if ($rows->pedido != 1) { ?> -
                    <?php } ?>
                    <?php
                    echo CHtml::link('<i class="fa fa-file-pdf-o"></i>
                Ver', array('cotizaciones/pdf/' . $rows->id_cotizacion), array('class' => "btn btn-danger", 'target' => '_blank'));
                    ?>
                    -
                <?php
                    if ($rows->pedido != 1) {
                      echo CHtml::link('<i class="fa fa-check"></i> Crear pedido', array('proyectos/crear/' . $rows->id_cotizacion), array('class' => "btn btn-primary"));
                    } else if ($rows->pedido == 1) {
                      echo 'Esta cotizacion ya cuenta con pedido';
                    }
                  }
                }
                ?>

              </td>

            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</div>


<script>
  $(document).ready(function() {
    const tableWidth = $('#listacotizaciones').width();
    // asignamos el anchotabla
    const scroll = document.querySelector("#scroll-elem-fixedcot");
    scroll.style.width = (tableWidth + 15) + 'px';
    document.querySelector("#scroll-container-fixedcot").addEventListener("scroll", function() {
      document.querySelector(".tablescrolcot").scrollLeft = this.scrollLeft;
    })
  })
</script>