<?php

namespace lispa\amos\een\models\base;

use Yii;

/**
* This is the base-model class for table "een_network_node".
*
    * @property integer $id
    * @property string $name
    * @property integer $description
    * @property string $created_at
    * @property string $updated_at
    * @property string $deleted_at
    * @property integer $created_by
    * @property integer $updated_by
    * @property integer $deleted_by
    *
            * @property \backend\modules\pateradmin\models\EenStaff[] $eenStaff
    */
class EenNetworkNode extends \lispa\amos\core\record\Record
{


/**
* @inheritdoc
*/
public static function tableName()
{
return 'een_network_node';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['name'], 'required'],
            [['description', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => Yii::t('amoseen', 'ID'),
    'name' => Yii::t('amoseen', 'Name'),
    'description' => Yii::t('amoseen', 'Description'),
    'created_at' => Yii::t('amoseen', 'Created at'),
    'updated_at' => Yii::t('amoseen', 'Updated at'),
    'deleted_at' => Yii::t('amoseen', 'Deleted at'),
    'created_by' => Yii::t('amoseen', 'Created by'),
    'updated_by' => Yii::t('amoseen', 'Updated at'),
    'deleted_by' => Yii::t('amoseen', 'Deleted at'),
];
}

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getEenStaff()
    {
    return $this->hasMany(\backend\modules\pateradmin\models\EenStaff::className(), ['een_network_node_id' => 'id']);
    }
}
