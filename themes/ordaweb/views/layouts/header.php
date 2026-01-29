<header id="header" class="header-narrow header-full-width"
	data-plugin-options='{"stickyEnabled": true, "stickyEnableOnBoxed": true, "stickyEnableOnMobile": true, "stickyStartAt": 0, "stickySetTop": "0"}'>
	<div class="header-body">
		<div class="header-container container">
			<div class="header-row">

				<div class="header-column">

					<div class="header-logo">

						<a href="<?php echo Yii::app()->baseUrl; ?>/administracion/"
							title="<?php echo Yii::app()->name ?> INICIO">
							<?= $this->ObtenerLogotipo(); ?>
						</a>

					</div>

				</div>

				<div class="header-column">

					<div class="header-row">

						<div class="header-nav header-nav-stripe">

							<button class="btn header-btn-collapse-nav" data-toggle="collapse"
								data-target=".header-nav-main">

								<i class="fa fa-bars"></i>

							</button>

							<div
								class="header-nav-main header-nav-main-square header-nav-main-effect-1 header-nav-main-sub-effect-1 collapse mt-none">

								<nav>
									<?php
									// permisos
									$kpip = $this->VerificarAcceso(12, Yii::app()->user->id);
									$kpii = $this->VerificarAcceso(13, Yii::app()->user->id);
									$reporteingresos = $this->VerificarAcceso(19, Yii::app()->user->id);
									$reporteingusu = $this->VerificarAcceso(20, Yii::app()->user->id);
									// 
									$pp = $this->VerificarAcceso(27, Yii::app()->user->id);
									$prv = $this->VerificarAcceso(28, Yii::app()->user->id);


									// Obtenemos el controller y la accion
									$M_Controller = Yii::app()->controller->id;
									$M_Method = Yii::app()->controller->action->id;
									?>
									<?php $this->widget(
										'zii.widgets.CMenu',
										array(

											'htmlOptions' => array('class' => 'nav nav-pills', 'id' => 'mainNav'),

											'activeCssClass' => 'active',
											'submenuHtmlOptions' => array(
												'class' => 'dropdown-menu',
											),

											'items' => array(

												array(
													'label' => 'Ventas',
													'url' => array('administracion/index'),
													'active' => $M_Controller == 'administracion' && $M_Method != 'miperfil',
													'linkOptions' => array(
														'class' => 'dropdown-toggle',
													),
													'itemOptions' => array('class' => 'dropdown'),
													'items' => array(
														array('label' => 'Tareas', 'url' => array('administracion/listaactividades')),
														array('label' => 'Prospectos', 'url' => array('administracion/lista')),
														array('label' => 'Calendario', 'url' => array('administracion/calendario')),
														array('label' => 'Mensajes', 'url' => array('administracion/enviarmensaje'))
													)
												),
												array(
													'label' => 'Cotizaciones',
													'url' => array('cotizaciones/lista'),
													'active' => $M_Controller == 'cotizaciones',
													'linkOptions' => array(
														'class' => 'dropdown-toggle',
													),
													'itemOptions' => array('class' => 'dropdown'),
													'items' => array(
														array('label' => 'Crear Cotización', 'url' => array('administracion/crearcotizaciones'))
													)
												),

												array(
													'label' => 'Clientes',
													'url' => array('clientes/admin'),
													'active' => $M_Controller == 'clientes',

												),


												array('label' => 'Pedidos', 'url' => array('proyectos/lista'), 'active' => $M_Controller == 'proyectos'),
												array(
													'label' => 'Ingresos',
													'url' => array('contabilidadingresos/index'),
													'active' => $M_Controller == 'contabilidadingresos',
													'linkOptions' => array(
														'class' => 'dropdown-toggle',
													),
													'itemOptions' => array('class' => 'dropdown'),
													'items' => array(
														array('label' => 'Pendientes de Cobro', 'url' => array('/contabilidadingresos/pendientespago/')),
														array('label' => 'KPI ingresos', 'url' => array('/contabilidadingresos/kpiingresos'), 'visible' => $kpii),
														array('label' => 'Reporte Ingresos costos', 'url' => array('/contabilidadingresos/ReporteIngresosCostos'), 'visible' => $reporteingresos),
														array('label' => 'Reporte ventas de usuarios', 'url' => array('/contabilidadingresos/ReporteVentasUsuarios'), 'visible' => $reporteingusu),

													)
												),
												array(
													'label' => 'Informes',
													'url' => array('informes/lista'),
													'active' => $M_Controller == 'informes',
													'linkOptions' => array(
														'class' => 'dropdown-toggle',
													),
													'itemOptions' => array('class' => 'dropdown'),
													'items' => array(
														array('label' => 'Oportunidades', 'url' => array('informes/Oportunidades')),
														array('label' => 'Ventas', 'url' => array('informes/ventas')),
														array('label' => 'Ingresos', 'url' => array('informes/ingresos')),
														array('label' => 'Cuentas por cobrar', 'url' => array('informes/cuentasporcobrar')),
													)
												),


												array(
													'label' => 'Productos',
													'url' => array('productos/admin'),
													'active' => $M_Method == 'productos',
													'linkOptions' => array(
														'class' => 'dropdown-toggle',
													),
													'itemOptions' => array('class' => 'dropdown'),
													'items' => array(
														array('label' => 'Pizarra de fabricación', 'url' => array('productos/Reportefabricacion')),
														array('label' => 'Productos precios', 'url' => array('productosprecios/admin'), 'visible' => $pp),
														array('label' => 'KPI productos', 'url' => array('productos/kpi'), 'visible' => $kpip),
														array('label' => 'Reporte de ventas productos', 'url' => array('productos/reporteventas'), 'visible' => $prv),
													)
												),
												array(
													'label' => 'Proveedores',
													'url' => array('proveedores/admin'),
													'active' => $M_Method == 'proveedores',
													// 'linkOptions' => array(
													// 	'class' => 'dropdown-toggle',
													// ),
													// 'itemOptions' => array('class' => 'dropdown'),
													// 'items' => array(
													// 	array('label' => 'Pizarra de fabricación', 'url' => array('productos/Pizarraproductos')),
													// 	array('label' => 'Productos precios', 'url' => array('productosprecios/admin')),
													// 	array('label' => 'KPI productos', 'url' => array('productos/kpi'), 'visible' => $kpip),
													// 	array('label' => 'Reporte de ventas productos', 'url' => array('productos/reporteventas')),
													// )
												),
												array(
													'label' => 'Inventario',
													'url' => array('inventario/index'),
													'active' => $M_Method == 'index',
													'linkOptions' => array(
														'class' => 'dropdown-toggle',

													),
													'itemOptions' => array('class' => 'dropdown'),
													'items' => array(
														array('label' => 'Sucursales', 'url' => array('sucursales/index')),
														array('label' => 'Inventario', 'url' => array('sucursales/controlInventario')),
														#array('label' => 'Precios de Venta', 'url' => array('inventario/preciosdeventa')),
														array('label' => 'Transferencias', 'url' => array('transferencias/index')),
														array('label' => 'Movimiento de Ajuste', 'url' => array('inventario/movimientoajuste')),
														array('label' => 'Movimientos', 'url' => array('inventario/movimientos')),
														array('label' => 'Ordenes de compra', 'url' => array('ordenescompra/index')),
													)
												),

												array(
													'label' => 'Modulos',
													'url' => array('administracion/modulos'),
													'active' => $M_Method == 'modulos',
													'linkOptions' => array(
														'class' => 'dropdown-toggle',

													),
													'itemOptions' => array('class' => 'dropdown'),
													'items' => array(
														array('label' => 'Configuración', 'url' => array('administracion/configuracion')),
														array('label' => 'Usuarios y Perfiles', 'url' => array('administracion/usuariosperfiles')),
														// array('label' => 'Catalogos', 'url' => array('administracion/catalogos')),
														// array('label' => 'Catalogos Generales', 'url' => array('administracion/catalogosgenerales')),
													)
												),


												array(
													'label' => Yii::app()->user->name,
													'url' => '#',
													'active' => $M_Method == 'modulos',
													'linkOptions' => array(
														'class' => 'dropdown-toggle',
													),
													'itemOptions' => array('class' => 'dropdown'),
													'items' => array(
														// array('label' => 'Instancia: ' . Yii::app()->session['basededatos'], 'url' => '#'),
														array('label' => 'Mi Perfil', 'url' => array('administracion/miperfil')),
														array('label' => 'Salir', 'url' => array('/site/logout/' . Yii::app()->session['basededatos'])),
													)
												)
											),

										)
									); ?>

								</nav>

							</div>

						</div>

					</div>

				</div>

			</div>

		</div>

	</div>

</header>