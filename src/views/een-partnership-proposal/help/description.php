<?php
use open20\amos\admin\AmosAdmin;

$profile = \open20\amos\admin\models\UserProfile::find()->andWhere(['user_id' => \Yii::$app->user->id])->one();
$link    = "/".AmosAdmin::getModuleName()."/user-profile/update?id=".$profile->id.'#w44-tab5';

$tags = \open20\amos\tag\models\Tag::find()
    ->innerJoin('cwh_tag_owner_interest_mm', 'tag.id = cwh_tag_owner_interest_mm.tag_id')
    ->andWhere(['cwh_tag_owner_interest_mm.record_id' => $profile->id])
    ->andWhere(['cwh_tag_owner_interest_mm.classname' => 'open20\amos\admin\models\UserProfile'])
    ->andWhere(['cwh_tag_owner_interest_mm.interest_classname' => 'simple-choice'])
    ->andWhere(['root' => 3])
    //->andWhere(['lvl' => 3])
    ->andWhere(['cwh_tag_owner_interest_mm.deleted_at' => null])
    ->groupBy('tag.id')
    ->limit(10);

$arayTag = '';
foreach ($tags->all() as $tag) {
    $arayTag .= '<div class="tags-list-single m-r-15 pull-left" data-tag="2006">
        <p><span class="am am-label"> </span> <em><span></sp>'.$tag->nome.'</span></em> </p>
    </div>';
}
?>

<?php if (!strcmp(Yii::$app->controller->action->id, 'own-interest')) { ?>
    <p><?=
        \open20\amos\een\AmosEen::t('amoseen',
            'Consulta o ricerca le proposte di collaborazione internazionali promosse da <a href="http://een.ec.europa.eu">Enterprise Europe Network</a> selezionate sulla base degli interessi che hai indicato nel tuo profilo:',
            ['link_profilo' => $link])
        ?></p>
    <div class="col-xs-12"><?= $arayTag ?></div>
    <p><?=
    \open20\amos\een\AmosEen::t('amoseen',
        "Segui <a href='{link_profilo}'>questo link</a> se desideri modificarli o per cambiare la periodicità con cui ricevi le notifiche. Per pubblicare una proposta di collaborazione fai clic su «crea una proposta»",
        ['link_profilo' => $link])
        ?></p>


    <?php
} else if (strcmp(Yii::$app->controller->action->id, 'archived')) {
    $modelSearch  = new \open20\amos\een\models\search\EenPartnershipProposalSearch();
    /** @var  $dataProvider \yii\data\ActiveDataProvider */
    $dataProvider = $modelSearch->searchAll([]);

    $n = $dataProvider->getTotalCount();
    ?>
    <p><?=
        \open20\amos\een\AmosEen::t('amoseen',
            "#helptext_een_proposal",
            ['link_profilo' => $link, 'n' => $n])
        ?></p>
    <p><?= \open20\amos\een\AmosEen::t('amoseen',
        "E' un servizio in collaborazione con il progetto <a href='http://www.eensimpler.it'>SIMPLER</a>, coordinato da Finlombarda s.p.a.")
    ?></p>

<?php } ?>
