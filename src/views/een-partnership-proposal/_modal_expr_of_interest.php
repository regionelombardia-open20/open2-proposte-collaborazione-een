<?php

use yii\bootstrap\Modal;
use open20\amos\een\AmosEen;
use open20\amos\core\forms\ActiveForm;
use yii\helpers\Html;
use kartik\widgets\Select2;


?>

<?php
$alert = AmosEen::t('amoseen',
    'Hai già richiesto informazioni per questa proposta di collaborazione. Attendi la risposta dello staff EEN');
Modal::begin([
    'id' => 'interestPopup-'.$model->id,
    'header' => '<b>'.AmosEen::t('amoseen', '#expr_of_interest').'</b>',
]);
?>
<?php if (\open20\amos\een\models\EenExprOfInterest::isNumExprOfInterestExceeded()) { ?>
	<div class="col-xs-12 nop m-b-15">
		<p><?= AmosEen::t('amoseen', '#limit_expr_of_interest_exceeded2') ?></p>
        <?php
        echo \yii\helpers\Html::a(AmosEen::t('amoseen', '#close'), ['#'],
            ['class' => "btn btn-secondary pull-right", 'data-dismiss' => "modal"]);
        ?>

	</div>
<?php } else { ?>

    <?php if(!empty(\Yii::$app->controller->module->expofintintoform['emailtosend'])){
        $form = ActiveForm::begin([
            'method' => 'post',
            'action' => ['een-expr-of-interest-form/send-info-request'],
        ]);
        $modelForm=  new \open20\amos\een\models\EenExprOfInterestForm;
        $modelForm->userprofile_id = \Yii::$app->user->identity->profile->id;
        $modelForm->name = \Yii::$app->user->identity->profile->getNomeCognome();
        $modelForm->email = \Yii::$app->user->identity->email;
        $modelForm->phone = \Yii::$app->user->identity->profile->telefono;
        $modelForm->een_partnership_proposal_id = $model->id;
        ?>

		<!-- name -->

		<div class="col-md-12">

            <?= $form->field($modelForm, 'name')->textInput()?>
            <?= $form->field($modelForm, 'email')->textInput()?>
            <?= $form->field($modelForm, 'phone')->textInput()?>
            <?= $form->field($modelForm, 'address')->textInput()?>
            <?= $form->field($modelForm, 'note')->textarea([
                'rows' => 5, 'maxlength' => 1200,
            ])?>
            <?= $form->field($modelForm, 'user_type')->widget(Select2::classname(), [
                'options' => [
                    'placeholder' => 'Seleziona',
                    'disabled' => false,
                    'id' => 'user_type'],
                'data' => [\open20\amos\een\models\EenExprOfInterestForm::CERCO_PARTNER =>\open20\amos\een\models\EenExprOfInterestForm::CERCO_PARTNER,
                    \open20\amos\een\models\EenExprOfInterestForm::OFFRO_PRODOTTO =>\open20\amos\een\models\EenExprOfInterestForm::OFFRO_PRODOTTO,
                    \open20\amos\een\models\EenExprOfInterestForm::CERCO_PRODOTTO =>\open20\amos\een\models\EenExprOfInterestForm::CERCO_PRODOTTO
                ],
            ]); ?>
            <?= $form->field($modelForm, 'userprofile_id')->hiddenInput()->label('') ?>
            <?= $form->field($modelForm, 'een_partnership_proposal_id')->hiddenInput()->label('') ?>

		</div>


		<div class="col-xs-12">
			<div class="pull-right">
                <?= Html::submitButton('Invia', ['class' => 'btn btn-navigation-primary']) ?>
			</div>
		</div>

		<div class="clearfix"></div>
		<!--a><p class="text-center">Ricerca avanzata<br>
                < ?=AmosIcons::show('caret-down-circle');?>
            </p></a-->
        <?php ActiveForm::end(); ?>

    <?php } else { ?>

		<div class="col-xs-12 nop m-b-15">
            <?php
            $link = \yii\helpers\Html::a(AmosEen::t('amoseen', 'clicca qui'),
                ['/een/een-expr-of-interest/create', 'idPartnershipProposal' => $model->id, 'request_more_info' => true],
                [
                    'onclick' => $model->isRequestInfoSended() ? "alert('".$alert."'); return false;" : ''
                ]);
            ?>
			<p><?=
                AmosEen::t('amoseen',
                    '<p style="font-size: 1.5em; text-align: justify;">Per entrare in contatto con chi ha fatto questa proposta di collaborazione o per conoscere meglio i servizi di Enterprise Europe Network, scrivi a: <a href="mailto:openinnovation@finlombarda.it">openinnovation@finlombarda.it</a></p><br>
    <p style="text-align: justify;">E’ una iniziativa realizzata in collaborazione con Finlombarda spa, partner di Enterprise Europe Network, per promuovere opportunità di collaborazione su progetti di ricerca e innovazione tecnologica e sociale. Si rivolge ad imprese, start up già costituite, università, centri di ricerca, pubbliche amministrazioni, associazioni e altri enti. Il servizio è gratuito.</p>',
                    ['link' => $link])
                ?></p>
            <?php /* \open20\amos\core\helpers\Html::a(AmosEen::t('amoseen', '#get_in_touch_with_proponent'), ['/een/een-expr-of-interest/create' , 'idPartnershipProposal' => $model->id] ,[
            'title' => AmosEen::t('amoseen', '#get_in_touch_with_proponent'),
            'class' => 'btn btn-navigation-primary pull-right  m-t-10'
            ]); */ ?>
		</div>


    <?php }?>
<?php } ?>


<?php
Modal::end();
