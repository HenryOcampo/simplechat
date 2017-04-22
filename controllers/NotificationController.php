<?php

namespace app\controllers;

use sngrl\PhpFirebaseCloudMessaging\Client;
use sngrl\PhpFirebaseCloudMessaging\Message;
use sngrl\PhpFirebaseCloudMessaging\Recipient\Device;
use sngrl\PhpFirebaseCloudMessaging\Notification;


class NotificationController extends \yii\web\Controller
{
	public function actionNotify($title, $body, $device){
		$server_key = 'AAAAdayICMY:APA91bHy113wvRPByLf9uhmEESRevlLLn7YHTpfDN_n3yguy353SsWjQRJ4Wp43VPB9YPAdJKQzRbbuFI3OTbbMZLBa0xMl7eP1EA6QK37syScrkog07NOP2QvQzEvU1ttdF27Bb8y8w';
		$client = new Client();
		$client->setApiKey($server_key);
		$client->injectGuzzleHttpClient(new \GuzzleHttp\Client());

		$message = new Message();
		$message->setPriority('high');
		$message->addRecipient(new Device( $device ));
		$message
			->setNotification(new Notification($title, $body))
			->setData(['key' => 'value'])
		;

		$response = $client->send($message);
		var_dump($response->getStatusCode());
		var_dump($response->getBody()->getContents());
	}
	
	public function actionIndex()
    {
        return $this->render('index');
    }

}
