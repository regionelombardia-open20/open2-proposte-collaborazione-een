<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;

$isInfo = $model->is_request_more_info;
$chiudiEoi = \lispa\amos\een\AmosEen::t('amoseen', "Chiudi il caso");
$chiudiEInfo = \lispa\amos\een\AmosEen::t('amoseen', "Chiudi il caso");

$js= <<<JS
    var closed = document.getElementById("EenExpressionOfInterestWorkflow/CLOSED");
    if($isInfo == 1){
        $(closed).text("$chiudiEInfo");
    }
    else {
        $(closed).text("$chiudiEoi");
    }
JS;
$this->registerJs($js);

/**
 * @var yii\web\View $this
 * @var \lispa\amos\een\models\EenExprOfInterest $model
 */
if ($model->is_request_more_info == 1) {
    $this->title = \lispa\amos\een\AmosEen::t('amoseen', '#request_info');
} else {
    $this->title = \lispa\amos\een\AmosEen::t('amoseen', '#expr_of_interest');

}
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="een-expr-of-interest-view post-details col-xs-12">

    <?php $form = \lispa\amos\core\forms\ActiveForm::begin(); ?>

    <div class="col-xs-12 nop">
        <?= \lispa\amos\core\forms\WorkflowTransitionWidget::widget([
            'form' => $form,
            'model' => $model,
            'workflowId' => \lispa\amos\een\models\EenExprOfInterest::EEN_EXPR_OF_INTEREST_WORKFLOW,
            'classDivIcon' => 'pull-left',
            'classDivMessage' => 'pull-left message',
            'viewWidgetOnNewRecord' => true
        ]); ?>

        <?php
        if (\Yii::$app->user->can('STAFF_EEN') && $model->status == \lispa\amos\een\models\EenExprOfInterest::EEN_EXPR_WORKFLOW_STATUS_CLOSED) {
            echo Html::a(\lispa\amos\een\AmosEen::t('amoseen', '#generate_pdf'), ['/een/een-expr-of-interest/pdf', 'id' => $model->id], [
                'class' => ['btn btn-primary pull-right m-t-15'],
                'title' => \lispa\amos\een\AmosEen::t('amoseen', '#generate_pdf')
            ]);
        }
        ?>
    </div>

    <?php if (\Yii::$app->user->can('STAFF_EEN') && $model->isLoggedUserInCharge() && $model->is_request_more_info == 0) { ?>
        <div class="col-xs-6">
            <?php echo  $form->field($model, 'sub_status')->widget(\kartik\select2\Select2::className(), [
                'data' => $model->getAvaiableSubStatus(),
                'options' => [
                    'placeholder' => \lispa\amos\een\AmosEen::t('amoseen', 'Select...')],
                'pluginOptions' => [
                    'allowClear' => true,
                ]
            ]) ?>
        </div>
    <?php } ?>
    <div class="container-general-info col-xs-12">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'user.userProfile.nomeCognome',
                'eenPartnershipProposal.reference_external',
                'eenPartnershipProposal.content_title',
                [
                    'attribute' => 'een_network_node_id',
                    'value' => function ($model) {
                        return $model->eenNetworkNode->name;
                    }
                ],
                [
                    'attribute' => 'een_staff_id',
                    'value' => function ($model) {
                        if (!empty($model->eenStaff->user->userProfile)) {
                            return $model->eenStaff->user->userProfile->nomeCognome;
                        } else return '';
                    },
                    'label' => \lispa\amos\een\AmosEen::t('amoseen', '#assigned_to'),
                ],
                [
                    'label' => \lispa\amos\een\AmosEen::t('amoseen', 'Presa in carico'),
                    'value' => function ($model) {
                        if($model->status != \lispa\amos\een\models\EenExprOfInterest::EEN_EXPR_WORKFLOW_STATUS_SUSPENDED && !empty($model->een_staff_id)){
                            return true;
                        }
                        else return false;
                    },
                    'format' => 'boolean'
                ],
                [
                    'label' => \lispa\amos\een\AmosEen::t('amoseen', '#status'),
                    'value' => function ($model) {
                        return \lispa\amos\een\AmosEen::t('amoseen', $model->workflowStatus->label);
                    }
                ],
                [
                    'label' => \lispa\amos\een\AmosEen::t('amoseen', '#sub_status'),
                    'value' => function ($model) {
                        if (!empty(\lispa\amos\een\models\EenExprOfInterest::getSubstatus()[$model->sub_status])) {
                            return \lispa\amos\een\models\EenExprOfInterest::getSubstatus()[$model->sub_status];
                        }
                    }
                ],
                [
                    'attribute' => 'created_at',
                    'format' => ['datetime', (isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A'],
                ],
                [
                    'attribute' => 'know_een',
                    'format' => 'boolean'
                ],
                [
                    'attribute' => 'note',
                    'label' => \lispa\amos\een\AmosEen::t('amoseen', '#note'),
                ],
                [
                    'attribute' => 'updated_at',
                    'format' => ['datetime', (isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A'],
                ],
            ],
            'options' => ['class' => 'table-info']

        ]) ?>
    </div>
    <br>

    <?php if ($model->is_request_more_info == 0 && $model->een_network_node_id == 1) { ?>
        <div class="container-general-info col-xs-12">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'company_organization',
                    'sector',
                    'address',
                    'city',
                    'postal_code',
                    'web_site',
                    'contact_person',
                    'phone',
                    'fax',
                    'email:email',
                    [
                        'attribute' => 'technology_interest',
                        'label' => \lispa\amos\een\AmosEen::t('amoseen', '#question_technology_interest'),
                    ],
                    [
                        'attribute' => 'organization_presentation',
                        'label' => \lispa\amos\een\AmosEen::t('amoseen', '#question_organization_presentation'),
                    ],
                    [
                        'attribute' => 'information_request',
                        'label' => \lispa\amos\een\AmosEen::t('amoseen', '#question_information_request1'),
                    ],
                ],
                'options' => ['class' => 'table-info']

            ]); ?>
        </div>
    <?php } ?>


    <div class="col-xs-12 m-t-10">
        <?php
        if (\Yii::$app->user->can('STAFF_EEN') && $model->isLoggedUserInCharge() && $model->is_request_more_info == 0) {
            echo \lispa\amos\core\forms\CloseSaveButtonWidget::widget([
                'model' => $model,
                'urlClose' => Url::previous(),
                'closeButtonLabel' => \lispa\amos\een\AmosEen::t('amoseen', 'Back to list')

            ]);
        } else {
            echo Html::a(\lispa\amos\een\AmosEen::t('amoseen', 'Back to list'), Url::previous(), ['class' => 'btn btn-secondary pull-right']);
        } ?>
    </div>

    <?php \lispa\amos\core\forms\ActiveForm::end(); ?>

</div>
