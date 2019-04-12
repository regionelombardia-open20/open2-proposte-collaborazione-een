<?php

namespace lispa\amos\een\models;

use lispa\amos\admin\models\UserProfile;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "een_staff".
 */
class EenStaff extends \lispa\amos\een\models\base\EenStaff
{
    public function representingColumn()
    {
        return [
            //inserire il campo o i campi rappresentativi del modulo
        ];
    }

    public function attributeHints()
    {
        return [
        ];
    }

    /**
     * Returns the text hint for the specified attribute.
     * @param string $attribute the attribute name
     * @return string the attribute hint
     */
    public function getAttributeHint($attribute)
    {
        $hints = $this->attributeHints();
        return isset($hints[$attribute]) ? $hints[$attribute] : null;
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
        ]);
    }

    public function attributeLabels()
    {
        return
            ArrayHelper::merge(
                parent::attributeLabels(),
                [
                ]);
    }

    /**
     * @return mixed
     */
    public static function getProfileStaffDefault(){
        $staff_default = UserProfile::find()
            ->innerJoin('een_staff', 'user_profile.user_id = een_staff.user_id')
            ->andWhere(['een_staff.staff_default' => 1])->one();
        return $staff_default;
    }

    public function deleteStaffMemeber(){

    }

}
