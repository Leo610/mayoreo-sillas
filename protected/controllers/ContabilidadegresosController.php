<?php

class ContabilidadegresosController extends Controller
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
			array('allow', // allow authenticated users to access all actions
          'users'=>array('@'),
            ),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}


	public function actionAgregar()
	{
		// En base al identificador, obtenemos el proceso
		$Identificador = explode("-",$_POST['Contabilidadegresos']['contabilidad_egresos_identificador']); 
		$Pago = $_POST['Contabilidadegresos']['contabilidad_egresos_cantidad'];
		
		switch (trim($Identificador['0'])) {
			case 'Orden De Compra':
				$DatosOrden = Ordenesdecompra::model()->findbypk($Identificador['1']);

				if($DatosOrden->ordendecompra_totalpendiente>=$Pago){

					// Actualizamos los totales de la Orden de compra
					$DatosOrden->ordendecompra_totalpagado = $DatosOrden->ordendecompra_totalpagado + $Pago;
					$DatosOrden->ordendecompra_totalpendiente = $DatosOrden->ordendecompra_totalpendiente - $Pago; 
					$DatosOrden->ordendecompra_ultimamodif = date('Y-m-d H:i:s');
					if($DatosOrden->save())
					{
						$this->AgregarPago($_POST['Contabilidadegresos']);	
						Yii::app()->user->setFlash('success','Se agrego con exito.');
					}else
					{
						Yii::app()->user->setFlash('warning','Ocurrio un error inesperado.');
					}
					
				}else{
					Yii::app()->user->setFlash('warning','No puede pagar mas de lo pendiente.');
				}
			break;
			case 'Empleado':
				$Datos= Proyectosempleados::model()->findbypk($Identificador['1']);

				if($Datos->proyectos_empleados_totalpendiente>=$Pago){

					// Actualizamos los totales de la Orden de compra
					$Datos->proyectos_empleados_totalpagado = $Datos->proyectos_empleados_totalpagado + $Pago;
					$Datos->proyectos_empleados_totalpendiente = $Datos->proyectos_empleados_totalpendiente - $Pago; 
					$Datos->fecha_ultima_modif = date('Y-m-d H:i:s');
					if($Datos->save())
					{
						$this->AgregarPago($_POST['Contabilidadegresos']);	
						Yii::app()->user->setFlash('success','Se agrego con exito.');
					}else
					{
						Yii::app()->user->setFlash('warning','Ocurrio un error inesperado.');
					}
					
				}else{
					Yii::app()->user->setFlash('warning','No puede pagar mas de lo pendiente.');
				}
			break;
			
		}// termina el switch

		// Redireccionamos de la pagina de donde viene
		$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
		$this->redirect($urlfrom);
	}

	public function actionIndex()
	{
		$fechainicio = (isset($_GET['fechainicio']))?$_GET['fechainicio']:$this->_data_first_month_day(); 	
		$fechafin = (isset($_GET['fechafin']))?$_GET['fechafin']:$this->_data_last_month_day();

		// Obtenemos todos los egresos por el rango de fechas
		$params = 'contabilidad_egresos_fechaalta between "'.$fechainicio.' 00:00:00"  and "'.$fechafin.' 23:59:59" ';
		$ListaEgresos = Contabilidadegresos::model()->findAll($params);

		

		$this->render('index',
				array(
					'ListaEgresos'=>$ListaEgresos,
					'fechainicio'=>$fechainicio,
					'fechafin'=>$fechafin
					)
			);
	}

	public function actionPendientespago()
	{
		// Obtenemos las ordenes de compra finalizadas o cerradas y con total pendiente.
		$ListaOC = Ordenesdecompra::model()->findAll('ordendecompra_estatus in(2,4) and ordendecompra_totalpendiente>0');

		// Lista de empleados pendientes de pago.
		$Listaempleados = Proyectosempleados::model()->findAll('abierto=0 and proyectos_empleados_totalpendiente>0');

		// modelo de contabilidad egresos
		$model=new Contabilidadegresos;

		// Lista de bancos
		$ListaBancos = CHtml::listData(Bancos::model()->findAll(), 'id_banco','banco_nombre');

		// Lista de formas de pago
		$ListaFormasPago = CHtml::listData(Formasdepago::model()->findAll(), 'id_formapago','formapago_nombre');

		// Lista de monedas
		$ListaMonedas = CHtml::listData(Monedas::model()->findAll(), 'id_moneda','moneda_nombre');

		$this->render('pendientespago',
				array(
					'ListaOC'=>$ListaOC,
					'ListaBancos'=>$ListaBancos,
					'ListaFormasPago'=>$ListaFormasPago,
					'ListaMonedas'=>$ListaMonedas,
					'Listaempleados'=>$Listaempleados,
					'model'=>$model
					)
			);
	}

	public function AgregarPago($ArrayDatos)
	{
		// Insertamos el movimiento en contabilidadegresos
		$agregar=new Contabilidadegresos;
		$agregar->attributes=$ArrayDatos;
		$agregar->id_usuario = Yii::app()->user->id;
		$agregar->contabilidad_egresos_fechaalta=date('Y-m-d H:i:s');
		if($agregar->save())
		{
			Yii::app()->user->setFlash('success','Se guardo correctamente.');
		}else{
			Yii::app()->user->setFlash('warning','ERROR, ocurrio un error inesperado.');
		}	

	}



	
}