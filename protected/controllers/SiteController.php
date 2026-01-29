<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */

	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha' => array(
				'class' => 'CCaptchaAction',
				'backColor' => 0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page' => array(
				'class' => 'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		if (Yii::app()->user->isGuest) {
			$this->redirect(
				array(
					'site/login'
				)
			);
		}

		$this->redirect(
			array(
				'administracion/index'
				// 'site/login'
			)
		);

		$this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if ($error = Yii::app()->errorHandler->error) {
			if (Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}
	public function actionNoautorizado()
	{
		$this->render('noautorizado');
	}

	/**
	 * Displays the contact page
	 */
	public function actionContacto()
	{
		$model = new ContactForm;
		if (isset($_POST['ContactForm'])) {
			$model->attributes = $_POST['ContactForm'];
			if ($model->validate()) {
				$name = '=?UTF-8?B?' . base64_encode($model->name) . '?=';
				$subject = '=?UTF-8?B?' . base64_encode($model->subject) . '?=';
				$headers = "From: $name <{$model->email}>\r\n" .
					"Reply-To: {$model->email}\r\n" .
					"MIME-Version: 1.0\r\n" .
					"Content-Type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'], Yii::app()->name . ': ' . $subject, $model->body, $headers);
				Yii::app()->user->setFlash('contacto', 'Gracias por contactarnos. Responderemos a la brevedad posible.');
				$this->refresh();
			}
		}
		$this->render('contacto', array('model' => $model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$this->layout = 'login';

		// Verificamos que no tenga sesion
		if (!Yii::app()->user->isGuest) {
			$this->redirect(
				array(
					'administracion/index'
				)
			);
		}

		$model = new LoginForm;

		// if it is ajax validation request
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		//Seteamos el nombre de nuestra base de datos
		// $instancia = 'ventelia_demos';
		$instancia = 'ventelia_g8';
		// collect user input data
		// collect user input data
		if (!empty($_POST['LoginForm']['Usuario_Email']) && !empty($_POST['LoginForm']['Usuario_Password'])) {
			/*echo $instancia;
																										 exit;*/

			// Verificamos en la base de datos central si existe el correo
			// $VerificarUsuario = new WS_BD_Central();
			// $Instancia = $VerificarUsuario->IniciarSesion($_POST['LoginForm']['Usuario_Email'], $_POST['LoginForm']['Usuario_Password'], $instancia);

			// Verificamos que la instancia no este vacia
			// if ($Instancia['rspt'] == 1) {
			// dvb fiamos que use ventelia_g8
			#Yii::app()->session['basededatos'] = $Instancia['instancia'];
			Yii::app()->session['basededatos'] = 'ventelia_g8';

			$model->attributes = $_POST['LoginForm'];
			// Loguea y Redirige a Pagina principal
			if ($model->validate() && $model->login()) {
				$this->redirect(array('administracion/index'));
			} else {
				Yii::app()->user->setFlash('danger', '<strong>Error:</strong> Sus datos de inicio de sessión son incorrectos. Intente de nuevo por favor.');
			}
			// } else {
			// 	// No existe regresamos el error
			// 	Yii::app()->user->setFlash('danger', '<strong>Error:</strong> Sus datos de inicio de sessión son incorrectos. Intente de nuevo por favor.');
			// }
		}
		// display the login form
		$this->render('login', array('model' => $model));
	}


	/**
	 * Obtener municipios de una entidad 
	 */
	public function actionObtenermunicipios()
	{


		$id_entidad = $_POST['id_entidad'];
		if (empty($id_entidad)) {
			exit();
		}

		$municipios = Municipios::model()->findAll(array(
			'condition' => 'ID_Entidad=:id_entidad',
			'params' => array(':id_entidad' => $id_entidad),
			'order' => 'Municipio_Nombre'
		));


		if (count($municipios) > 0) {

			$options = '<option value="">-- Seleccione municipio --</option>';
			foreach ($municipios as $rows) {

				$options .= ' <option value="' . $rows['ID_Municipio'] . '">' . ($rows['Municipio_Nombre']) . '</option>';
			}
			#echo $options;
			echo CJSON::encode(array(
				'requestresult' => 'ok',
				'message' => 'Municipios encontrados con exito',
				'options' => $options
			));
			exit();
		} else {
			echo CJSON::encode(array(
				'requestresult' => 'fail',
				'message' => 'No se encontraron municipios'
			));
			exit();
		}
	}


	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		$empresa = Yii::app()->session['basededatos'];
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->createUrl('site/login') . '/' . $empresa);
	}

	/**
	 * This is the default 'empresa' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionEmpresa()
	{
		$ProductosNuevosLista = Productos::model()->findAll(
			array(
				"condition" => "Producto_Activo = 1",
				"order" => "Producto_FechaAlta DESC",
				"limit" => 18
			)
		);

		$this->render(
			'empresa',
			array(
				'ProductosNuevosLista' => $ProductosNuevosLista
			)
		);
	}

	/*
	 * METODO PARA INDEX DE LA ADMINISTRACION
	 */

	public function actionAdministracion()
	{

		$this->render('administracion');
	}


	/**
	 * 	LOGIN ADMINISTRADORES DE VENTELIA
	 */
	public function actionLoginadmin()
	{
		$this->layout = 'loginadmin';

		// Verificamos que no tenga sesion
		if (!Yii::app()->user->isGuest) {
			$this->redirect(
				array(
					'administracion/index'
				)
			);
		}

		$model = new LoginForm;

		// collect user input data
		// collect user input data
		if (!empty($_POST['LoginForm']['Usuario_Email']) && !empty($_POST['LoginForm']['Usuario_Password']) && $_POST['Instancia'] != '') {
			// Array de usuarios permitidos
			$email = $_POST['LoginForm']['Usuario_Email'];
			$password = $_POST['LoginForm']['Usuario_Password'];
			$db = $_POST['Instancia'];
			// Verificamos que esten bien las credenciales
			$auth = new UserIdentityadmin($email, $password);
			$auth->authenticate();
			if ($auth->errorCode === UserIdentityadmin::ERROR_NONE) {
				Yii::app()->session['basededatos'] = $db;
				Yii::app()->user->login($auth, 0);
				$this->redirect(array('administracion/index'));
			} else {
				Yii::app()->user->setFlash('danger', '<strong>Error:</strong> Sus datos de inicio de sessión son incorrectos. Intente de nuevo por favor.');
			}
		}
		// display the login form
		$this->render('login', array('model' => $model));
	}


	public function actionTestws()
	{
		$latinad = new WS_Latin();

		$resultado = $latinad->getAds();
	}
}
