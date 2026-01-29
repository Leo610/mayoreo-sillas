<?php
/* @var $this ProductosController */
/* @var $model Productos */


$this->pageTitle = 'Administración de Productos';
$this->breadcrumbs = array(
  'Modulos' => Yii::app()->createUrl('administracion/modulos'),
  'Productos',
);

?>
<script type="text/javascript">
  $(document).ready(function() {
    // funcion para mostrar productos por categoria
    $('.categoriaprodcuto').change(function() {
      var valordelselect = $(this).val();
      var url = '<?php echo $this->createUrl("Productos/admin"); ?>';
      if (valordelselect === 0) {
        window.location.href = url;
      }
      console.log(valordelselect);
      window.location.href = url + '?id=' + valordelselect;
    });



    // Funcion para mostrar el modal
    $("#abrirmodal").click(function() {
      $("#formmodal").modal('show');
      $('#Productos-form')[0].reset();
    });

    // Funcion para ordenar la lista de resultados
    $('#lista').DataTable({
      pageLength: 100,
      dom: 'lBfrtip',
      stateSave: true,
      buttons: [{
        extend: 'csv',
        title: 'Exportar',
        text: 'Exportar a EXCEL',
        exportOptions: {
          columns: ':not(.noExport)'
        }
      }, ]
    });

  });

  function Actualizar(id) {
    var jqxhr = $.ajax({
      url: "<?php echo $this->createUrl("Productos/datos"); ?>",
      type: "POST",
      dataType: "json",
      timeout: (120 * 1000),
      data: {
        id: id,
      },
      success: function(Response, newValue) {
        if (Response.requestresult == 'ok') {
          $('#Productos-form')[0].reset();
          $('#camposcalcular').hide();
          // Si el resultado es correcto, agregamos los datos al form del modal
          $("#Productos_producto_nombre").val(Response.Datos.producto_nombre);
          $("#Productos_producto_clave").val(Response.Datos.producto_clave);
          $("#Productos_producto_costo_compra").val(Response.Datos.producto_costo_compra);
          $("#Productos_producto_precio_venta_default").val(Response.Datos.producto_precio_venta_default);
          $("#Productos_producto_descripcion").val(Response.Datos.producto_descripcion);
          $("#Productos_id_lista_precio").val(Response.Datos.id_lista_precio);
          $("#Productos_id_producto").val(Response.Datos.id_producto);
          $("#Productos_id_categoria").val(Response.Datos.id_categoria).trigger('change');
          $("#Productos_producto_costo_compra").val(Response.Datos.producto_costo_compra);
          $("#Productos_producto_precio_venta_default").val(Response.Datos.producto_precio_venta_default);
          //
          $("#Productos_id_subcategoria").val(Response.Datos.id_subcategoria);
          $("#Productos_id_grupo").val(Response.Datos.id_grupo);
          $("#Productos_id_subgrupo").val(Response.Datos.id_subgrupo);
          $("#Productos_id_linea").val(Response.Datos.id_linea);
          $("#Productos_id_sublinea").val(Response.Datos.id_sublinea);
          $("#Productos_id_segmento").val(Response.Datos.id_segmento);
          $("#Productos_id_subsegmento").val(Response.Datos.id_subsegmento);
          $("#Productos_id_marca").val(Response.Datos.id_marca);
          $("#Productos_id_submarca").val(Response.Datos.id_submarca);
          $("#Productos_id_modelo").val(Response.Datos.id_modelo);
          $("#Productos_id_submodelo").val(Response.Datos.id_submodelo);
          $("#Productos_id_posicion").val(Response.Datos.id_posicion);
          $("#Productos_id_subposicion").val(Response.Datos.id_subposicion);
          $("#Productos_id_proveedor").val(Response.Datos.id_proveedor);
          $("#Productos_id_unidades_medida").val(Response.Datos.id_unidades_medida);
          $("#Productos_id_bodega_fabricacion").val(Response.Datos.id_bodega_fabricacion);
          $("#Productos_tipo").val(Response.Datos.tipo);
          $("#Productos_utilidad").val(Response.Datos.utilidad);


          //
          //mostramos la imagen
          if (Response.Datos.producto_imagen != '') {
            $('#imagendiv').show();
          }
          $("#imagenpro").attr("src", "../archivos/" + Response.Datos.producto_imagen);
          $("#imagenoriginal").val(Response.Datos.producto_imagen);

          $("#formmodal").modal('show');
        } else {

        }
      },
      error: function(e) {
        $.notify('Ocurrio un error inesperado', 'error');
      }
    });
  }
</script>
<?php include 'modal/_form.php'; ?>

<div class="row">
  <div class="col-md-12">
    <h1>Administración de Productos | <a href="#" class="btn btn-success" id="abrirmodal">
        Agregar Productos
      </a></h1>
  </div>

  <div class="col-md-4" style="margin-bottom: 10px;">
    <label for="">Filtrar por categoria</label>
    <select class="form-control categoriaprodcuto" name="" id="">
      <option value="0">Seleccione Categoria</option>
      <?php foreach ($Categoria as $cat) { ?>
        <option value="<?= $cat['id_catalogo_recurrente'] ?>" <?= (($cat['id_catalogo_recurrente'] == $valorPreseleccionado)) ? 'selected' : '' ?>>

          <?= $cat['nombre'] ?>
        </option>
      <?php } ?>
    </select>
  </div>

  <div class="col-md-12">
    <div class="table-responsive">
      <table id="lista" class="table  table-bordered  table-hover ">
        <thead>
          <tr>
            <th>ID</th>
            <th>Imagen</th>
            <th>Nombre</th>
            <th>Clave</th>
            <th class="noExport">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($lista as $rows) { ?>
            <tr>
              <td>
                <?= $rows['id_producto'] ?>
              </td>
              <td>
                <?php if ($rows['producto_imagen'] != '') { ?>
                  <img src="<?= Yii::app()->createUrl('archivos/' . $rows->producto_imagen) ?>" style="max-height:50px">
                <?php } ?>
              </td>
              <td>
                <?= $rows['producto_nombre'] ?>
              </td>
              <td>
                <?= $rows['producto_clave'] ?>
              </td>
              <td class="noExport">
                <?php



                echo CHtml::link(
                  '<i class="fa fa-pencil fa-lg"></i> Editar',
                  "javascript:;",
                  array(
                    'style' => 'cursor: pointer;',
                    "onclick" => "Actualizar(" . $rows['id_producto'] . "); return false;"
                  )
                );


                echo CHtml::link(
                  '<br><i class="fa fa-trash fa-lg"></i> Eliminar',
                  array('Productos/delete', 'id' => $rows['id_producto']),
                  array(
                    'submit' => array('Productos/delete', 'id' => $rows['id_producto']),
                    'class' => 'delete',
                    'confirm' => 'Seguro que lo deseas eliminar?'
                  )
                );
                ?>
              </td>

            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</div>