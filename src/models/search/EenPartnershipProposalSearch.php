<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\een\models\search
 * @category   CategoryName
 */

namespace open20\amos\een\models\search;

use open20\amos\core\interfaces\CmsModelInterface;
use open20\amos\core\module\AmosModule;
use open20\amos\core\record\CmsField;
use open20\amos\cwh\AmosCwh;
use open20\amos\cwh\query\CwhActiveQuery;
use open20\amos\een\AmosEen;
use open20\amos\een\models\EenPartnershipProposal;
use Yii;
use yii\base\InvalidConfigException;
use yii\console\Application;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Exception;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;
use yii\log\Logger;
use open20\amos\notificationmanager\models\NotificationChannels;
use yii\di\Container;
use open20\amos\notificationmanager\base\NotifyWidget;

/**
 * Class EenPartnershipProposalSearch
 * @package open20\amos\een\models\search
 */
class EenPartnershipProposalSearch extends EenPartnershipProposal implements CmsModelInterface
{

    private $container;

    /**
     * @var string $datum_submit_from
     */
    public $datum_submit_from;

    /**
     * @var string $datum_submit_to
     */
    public $datum_submit_to;

    /**
     * @var string $datum_deadline_from
     */
    public $datum_deadline_from;

    /**
     * @var string $datum_deadline_to
     */
    public $datum_deadline_to;

