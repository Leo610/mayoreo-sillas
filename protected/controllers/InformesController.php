<?php

class InformesController extends Controller
{
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
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
				'allow', // allow authenticated users to access all actions
				'users' => array('@'),
			),
			array(
				'deny',  // deny all users
				'users' => array('*'),
			),
		);
	}

	public function actionLista()
	{
		$VerificarAcceso = $this->VerificarAcceso(3, Yii::app()->user->id);
		if (!$VerificarAcceso) {
			// Lo redireccionamos a la pagina acceso restringido
			$this->redirect(Yii::app()->createURL('site/noautorizado'));
		}

		$this->render('lista');
	}

	/**
	 * REPORTE GENERAL DE VENTELIA
	 */
	public function actionOportunidades()
	{
		$VerificarAcceso = $this->VerificarAcceso(3, Yii::app()->user->id);
		if (!$VerificarAcceso) {
			// Lo redireccionamos a la pagina acceso restringido
			$this->redirect(Yii::app()->createURL('site/noautorizado'));
		}

		// Lista de Usuarios 
		$usuarioshijos = $this->ObtenerHijosUsuario(Yii::app()->user->id, '');
		$listausuarios = Usuarios::model()->findAll('ID_Usuario in (' . $usuarioshijos . ')');
		// Fechas
		// $fechainicio = (isset($_GET['fechainicio'])) ? $_GET['fechainicio'] : $this->_data_first_month_day();
		$fechainicio = (isset($_GET['fechainicio'])) ? $_GET['fechainicio'] : $this->_data_last_three_month_day();
		$fechafin = (isset($_GET['fechafin'])) ? $_GET['fechafin'] : $this->_data_last_month_day();
		$parametros = 'date(fecha_alta) BETWEEN "' . $fechainicio . '" and "' . $fechafin . '" ';
		// Estatus
		$estatus = "Todos";
		if (isset($_GET['estatus']) && ($_GET['estatus'] != 'Todos')) {
			$estatus = $_GET['estatus'];
			$parametros .= ' and estatus="' . $_GET['estatus'] . '"';
		}

		// Usuarios
		$usuario = "Todos";
		if (isset($_GET['usuario']) && ($_GET['usuario'] != 'Todos')) {
			$usuario = $_GET['usuario'];
			$parametros .= ' and id_usuario="' . $_GET['usuario'] . '"';
		}

		$resultados = CrmOportunidades::model()->findall($parametros);

		// Oportunidades
		$this->render(
			'oportunidades',
			array(
				'resultados' => $resultados,
				'listausuarios' => $listausuarios,
				'fechainicio' => $fechainicio,
				'fechafin' => $fechafin,
				'estatus' => $estatus,
				'usuario' => $usuario
			)
		);
	}
	public function actionCuentasporcobrar()
	{
		$VerificarAcceso = $this->VerificarAcceso(3, Yii::app()->user->id);
		if (!$VerificarAcceso) {
			// Lo redireccionamos a la pagina acceso restringido
			$this->redirect(Yii::app()->createURL('site/noautorizado'));
		}

		$fechainicio = (isset($_GET['fechainicio'])) ? $_GET['fechainicio'] : $this->_data_last_three_month_day();
		// $fechainicio = (isset($_GET['fechainicio'])) ? $_GET['fechainicio'] : $this->_data_first_month_day();
		$fechafin = (isset($_GET['fechafin'])) ? $_GET['fechafin'] : $this->_data_last_month_day();

		// modificart server
		$id_usuario = (isset($_GET['id_usuario'])) ? $_GET['id_usuario'] : '';
		$id_bodega = (isset($_GET['id_bodega'])) ? $_GET['id_bodega'] : 0;
		$nombreUsuario = (isset($_GET['nombreusuario'])) ? $_GET['nombreusuario'] : '';

		$Listamonedas = Monedas::model()->findall();
		// Obtenemos los clientes que cuenten con proyecto pendiente de cobro
		$ListaClientes = Yii::app()->dbadvert->createCommand()
			->select('*')
			->from('clientes c')
			->join('proyectos p', 'p.id_cliente = c.id_cliente')
			->where('p.proyecto_totalpendiente>0')
			->group('c.id_cliente')
			->queryall();
		$params = '';
		$params = ' proyecto_totalpendiente>0 and proyecto_fecha_alta BETWEEN "' . $fechainicio . ' 00:00:01" and "' . $fechafin . ' 23:59:59" ';
		$id_cliente = (isset($_GET['id_cliente']) and $_GET['id_cliente'] != NULL) ? $_GET['id_cliente'] : 0;
		if ($id_cliente != 0) {
			$params = ' and id_cliente=' . $id_cliente;
		}

		$id_moneda = (isset($_GET['id_moneda'])) ? $_GET['id_moneda'] : 1;
		$params .= ' and id_moneda =' . $id_moneda;


		if (!empty($id_usuario)) {

			$params .= ' and id_usuario = ' . $id_usuario . '';
		}

		// modificar server
		if (!empty($id_bodega)) {
			$params .= ' and  id_usuario IN (SELECT ID_Usuario FROM usuarios WHERE bodega = ' . $id_bodega . ')';
		}

		// modificar server
		// obtenemos las bodegas
		$bodegas_ = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente = 28');

		$bodegas = [];

		foreach ($bodegas_ as $row) {
			$bodegas[] = [
				'id' => $row['id_catalogo_recurrente'],
				'name' => $row['nombre']
			];
		}

		// Obtenemos los proyectos con total pendiente.
		$ListaProyectospp = Proyectos::model()->findAll($params);


		$this->render(
			'cuentasporcobrar',
			array(
				'ListaProyectospp' => $ListaProyectospp,
				'ListaClientes' => $ListaClientes,
				'Cliente' => $id_cliente,
				'listamonedas' => $Listamonedas,
				'id_moneda' => $id_moneda,
				'bodegas' => $bodegas,
				'nombre' => $nombreUsuario,
				'id_bodega' => $id_bodega,
				'fechainicio' => $fechainicio,
				'fechafin' => $fechafin,
			)
		);
	}

	public function actionCuentasporpagar()
	{
		$Listamonedas = Monedas::model()->findall();
		// Obtenemos los clientes que cuenten con proyecto pendiente de cobro
		$ListaProveedores = Yii::app()->dbadvert->createCommand()
			->select('*')
			->from('proveedores p')
			->join('ordenesdecompra oc', 'p.id_proveedor = oc.id_proveedor')
			->where('oc.ordendecompra_estatus=4 and oc.ordendecompra_totalpendiente>0')
			->queryall();

		$params = '';
		$id_proveedor = (isset($_GET['id_proveedor']) and $_GET['id_proveedor'] != NULL) ? $_GET['id_proveedor'] : 0;
		if ($id_proveedor != 0) {
			$params = ' and id_proveedor=' . $id_proveedor;
		}
		$id_moneda = (isset($_GET['id_moneda'])) ? $_GET['id_moneda'] : 0;
		$params .= ' and id_moneda =' . $id_moneda;
		// Obtenemos las ordenes de compra
		$ListaOC = Ordenesdecompra::model()->findAll('ordendecompra_estatus=4 and ordendecompra_totalpendiente>0' . $params);


		$this->render(
			'cuentasporpagar',
			array(
				'ListaOC' => $ListaOC,
				'ListaProveedores' => $ListaProveedores,
				'Proveedor' => $id_proveedor,
				'listamonedas' => $Listamonedas,
				'id_moneda' => $id_moneda
			)
		);
	}

	public function actionEgresos()
	{
		$Listamonedas = Monedas::model()->findall();
		// FECHA INICIO Y FECHA FIN
		$fechainicio = (isset($_GET['fechainicio'])) ? $_GET['fechainicio'] : $this->_data_first_month_day();
		$fechafin = (isset($_GET['fechafin'])) ? $_GET['fechafin'] : $this->_data_last_month_day();

		$parametros = 'contabilidad_egresos_fechaalta BETWEEN "' . $fechainicio . ' 00:00:01" and "' . $fechafin . ' 23:59:59" ';

		// Obtenemos el id del banco
		$id_banco = (isset($_GET['id_banco']) and $_GET['id_banco'] != NULL) ? $_GET['id_banco'] : 0;
		if ($id_banco > 0) {
			$parametros .= ' and id_banco=' . $id_banco;
		}

		$id_moneda = (isset($_GET['id_moneda'])) ? $_GET['id_moneda'] : 0;
		$parametros .= ' and id_moneda =' . $id_moneda;

		// Obtenemos todos los egresoso filtrados por fecha inicio y fin, y tal ves bancos
		$ListaEgresos = Contabilidadegresos::model()->findAll($parametros);

		// Obtenemos la lista de todos los bancos- cuentas disponibles.
		$ListaBancos = Bancos::model()->findAll();


		$this->render(
			'contabilidadegresos',
			array(
				'ListaEgresos' => $ListaEgresos,
				'ListaBancos' => $ListaBancos,
				'fechainicio' => $fechainicio,
				'fechafin' => $fechafin,
				'id_banco' => $id_banco,
				'listamonedas' => $Listamonedas,
				'id_moneda' => $id_moneda
			)
		);
	}

	public function actionIngresos()
	{
		$VerificarAcceso = $this->VerificarAcceso(3, Yii::app()->user->id);
		if (!$VerificarAcceso) {
			// Lo redireccionamos a la pagina acceso restringido
			$this->redirect(Yii::app()->createURL('site/noautorizado'));
		}
		$Listamonedas = Monedas::model()->findall();
		// FECHA INICIO Y FECHA FIN
		// $fechainicio = (isset($_GET['fechainicio'])) ? $_GET['fechainicio'] : $this->_data_first_month_day();
		$fechainicio = (isset($_GET['fechainicio'])) ? $_GET['fechainicio'] : $this->_data_last_three_month_day();
		$fechafin = (isset($_GET['fechafin'])) ? $_GET['fechafin'] : $this->_data_last_month_day();

		$id_usuario = (isset($_GET['id_usuario'])) ? $_GET['id_usuario'] : '';
		$id_bodega = (isset($_GET['id_bodega'])) ? $_GET['id_bodega'] : 0;
		$nombreUsuario = (isset($_GET['nombreusuario'])) ? $_GET['nombreusuario'] : '';

		$parametros = 'contabilidad_ingresos_fechaalta BETWEEN "' . $fechainicio . ' 00:00:01" and "' . $fechafin . ' 23:59:59" ';

		// Obtenemos el id del banco
		$id_banco = (isset($_GET['id_banco']) and $_GET['id_banco'] != NULL) ? $_GET['id_banco'] : 0;
		if ($id_banco > 0) {
			$parametros .= ' and id_banco=' . $id_banco;
		}
		$id_moneda = (isset($_GET['id_moneda'])) ? $_GET['id_moneda'] : 1;
		$parametros .= ' and id_moneda =' . $id_moneda;

		if (!empty($id_usuario)) {

			$parametros .= ' and id_usuario = ' . $id_usuario . '';
		}

		// modificar server
		if (!empty($id_bodega)) {
			$parametros .= ' and  id_usuario IN (SELECT ID_Usuario FROM usuarios WHERE bodega = ' . $id_bodega . ')';
		}

		// modificar server
		// obtenemos las bodegas
		$bodegas_ = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente = 28');

		$bodegas = [];

		foreach ($bodegas_ as $row) {
			$bodegas[] = [
				'id' => $row['id_catalogo_recurrente'],
				'name' => $row['nombre']
			];
		}

		// Obtenemos todos los egresoso filtrados por fecha inicio y fin, y tal ves bancos
		$ListaIngresos = Contabilidadingresos::model()->findAll($parametros);

		// Obtenemos la lista de todos los bancos- cuentas disponibles.
		$ListaBancos = Bancos::model()->findAll();


		$this->render(
			'contabilidadingresos',
			array(
				'ListaIngresos' => $ListaIngresos,
				'ListaBancos' => $ListaBancos,
				'fechainicio' => $fechainicio,
				'fechafin' => $fechafin,
				'id_banco' => $id_banco,
				'listamonedas' => $Listamonedas,
				'id_moneda' => $id_moneda,
				'bodegas' => $bodegas,
				'nombre' => $nombreUsuario,
				'id_bodega' => $id_bodega

			)
		);
	}

	/**
	 * REPORTE DE VENTAS
	 */
	public function actionVentas()
	{
		$VerificarAcceso = $this->VerificarAcceso(3, Yii::app()->user->id);
		if (!$VerificarAcceso) {
			// Lo redireccionamos a la pagina acceso restringido
			$this->redirect(Yii::app()->createURL('site/noautorizado'));
		}
		// echo "<pre>";
		// print_r($_GET);
		// echo "</pre>";
		// exit();

		$Listamonedas = Monedas::model()->findall();
		// FECHA INICIO Y FECHA FIN
		// $fechainicio = (isset($_GET['fechainicio'])) ? $_GET['fechainicio'] : $this->_data_first_month_day();
		$fechainicio = (isset($_GET['fechainicio'])) ? $_GET['fechainicio'] : $this->_data_last_three_month_day();
		$fechafin = (isset($_GET['fechafin'])) ? $_GET['fechafin'] : $this->_data_last_month_day();
		$idus = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';

		$id_bodega = (isset($_GET['id_bodega'])) ? $_GET['id_bodega'] : 0;

		// Obtenemos los clientes que cuenten con proyecto pendiente de cobro
		$ListaClientes = Yii::app()->dbadvert->createCommand()
			->select('*')
			->from('clientes c')
			->join('proyectos p', 'p.id_cliente = c.id_cliente')
			->where('p.proyecto_totalpendiente>0')
			->group('c.id_cliente')
			->queryall();
		$id_moneda = (isset($_GET['id_moneda'])) ? $_GET['id_moneda'] : 1;
		$parametros = 'proyecto_fecha_alta BETWEEN "' . $fechainicio . ' 00:00:01" and "' . $fechafin . ' 23:59:59" ';
		if ($idus != '') {
			$parametros .= ' and id_usuario = ' . $idus;
		}

		// modificar server
		if (!empty($id_bodega)) {
			$parametros .= ' and  id_usuario IN (SELECT ID_Usuario FROM usuarios WHERE bodega = ' . $id_bodega . ')';
		}

		// modificar server
		// obtenemos las bodegas
		$bodegas_ = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente = 28');

		$bodegas = [];

		foreach ($bodegas_ as $row) {
			$bodegas[] = [
				'id' => $row['id_catalogo_recurrente'],
				'name' => $row['nombre']
			];
		}
		// Obtenemos los proyectos con total pendiente.
		$ListaProyectospp = Proyectos::model()->findAll($parametros);


		$this->render(
			'ventas',
			array(
				'fechainicio' => $fechainicio,
				'fechafin' => $fechafin,
				'listamonedas' => $Listamonedas,
				'id_moneda' => $id_moneda,
				'ListaProyectospp' => $ListaProyectospp,
				'bodegas' => $bodegas,
				'id_bodega' => $id_bodega

			)
		);
	}

	// funcion para buscar usario ->lars 05/01/2024
	public function actionBuscarusuario()
	{
		$sql = 'SELECT ID_Usuario as id, CONCAT_WS("",NULL,Usuario_Nombre," ",Usuario_Email) as value, CONCAT_WS("",NULL,Usuario_Nombre," ",Usuario_Email)  as label FROM usuarios WHERE Usuario_Nombre LIKE :qterm or Usuario_Email LIKE :qterm';
		RActiveRecord::getAdvertDbConnection();
		$command = Yii::app()->dbadvert->createCommand($sql);
		$qterm = '%' . $_GET['term'] . '%';
		$command->bindParam(":qterm", $qterm, PDO::PARAM_STR);
		$result = $command->queryAll();
		echo CJSON::encode($result);
		exit;
	}
}
