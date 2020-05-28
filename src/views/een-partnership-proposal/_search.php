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
use kartik\datecontrol\DateControl;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var open20\amos\een\models\search\EenPartnershipProposalSearch $model
 * @var yii\widgets\ActiveForm $form
 */
$moduleTag = \Yii::$app->getModule('tag');
?>

<div class="<?= Yii::$app->controller->id ?>-search element-to-toggle" data-toggle-element="form-search">
    
    <?php
    $form = ActiveForm::begin([
        'action' => Yii::$app->controller->action->id,
        'method' => 'get',
        'options' => [
            'class' => 'default-form'
        ]
    ]);
    ?>
    
    <?= Html::hiddenInput("enableSearch", "1") ?>

    <div class="col-xs-12">
        <h2 class="title">
            <?= AmosEen::tHtml('amoseen', 'Search by'); ?>:
        </h2>
    </div>

    <div class="col-md-4">
        <?= $form->field($model, 'content_title')->textInput(['placeholder' => AmosEen::t('amoseen', 'Search by title')]) ?>
    </div>

    <div class="col-md-4">
        <?= $form->field($model, 'content_summary')->textInput(['placeholder' => AmosEen::t('amoseen', 'Search by summary')]) ?>
    </div>

    <div class="col-md-4">
        <?= $form->field($model, 'content_description')->textInput(['placeholder' => AmosEen::t('amoseen', 'Search by description')]) ?>
    </div>

    <div class="col-md-4">
        <?= $form->field($model, 'company_country_label')->widget(\kartik\widgets\Select2::className(),[
            'data' => $countryTypes,
            'options' => ['placeholder' => AmosEen::t('amoseen', 'Select...')],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]) ?>
    </div>

    <div class="col-md-4">
        <?= $form->field($model, 'reference_external')->textInput(['placeholder' => AmosEen::t('amoseen', 'Search by external reference')]) ?>
    </div>

    <div class="col-md-4">
    <?= $form->field($model, 'reference_type')
        ->dropDownList(
            $model->getReferenceTypes(),
            ['prompt' => AmosEen::t('amoseen', 'Search by reference type')]
        ) ?>
    </div>

<!--    <div class="col-md-4">-->
<!--        --><?php //echo $form->field($model, 'datum_submit_from')->widget(DateControl::className(), [
//            'type' => DateControl::FORMAT_DATE
//        ]) ?>
<!--    </div>-->
<!---->
<!--    <div class="col-md-4">-->
<!--        --><?php //echo $form->field($model, 'datum_submit_to')->widget(DateControl::className(), [
//            'type' => DateControl::FORMAT_DATE
//        ]) ?>
<!--    </div>-->
<!---->
<!--    <div class="col-md-4">-->
<!--        --><?php //echo $form->field($model, 'datum_deadline_from')->widget(DateControl::className(), [
//            'type' => DateControl::FORMAT_DATE
//        ]) ?>
<!--    </div>-->
<!---->
<!--    <div class="col-md-4">-->
<!--        --><?php //echo $form->field($model, 'datum_deadline_to')->widget(DateControl::className(), [
//            'type' => DateControl::FORMAT_DATE
//        ]) ?>
<!--    </div>-->

    <div class="col-md-4">
        <?= $form->field($model, 'general_search')->label(AmosEen::t('amoseen', 'Ricerca libera')) ?>
    </div>

    <?php if (isset($moduleTag) && in_array(\open20\amos\een\models\EenPartnershipProposal::className(), $moduleTag->modelsEnabled) && $moduleTag->behaviors): ?>
        <div class="col-xs-12">
            <label><?= AmosEen::t('amoseen', 'Keyword tecnologica')?> </label>

            <?php
            $params = \Yii::$app->request->getQueryParams();
            echo \open20\amos\tag\widgets\TagWidget::widget([
                'model' => $model,
                'attribute' => 'tagValues',
                'hideHeader' => true,
                'form' => $form,
                'isSearch' => true,
                'singleFixedTreeId' => 3,
                'form_values' => isset($params[$model->formName()]['tagValues']) ? $params[$model->formName()]['tagValues'] : []
            ]);
            ?>
        </div>
    <?php endif; ?>



    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::resetButton(AmosEen::tHtml('amoseen', 'Reset'), ['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton(AmosEen::tHtml('amoseen', 'Search'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>
    <!--a><p class="text-center">Advanced search<br>
            < ?=AmosIcons::show('caret-down-circle');?>
        </p></a-->
    <?php ActiveForm::end(); ?>
</div>
