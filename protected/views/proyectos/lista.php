<?php
/* @var $this ProyectosController */
$this->pageTitle = 'Lista de pedidos';

$this->breadcrumbs = array(
  'Lista pedidos',
);
$ver = $this->VerificarAcceso(29, Yii::app()->user->id);

$pedidos = 'Lista de pedidos';
if (isset($_GET['id_cliente']) && !empty(trim($_GET['id_cliente']))) {
  $this->renderpartial('//clientes/menu', array('opcionmenu' => 3));
  $cliente = Clientes::model()->find('id_cliente =' . $_GET['id_cliente']);
  $pedidos = 'Pedidos del cliente ' . $cliente['cliente_nombre'];
}
?>
<script type="text/javascript">
  $(document).ready(function() {
    // Funcion para ordenar la lista de resultados, lo ordena por la columna 3, ya que empieza 0,1,2,3,4 y muestra  5 resultados, solamente
    $('#listaproyectos').DataTable({
      pageLength: 100,
      "order": [
        [0, "desc"]
      ],
      dom: 'lBfrtip',
      // buttons: [{
      //     extend: 'csv',
      //     title: 'Pedidos',
      //     text: 'Exportar a EXCEL',
      //     exportOptions: {
      //       columns: ':not(.noExport)'
      //     }
      //   },

      // ]
      buttons: [{
        extend: 'csv',
        title: 'Pedidos',
        text: 'Exportar a EXCEL',
        exportOptions: {
          columns: ':not(.noExport)',
          format: {
            body: function(data, row, column, node) {
              try {
                // Revisa si el nodo es un select directamente
                if (node && node.nodeName === "TD" && $(node).find("select").length > 0) {
                  return $(node).find("select option:selected").text().trim();
                }
              } catch (e) {
                console.error("Export format error:", e);
              }

              // Si no es select, devuelve el texto plano
              return $(node).text().trim();
            }
          }
        }
      }]


    });
  });

  function cambiarestatus(event) {
    // console.log('this aca', e);
    // let id_estatus = $(this).val();
    let id_estatus = event.target.value;
    // let id_orden_de_compra = $(this).data("id_proyecto")
    let id_orden_de_compra = event.target.dataset.id_proyecto;

    let admin = <?php echo $this->VerificarAcceso(35, Yii::app()->user->id) ? 'true' : 'false'; ?>;
    if (!admin && id_estatus != 8) {
      $.notify('Solo los administradores pueden modificar un estatus ya entregado ', "error");
      setTimeout(() => {
        location.reload();

      }, 2000);
      return false;
    }
    console.log('estatus ', id_estatus);
    console.log('id compra ', id_orden_de_compra);

    $.ajax({
      url: "<?php echo $this->createUrl("Proyectos/Actualizarestatus"); ?>",
      type: "POST",
      dataType: "json",
      timeout: (120 * 1000),
      data: {
        id_orden_de_compra,
        id_estatus
      },
      success: function(Response, newValue) {
        if (Response.requestresult == 'ok') {
          $.notify(Response.message, "success");
          setTimeout(() => {
            location.reload();
          }, 2000);
        } else {
          $.notify(Response.message, "error");
        }
      },
      error: function(e) {
        $.notify("Verifica los campos e intente de nuevo", "error");
      }
    });
  };
</script>


<div class="row">
  <div class="col-md-12">
    <h1>
      <?= $pedidos ?>
    </h1>

  </div>
  <div class="col-md-12">
    <fieldset>
      <legend>Filtros </legend>
      <form method="GET">
        <div class="col-md-3">
          <select name="proyecto_estatus" onchange="this.form.submit()" class="form-control">
            <option value="-9">
              -- Todos los estatus --
            </option>
            <option value="0" <?= ($proyecto_estatus == 0 and $proyecto_estatus != 'Todos') ? 'selected' : ''; ?>>
              Generado
            </option>
            <option value="2" <?= ($proyecto_estatus == 2) ? 'selected' : ''; ?>>En Producción</option>
            <option value="6" <?= ($proyecto_estatus == 6) ? 'selected' : ''; ?>>Empacado</option>
            <option value="8" <?= ($proyecto_estatus == 8) ? 'selected' : ''; ?>>Entregado</option>
            <option value="7" <?= ($proyecto_estatus == 7) ? 'selected' : ''; ?>>Cancelado</option>
            <!-- <option value="5" <? //= ($proyecto_estatus == 5) ? 'selected' : '';   
                                    ?>>Proyectos finalizados</option> -->
          </select>
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
        <div class="col-md-3">
          <?php
          $this->widget(
            'zii.widgets.jui.CJuiAutoComplete',
            array(
              'name' => 'nombreusuario',
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
              'value' => $nombre,
              'htmlOptions' => array(
                'class' => 'form-control',
                'placeholder' => 'Buscar Vendedor'
              )
            )
          );
          ?>
        </div>
        <div class="col-md-3 ">
          <br>
          <select name="id_bodega" class="form-control">
            <option value="0">
              -- Todas las bodegas --
            </option>
            <?php foreach ($bodegas as $row) { ?>
              <option value="<?= $row['id'] ?>" <?= ($id_bodega == $row['id']) ? 'selected' : ''; ?>><?= $row['name'] ?></option>
            <?php } ?>
          </select>
        </div>
        <input type="hidden" name="id_usuario" id="id_usuario">
        <div class="col-md-2">
          <br>
          <?php echo CHtml::submitButton('Filtro', array('class' => 'btn btn-success btn-ml')); ?>
        </div>

      </form>
    </fieldset>
  </div>
