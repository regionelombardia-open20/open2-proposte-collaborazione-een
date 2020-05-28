<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    Open20Package
 * @category   CategoryName
 */
/**@var $model \open20\amos\een\models\EenExprOfInterest */

$form = \open20\amos\core\forms\ActiveForm::begin();
?>

    <div class="col-xs-12 nop">
        <h2><?= $model->eenPartnershipProposal->content_title ?></h2>
        <i><?= \open20\amos\een\AmosEen::t('amoseen','#code_proposal_partnership:') . ' ' . $model->eenPartnershipProposal->reference_external ?></i>
    </div>
    <?php echo $form->field($model, 'een_staff_id')->widget(\kartik\select2\Select2::className(),[
        'data' => \yii\helpers\ArrayHelper::map(\open20\amos\een\models\EenStaff::find()->all(), 'id', 'user.userProfile.nomeCognome'),
        'options' => ['placeholder' => \open20\amos\een\AmosEen::t('amoseen','Select...')],
        'pluginOptions' => [
            'allowClear' => true,
        ]
    ])?>
<?= \open20\amos\core\forms\CloseSaveButtonWidget::widget(['model' => $model]); ?>


<?php \open20\amos\core\forms\ActiveForm::end(); ?>