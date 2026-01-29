<?php
$this->pageTitle='Ubicación de '.$registro['nombre'];
$this->pageDescription = '';
$this->breadcrumbs=array(
    'Lista sucursales'=>yii::app()->createUrl('sucursales/index'),
    $registro['nombre']=>yii::app()->createUrl('sucursales/detalle/'.$registro['id']),
    $this->pageTitle
    );

$op_menu_sucursal = $registro->id; // es el id de la sucursal a editar o a agregar cosas
$op_menu = 3;
$this->renderpartial('//inventario/menu',array('op_menu'=>13));
include 'menu.php';
?>
<div class="panel">
	<div id="current" class="col-md-12" style="text-align: center;">
        <h2 class="mt-0" > <span style="font-weight:500 ">Latitud  = </span><span id="latitud"><?=$registro['latitud']?></span> <span style="font-weight:500 "> Longitud  = </span><span id="longitud"><?=$registro['longitud']?></span> <br></h2> 
        <div class="embed-responsive embed-responsive-16by9" style="height: 400px; width: 100%">
            <div id="map" ></div>
        </div>
        <script>
          function initMap() {
            var uluru = {lat: <?=($registro['latitud']!='')?$registro['latitud']:'25.6667';?>, lng: <?=($registro['longitud']!='')?$registro['longitud']:'-100.3167';?>};
            var map = new google.maps.Map(document.getElementById('map'), {
              zoom: 14,
              center: uluru
            });
            var marker = new google.maps.Marker({
              position: uluru,
              map: map,
              draggable: true
            });
            google.maps.event.addListener(marker, 'dragend', function (evt) {
                document.getElementById('latitud').innerHTML = evt.latLng.lat();
                document.getElementById('longitud').innerHTML = evt.latLng.lng();
                // hacer una peticion ajax para actualizar los campos de latitud y longitud del cliente
                Actualizarcampo(evt.latLng.lat(),evt.latLng.lng(),<?=$registro['id']?>);
                
            });
            google.maps.event.addListener(marker, 'dragstart', function (evt) {
                //document.getElementById('current').innerHTML = '<p>Currently dragging marker...</p>';
            });
            map.setCenter(marker.position);
            marker.setMap(map);
          }
        </script>
        <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCz5itTCz2hYbTj1MzYoj8BG9E9qI7r1Do&callback=initMap">
        </script>
	</div>
</div>
<script type="text/javascript">
    function Actualizarcampo(latitud,longitud,id)
    {
        // enviamos la petición ajaxx
        var jqxhr = $.ajax({
            url: "<?php echo $this->createUrl("sucursales/actualizarcampoajax"); ?>",
            type: "POST",
            dataType : "json",
            timeout : (120 * 1000),
            data: {
                campo:'latitud',
                valor:latitud,
                id:id,
            },
            success : function(Response) {
                if (Response.requestresult == 'ok') {
                    toastr.success(Response.message, {timeOut: 500})
                }else{
                   toastr.warning(Response.message, {timeOut: 500})
                }
            },
            error: function(e){
                    toastr.warning('Ocurrio un error inesperado', {timeOut: 500})
                }
        });
        // enviamos la petición ajaxx
        var jqxhr = $.ajax({
            url: "<?php echo $this->createUrl("sucursales/actualizarcampoajax"); ?>",
            type: "POST",
            dataType : "json",
            timeout : (120 * 1000),
            data: {
                campo:'longitud',
                valor:longitud,
                id:id,
            },
            success : function(Response) {
                if (Response.requestresult == 'ok') {
                    toastr.success(Response.message, {timeOut: 500})
                }else{
                   toastr.warning(Response.message, {timeOut: 500})
                }
            },
            error: function(e){
                    toastr.warning('Ocurrio un error inesperado', {timeOut: 500})
                }
        });
    }
</script>
