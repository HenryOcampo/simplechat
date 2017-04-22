<?php

namespace app\controllers;

use yii\rest\ActiveController;
use yii\web\Response;
use yii\filters\ContentNegotiator;
use yii\db\Query;
use app\models\Message;
use app\models\User;

class MessageController extends ActiveController
{
	public $modelClass = 'app\models\Message';
	
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
	
	public function actionGetmessage($transmitter, $receiver){
		$arreglo = array();
		
		$query = new Query();
		$query->select('*')
			->from('message')
			->where(['transmitter'=>$transmitter, 'receiver'=>$receiver]);
		$rows1 = $query->all();
		
		$query = new Query();
		$query->select('*')
			->from('message')
			->where(['transmitter'=>$receiver, 'receiver'=>$transmitter]);
		$rows2 = $query->all();
		
		for($i=0; $i<count($rows1); $i++){
			array_push($arreglo, $rows1[$i] );
		}
		for($i=0; $i<count($rows2); $i++){
			array_push($arreglo, $rows2[$i] );
		}
		
		return json_encode($arreglo);
	}
	//===============================================================================================
	
	public function actionSetmessage(){
		$model = new Message();
		$model->transmitter = $_POST['transmitter'];
		$model->receiver = $_POST['receiver'];
		$model->text = $_POST['text'];
		$model->date = $_POST['date'];
		$model->type = $_POST['type'];
		$model->save();
		
		//enviando post notification al receptor
		$usuario = User::find()->where("celphone=:celphone", [":celphone" => $model->transmitter])->one();
		$usuario2 = User::find()->where("celphone=:celphone", [":celphone" => $model->receiver])->one();
		
		$title = $usuario->name;
		$body = $model->text;
		$device = $usuario2->deviceid;
		return $this->redirect("/chat/web/notification/notify?title=".$title."&body=".$body."&device=".$device);
	}
	//===============================================================================================
	
}
