<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    Open20Package
 * @category   CategoryName
 */

use open20\amos\een\AmosEen;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

$this->title = AmosEen::t('amoseen', 'Pubblica una proposta di collaborazione');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="proposte-collaborazione-een-index">
    <?php $form = \open20\amos\core\forms\ActiveForm::begin() ?>

    <div class="col-xs-12 m-b-10">
        <p><?= AmosEen::t('amoseen', "Completa il form e ti metteremo in contatto con il centro della Enterprise Europe Network più vicino a te che ti fornirà tutte le informazioni necessarie e ti assisterà nella pubblicazione della proposta di collaborazione.") ?>
        </p>
    </div>
    <div class="col-xs-12">

        <div class="row">
            <div class="col-lg-6 col-sm-6">
                <?= $form->field($model, 'een_network_node_id')->widget(Select2::className(), [
                    'data' => ArrayHelper::map(\open20\amos\een\models\EenNetworkNode::find()->all(), 'id', 'name'),
                    'options' => [
                        'id' => 'area-id',
                        'placeholder' => AmosEen::t('amoseen', 'Select...')],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ]
                ])->label($model->getLabels()['een_network_node_id']); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-sm-12">
                <?= $form->field($model, 'text')->textarea(['rows' => 5])
                    ->label($model->getLabels()['text']); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 col-sm-6">
                <?= $form->field($model, 'interestedTo')
                    ->radioList([
                        '1' => AmosEen::t('amoseen', 'Collaborazioni commerciali'),
                        '2' => AmosEen::t('amoseen', 'Ricerca / Offerta di tecnologie innovative'),
                        '3' => AmosEen::t('amoseen', 'partecipazione a programmi internazionali di ricerca'),
                    ], [])
                    ->label($model->getLabels()['interestedTo'] ."<span class'text-danger'> *</span>",
                        ['class' => 'no-asterisk control-label']) ?>
            </div>
        </div>
    </div>
    <div class="col-xs-12">
        <?= \yii\helpers\Html::submitButton(AmosEen::t('amoseen', "Invia proposta"), ['class' => 'btn btn-navigation-primary pull-right'])?>
    </div>
    <?php \open20\amos\core\forms\ActiveForm::end() ?>
</div>


