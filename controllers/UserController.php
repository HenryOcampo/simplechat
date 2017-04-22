<?php

namespace app\controllers;

use yii\rest\ActiveController;
use yii\web\Response;
use yii\filters\ContentNegotiator;
use yii\db\Query;
use app\models\User;

class UserController extends ActiveController
{
	public $modelClass = 'app\models\User';
	
	public function behaviors()
	{
		return [
			[
				'class' => ContentNegotiator::className(),
				'only' => ['index', 'view'],
				'formats' => [
					'application/json' => Response::FORMAT_JSON,
				],
			],
		];
	}
	//===============================================================================================
	
	public function actionGetusers(){
		$lista = json_decode( $_POST["cellphones"] );
		$listaInterseccion = array();
		$usuario = null;
		
		for($i=0; $i<count($lista); $i++){
			$usuario = User::find()->where("celphone=:celphone", [":celphone" => $lista[$i]])->one();
			if( $usuario != null ){
				array_push($listaInterseccion, 
					['celphone'=>$usuario->celphone, 
					 'name'=>$usuario->name,
					 'photo'=>$usuario->photo,
					 'state'=>$usuario->state,
				]);
			}
			$usuario=null;
		}
		
		return json_encode($listaInterseccion);
	}
	//===============================================================================================
	
	public function actionSetuser(){
		//\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$model = new User();
		$model->celphone = $_POST['celphone'];
		$model->name = $_POST['name'];
		$model->photo = $_POST['photo'];
		$model->password = $_POST['password'];
		$model->state = $_POST['state'];
		$model->deviceid = $_POST['deviceid'];
		$model->save();
		return "true";
	}
	//===============================================================================================
	
	public function actionUpdateuser(){
		$celphone = $_POST['celPhone'];
		$devive = $_POST['device'];
		$usuario = User::find()->where("celphone=:celphone", [":celphone" => $celPhone ])->one();
		$usuario->updateAttributes(['deviceid'=>$device]);
	}
	//===============================================================================================
	
	public function actionLogin(){
		$usuario = null;
		$usuario = User::find()->where("celphone=:celphone", [":celphone" => $_POST['celphone'] ])
							   ->andWhere("password=:password", [":password" => $_POST['password'] ])
							   ->one();
		if($usuario!=null){	
			if( strcmp($usuario->celphone, $_POST['celphone'])==0 && strcmp($usuario->password, $_POST['password'])==0){
				return "true";
			}else{
				return "false";
			}
		}else{
			return "false";
		}
	}
	//===============================================================================================
	
}
