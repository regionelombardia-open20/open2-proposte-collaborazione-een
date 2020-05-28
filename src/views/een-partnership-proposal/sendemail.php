<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\een\views\een-partnership-proposal
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\een\AmosEen;


/**
 * @var yii\web\View $this
 * @var open20\amos\een\models\search\EenPartnershipProposalSearch $model
 * @var yii\widgets\ActiveForm $form
 */


?>

<div >

    <div >
        <h2 class="title">
            <?= AmosEen::t('amoseen', '#mailsended'); ?>
        </h2>
    </div>
    <a class= 'btn btn-navigation-primary' href="<?= Yii::$app->urlManager->createUrl(['/' . AmosEen::getModuleName() .'/een-partnership-proposal']); ?>" title="<?= AmosEen::t('amoseen', '#return') ?>"><?= AmosEen::t('amoseen', '#return') ?></a>
</div>