    /**
     * @var string
     */
    public $general_search;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [[
                'content_title',
                'content_summary',
                'company_country_label',
                'content_description',
                'reference_external',
                'reference_type',
                'datum_submit_from',
                'datum_submit_to',
                'datum_deadline_from',
                'datum_deadline_to',
                'general_search',
                'created_at',
                'updated_at',
                'deleted_at'
            ], 'safe']
        ];
    }


    /**
     * EenPartnershipProposalSearch constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->container = new Container();
        $this->container->set('notify', Yii::$app->getModule('notify'));
        parent::__construct($config);
    }

    /**
     */
    public function behaviors()
    {
        $parentBehaviors = parent::behaviors();

        $behaviors = [];
        //if the parent model  is a model enabled for tags, Search will have TaggableBehavior too
        $moduleTag = \Yii::$app->getModule('tag');
        if (isset($moduleTag) && in_array(EenPartnershipProposal::className(), $moduleTag->modelsEnabled) && $moduleTag->behaviors) {
            $behaviors = ArrayHelper::merge($moduleTag->behaviors, $behaviors);
        }

        return ArrayHelper::merge($parentBehaviors, $behaviors);
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'datum_submit_from' => AmosEen::t('amoseen', 'Submit date from'),
            'datum_submit_to' => AmosEen::t('amoseen', 'Submit date to'),
            'datum_deadline_from' => AmosEen::t('amoseen', 'Deadline date from'),
            'datum_deadline_to' => AmosEen::t('amoseen', 'Deadline date to'),
        ]);
    }

    /**
     * Construct query to pass to the data provider to vie a list of news, depending on the index tab $type
     *
     * @param array $params $_GET search parameters
     * @param string $type Depending on the index tab calling the search methods (tab created-by, tab to-validate, tab all,...)
     * @param bool|false $only_drafts
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function buildQuery($params, $type, $only_drafts = false)
    {
        try {
            /** @var ActiveQuery $query */
            $query = $this->baseSearch($params);

            /** @var string $classname News className to check cwh */
            $classname = EenPartnershipProposal::className();

            /** @var AmosCwh $moduleCwh */
            $moduleCwh = Yii::$app->getModule('cwh');

            /** @var CwhActiveQuery $cwhActiveQuery */
            $cwhActiveQuery = null;

            if (isset($moduleCwh)) {
                if (!\Yii::$app instanceof Application) {
                    $moduleCwh->setCwhScopeFromSession();
                }
                $cwhActiveQuery = new \open20\amos\cwh\query\CwhActiveQuery($classname, [
                    'queryBase' => $query,
                ]);
            }
            $isSetCwh = $this->isSetCwh($moduleCwh, $classname);
            //composes the query based on the type of news requests
            switch ($type) {
                case 'created-by':
                case 'to-validate':
                    throw new InvalidConfigException('Not implemented');
                    break;
                case 'own-interest':
                    if ($isSetCwh) {
                        $query = $cwhActiveQuery->getQueryCwhOwnInterest();
                    }
                    break;
                case 'all':
                    if ($isSetCwh) {
                        $query
                            ->andWhere(['>=', 'datum_deadline', new Expression('CURDATE()')]);
//                        $query = $cwhActiveQuery->getQueryCwhAll();
                    }
                    break;
                case 'archived':
                    if ($isSetCwh) {
                        $query
                            ->andWhere(['<', 'datum_deadline', new Expression('CURDATE()')]);
                    }
                    break;
            }
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
        }
        return $query;
    }

    /**
     * Basic search of news. Returns all the news and not canceled.
     *
     * @param   array $params Parametri
     * @return \yii\db\ActiveQuery
     */
    public function baseSearch($params)
    {
        //init the default search values
        $this->initOrderVars();

        //check params to get orders value
        $this->setOrderVars($params);

        return EenPartnershipProposal::find()->distinct();
    }

    /**
     * @param AmosModule $moduleCwh
     * @param string $classname
     * @return bool
     */
    private function isSetCwh($moduleCwh, $classname)
    {
        if (isset($moduleCwh) && in_array($classname, $moduleCwh->modelsEnabled)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Base filter.
     * @param ActiveQuery $query
     * @return mixed
     */
    public function baseFilter($query)
    {
        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'content_title', $this->content_title]);
        $query->andFilterWhere(['like', 'content_summary', $this->content_summary]);
        $query->andFilterWhere(['like', 'content_description', $this->content_description]);
        $query->andFilterWhere(['like', 'reference_external', $this->reference_external]);
        $query->andFilterWhere(['=', 'reference_type', $this->reference_type]);
        $query->andFilterWhere(['OR',
            ['LIKE', 'content_description', $this->general_search],
            ['LIKE', 'content_title', $this->general_search],
            ['LIKE', 'content_summary', $this->general_search],
        ]);
        $query->andFilterWhere(['company_country_label' => $this->company_country_label]);
        $query->andFilterWhere(['>=', 'datum_submit', $this->datum_submit_from]);
        $query->andFilterWhere(['<=', 'datum_submit', $this->datum_submit_to]);
        $query->andFilterWhere(['>=', 'datum_deadline', $this->datum_deadline_from]);
        $query->andFilterWhere(['<=', 'datum_deadline', $this->datum_deadline_to]);

        $params = \Yii::$app->request->get();
        if (isset($params[$this->formName()]['tagValues'])) {
            $tagValues = $params[$this->formName()]['tagValues'];
            $this->setTagValues($tagValues);
            if (is_array($tagValues) && !empty($tagValues)) {
                $andWhere = "";
                $i = 0;
                foreach ($tagValues as $rootId => $tagId) {
                    if (!empty($tagId)) {
                        if ($i == 0) {
                            $query->innerJoin('entitys_tags_mm entities_tag',
                                "entities_tag.classname = '" . addslashes(EenPartnershipProposal::className()) . "' AND entities_tag.record_id=een_partnership_proposal.id");

                        } else {
                            $andWhere .= " OR ";
                        }
                        $andWhere .= "(entities_tag.tag_id in (" . $tagId . ") AND entities_tag.root_id = " . $rootId . " AND entities_tag.deleted_at is null)";
                        $i++;
                    }
                }
                $andWhere .= "";
                if (!empty($andWhere)) {
                    $query->andWhere($andWhere);
                }
            }
        }

        return $query;
    }

    /**
     * Method that searches all the news validated.
     *
     * @param array $params
     * @param int $limit
     * @return ActiveDataProvider
     */
    public function searchOwnInterest($params, $limit = null)
    {
        return $this->search($params, $limit, "own-interest");
    }

    /**
     * Method that searches all the news validated.
     *
     * @param array $params
     * @param int $limit
     * @return ActiveDataProvider
     */
    public function searchArchived($params, $limit = null)
    {
        return $this->search($params, $limit, 'archived');
    }

    /**
     * Method that searches all the news validated.
     *
     * @param array $params
     * @param int $limit
     * @return ActiveDataProvider
     */
    public function searchAll($params, $limit = null)
    {
        return $this->search($params, $limit, 'all');
    }

    /**
     * @param $params
     * @param null $limit
     * @param null $type
     * @param bool $only_drafts
     * @return ActiveDataProvider
     */
    public function search($params, $limit = null, $type = null, $only_drafts = false)
    {
        $query = $this->buildQuery($params, $type, $only_drafts);
        $query->limit($limit);

        $dp_params = ['query' => $query,];
        if ($limit) {
            $dp_params ['pagination'] = false;
        }

        $notify = $this->getNotifier();
        if ($notify && !\Yii::$app instanceof Application) {
            $cloneQuery = clone($query);
            $notify->notificationOff(Yii::$app->getUser()->id, EenPartnershipProposal::className(), $cloneQuery->select('id'),
                NotificationChannels::CHANNEL_READ);
        }
        //set the data provider
        $dataProvider = new ActiveDataProvider($dp_params);

        //check if can use the custom module order
        if ($this->canUseModuleOrder()) {
            $dataProvider->setSort($this->createOrderClause());
        } else { //for widget graphic last news, order is incorrect without this else
            $dataProvider->setSort([
                'defaultOrder' => [
                    'datum_update' => SORT_DESC
                ]
            ]);
        }

        if (!($this->load($params) && $this->validate())) {
            if ($type != 'archived') {
                $query->andFilterWhere(['>=', 'datum_deadline', date('Y-m-d', strtotime('-1 day'))]);
            }
            return $dataProvider;
        }

        $this->baseFilter($query);
        return $dataProvider;
    }

    /**
     * @param $notifier
     */
    public function setNotifier(NotifyWidget $notifier)
    {
        $this->container->set('notify', $notifier);
    }

    /**
     * @return $this
     */
    public function getNotifier()
    {
        return $this->container->get('notify');
    }

    /**
     * @param array $params
     * @param null $limit
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function latestPartenershipProposalSearch($params, $limit = null)
    {
        $dataProvider = $this->searchAll($params);
        $dataProvider->query->orderBy(['created_at' => SORT_DESC]);
        $dataProvider->pagination->pageSize = $limit;
        return $dataProvider;
    }



    /**
     * Search method useful to retrieve news to show in frontend (with cms)
     *
     * @param $params
     * @param int|null $limit
     * @return ActiveDataProvider
     */
    public function cmsSearch($params, $limit = null)
    {
        $params = array_merge($params, \Yii::$app->request->get());
        $this->load($params);
        $dataProvider = $this->searchAll($params);
        //$this->applySearchFilters($query);


        if (!empty($params["withPagination"])) {
            $dataProvider->setPagination(['pageSize' => $limit]);
            $dataProvider->query->limit(null);
        } else {
            $dataProvider->query->limit($limit);
        }

        if (!empty($params["conditionSearch"])) {
            $commands = explode(";", $params["conditionSearch"]);
            foreach ($commands as $command) {
                $dataProvider->query->andWhere(eval("return ".$command.";"));
            }
        }

        return $dataProvider;
    }

    /**
     *
     * @return array
     */
    public function cmsViewFields()
    {
        $viewFields = [];

        $viewFields[] = new CmsField("title", "TEXT", 'een', $this->attributeLabels()["content_title"]);
        $viewFields[] = new CmsField("short_description", "TEXT", 'een', $this->attributeLabels()['content_summary']);

        return $viewFields;
    }

    /**
     *
     * @return array
     */
    public function cmsSearchFields()
    {
        $searchFields = [];
        $searchFields[] = new CmsField("content_title", "TEXT");
        $searchFields[] = new CmsField("content_summary", "TEXT");;

        return $searchFields;
    }

    /**
     *
     * @param int $id
     * @return boolean
     */
    public function cmsIsVisible($id)
    {
        $retValue = true;
        return $retValue;
    }

}
