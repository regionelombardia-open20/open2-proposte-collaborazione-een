<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    Open20Package
 * @category   CategoryName
 */

/** @var $model \open20\amos\een\models\EenExprOfInterestHistory */
\yii\bootstrap\Modal::begin([
    'header' => '<h2>'.\open20\amos\een\AmosEen::t('amoseen','#history').'</h2>',
    'size' => \yii\bootstrap\Modal::SIZE_LARGE,
    'toggleButton' => ['label' => \open20\amos\core\icons\AmosIcons::show('time'), 'title' => \open20\amos\een\AmosEen::t('amoseen','#history'), 'class' => 'btn btn-tool-secondary'],
]);
?>

<table class="table">
    <thead>
    <tr>
        <th scope="col"><?= \open20\amos\een\AmosEen::t('amoseen', 'Data e ora')?></th>
        <th scope="col"><?= \open20\amos\een\AmosEen::t('amoseen', 'Uscito da stato')?></th>
        <th scope="col"><?= \open20\amos\een\AmosEen::t('amoseen', 'Entrato in stato')?></th>
        <th scope="col"><?= \open20\amos\een\AmosEen::t('amoseen', 'In carico a - precedente')?></th>
        <th scope="col"><?= \open20\amos\een\AmosEen::t('amoseen', 'In carico a - corrente')?></th>
        <th scope="col"><?= \open20\amos\een\AmosEen::t('amoseen', 'Operazione effettuata da')?></th>
    </tr>
    </thead>
    <tbody>

    <?php
    foreach ($model->eenExprOfInterestHistory as $status_transition):
        $wfStatusStart = $status_transition->start_status;
        $wfStatusStartExploded = explode('/', $wfStatusStart);
        $wfStatusEnd = $status_transition->end_status;
        $wfStatusEndExploded = explode('/', $wfStatusEnd);
        $objLabelStart = \cornernote\workflow\manager\models\Status::find()->andWhere(['id' => isset($wfStatusStartExploded[1]) ? $wfStatusStartExploded[1] : '', 'workflow_id' => isset($wfStatusStartExploded[0]) ? $wfStatusStartExploded[0] : ''])->one();
        $objLabelEnd = \cornernote\workflow\manager\models\Status::find()->andWhere(['id' => isset($wfStatusEndExploded[1]) ? $wfStatusEndExploded[1] : '', 'workflow_id' => isset($wfStatusEndExploded[0]) ? $wfStatusEndExploded[0] : ''])->one();

        $substatusStart = '';
        $substatusEnd = '';

        if($status_transition->start_sub_status) {
            $substatusStart = '(' . $model->getSubstatus()[$status_transition->start_sub_status] . ')';
        }
        if($status_transition->end_sub_status) {
            $substatusEnd = '(' . $model->getSubstatus()[$status_transition->end_sub_status] . ')';
        }

        if($status_transition->startInCharge) {
            $startInCharge = $status_transition->startInCharge;
        }
        if($status_transition->endInCharge) {
            $endInCharge = $status_transition->endInCharge;
        }

        $up = \open20\amos\admin\models\UserProfile::find()->andWhere(['user_id' => $status_transition->created_by])->one();
        ?>
        <tr>
            <th scope="row"><?= Yii::$app->formatter->asDatetime($status_transition->created_at) ?></th>
            <td><?= empty($objLabelStart) ? '' : \open20\amos\een\AmosEen::t('amoseen',$objLabelStart->label) . ' '. $substatusStart ?></td>
            <td><?= empty($objLabelEnd) ? '' : \open20\amos\een\AmosEen::t('amoseen',$objLabelEnd->label) . ' '.  $substatusEnd ?></td>
            <td><?= empty($startInCharge)? '':$startInCharge->userProfile->getNomeCognome() ?></td>
            <td><?= empty($endInCharge)? '':$endInCharge->userProfile->getNomeCognome() ?></td>

            <td><?= empty($up)? '':$up->getNomeCognome() ?></td>
        </tr>
        <?php
    endforeach;
    ?>
    </tbody>
</table>
<?php
\yii\bootstrap\Modal::end();
?>

