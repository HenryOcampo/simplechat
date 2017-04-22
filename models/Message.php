<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "message".
 *
 * @property integer $cod_msj
 * @property string $transmitter
 * @property string $receiver
 * @property string $text
 * @property string $date
 * @property integer $type
 *
 * @property User $transmitter0
 * @property User $receiver0
 */
class Message extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['transmitter', 'receiver', 'text', 'date', 'type'], 'required'],
            [['text'], 'string'],
            [['date'], 'safe'],
            [['type'], 'integer'],
            [['transmitter', 'receiver'], 'string', 'max' => 8],
            [['transmitter'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['transmitter' => 'celphone']],
            [['receiver'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['receiver' => 'celphone']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cod_msj' => 'Cod Msj',
            'transmitter' => 'Transmitter',
            'receiver' => 'Receiver',
            'text' => 'Text',
            'date' => 'Date',
            'type' => 'Type',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransmitter0()
    {
        return $this->hasOne(User::className(), ['celphone' => 'transmitter']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceiver0()
    {
        return $this->hasOne(User::className(), ['celphone' => 'receiver']);
    }
}
