<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    Open20Package
 * @category   CategoryName
 */
use yii\bootstrap\Modal;
use open20\amos\een\AmosEen;
?>

<?php
$alert = AmosEen::t('amoseen', 'Hai già richiesto informazioni per questa proposta di collaborazione. Attendi la risposta dello staff EEN');
    Modal::begin([
        'id' => 'interestPopup-'.$model->id,
        'header' => '<b>' . AmosEen::t('amoseen', '#expr_of_interest') . '</b>',
    ]);?>
    <?php if(\open20\amos\een\models\EenExprOfInterest::isNumExprOfInterestExceeded()) { ?>
        <div class="col-xs-12 nop m-b-15">
            <p><?= AmosEen::t('amoseen', '#limit_expr_of_interest_exceeded2') ?></p>
            <?php echo \yii\helpers\Html::a(AmosEen::t('amoseen', '#close'), ['#'], ['class' => "btn btn-secondary pull-right", 'data-dismiss' => "modal"]);?>

        </div>
    <?php } else { ?>
    <div class="col-xs-12 nop m-b-15">
        <?php $link = \yii\helpers\Html::a(AmosEen::t('amoseen', 'clicca qui'), ['/een/een-expr-of-interest/create' ,  'idPartnershipProposal' => $model->id, 'request_more_info' => true],[
            'onclick' => $model->isRequestInfoSended() ? "alert('".$alert."'); return false;" : ''
        ]);?>
        <p><?=AmosEen::t('amoseen', '#text_require_more_info1', ['link' => $link])?></p>
        <?= \open20\amos\core\helpers\Html::a(AmosEen::t('amoseen', '#get_in_touch_with_proponent'), ['/een/een-expr-of-interest/create' , 'idPartnershipProposal' => $model->id] ,[
            'title' => AmosEen::t('amoseen', '#get_in_touch_with_proponent'),
            'class' => 'btn btn-navigation-primary pull-right  m-t-10'
        ]);?>
    </div>
    <?php } ?>


<?php Modal::end();