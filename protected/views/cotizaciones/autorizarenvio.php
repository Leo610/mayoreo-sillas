<?php
/* @var $this CotizacionesController */
$this->pageTitle='Autorizar';

$this->breadcrumbs=array(
	'Cotizaciones'=>Yii::app()->createUrl("/cotizaciones/lista?cotizacion_estatus=1"),
	'Autorizar Cotizacion',
);
?>
<div class="row">
  <div class="col-md-8">
   <h1>Autorizar Cotizacion</h1>
  </div>
  </div>

<div class="row">
	<div class="col-md-12">
		<fieldset>
			<legend>Datos Cliente</legend>
			<div class="col-md-3">
				Nombre:<br><strong><?=$DatosCliente->cliente_nombre; ?></strong><br>
				Cliente Tipo:<br><strong><?=$DatosCliente['rl_cliente_tipo']['nombre']; ?></strong><br>
				Clasificacion:<br><strong><?=$DatosCliente['rl_cliente_tipo_clasificacion']['nombre']; ?></strong><br>
				Como Trabajarlo:<br><strong><?=$DatosCliente['rl_cliente_como_trabajarlo']['nombre']; ?></strong><br>
			</div>
			<div class="col-md-3">

				Teléfono:<br><strong><?=$DatosCliente->cliente_telefono; ?></strong><br>
				Email:<br><strong><?=$DatosCliente->cliente_email; ?></strong><br>
				Calle:<br><strong><?=$DatosCliente->cliente_calle; ?></strong><br>
				Empresa:<br><strong><?=$DatosCliente->rl_empresas->empresa; ?></strong><br>
			</div>
			<div class="col-md-3">
				Colonia:<br><strong><?=$DatosCliente->cliente_colonia; ?></strong><br>
				Número Interior:<br><strong><?=$DatosCliente->cliente_numeroexterior; ?> <?=$DatosCliente->cliente_numerointerior; ?></strong><br>
				Código Postal:<br><strong><?=$DatosCliente->cliente_codigopostal; ?></strong><br>
				Lista Precio:<br><strong><?=$DatosCliente->rl_listaprecios->listaprecio_nombre; ?></strong><br>
			</div>
			<div class="col-md-3">
				Municipio:<br><strong><?=$DatosCliente->cliente_municipio; ?></strong><br>
				Entidad:<br><strong><?=$DatosCliente->cliente_entidad; ?></strong><br>
				País:<br><strong><?=$DatosCliente->cliente_pais; ?></strong><br>
				Tipo de Precio:<br><strong><?php
				$Tipoprecio = $this->ObtenerTipoPrecio($DatosCotizacion->tipo_precio);
				echo $Tipoprecio['label'];
				 ?></strong><br>
				
			</div>
		</fieldset>
	</div>
</div>
<a href="<?php echo $this->createUrl("cotizaciones/autorizar/".$DatosCotizacion->id_cotizacion); ?>" class="btn btn-success btn-lg mr-md" >
											Autorizar Envio
</a>
<a href="<?php echo $this->createUrl("cotizaciones/pdf/".$DatosCotizacion->id_cotizacion); ?>" class="btn btn-danger btn-lg mr-md" target="_blank">
											Ver pdf
</a>
