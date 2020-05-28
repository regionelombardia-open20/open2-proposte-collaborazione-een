<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\views\email
 * @category   CategoryName
 */

use open20\amos\core\forms\ItemAndCardHeaderWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\interfaces\ContentModelInterface;
use open20\amos\core\interfaces\ViewModelInterface;
use open20\amos\core\record\Record;

/**
 * @var Record|ContentModelInterface|ViewModelInterface $model
 * @var \open20\amos\admin\models\UserProfile $profile
 */

if (!empty($profile)) {
    $this->params['profile'] = $profile;
}

$nTagUser = \open20\amos\cwh\models\CwhTagOwnerInterestMm::find()
    ->andWhere(['record_id' => $profile->id])
    ->andWhere(['classname' => \open20\amos\admin\models\UserProfile::className()])->count();
if($nTagUser == 0){
    $text =  \open20\amos\een\AmosEen::t('amoseen', 'Vuoi ricevere o pubblicare proposte di collaborazione internazionali su temi di tuo interesse? Segui <a href="{url}">questo link</a> per sapere come.',[
            //'url' =>  \Yii::$app->params['platform']['backendUrl'].'/admin/user-profile/update?id='.$profile->id,
         'url' => \Yii::$app->params['platform']['backendUrl'].'/een/een-partnership-proposal/index'
    ]);
} else {
    $text = \open20\amos\een\AmosEen::t('amoseen', "Ricevi una selezione di proposte di collaborazione da tutto il mondo sulla base degli interessi indicati nel tuo profilo: segui <a href='{url}'>questo link</a> se desideri modificarli.", [
        'url' => \Yii::$app->params['platform']['backendUrl'].'/een/een-partnership-proposal/own-interest'
    ]);
}

?>


<div style="box-sizing:border-box;padding-bottom: 5px; margin-left: 10px;">
    <h4><?= $text?></h4>
</div>

