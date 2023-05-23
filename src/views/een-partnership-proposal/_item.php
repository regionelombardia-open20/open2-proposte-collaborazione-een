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
use open20\amos\core\views\toolbars\StatsToolbar;
use open20\amos\een\AmosEen;
use yii\bootstrap\Modal;
use open20\amos\core\forms\ActiveForm;

/**
 * @var yii\web\View $this
 * @var open20\amos\een\models\EenPartnershipProposal $model
 */

?>
<?php
$currentUrl = explode("?", \yii\helpers\Url::current());
$isArchived = false;
if ($currentUrl[0] == '/een/een-partnership-proposal/archived') {
    $isArchived = true;
}
?>
<div class="listview-container">
    <div class="proposte-een-item col-xs-12">
        <div class="row row-d-flex">
            <div class="col-sm-3 info-proposte-collaborazione">
                <div class="flexbox">
                    <div class="col-auto">
                        <div class="tipo-collaborazione small text-warning">
                            <span class="mdi mdi-earth"></span> <span><?= AmosEen::t('amoseen', 'Dal mondo') ?></span>
                        </div>
                        <div class="date-end bg-secondary">
                            <small><?= AmosEen::t('amoseen', 'Scadenza') . ': ' ?></small>
                            <strong><?= Yii::$app->getFormatter()->asDate($model->datum_deadline, 'long') ?></strong>
                        </div>
                    </div>
                </div>
                <div class="other-info m-t-10">
                    <small><?= $model->getAttributeLabel('datum_submit') ?>:</small>
                    <strong><?= Yii::$app->getFormatter()->asDate($model->datum_submit) ?></strong>
                    <div class="published-by">
                        <?php if (\Yii::$app->controller->id != 'archived') { ?>
                            <div class="item">
                                <small><?= $model->getAttributeLabel('datum_update') ?>:</small>
                                <strong><?= Yii::$app->getFormatter()->asDate($model->datum_update) ?></strong>
                            </div>
                        <?php } ?>
                        <div class="item">
                            <small><?= $model->getAttributeLabel('company_country_label') ?>:</small>
                            <strong><?= $model->company_country_label ?></strong>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-sm-9 border-left">
                <div class="content-proposte-collaborazione">
                    <div class="title">
                        <?=
                        Html::a(Html::tag('h3', $model->content_title, ['class' => '']), ['view', 'id' => $model->id], ['class' => 'link-list-title', 'title' => $model->content_title]);
                        ?>

                    </div>
                    <p>
                        <?php
                        if (strlen($model->content_summary) > 800) :
                            $stringCut = substr($model->content_summary, 0, 800);
                            echo substr($stringCut, 0, strrpos($stringCut, ' ')) . '... ';
                        else :
                            echo $model->content_summary;
                        endif;
                        ?>


                    </p>
                    <p>
                        <span class="label label-default"><?= $model->getReferenceTypeLabel() ?></span>
                    </p>
                    <div class="footer-item p-t-20">
                        <div class="blockquote-footer m-t-10">
                            <?= AmosEen::t('amoseen', 'Identificativo proposta') . ': <em>' . $model->reference_external . '</em>' ?>
                        </div>

                        <a class="readmore m-t-10" href="<?= Yii::$app->getUrlManager()->createUrl([
                            '/een/een-partnership-proposal/view',
                            'id' => $model->id
                        ]) ?>"
                           title="<?= AmosEen::t('amoseen', 'Dettaglio proposta') ?>"><?= AmosEen::t('amoseen', 'Dettaglio proposta') ?></a>

                    </div>
                </div>

            </div>
        </div>

    </div>

</div>