</div>
<div id="scroll-container-fixedproy"
  style="overflow-x: scroll;height:50px;position: fixed;bottom: 1%; left: 0;z-index: 100;width: 100%; padding:0 10px ;">
  <div id="scroll-elem-fixedproy" style=" height:100%; padding: 10px">
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="table-responsive tablescrolproy">
      <table id="listaproyectos" class="table table-bordered table-hover table-striped">
        <thead>
          <tr>
            <th>Folio</th>
            <th>Ventas</th>
            <th>Fecha</th>
            <th>Nombre cliente</th>

            <th>Localidad</th>
            <th>Promesa</th>
            <th>Monto Neto</th>
            <th>Anticipo</th>
            <th>Forma pago</th>
            <th>F Anticipo</th>
            <th>Finiquito</th>
            <th>Forma Pago</th>
            <th>F Finiquito</th>
            <th>Estatus</th>
            <th>Cambio de Status</th>
            <th>Status</th>
            <th class="noExport">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($listaproyectos as $rows) {
            $datosanticipo = Contabilidadingresos::model()->find(
              array(
                'condition' => 'contabilidad_ingresos_identificador =  :id',
                'params' => array(':id' => 'Pedido - ' . $rows['id_proyecto'] . ''),
                'order' => 'contabilidad_ingresos_identificador desc'
              )
            );
          ?>
            <tr>
              <td
                style="background: <?= ($rows->proyecto_estatus == 7) ? 'red' : '' ?> ;color: <?= ($rows->proyecto_estatus == 7) ? 'white' : '' ?>;">
                <?= $rows['id_proyecto'] ?>
              </td>
              <td>
                <?= $rows['rl_usuarios']['Usuario_Nombre'] ?>
              </td>
              <td>
                <?= $this->Fechacortada(date("d F Y", strtotime($rows['proyecto_fecha_alta']))); ?>
              </td>
              <td>
                <?= $rows['rl_clientes']['cliente_nombre'] ?>
              </td>
              <td>
                <?= $rows['rl_clientes']['cliente_entidad'] ?>,
                <?= $rows['rl_clientes']['cliente_municipio'] ?>
              </td>
              <td>
                <?= $this->Fechentrega($rows['id_proyecto']) ?>
              </td>
              <td>
                <?= ($ver) ? '$' . number_format($rows['proyecto_total'], 2) : 'No cuenta con permiso para ver el precio' ?>

              </td>
              <td>
                <?php if (!empty($datosanticipo)) { ?>

                  <?= ($ver) ? '$' . number_format($datosanticipo['contabilidad_ingresos_cantidad'], 2) : 'No cuenta con permiso para ver el precio' ?>
                <?php } else { ?>

                <?php } ?>
              </td>
              <td>
                <?php
                $datos = $this->Formap($rows['id_proyecto']);
                echo isset($datos['fp']) ? $datos['fp'] : '';
                ?>



              </td>
              <td>
                <?php
                echo isset($datos['fecha']) ? $datos['fecha'] : '';
                ?>
              </td>
              <td>
                <?php
                if (isset($datos['montotxtfiniquito'])) {
                  echo ($ver) ? $datos['montotxtfiniquito'] : 'No cuenta con permiso para ver el precio';
                }
                // echo isset($datos['montotxtfiniquito']) ? $datos['montotxtfiniquito'] : '';
                ?>
              </td>
              <td>
                <?php
                echo isset($datos['fptxtfiniquito']) ? $datos['fptxtfiniquito'] : '';
                ?>
              </td>
              <td>
                <?php
                echo isset($datos['fechatxtfiniquito']) ? $datos['fechatxtfiniquito'] : '';
                ?>
              </td>
              <td>
                <div class="col-md-5">Estatus: <h3 class="mb-none" style="font-weight:bold;">
                    <select name="id_estatus" onChange="cambiarestatus(event)"
                      data-id_proyecto="<?= $rows['id_proyecto'] ?>" data-status="<?= $rows->proyecto_estatus ?>">
                      <option value="0" <?= ($rows->proyecto_estatus == 0) ? 'selected' : ''; ?>>Generado</option>
                      <!-- <option value="1" <?= ($rows->proyecto_estatus == 1) ? 'selected' : ''; ?>>Por Surtir</option> -->
                      <option value="2" <?= ($rows->proyecto_estatus == 2) ? 'selected' : ''; ?>>En Producción</option>
                      <!-- <option value="4" <?= ($rows->proyecto_estatus == 4) ? 'selected' : ''; ?>>Pagado</option> -->
                      <option value="6" <?= ($rows->proyecto_estatus == 6) ? 'selected' : ''; ?>>Empacado</option>
                      <option value="8" <?= ($rows->proyecto_estatus == 8) ? 'selected' : ''; ?>>Entregado
                      <option value="7" <?= ($rows->proyecto_estatus == 7) ? 'selected' : ''; ?>>Cancelado</option>
                      </option>
                    </select>
                  </h3>
                </div>
              </td>
              <td style="min-width: 250px;">
                <?= $rows->obtenerLog($rows['id_proyecto']) ?>
              </td>
              <td class="<?= $rows->ingresosConfirmados($rows['id_proyecto']) == 0 ? 'danger' : 'success'; ?>">
                <?= $rows->ingresosConfirmados($rows['id_proyecto']) == 0 ? 'NO CONFIRMADOS' : 'CONFIRMADOS'; ?>
              </td>
              <td class="noExport">
                <?php
                echo CHtml::link('<i class="fa fa-search"></i> Ver', array('proyectos/ver/' . $rows['id_proyecto']), array('class' => "btn btn-success"));

                echo CHtml::link('<i class="fa fa-file-pdf-o"></i>
                Ver', array('proyectos/pdfpedido/' . $rows['id_proyecto']), array('class' => "btn btn-danger", "style" => "", 'target' => '_blank'));

                ?>
              </td>
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
    const tableWidth = $('#listaproyectos').width();



    // asignamos el anchotabla
    const scroll = document.querySelector("#scroll-elem-fixedproy");
    scroll.style.width = (tableWidth + 15) + 'px';
    document.querySelector("#scroll-container-fixedproy").addEventListener("scroll", function() {
      document.querySelector(".tablescrolproy").scrollLeft = this.scrollLeft;
    })
  })

  function cambiarFormaPago(event) {
    const id_forma_pago = event.target.value;
    const id_proyecto = event.target.dataset.id_proyecto;

    $.ajax({
      url: "<?= $this->createUrl('proyectos/actualizarformapago'); ?>",
      type: "POST",
      dataType: "json",
      timeout: 12000,
      data: {
        id_proyecto,
        id_forma_pago
      },
      success: function(resp) {
        if (resp.requestresult === 'ok') {
          $.notify(resp.message || 'Forma de pago actualizada', "success");
        } else {
          $.notify(resp.message || 'No fue posible actualizar', "error");
        }
      },
      error: function() {
        $.notify("Error de comunicación, intenta de nuevo", "error");
      }
    });
  }
