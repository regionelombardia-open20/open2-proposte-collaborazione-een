<?php
$profile = \lispa\amos\admin\models\UserProfile::find()->andWhere(['user_id' => \Yii::$app->user->id])->one();
$link = "/admin/user-profile/update?id=".$profile->id;?>
<p><?= \lispa\amos\een\AmosEen::t('amoseen', '#info_een2', ['link_profilo' => $link])?></p>