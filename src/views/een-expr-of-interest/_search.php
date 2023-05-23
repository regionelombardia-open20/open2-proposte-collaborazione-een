<?php

use open20\amos\core\helpers\Html;
use yii\widgets\ActiveForm;
use open20\amos\een\AmosEen;

/**
* @var yii\web\View $this
* @var backend\models\EenExprOfInterestSearch $model
* @var yii\widgets\ActiveForm $form
*/
?>

<div class="col-xs-12 een-expr-of-interest-search element-to-toggle" data-toggle-element="form-search">
    <div class="col-xs-12"><h2>Cerca per:</h2></div>

    <?php $form = ActiveForm::begin([
        'action' => Yii::$app->controller->action->id,
        'method' => 'get',
        'options' => [
            'class' => 'default-form'
        ]
    ]);

    echo Html::hiddenInput("enableSearch", "1");
    echo Html::hiddenInput("currentView", Yii::$app->request->getQueryParam('currentView'));
    ?>



    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'reference_external')->label(AmosEen::t('amoseen', '#reference_external')) ?>
    </div>
    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'title_proposal')->label(AmosEen::t('amoseen', '#title_proposal')) ?>
    </div>
    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'type_expr_of_interest')->widget(\kartik\select2\Select2::className(),[
            'data' => [
                0 => \open20\amos\een\AmosEen::t('amoseen', '#expr_of_interest'),
                1 => \open20\amos\een\AmosEen::t('amoseen', '#request_info'),
            ],
            'options' => [
                'placeholder' => \open20\amos\een\AmosEen::t('amoseen','Select...')],
            'pluginOptions' => [
                'allowClear' => true,
            ]
        ])->label(AmosEen::t('amoseen', '#type_expr_of_interest')) ?>
    </div>
    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'date_from')->widget(\kartik\datecontrol\DateControl::className(), [
            'type' => \kartik\datecontrol\DateControl::FORMAT_DATE
        ])->label(AmosEen::t('amoseen', '#date_from')) ?>
    </div>
    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'date_to')->widget(\kartik\datecontrol\DateControl::className(), [
            'type' => \kartik\datecontrol\DateControl::FORMAT_DATE
        ])->label(AmosEen::t('amoseen', '#date_to'))  ?>
    </div>
    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'statusSearch')->widget(\kartik\select2\Select2::className(),[
            'data' => [
                \open20\amos\een\models\EenExprOfInterest::EEN_EXPR_WORKFLOW_STATUS_SUSPENDED => \open20\amos\een\AmosEen::t('amoseen', 'Suspended'),
                \open20\amos\een\models\EenExprOfInterest::EEN_EXPR_WORKFLOW_STATUS_CLOSED => \open20\amos\een\AmosEen::t('amoseen', 'Closed'),
                \open20\amos\een\models\EenExprOfInterest::EEN_EXPR_WORKFLOW_STATUS_TAKENOVER=> \open20\amos\een\AmosEen::t('amoseen', 'Taken over'),
            ],
            'options' => [
                'placeholder' => \open20\amos\een\AmosEen::t('amoseen','Select...')],
            'pluginOptions' => [
                'allowClear' => true,
            ]
        ])->label(AmosEen::t('amoseen','#status')) ?>
    </div>

    <?php
    $currentUrl = explode("?", \yii\helpers\Url::current());
    if($currentUrl[0] == '/een/een-expr-of-interest/index-received') { ?>
        <div class="col-sm-6 col-lg-4">
            <?php  echo $form->field($model, 'nome') ?>
        </div>
        <div class="col-sm-6 col-lg-4">
            <?php  echo $form->field($model, 'cognome') ?>
        </div>
    <?php } ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'city') ?>

    <?php // echo $form->field($model, 'postal_code') ?>

    <?php // echo $form->field($model, 'web_site') ?>

    <?php // echo $form->field($model, 'contact_person') ?>

    <?php // echo $form->field($model, 'phone') ?>

    <?php // echo $form->field($model, 'fax') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'technology_interest') ?>

    <?php // echo $form->field($model, 'organization_presentation') ?>

    <?php // echo $form->field($model, 'privacy') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'deleted_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'deleted_by') ?>

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::a(\open20\amos\een\AmosEen::tHtml('amoseen', 'Annulla'), [Yii::$app->controller->action->id, 'currentView' => Yii::$app->request->getQueryParam('currentView')],
                ['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton(\open20\amos\een\AmosEen::tHtml('amoseen', 'Search'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>
<!--a><p class="text-center">Ricerca avanzata<br>
            < ?=AmosIcons::show('caret-down-circle');?>
        </p></a-->
    <?php ActiveForm::end(); ?>

</div>
<div class="clearfix"></div>

