<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\een\views\een-partnership-proposal
 * @category   CategoryName
 */

use lispa\amos\core\helpers\Html;
use lispa\amos\een\AmosEen;


/**
 * @var yii\web\View $this
 * @var lispa\amos\een\models\search\EenPartnershipProposalSearch $model
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
