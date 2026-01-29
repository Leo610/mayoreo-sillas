<?php

class AdministracionController extends Controller
{
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl',
			// perform access control for CRUD operations
			'postOnly + delete',
			// we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array(
				'allow',
				// allow authenticated users to access all actions
				'users' => array('@'),
			),
			array(
				'deny',
				// deny all users
				'users' => array('*'),
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionCalendario()
	{
		$VerificarAcceso = $this->VerificarAcceso(15, Yii::app()->user->id);
		if (!$VerificarAcceso) {
			// Lo redireccionamos a la pagina acceso restringido
			$this->redirect(Yii::app()->createURL('site/noautorizado'));
		}


		// Renderizamos la vista
		$this->render('calendario');
	}
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */

	public function actionLista()
	{
		$VerificarAcceso = $this->VerificarAcceso(15, Yii::app()->user->id);
		if (!$VerificarAcceso) {
			// Lo redireccionamos a la pagina acceso restringido
			$this->redirect(Yii::app()->createURL('site/noautorizado'));
		}
		// Obtenemos el model de clientes
		$modelClientes = new Clientes;
		// Obtenemos el model de crmacciones
		$modelCrmdetalles = new Crmdetalles;

		// Obtenemos todos los usuarios relacionados
		$usuarioshijos = $this->ObtenerHijosUsuario(Yii::app()->user->id, '');
		// TERMINA 
		// Obtenemos la lista de prospectos
		$listaprospectos = Clientes::model()->findAll('id_usuario in (' . $usuarioshijos . ')');

		//lista para obtener las acciones
		$arraylistacrmacciones = CHtml::listData(Crmacciones::model()->findAll(), 'id_crm_acciones', 'crm_acciones_nombre');

		//lista para obtener los usuarios
		$arraylistausuarios = CHtml::listData(Usuarios::model()->findAll(), 'id_usuario', 'usuario_nombre');

		// Obtenemos las listas de precios, para asignarlas al cliente.
		$arraylistaprecios = CHtml::listData(ListaPrecios::model()->findAll(), 'id_lista_precio', 'listaprecio_nombre');

		$cliente_tipo_clasificacion = CHtml::listData(CatalogosRecurrentes::model()->findAll('id_grupo_recurrente=9'), 'id_catalogo_recurrente', 'nombre');
		$cliente_como_trabajarlo = CHtml::listData(CatalogosRecurrentes::model()->findAll('id_grupo_recurrente=10'), 'id_catalogo_recurrente', 'nombre');
		$cliente_tipo = CHtml::listData(CatalogosRecurrentes::model()->findAll('id_grupo_recurrente=3'), 'id_catalogo_recurrente', 'nombre');

		$arraytipoprecioc = [
			1 => 'Precio lista',
			2 => '50 - 99',
			3 => '100 - 199',
			4 => '200 o más',
			// 5 => '300 o mas',
			6 => 'Distribuidores',
		];



		$this->render(
			'lista',
			array(
				'listaprospectos' => $listaprospectos,
				'modelClientes' => $modelClientes,
				'modelCrmdetalles' => $modelCrmdetalles,
				'arraylistacrmacciones' => $arraylistacrmacciones,
				'arraylistausuarios' => $arraylistausuarios,
				'arraylistaprecios' => $arraylistaprecios,
				'cliente_tipo_clasificacion' => $cliente_tipo_clasificacion,
				'cliente_como_trabajarlo' => $cliente_como_trabajarlo,
				'cliente_tipo' => $cliente_tipo,
				'precio' => $arraytipoprecioc
			)
		);
	}



	public function actionCrearprospecto()
	{

		//Obtenemos los campos de clientes
		$agregarprospecto = new Clientes;
		$agregarprospecto->attributes = $_POST['Clientes'];
		// VERIFICAMOS EL CAMPO DE EMPRESA, SI ES MAYOR A >0 QUIERE DECIR QUE SI SELECCIONARON, EN CASO CONRTARIO SI ES DIFERENTE DE NULO QUIERE DECIR QUE DEBEMOS INSERTAR EL NUEVO REGISTRO
		if ($agregarprospecto->id_empresa == '' && !$agregarprospecto->id_empresa > 0) {
			$valorautocomplete = $_POST['autocomplete'];
			// Quiere decir que escribieron algo y no es una empresa existente, la agregamos
			$id_empresa = $this->GenerarRegistroAutoComplete('Empresas', 'empresa', $valorautocomplete, 'id');
			$agregarprospecto->id_empresa = $id_empresa;
		}
		// TERMINA
		// VERIFICAMOS EL AUTOCOMPLETE DE cliente_tipo_clasificacion
		if ($agregarprospecto->cliente_tipo_clasificacion == '' && !$agregarprospecto->cliente_tipo_clasificacion > 0) {
			if (isset($_POST['autocomplete_tipoclasificacion']) && $_POST['autocomplete_tipoclasificacion'] != '') {
				$valorautocomplete = $_POST['autocomplete_tipoclasificacion'];
				// Quiere decir que escribieron algo y no es una empresa existente, la agregamos
				$cliente_tipo_clasificacion = $this->GenerarRegistroAutoComplete('CatalogosRecurrentes', 'nombre', $valorautocomplete, 'id_catalogo_recurrente', 9);
				$agregarprospecto->cliente_tipo_clasificacion = $cliente_tipo_clasificacion;
			}
		}
		// TERMINA
		// VERIFICAMOS EL AUTOCOMPLETE DE cliente_como_trabajarlo
		if ($agregarprospecto->cliente_como_trabajarlo == '' && !$agregarprospecto->cliente_como_trabajarlo > 0) {
			if (isset($_POST['autocomplete_comotrabajarlo']) && $_POST['autocomplete_comotrabajarlo'] != '') {
				$valorautocomplete = $_POST['autocomplete_comotrabajarlo'];
				// Quiere decir que escribieron algo y no es una empresa existente, la agregamos
				$cliente_como_trabajarlo = $this->GenerarRegistroAutoComplete('CatalogosRecurrentes', 'nombre', $valorautocomplete, 'id_catalogo_recurrente', 10);
				$agregarprospecto->cliente_como_trabajarlo = $cliente_como_trabajarlo;
			}
		}
		// TERMINA
		// VERIFICAMOS EL AUTOCOMPLETE DE cliente_tipo
		if ($agregarprospecto->cliente_tipo == '' && !$agregarprospecto->cliente_tipo > 0) {
			if (isset($_POST['autocomplete_clientetipo']) && $_POST['autocomplete_clientetipo'] != '') {
				$valorautocomplete = $_POST['autocomplete_clientetipo'];
				// Quiere decir que escribieron algo y no es una empresa existente, la agregamos
				$cliente_tipo = $this->GenerarRegistroAutoComplete('CatalogosRecurrentes', 'nombre', $valorautocomplete, 'id_catalogo_recurrente', 3);
				$agregarprospecto->cliente_tipo = $cliente_tipo;
			}
		}
		// TERMINA
		$agregarprospecto->id_usuario = Yii::app()->user->id;
		$agregarprospecto->cliente_codigopostal = $_POST['cliente_codigopostal'];
		$agregarprospecto->save();
		$id_prospecto = $agregarprospecto->id_cliente;
		//Agregamos el prospecto
		if ($agregarprospecto->save()) {
			// Agregamos la oportunidad
			$addoportunidad = new CrmOportunidades;
			$addoportunidad->id_cliente = $id_prospecto;
			$addoportunidad->estatus = 'SEGUIMIENTO';
			$addoportunidad->fecha_alta = date('Y-m-d H:i:s');
			$addoportunidad->id_usuario = Yii::app()->user->id;
			$addoportunidad->fecha_ultima_modificacion = date('Y-m-d H:i:s');

			if ($addoportunidad->save()) {
				$botton = CHtml::link('VER OPORTUNIDAD', array('administracion/crmver/' . $addoportunidad->id), array('class' => 'btn btn-success'));
			} else {
				$botton = ''; /*
																																													 print_r($addoportunidad->getErrors());
																																													 exit;*/
			}


			Yii::app()->user->setFlash('success', "Se agrego con exito. " . $botton);
		} else {
			Yii::app()->user->setFlash('warning', "Ocurrio un error inesperado");
		}
		// Redireccionamos de la pagina de donde viene
		$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
		$this->redirect($urlfrom);
	}
	public function actionCrmver($id)
	{

		$ID_Usuario = Yii::app()->user->id;
		// Obtenemos la informacion  de la oportunindad en base a la primary key
		$DatosOportunidad = CrmOportunidades::model()->findBypk($id);

		$DatosFrecuencia = ClientesFrecuencias::model()->find('id_cliente=' . $DatosOportunidad->id_cliente);

		$model = Clientes::model()->findByPk($DatosOportunidad->id_cliente);

		$agregarproducto = new ClientesProductos;
		$agregarfrecuencia = new ClientesFrecuencias;
		$modelProducto = new Productos;

		// Obtenemos la lista de acciones que tiene la oportunindad
		$listadetallescliente = Crmdetalles::model()->findall('id_oportunidad=' . $id);


		$listaclientesproductos = ClientesProductos::model()->findAll('id_cliente=' . $DatosOportunidad->id_cliente);

		$listaclientefrecuencias = ClientesFrecuencias::model()->findAll('id_cliente=' . $DatosOportunidad->id_cliente);

		$Frecuencia = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente=12');
		$ListaFrecuencia = CHtml::listData($Frecuencia, 'id_catalogo_recurrente', 'nombre');


		$arraylistaproducto = CHtml::listData(Productos::model()->findAll(), 'id_producto', 'producto_nombre');

		// Obtenemos el model de crmacciones
		$modelCrmdetalles = new Crmdetalles;


		// Obtenemos los tipos de estapas
		$Cancelacion = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente=11');
		$ListaCancelacion = CHtml::listData($Cancelacion, 'id_catalogo_recurrente', 'nombre');

		//lista para obtener las acciones
		$arraylistacrmacciones = CHtml::listData(Crmacciones::model()->findAll(), 'id_crm_acciones', 'crm_acciones_nombre');

		// Variables para el form de editar datos del cliente.');
		$ClienteEditar = new Clientes();
		// Obtenemos las listas de precios, para asignarlas al cliente.
		$arraylistaprecios = CHtml::listData(ListaPrecios::model()->findAll(), 'id_lista_precio', 'listaprecio_nombre');

		// Obtenemos la lista de cotizaciones del cliente
		$listacotizaciones = Cotizaciones::model()->findAll('id_oportunidad=' . $id);

		$listaetapa = CHtml::listData(Crmetapas::model()->findAll(array('condition' => '', 'order' => 'orden asc')), 'id', 'nombre');

		$Clasificacion = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente=9');
		$ListaClasificacion = CHtml::listData($Clasificacion, 'id_catalogo_recurrente', 'nombre');

		$Trabajarlo = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente=10');
		$ListaComoTrabajarlo = CHtml::listData($Trabajarlo, 'id_catalogo_recurrente', 'nombre');

		$ListaEmpresa = CHtml::listData(Empresas::model()->findAll(), 'id', 'empresa');

		// Obtenemos los tipos de estapas
		$Tipo = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente=3');
		$listatipo = CHtml::listData($Tipo, 'id_catalogo_recurrente', 'nombre');

		// Modelo para agregar otro agente , obtenemos todos los agentes involucrados y la lista de agentes.
		$agregarCOI = new CrmOportunidadesInvolucrados;
		$ListaCOI = CrmOportunidadesInvolucrados::model()->findAll('id_oportunidad=' . $id . ' and eliminado = 0');
		// Obtenemos los usuarios ya involucrados
		$Agentesyainvolucrados = $this->ObtenerHijosOportunidad($id) . '0';
		$ListaAgentes = CHtml::listData(Usuarios::model()->findAll('ID_Usuario!=' . $ID_Usuario . ' and ID_Usuario not in (' . $Agentesyainvolucrados . ')'), 'ID_Usuario', 'Usuario_Nombre');

		$arraytipoprecioc = [
			1 => 'Precio lista',
			2 => '50 - 99',
			3 => '100 - 199',
			4 => '200 o más',
			// 5 => '300 o mas',
			6 => 'Distribuidores',
		];

		$this->render(
			'crmver',
			array(
				'datoscliente' => $model,
				'listadetallescliente' => $listadetallescliente,
				'modelCrmdetalles' => $modelCrmdetalles,
				'arraylistacrmacciones' => $arraylistacrmacciones,
				'model' => $ClienteEditar,
				'arraylistaprecios' => $arraylistaprecios,
				'listacotizaciones' => $listacotizaciones,
				'DatosOportunidad' => $DatosOportunidad,
				'DatosFrecuencia' => $DatosFrecuencia,
				'listaclientesproductos' => $listaclientesproductos,
				'listaclientefrecuencias' => $listaclientefrecuencias,
				'ListaFrecuencia' => $ListaFrecuencia,
				'agregarproducto' => $agregarproducto,
				'agregarfrecuencia' => $agregarfrecuencia,
				'arraylistaproducto' => $arraylistaproducto,
				'modelProducto' => $modelProducto,
				'listaetapa' => $listaetapa,
				'ListaClasificacion' => $ListaClasificacion,
				'ListaComoTrabajarlo' => $ListaComoTrabajarlo,
				'ListaEmpresa' => $ListaEmpresa,
				'ListaCancelacion' => $ListaCancelacion,
				'listatipo' => $listatipo,
				'agregarCOI' => $agregarCOI,
				'ListaCOI' => $ListaCOI,
				'ListaAgentes' => $ListaAgentes,
				'precio' => $arraytipoprecioc
			)
		);
	}



	public function actionCrearcotizaciones()
	{
		$VerificarAcceso = $this->VerificarAcceso(16, Yii::app()->user->id);
		if (!$VerificarAcceso) {
			// Lo redireccionamos a la pagina acceso restringido
			$this->redirect(Yii::app()->createURL('site/noautorizado'));
		}

		$id = (isset($_GET['id'])) ? $_GET['id'] : 0;
		$id_oportunidad = (isset($_GET['id_oportunidad'])) ? $_GET['id_oportunidad'] : 0;

		// Obtenemos los datos del cliente, seleccionado
		$DatosCliente = Clientes::model()->findBypk($id);

		$DatosOportunidad = CrmOportunidades::model()->findBypk($id_oportunidad);

		// Mandamos el modelo de cotizaciones
		$model = new Cotizaciones;

		// Modelo cotizacionesproductos
		$model_cot_arch = new Cotizacionesarchivos;
		// if(isset($_POST) && !empty($_POST)){
		// print_r($_POST}
		// Si hacen clic en crear cotizacion
		if (isset($_POST['Cotizaciones'])) {

			// vamos a revisar que este cliente no tenga cotizaciones pendeintes de cerrar
			$id_cliente = isset($_POST['Cotizaciones']['id_cliente']) ? $_POST['Cotizaciones']['id_cliente'] : '';
			// si el pedido es 0 y la cotizacion estatus es = 2 quiere decir que la cotizacion fue cancelada entonces que no rediriga
			$cotizacionesPendietes = Cotizaciones::model()->find(
				array(
					'condition' => 'id_cliente = :id and pedido=0 and cotizacion_estatus != 2',
					'params' => array(':id' => $id_cliente)
				)
			);

			if (!empty($cotizacionesPendietes)) {
				// si entro al if quiere decir que tiene cotizaciones cuyo pedido aun nos e a generado
				Yii::app()->user->setFlash('warning', "El cliente cuenta con una cotizacion pendiente por terminar " . CHtml::link('Visitar', ['cotizaciones/Actualizarcotizacion/' . $cotizacionesPendietes['id_cotizacion']]));
				$this->redirect(Yii::app()->createUrl('administracion/crearcotizaciones/crear'));
			}

			$model->attributes = $_POST['Cotizaciones'];
			$model->cotizacion_fecha_alta = date('Y-m-d H:i:s');
			$model->id_usuario = Yii::app()->user->id;
			$model->cotizacion_estatus = 0;
			$model->cotizacion_ultima_modificacion = date('Y-m-d H:i:s');
			$model->sumar_iva = 0;


			if ($model->save()) {

				// Sumamos + 1 en la tabla de tipo proyectos el tipo seleccionado, columna serie_cotizacion
				// $tipoproyecto = TipoProyectos::model()->findBypk($model->id_tipo_proyecto);

				// $tipoproyecto['serie_cotizacion'] = $tipoproyecto['serie_cotizacion'] + 1;
				// $tipoproyecto->save();
				// En caso de que se guarde, verificamos si tiene archivos
				//print_r($_FILES["Cotizacionesarchivos"]);
				// hay error al parecer no existe file[cotizaciones] yo digo que si files esta vacio no entre aqui

				if (!empty($_FILES)) {

					for ($i = 0; $i < count($_FILES["Cotizacionesarchivos"]['name']['cotizacion_archivo']); $i++) {
						$uploadedFile = CUploadedFile::getInstance($model_cot_arch, "cotizacion_archivo[$i]");
						$rnd = rand(0, 9999);
						if ($uploadedFile != '') {
							$agregararchivo = new Cotizacionesarchivos;
							$fileName = "{$rnd}-{$uploadedFile}"; // random number + file name
							$agregararchivo->cotizacion_archivo = $fileName;
							$agregararchivo->cotizacion_archivo_nombre = $i;
							$agregararchivo->id_cotizacion = $model->id_cotizacion;
							$uploadedFile->saveAs(Yii::app()->basePath . '/../companias/' . Yii::app()->session['basededatos'] . '/archivos/' . $fileName);
							$agregararchivo->save();

						}
					}

				}
				// asigamos el numero de cotizacion
				$numero_cotizacion = date('Y') . '-' . $model->id_tipo_proyecto . '-' . $model->id_cotizacion;
				$model->numero_cotizacion = $numero_cotizacion;
				$model->save();

				Yii::app()->user->setFlash('success', "Cotización generada con éxito");

				// Redireccionamos de la pagina de donde viene
				// Redireccionamos para agrgar productos
				$this->redirect(Yii::app()->createUrl('cotizaciones/actualizarcotizacion/' . $model->id_cotizacion));

			}

		}

		// lista de precios

		$arraylistamoneda = CHtml::listData(Monedas::model()->findAll(), 'id_moneda', 'moneda_nombre');

		$arraylistatipoproyecto = CHtml::listData(TipoProyectos::model()->findAll(), 'id_tipo_proyecto', 'nombre');

		$arraylistaprecios = CHtml::listData(ListaPrecios::model()->findAll(), 'id_lista_precio', 'listaprecio_nombre');
		$arraytipoprecio = [
			1 => 'Precio lista',
			2 => '50 - 99',
			3 => '100 - 199',
			4 => '200 o más',
			// 5 => '300 o mas',
			6 => 'Distribuidores',
		];



		// echo "<pre>";
		// print_r($DatosCliente);
		// echo "</pre>";
		// exit();

		$this->render(
			'crearcotizaciones',
			array(
				'DatosCliente' => $DatosCliente,
				'arraylistamoneda' => $arraylistamoneda,
				'arraylistatipoproyecto' => $arraylistatipoproyecto,
				'arraylistaprecios' => $arraylistaprecios,
				'model' => $model,
				'model_cot_arch' => $model_cot_arch,
				'id_oportunidad' => $id_oportunidad,
				'DatosOportunidad' => $DatosOportunidad,
				'arraytipoprecio' => $arraytipoprecio

			)
		);

	}

	public function actionModulos()
	{
		// Verificamos el acceso
		if (!$this->VerificarAcceso(1, Yii::app()->user->id)) {
			$this->redirect(Yii::app()->createUrl('site/noautorizado'));
		}
		// Obtenemos todos los grupos recurrentes
		$listagrupos = GruposRecurrentes::model()->findall('comentarios is null');


		$arreglo_modulos = [];
		$otrosmodulos = [
			[
				'url' => Yii::app()->createUrl('configuracion/index'),
				'nombre' => 'Configuración'
			],
			[
				'url' => Yii::app()->createUrl('productos/admin'),
				'nombre' => 'Productos'
			],
			[
				'url' => Yii::app()->createUrl('proveedores/admin'),
				'nombre' => 'Proveedores'
			],
			[
				'url' => Yii::app()->createUrl('formasdepago/admin'),
				'nombre' => 'Formas de Pago'
			],
			[
				'url' => Yii::app()->createUrl('productosprecios/admin'),
				'nombre' => 'Productos precios'
			],
			[
				'url' => Yii::app()->createUrl('clientes_frecuencias/admin'),
				'nombre' => 'Clientes Frecuencias'
			],
			[
				'url' => Yii::app()->createUrl('crmetapas/admin'),
				'nombre' => 'CRM Etapas'
			],
			[
				'url' => Yii::app()->createUrl('cotizacionesplantterminos/admin'),
				'nombre' => 'Plantilla para Cotizaciones'
			],
			[
				'url' => Yii::app()->createUrl('crmacciones/admin'),
				'nombre' => 'Crm acciones'
			],
			[
				'url' => Yii::app()->createUrl('usuarios/admin'),
				'nombre' => 'Usuarios'
			],
			[
				'url' => Yii::app()->createUrl('perfiles/index'),
				'nombre' => 'Perfiles'
			],
		];

		foreach ($otrosmodulos as $om) {
			$arreglo_modulos[] = [
				'url' => $om['url'],
				'nombre' => $om['nombre']
			];
		}
		foreach ($listagrupos as $row) {
			$arreglo_modulos[] = [
				'url' => Yii::app()->createUrl('grupos_recurrentes/index/' . $row->id_grupo_recurrente),
				'nombre' => $row->nombre
			];

		}




		$this->render('modulos', array('arreglo_modulos' => $arreglo_modulos));
	}

	public function actionContabilidad()
	{
		// Verificamos el acceso
		if (!$this->VerificarAcceso(5)) {
			$this->redirect(Yii::app()->createUrl('administracion/accesorestringido'));
		}

		// Obtenemos los ultimos 5 egresos, quiere decir lo mas recientes arriba
		$ListaEgresos = Contabilidadegresos::model()->findAll(array('order' => 'contabilidad_egresos_fechaalta desc', 'limit' => '5'));

		// Obtenemos los ultimos 5 ingresos, quiere decir que lo mas reciente arriba
		$ListaIngresos = Contabilidadingresos::model()->findAll(array('order' => 'contabilidad_ingresos_fechaalta desc', 'limit' => '5'));

		$this->render(
			'contabilidad',
			array(
				'ListaEgresos' => $ListaEgresos,
				'ListaIngresos' => $ListaIngresos
			)
		);
	}

	public function actionCrearoportunidad()
	{

		//Agregamos el detalle, verificamos que la accion no sea para fechas anteriores al dia de hoy.
		$fechahoy = date('Y-m-d H:i:s');
		$id_cliente = $_POST['CrmOportunidades']['id_cliente'];

		// Agregamos la oportunidad
		$addoportunidad = new CrmOportunidades;
		$addoportunidad->id_cliente = $id_cliente;
		$addoportunidad->nombre = $_POST['CrmOportunidades']['nombre'];
		$addoportunidad->estatus = 'SEGUIMIENTO';
		$addoportunidad->id_etapa = ($_POST['CrmOportunidades']['id_etapa'] != '') ? $_POST['CrmOportunidades']['id_etapa'] : 4;
		$addoportunidad->fecha_alta = date('Y-m-d H:i:s');
		$addoportunidad->id_usuario = Yii::app()->user->id;
		$addoportunidad->fecha_ultima_modificacion = date('Y-m-d H:i:s');
		$addoportunidad->fecha_tentativa_cierre = $_POST['CrmOportunidades']['fecha_tentativa_cierre'];
		$addoportunidad->valor_negocio = $_POST['CrmOportunidades']['valor_negocio'];

		if ($addoportunidad->save()) {
			$regtiempo = new Crmtiempos;
			$regtiempo->id_oportunidad = $addoportunidad->id;
			$regtiempo->id_etapa = $addoportunidad->id_etapa;
			$regtiempo->fecha_alta = date('Y-m-d H:i:s');
			$regtiempo->save();
			// Registramos el tiempo en crmtiempos
			Yii::app()->user->setFlash('success', "Se agrego con exito");
		} else {
			Yii::app()->user->setFlash('warning', "Ocurrio un error inesperado");
		}

		// Redireccionamos de la pagina de donde viene
		$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
		$this->redirect('crmver/' . $addoportunidad->id);
	}


	public function actionListaoportunidades()
	{
		// Obtenemos el model de clientes
		$modelClientes = new Clientes;
		// Obtenemos el model de crmacciones
		$modelCrmdetalles = new Crmdetalles;

		// Obtenemos la lista de prospectos
		$listaoportunidades = CrmOportunidades::model()->findAll('estatus="SEGUIMIENTO"');

		//lista para obtener las acciones
		$arraylistacrmacciones = CHtml::listData(Crmacciones::model()->findAll(), 'id_crm_acciones', 'crm_acciones_nombre');

		//lista para obtener los usuarios
		$arraylistausuarios = CHtml::listData(Usuarios::model()->findAll(), 'id_usuario', 'usuario_nombre');

		// Obtenemos las listas de precios, para asignarlas al cliente.
		$arraylistaprecios = CHtml::listData(ListaPrecios::model()->findAll(), 'id_lista_precio', 'listaprecio_nombre');


		$this->render(
			'listaoportunidades',
			array(
				'listaoportunidades' => $listaoportunidades,
				'modelClientes' => $modelClientes,
				'modelCrmdetalles' => $modelCrmdetalles,
				'arraylistacrmacciones' => $arraylistacrmacciones,
				'arraylistausuarios' => $arraylistausuarios,
				'arraylistaprecios' => $arraylistaprecios,
			)
		);
	}

	public function actionListaproyectos()
	{


		$fechainicio = (isset($_GET['fechainicio'])) ? $_GET['fechainicio'] : $this->_data_first_month_day();
		$fechafin = (isset($_GET['fechafin'])) ? $_GET['fechafin'] : $this->_data_last_month_day();

		// Obtenemos el filtro por fechas
		$parametros = ' proyecto_fecha_alta BETWEEN "' . $fechainicio . ' 00:00:01" and "' . $fechafin . ' 23:59:59" ';

		// Obtenemos la lista de proyectos con los parametros ingresados
		$listaproyectos = Proyectos::model()->findAll($parametros . 'and id_almacen=1');



		$this->render(
			'listaproyectos',
			array(
				'listaproyectos' => $listaproyectos,
				'fechainicio' => $fechainicio,
				'fechafin' => $fechafin
			)
		);
	}

	/**
	 * METODO PARA OBTENER TODOS LOS CODIGOS POSTALES - JS
	 */
	public function actionObtenerCP()
	{


		$sql = 'SELECT d_codigo as id, d_codigo as value, d_codigo as label FROM codigos_postales WHERE d_codigo LIKE :qterm GROUP BY d_codigo';
		RActiveRecord::getAdvertDbConnection();
		$command = Yii::app()->dbadvert->createCommand($sql);
		$qterm = '%' . $_GET['term'] . '%';
		$command->bindParam(":qterm", $qterm, PDO::PARAM_STR);
		$result = $command->queryAll();
		echo CJSON::encode($result);
		exit;

	}

	public function actionObtenercolonias()
	{
		$codigo_postal = $_POST['codigopostal'];
		// En base al codigo postal obtenemos las colonias, municipio y entidades

		$Entidades = CodigosPostales::model()->findAll(
			array(
				'condition' => 'd_codigo=' . $codigo_postal,
				'group' => 'd_estado',
			)
		);
		$op_entidades = '<option>-- Seleccione --</option>';
		$op_municipios = '<option>-- Seleccione --</option>';
		$op_colonias = '<option>-- Seleccione --</option>';
		$a = 1;
		$selected = '';
		foreach ($Entidades as $rows) {
			if ($a == 1) {
				$selected = 'selected';
			}
			$op_entidades .= '
				<option value="' . $rows->d_estado . '" ' . $selected . '>' . $rows->d_estado . '</option>
			';
		}
		$Municipios = CodigosPostales::model()->findAll(
			array(
				'condition' => 'd_codigo=' . $codigo_postal,
				'group' => 'D_mnpio',
			)
		);
		$a = 1;
		$selected = '';
		foreach ($Municipios as $rows) {
			if ($a == 1) {
				$selected = 'selected';
			}
			$op_municipios .= '
				<option value="' . $rows->D_mnpio . '" ' . $selected . '>' . $rows->D_mnpio . '</option>
			';
		}
		$Colonias = CodigosPostales::model()->findAll(
			array(
				'condition' => 'd_codigo=' . $codigo_postal,
				'group' => 'd_asenta',
			)
		);
		foreach ($Colonias as $rows) {
			$op_colonias .= '
				<option value="' . $rows->d_asenta . '">' . $rows->d_asenta . '</option>
			';
		}

		echo CJSON::encode(
			array(
				'requestresult' => 'ok',
				'message' => 'Informacion encontrada',
				'op_entidades' => $op_entidades,
				'op_municipios' => $op_municipios,
				'op_colonias' => $op_colonias,
			)
		);
	}


	public function actionActualizarcampos()
	{
		$id = $_POST['id'];
		$Datos = CrmOportunidades::model()->findByPk($id);
		if (!empty($Datos)) {

			$campo = $_POST['campo'];
			$Datos->$campo = $_POST['valor'];
			$Datos->fecha_ultima_modificacion = date('Y-m-d H:i:s');
			if ($Datos->save()) {
				// Si el campo es estatus, registramos es etapa en  crm tiempos
				if ($_POST['campo'] == 'id_etapa') {
					$regtiempo = new Crmtiempos;
					$regtiempo->id_oportunidad = $Datos->id;
					$regtiempo->id_etapa = $Datos->id_etapa;
					$regtiempo->fecha_alta = date('Y-m-d H:i:s');
					$regtiempo->save();
				}
				echo CJSON::encode(
					array(
						'requestresult' => 'ok',
						'message' => 'Se actualizo con exito ' . $Datos->getAttributeLabel($_POST['campo']) . '.'
					)
				);
			} else {
				echo CJSON::encode(
					array(
						'requestresult' => 'fail',
						'message' => 'Ocurrio un error inesperado',
						'error' => print_r($Datos->getErrors())
					)
				);
			}

		} else {
			echo CJSON::encode(
				array(
					'requestresult' => 'fail',
					'message' => 'Ocurrio un error inesperado, no se encontro la oportunidad',
				)
			);
		}

	}

	public function actionActualizarcamposCliente()
	{
		// Verificamos si ya existe.
		$DatosFrecuen = ClientesFrecuencias::model()->find('id_cliente=' . $_POST['id_cliente']);


		$campo = $_POST['campo'];


		if (!empty($DatosFrecuen)) {
			// Si ya existe, solo actualizamos

			$DatosFrecuen[$campo] = $_POST['valor'];

			if ($DatosFrecuen->save()) {
				echo CJSON::encode(
					array(
						'requestresult' => 'ok',
						'message' => 'Se actualizo con exito ' . $DatosFrecuen->getAttributeLabel($_POST['campo']) . '.'
					)
				);
			} else {
				echo CJSON::encode(
					array(
						'requestresult' => 'fail',
						'message' => 'Ocurrio un error inesperado',
						'error' => print_r($DatosFrecuen->getErrors())
					)
				);
			}

		} else {
			// Hacemos un insert en caso de que no exista
			$regtfrecuencia = new ClientesFrecuencias;
			$regtfrecuencia->id_cliente = $_POST['id_cliente'];
			$regtfrecuencia[$campo] = $_POST['valor'];
			$regtfrecuencia->save();

			echo CJSON::encode(
				array(
					'requestresult' => 'ok',
					'message' => 'Se actualizo con exito ' . $regtfrecuencia->getAttributeLabel($_POST['campo']) . '.'
				)
			);
		}

	}


	/**
	 * METODO PARA MOSTRAR LAS OPORTUNIDADES POR ETAPAS
	 */
	public function actionindex()
	{
		$VerificarAcceso = $this->VerificarAcceso(15, Yii::app()->user->id);
		if (!$VerificarAcceso) {
			// Lo redireccionamos a la pagina acceso restringido
			$this->redirect(Yii::app()->createURL('site/noautorizado'));
		}
		// Obtenemos los tipos de estapas
		$Etapas = Crmetapas::model()->findAll(array('condition' => '', 'order' => 'orden asc'));

		// Obtenemos el model de CrmOportunidades
		$crm_oportunidades = new CrmOportunidades;
		//lista de clientes
		// Obtenemos todos los usuarios relacionados
		$usuarioshijos = $this->ObtenerHijosUsuario(Yii::app()->user->id, '');
		// TERMINA 
		$listaclientes = CHtml::listData(Clientes::model()->findAll('id_usuario in (' . $usuarioshijos . ')'), 'id_cliente', 'cliente_nombre');
		// Obtenemos los tipos de estapas
		$listaetapa = CHtml::listData($Etapas, 'id', 'nombre');

		$Clasificacion = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente=9');
		$ListaClasificacion = CHtml::listData($Clasificacion, 'id_catalogo_recurrente', 'nombre');

		$Trabajarlo = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente=10');
		$ListaComoTrabajarlo = CHtml::listData($Trabajarlo, 'id_catalogo_recurrente', 'nombre');

		$model = new Clientes;
		// Obtenemos las listas de precios, para asignarlas al cliente.
		$arraylistaprecios = CHtml::listData(ListaPrecios::model()->findAll(), 'id_lista_precio', 'listaprecio_nombre');
		$ListaEmpresa = CHtml::listData(Empresas::model()->findAll(), 'id', 'empresa');

		// Obtenemos los tipos de estapas
		$Tipo = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente=3');
		$listatipo = CHtml::listData($Tipo, 'id_catalogo_recurrente', 'nombre');

		$arraytipoprecio = [
			1 => 'Precio lista',
			2 => '50 - 99',
			3 => '100 - 199',
			4 => '200 o más',
			// 5 => '300 o mas',
			6 => 'Distribuidores',
		];

		$this->render(
			'index',
			array(
				'model' => $model,
				'Etapas' => $Etapas,
				'crm_oportunidades' => $crm_oportunidades,
				'listaclientes' => $listaclientes,
				'listaetapa' => $listaetapa,
				'arraylistaprecios' => $arraylistaprecios,
				'ListaEmpresa' => $ListaEmpresa,
				'ListaClasificacion' => $ListaClasificacion,
				'ListaComoTrabajarlo' => $ListaComoTrabajarlo,
				'listatipo' => $listatipo,
				'precio' => $arraytipoprecio
			)
		);
	}

	/**
	 * METODO PARA MOSTRAR LA LISTA DE ACTIVIDADES
	 */
	public function actionListaactividades()
	{
		$VerificarAcceso = $this->VerificarAcceso(15, Yii::app()->user->id);
		if (!$VerificarAcceso) {
			// Lo redireccionamos a la pagina acceso restringido
			$this->redirect(Yii::app()->createURL('site/noautorizado'));
		}
		// Obtenemos todos los usuarios relacionados
		$usuarioshijos = $this->ObtenerHijosUsuario(Yii::app()->user->id, '');
		// TERMINA 
		// echo '<pre>';
		// print_r($usuarioshijos);
		// echo '</pre>';
		// Obtenemos la lista de prospectos
		$listaactividades = Crmdetalles::model()->with(
			array(
				'rl_oportunidad' => array('condition' => 'rl_oportunidad.estatus="SEGUIMIENTO"')
			)
		)->findAll('crm_detalles_usuario_alta in (' . $usuarioshijos . ')');
		// echo '<pre>';
		// print_r($listaactividades);
		// echo '</pre>';
		// exit;
		// Obtenemos el model de crmacciones
		$modelCrmdetalles = new Crmdetalles;

		// Obtenemos la lista de prospectos
		$listaoportunidades = CrmOportunidades::model()->findAll('estatus="SEGUIMIENTO" and id_usuario in (' . $usuarioshijos . ')');

		//lista de clientes
		$listaclientes = CHtml::listData(Clientes::model()->findAll('id_usuario = ' . Yii::app()->user->id), 'id_cliente', 'cliente_nombre');

		//lista para obtener las acciones
		$arraylistacrmacciones = CHtml::listData(Crmacciones::model()->findAll(), 'id_crm_acciones', 'crm_acciones_nombre');

		$this->render(
			'listaactividades',
			array(
				'listaactividades' => $listaactividades,
				'modelCrmdetalles' => $modelCrmdetalles,
				'listaoportunidades' => $listaoportunidades,
				'listaclientes' => $listaclientes,
				'arraylistacrmacciones' => $arraylistacrmacciones
			)
		);
	}

	public function actionRutas()
	{


		// VARIABLES
		$fechainicio = (isset($_GET['fechainicio'])) ? $_GET['fechainicio'] : $this->_data_first_month_day();
		$fechafin = (isset($_GET['fechafin'])) ? $_GET['fechafin'] : $this->_data_last_month_day();
		$parametros = 'fecha_desde BETWEEN "' . $fechainicio . ' 00:00:01" and "' . $fechafin . ' 23:59:59" and id_vendedor = ' . Yii::app()->user->id . ' ';


		$lista = Rutas::model()->findAll($parametros);
		$model = new Rutas;

		$this->render(
			'rutas',
			array(
				'model' => $model,
				'lista' => $lista,
				'fechainicio' => $fechainicio,
				'fechafin' => $fechafin
			)
		);
	}

	public function actionRutadetalle()
	{
		$id = (isset($_GET['id']) && $_GET['id'] != '') ? $_GET['id'] : '0';
		// Obtenemos los datos de la ruta.
		$Ruta = Rutas::model()->find('id=:id and id_vendedor=:id_vendedor', array(':id' => $id, ':id_vendedor' => Yii::app()->user->id));
		if (empty($Ruta)) {
			$this->redirect(Yii::app()->createUrl('administracion/rutas'));
		}
		// Obtenemos todas las rutas hijas
		$Rutadetalle = RutasDetalles::model()->findall(
			array(
				'condition' => 'id_ruta=:idruta',
				'params' => array(':idruta' => $Ruta->id),
				'order' => 'orden asc'
			)
		);

		$model = new RutasDetalles;
		//lista de clientes en base a las oportunidades de ese vendedor
		$arrayclientes = CrmOportunidades::model()->findall(
			array(
				'condition' => 'id_usuario=:id_usuario',
				'params' => array(':id_usuario' => Yii::app()->user->id),
				'group' => 'id_cliente',
			)
		);
		$listaclientes = array();
		foreach ($arrayclientes as $datos) {
			$listaclientes[$datos->id_cliente] = $datos->rl_clientes->cliente_nombre;
		}

		$this->render(
			'rutadetalle',
			array(
				'Ruta' => $Ruta,
				'Rutadetalle' => $Rutadetalle,
				'listaclientes' => $listaclientes,
				'model' => $model
			)
		);
	}

	/**
	 * Metodo para los mensajes
	 */
	public function actionEnviarmensaje()
	{
		$VerificarAcceso = $this->VerificarAcceso(15, Yii::app()->user->id);
		if (!$VerificarAcceso) {
			// Lo redireccionamos a la pagina acceso restringido
			$this->redirect(Yii::app()->createURL('site/noautorizado'));
		}
		$id = (isset($_GET['id'])) ? $_GET['id'] : 0;

		$model = new Mensajes;
		$Usuarios = Usuarios::model()->findAll('ID_Usuario!=' . Yii::app()->user->id);
		$listausuarios = array();
		foreach ($Usuarios as $rows) {
			$listausuarios[$rows['ID_Usuario']] = $rows['Usuario_Nombre'] . ' (' . $rows['Usuario_Email'] . ')';
		}

		$this->render(
			'enviarmensaje',
			array(
				'model' => $model,
				'listausuarios' => $listausuarios
			)
		);
	}
	/**
	 * Metodo para los mensajes
	 */
	public function actionMensajesrecibidos()
	{
		$fechainicio = (isset($_GET['fechainicio'])) ? $_GET['fechainicio'] : $this->_data_first_month_day();
		$fechafin = (isset($_GET['fechafin'])) ? $_GET['fechafin'] : $this->_data_last_month_day();
		$parametros = 'fecha_alta BETWEEN "' . $fechainicio . ' 00:00:01" and "' . $fechafin . ' 23:59:59" and id_destinatario = ' . Yii::app()->user->id . ' ';

		$lista = Mensajes::model()->findAll($parametros);

		$this->render(
			'mensajesrecibidos',
			array(
				'lista' => $lista,
				'fechainicio' => $fechainicio,
				'fechafin' => $fechafin
			)
		);
	}
	/**
	 * Metodo para los mensajes
	 */
	public function actionMensajesenviados()
	{
		$fechainicio = (isset($_GET['fechainicio'])) ? $_GET['fechainicio'] : $this->_data_first_month_day();
		$fechafin = (isset($_GET['fechafin'])) ? $_GET['fechafin'] : $this->_data_last_month_day();
		$parametros = 'fecha_alta BETWEEN "' . $fechainicio . ' 00:00:01" and "' . $fechafin . ' 23:59:59" and id_remitente = ' . Yii::app()->user->id . ' ';


		$lista = Mensajes::model()->findAll($parametros);

		$this->render(
			'mensajesenviados',
			array(
				'lista' => $lista,
				'fechainicio' => $fechainicio,
				'fechafin' => $fechafin
			)
		);
	}

	/**
	 * METODO PARA MANEJAR LOS AGRUPADOS DE LOS MENUS CONFIGURACION
	 */
	public function actionConfiguracion()
	{
		// Verificamos el acceso
		if (!$this->VerificarAcceso(4, Yii::app()->user->id)) {
			$this->redirect(Yii::app()->createUrl('site/noautorizado'));
		}
		$this->render('configuracion');
	}

	/**
	 * METODO PARA MANEJAR LOS AGRUPADOS DE LOS MENUS CONFIGURACION
	 */
	public function actionUsuariosperfiles()
	{
		// Verificamos el acceso
		if (!$this->VerificarAcceso(5, Yii::app()->user->id)) {
			$this->redirect(Yii::app()->createUrl('site/noautorizado'));
		}
		$this->render('usuariosperfiles');
	}

	/**
	 * METODO PARA MANEJAR LOS AGRUPADOS DE LOS MENUS CONFIGURACION
	 */
	public function actionCatalogos()
	{
		// Verificamos el acceso
		if (!$this->VerificarAcceso(6, Yii::app()->user->id)) {
			$this->redirect(Yii::app()->createUrl('site/noautorizado'));
		}
		$this->render('catalogos');
	}

	/**
	 * METODO PARA MANEJAR LOS AGRUPADOS DE LOS MENUS CONFIGURACION
	 */
	public function actionCatalogosgenerales()
	{
		// Verificamos el acceso
		if (!$this->VerificarAcceso(7, Yii::app()->user->id)) {
			$this->redirect(Yii::app()->createUrl('site/noautorizado'));
		}
		$listagrupos = GruposRecurrentes::model()->findall('comentarios is null');

		$this->render('catalogosgenerales', array('listagrupos' => $listagrupos));
	}


	/**
	 * METODO PARA BUSCAR CLIENTE MEDIANTE AJAX PARA EL ATUCOMPLETE
	 */
	public function actionBuscadorclienteajax()
	{
		RActiveRecord::getAdvertDbConnection();
		$sql = 'SELECT id_cliente as id, CONCAT_WS("",NULL,cliente_nombre," ",cliente_rfc," ",cliente_razonsocial," ",cliente_razonsocial," ",cliente_email) as value, CONCAT_WS("",NULL,cliente_nombre," ",cliente_rfc," ",cliente_razonsocial," ",cliente_razonsocial," ",cliente_email) as label
    	 FROM clientes
    	 WHERE 
    	 	 CONCAT_WS("",NULL,cliente_nombre," ",cliente_rfc," ",cliente_razonsocial," ",cliente_razonsocial," ",cliente_email) LIKE :qterm';
		$command = Yii::app()->dbadvert->createCommand($sql);
		$qterm = '%' . trim($_GET['term']) . '%';
		$command->bindParam(":qterm", $qterm, PDO::PARAM_STR);
		$result = $command->queryAll();
		echo CJSON::encode($result);
		exit;
	}


	/**
																																																																																																															 METODO PARA BUSCAR PRODUCTO
																																																																																																															 **/
	public function actionBuscadorproducto()
	{
		$sql = 'SELECT id_producto, producto_nombre, producto_clave, producto_imagen, CONCAT_WS("",NULL,producto_nombre," ",producto_clave) as value, CONCAT_WS("",NULL,producto_nombre," ",producto_clave)  as label,producto_descripcion as descripcion FROM productos WHERE producto_estatus = 1 and producto_nombre LIKE :qterm or producto_clave LIKE :qterm';
		RActiveRecord::getAdvertDbConnection();
		$command = Yii::app()->dbadvert->createCommand($sql);
		$qterm = '%' . $_GET['term'] . '%';
		$command->bindParam(":qterm", $qterm, PDO::PARAM_STR);
		$result = $command->queryAll();

		#print_r($result);exit;
		echo CJSON::encode($result);
		exit;
	}

	public function actionBuscadorproductooc()
	{


		$sql = '
		SELECT 
			p.id_producto as id_producto,
			CONCAT_WS(" ",NULL,p.producto_nombre,p.producto_clave) as value,
			CONCAT_WS(" ",NULL,p.producto_nombre,p.producto_clave) as label,
			p.producto_imagen,
			p.producto_nombre,
			p.producto_clave
		FROM
			productos p 
		WHERE 
			p.producto_estatus = 1 and p.producto_nombre LIKE :qterm or p.producto_clave LIKE :qterm and p.eliminado = 0';

		$sql .= '
		LIMIT 15';


		$command = Yii::app()->db->createCommand($sql);
		$qterm = "%" . $_GET['term'] . "%";
		$command->bindParam(":qterm", $qterm, PDO::PARAM_STR);
		$result = $command->queryAll();
		echo CJSON::encode($result);
		exit;
	}


	/**
	 * METODO PARA MOSTRAR LA PANTALLA DE MI PERFIL
	 */
	public function actionMiperfil()
	{
		$Datosusuario = Usuarios::model()->findBypk(Yii::app()->user->id);

		$this->render(
			'miperfil',
			array(
				'model' => $Datosusuario
			)
		);
	}

	/**
	 * proceso que regresa si debemos o no activar el sonido, este suena cuando se crea un proyecto
	 * dvb 17 01 2024
	 */
	public function actionRevisarsonido()
	{

		$proyectos = ProyectosNotificaciones::model()->findAll([
			'condition' => 'estatus = 0 and eliminado = 0 and id_usuario = :usuario',
			'params' => [':usuario' => Yii::app()->user->id]
		]);
		// recorremos todos
		foreach ($proyectos as $rows) {
			$rows->estatus = 1; // la marcamos como vista
			$rows->save();
		}
		$activarsonido = empty($proyectos) ? 0 : 1;
		//
		echo CJSON::encode([
			'requestresult' => 'ok',
			'activarsonido' => $activarsonido
		]);
		exit;
	}

	// action para cambiar el precio al cliente al crear la cotizacion lars 14/02/24

	public function actionCambiarlistapreciocliente()
	{
		$id = isset($_POST['id']) ? $_POST['id'] : '';
		$valor = isset($_POST['nuevoprecio']) ? $_POST['nuevoprecio'] : '';

		if (!empty($id) && !empty($valor)) {

			$cliente = Clientes::model()->find('id_cliente = ' . $id);

			$cliente->id_lista_precio = $valor;

			if ($cliente->save()) {
				echo CJSON::encode([
					'requestresult' => 'ok',
					'message' => 'Se actualizo el precio en el cliente correctamente',
				]);
			} else {
				echo CJSON::encode([
					'requestresult' => 'fail',
					'message' => 'no se actualizo el precio en el cliente',
				]);
			}
		} else {
			echo CJSON::encode([
				'requestresult' => 'fail',
				'message' => 'No se encontraron datos',
			]);
		}
	}
}