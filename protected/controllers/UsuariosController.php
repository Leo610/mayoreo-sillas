<?php

class UsuariosController extends Controller
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = '//layouts/column2';

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
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreateorUpdate()
	{
		$id = $_POST['Usuarios']['ID_Usuario'];
		$model = $this->loadModel($id);
		if (empty($model)) { // Si el model esta vacio, quiere decir que es un insert nuevo. en caso contrario update
			$model = new Usuarios;
			if (isset($_POST['Usuarios'])) {
				$model->attributes = $_POST['Usuarios'];
				$model->Usuario_Password = sha1($_POST['Usuarios']['Usuario_Password']);
				$model->level = 99;
				if ($model->save()) {
					// Una ves registrado el usuario, lo insertamos en la base de datos central.
					// Verificamos en la base de datos central si existe el correo
					// $VerificarUsuario = new WS_BD_Central();
					// $VerificarUsuario->AgregarUsuario(
					// 	Yii::app()->session['basededatos'],
					// 	$_POST['Usuarios']['Usuario_Email'],
					// 	$_POST['Usuarios']['Usuario_Password'],
					// 	0
					// );
					Yii::app()->user->setFlash('success', 'Se guardo correctamente.');
				} else {
					Yii::app()->user->setFlash('warning', $this->ObtenerError($model->geterrors()));
				}
			} else {
				Yii::app()->user->setFlash('warning', 'Sin datos para guardar.');
			}
		} // end empty($model)
		else {
			if (isset($_POST['Usuarios'])) {
				$datosactuales = $model;
				$model->attributes = $_POST['Usuarios'];
				if ($datosactuales['id_usuario_padre'] == $datosactuales['ID_Usuario']) {
					$model['id_usuario_padre'] = 0;
				}
				if ($model->save()) {
					Yii::app()->user->setFlash('success', 'Se actualizo correctamente el usuario.');
				} else {
					Yii::app()->user->setFlash('warning', 'No se guardo correctamente .');
				}
			}
		}
		// Redireccionamos de la pagina de donde viene
		$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
		$this->redirect($urlfrom);
	}
	/**
	 * METODO PARA ACTUALZIAR EL PASSWORD DEL USUARIO
	 */
	public function actionActualizarpssword()
	{
		$id = $_POST['Usuarios']['ID_Usuario'];
		$model = $this->loadModel($id);
		$model->Usuario_Password = sha1($_POST['Usuarios']['Usuario_Password']);
		if ($model->save()) {
			// $VerificarUsuario = new WS_BD_Central();
			// /*
			// 																							  Actualizarpswusinst($bd,$emailnuevo,$emailanterior,$ps)
			// 																						  */
			// $VerificarUsuario->Actualizarpswusinst(
			// 	Yii::app()->session['basededatos'],
			// 	$_POST['Usuarios']['Usuario_Email'],
			// 	$_POST['Usuarios']['Usuario_Email'],
			// 	$_POST['Usuarios']['Usuario_Password']
			// );
			Yii::app()->user->setFlash('success', 'Se actualizo correctamente la contraseña.');
		} else {
			Yii::app()->user->setFlash('warning', 'No se guardo correctamente .');
		}
		// Redireccionamos de la pagina de donde viene
		$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
		$this->redirect($urlfrom);
	}


	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$usuario = $this->loadModel($id);
		$usuario->eliminado = 1;
		$usuario->save();
		// $VerificarUsuario = new WS_BD_Central();
		// /*
		// 																Actualizarpswusinst($bd,$emailnuevo,$emailanterior,$ps)
		// 															*/
		// $VerificarUsuario->Eliminarusinst(
		// 	Yii::app()->session['basededatos'],
		// 	$usuario['Usuario_Email']
		// );
		Yii::app()->user->setFlash('success', 'Se elimino con exito.');
		// Redireccionamos de la pagina de donde viene
		$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
		$this->redirect($urlfrom);
	}



	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		#print_r(explode('=', Yii::app()->db->connectionString));exit;
		$Equipo = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente=6');
		$listaequipo = CHtml::listData($Equipo, 'id_catalogo_recurrente', 'nombre');

		$Mercado = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente=7');
		$listamercado = CHtml::listData($Mercado, 'id_catalogo_recurrente', 'nombre');

		$Ubicacion = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente=8');
		$listaubicacion = CHtml::listData($Ubicacion, 'id_catalogo_recurrente', 'nombre');

		$Zona = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente=26');
		$listazona = CHtml::listData($Zona, 'id_catalogo_recurrente', 'nombre');

		// traemos las bodegas
		$bodega = CatalogosRecurrentes::model()->findAll('id_grupo_recurrente = 28');
		$listabodega = CHtml::listData($bodega, 'id_catalogo_recurrente', 'nombre');

		$ListaPerfiles = CHtml::listData(Perfiles::model()->findAll(), 'id_perfil', 'nombre');

		$ListaUsuarios = CHtml::listData(Usuarios::model()->findAll('eliminado = 0'), 'ID_Usuario', 'Usuario_Nombre');


		$this->layout = "main"; // Utilizamos el layout para administracion
		$lista = Usuarios::model()->findAll('eliminado = 0');

		$listamodulos = Modulos::model()->findAll();

		$model = new Usuarios();

		$this->render(
			'admin',
			array(
				'model' => $model,
				'lista' => $lista,
				'listamodulos' => $listamodulos,
				'listaequipo' => $listaequipo,
				'listamercado' => $listamercado,
				'listaubicacion' => $listaubicacion,
				'ListaUsuarios' => $ListaUsuarios,
				'listazona' => $listazona,
				'ListaPerfiles' => $ListaPerfiles,
				'bodega' => $listabodega
			)
		);
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Usuarios the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model = Usuarios::model()->findByPk($id);
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Usuarios $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'usuarios-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	/**
	 * REGRESA LOS DATOS DE USUARIO
	 * @param ID CATEGORIA
	 */
	public function actionUsuariodatos()
	{
		$id = $_POST['id'];
		$Datos = Usuarios::model()->findBySQL('select ID_Usuario, Usuario_Nombre, id_perfil, equipo_venta, ubicacion, mercado, zona, id_usuario_padre, Usuario_Email,bodega from usuarios where ID_Usuario =' . $id);


		// Obtenemos los permisos del usuario

		echo CJSON::encode(
			array(
				'requestresult' => 'ok',
				'Datos' => $Datos,
				'message' => 'Informacion encontrada'
			)
		);
	}

	public function actionAgregarpermiso()
	{
		// Obtenemos las variables
		$ID_Usuario = $_POST['id_usuario'];
		$ID_Modulo = $_POST['id_modulo'];
		$ValorPermiso = $_POST['valor'];

		// Verificamos que el permiso no exista mediante id usuario y id modulo
		$Permiso = Permisosmodulos::model()->find('id_modulos=' . $ID_Modulo . ' and id_usuario=' . $ID_Usuario);
		// Verificamos
		if (empty($Permiso)) {
			// Realizamos un insert
			$Agregarpermiso = new Permisosmodulos;
			$Agregarpermiso->id_modulos = $ID_Modulo;
			$Agregarpermiso->id_usuario = $ID_Usuario;
			$Agregarpermiso->permisos_modulos_permitido = $ValorPermiso;
			if ($Agregarpermiso->save()) {
				echo CJSON::encode(
					array(
						'requestresult' => 'ok',
						'message' => 'Se guardo con exito el permiso.'
					)
				);
			} else {
				echo CJSON::encode(
					array(
						'requestresult' => 'fail',
						'message' => 'Ocurrio un errror con el permiso.'
					)
				);
			}
		} else {
			// Realizamos un update
			$Permiso->permisos_modulos_permitido = $ValorPermiso;
			if ($Permiso->save()) {
				echo CJSON::encode(
					array(
						'requestresult' => 'ok',
						'message' => 'Se guardo con exito el permiso.'
					)
				);
			} else {
				echo CJSON::encode(
					array(
						'requestresult' => 'fail',
						'message' => 'Ocurrio un errror con el permiso.'
					)
				);
			}
		}
	} // TERMINA	public function actionagregarPermiso()

}
