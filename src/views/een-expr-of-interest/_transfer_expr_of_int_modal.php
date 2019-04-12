<?php
/**@var $model \lispa\amos\een\models\EenExprOfInterest */

$form = \lispa\amos\core\forms\ActiveForm::begin();
?>

    <div class="col-xs-12 nop">
        <h2><?= $model->eenPartnershipProposal->content_title ?></h2>
        <i><?= \lispa\amos\een\AmosEen::t('amoseen','#code_proposal_partnership:') . ' ' . $model->eenPartnershipProposal->reference_external ?></i>
    </div>
    <?php echo $form->field($model, 'een_staff_id')->widget(\kartik\select2\Select2::className(),[
        'data' => \yii\helpers\ArrayHelper::map(\lispa\amos\een\models\EenStaff::find()->all(), 'id', 'user.userProfile.nomeCognome'),
        'options' => ['placeholder' => \lispa\amos\een\AmosEen::t('amoseen','Select...')],
        'pluginOptions' => [
            'allowClear' => true,
        ]
    ])?>
<?= \lispa\amos\core\forms\CloseSaveButtonWidget::widget(['model' => $model]); ?>


<?php \lispa\amos\core\forms\ActiveForm::end(); ?>