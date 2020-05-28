<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    Open20Package
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;

/**
* @var yii\web\View $this
* @var \open20\amos\een\models\EenExprOfInterest $model
 * @var \open20\amos\een\models\EenPartnershipProposal $modelEenPartenership
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
