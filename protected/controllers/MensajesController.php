<?php

class MensajesController extends Controller
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
	public function actionCrear()
	{


		if (isset($_POST['Mensajes'])) {
			/*$model->attributes=$_POST['Mensajes'];	*/
			$asunto = $_POST['Mensajes']['asunto'];
			$mensaje = $_POST['Mensajes']['mensaje'];
			// foreach para enviar mensjes multiples
			$a = 1;
			$id_mensaje_padre = 0;

			foreach ($_POST['id_destinatario'] as $destinatario) {

				$model = new Mensajes;
				$model->asunto = $asunto;
				$model->mensaje = $mensaje;
				$model->id_destinatario = $destinatario;
				$model->id_remitente = Yii::app()->user->id; // quien envia el mensaje, este es el usuario en sesion
				$model->fecha_alta = date('Y-m-d H:i:s');
				$model->id_mensaje_padre = $id_mensaje_padre;
				if ($model->save()) {
					$mensajecorreo = 'Mensaje recibido de mayoreodesillas.com:
										<br><br>' . $mensaje . '<br><br>
									Mensaje enviado por: ' . $model['rl_destinatario']['Usuario_Nombre'] . ' 
									(' . $model['rl_destinatario']['Usuario_Email'] . ')';
					// Enviamos correo de aviso al usuario.
					Yii::import('ext.yii-mail.YiiMailMessage');
					$message = new YiiMailMessage;
					$message->contentType = "text/html";
					$message->subject = 'Mensaje recibido de mayoreodesillas.com: ' . $asunto;
					$message->setBody($mensajecorreo, 'text/html');
					$message->addTo($model['rl_destinatario']['Usuario_Email'], $model['rl_destinatario']['Usuario_Nombre']);
					$message->setFrom(array($model['rl_remitente']['Usuario_Email'] => $model['rl_remitente']['Usuario_Nombre']));
					$message->from = Yii::app()->params['frommail'];
					// if($model['rl_destinatario']['Usuario_Email']!='' && $model['rl_remitente']['Usuario_Email']!='')
					// {
					Yii::app()->mail->send($message);
					// }


					Yii::app()->user->setFlash('success', "Se envio con exito el correo.");
					// Termina
				} else {
					print_r($model->getErrors());
					Yii::app()->user->setFlash('error', "Ocurrio un error inesperado.");

				}
				if ($a == 1) {
					$id_mensaje_padre = $model->id;
				}
				$a++;

			}
			// termina
		}
		/*$this->redirect(Yii::app()->createUrl('administracion/mensajesenviados'));*/
		// Redireccionamos de la pagina de donde viene
		$urlfrom = Yii::app()->getRequest()->getUrlReferrer();
		$this->redirect($urlfrom);
	}

	public function actionActualizarestatus()
	{
		$id = $_POST['id'];
		$nuevovalor = $_POST['nuevovalor'];

		$model = $this->loadModel($id);
		$model->estatus = $nuevovalor;
		if (!empty($id)) {
			$model->save();
			echo CJSON::encode(
				array(
					'requestresult' => 'ok',
					'message' => 'Estatus actualizado',
				)
			);

		} else {

			echo CJSON::encode(
				array(
					'requestresult' => 'error',
					'message' => 'Error, favor de verificar',
				)
			);

		}

	}

	public function loadModel($id)
	{
		$model = Mensajes::model()->findByPk($id);
		return $model;
	}

	public function actionDatos()
	{
		$id = $_POST['id'];
		$Datos = Mensajes::model()->findBypk($id);

		$model = $this->loadModel($id);

		echo CJSON::encode(
			array(
				'requestresult' => 'ok',
				'Datos' => $Datos,
				'model' => $model
			)
		);
	}


}