<?php

use lispa\amos\core\helpers\Html;

/**
* @var yii\web\View $this
* @var \lispa\amos\een\models\EenExprOfInterest $model
 * @var \lispa\amos\een\models\EenPartnershipProposal $modelEenPartenership
 */

$this->title = Yii::t('cruds', 'Create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('cruds', 'Een Expr Of Interest'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="een-expr-of-interest-create">
    <?= $this->render('_form', [
        'model' => $model,
        'modelEenPartenership' => $modelEenPartenership
    ]) ?>

</div>