</script>

<!-- <div class="row">
  <div class="col-md-12">
    <div class="table-responsive">
      <table id="listaproyectos" class="table table-bordered table-hover ">
        <thead>
          <tr>
            <th>Num</th> -->
<!-- <th>Nombre</th> -->
<!-- <th>Cliente</th>
            <th>Estatus</th>

            <th>Usuario</th>
            <th>Fecha Alta</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody> -->
<!-- <?php
      // foreach ($listaproyectos as $rows) { 
      ?> -->
<!-- <tr>
              <td> -->
<!-- <? //= $rows['id_proyecto'] 
      ?> -->
<!-- </td> -->
<!-- <td>
                <? //= $rows['proyecto_nombre'] 
                ?>
              </td> -->
<!-- <td>
                <//?= $rows['rl_clientes']['cliente_nombre'] ?>
              </td>
              <td>
                <//?= $this->ObtenerEstatus($rows['proyecto_estatus']) ?>
              </td>

              <td>
                <//?= $rows['rl_usuarios']['Usuario_Nombre'] ?>
              </td>
              <td>
                <//?= $rows['proyecto_fecha_alta'] ?>
              </td>
              <td>
                <? //php
                // echo CHtml::link('<i class="fa fa-search"></i> Ver', array('proyectos/ver/' . $rows['id_proyecto']), array('class' => "btn btn-success"));

                //     echo CHtml::link('<i class="fa fa-file-pdf-o"></i>
                // Ver', array('proyectos/pdfpedido/' . $rows['id_proyecto']), array('class' => "btn btn-danger", "style" => "margin-left:10px", 'target' => '_blank'));

                ?>
              </td>

            </tr>
          <? //php } 
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div> -->