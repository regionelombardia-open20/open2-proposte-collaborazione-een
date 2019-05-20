<?php

namespace lispa\amos\een\commands\models;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;
use yii\helpers\FileHelper;
use yii\helpers\Json;

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos-proposte-collaborazione-een
 * @category   CategoryName
 */
class CollaborationProposalEen extends Model
{

    public $podXml = null;
    public $eenId = null;

    public static function findAll($params)
    {
        $wsdl = \Yii::$app->controller->module->wsdl;


        $soapRequest = [
            "request" => ArrayHelper::merge(
                \Yii::$app->controller->module->findAllAccessPointRequest
                , $params
            )
        ];

        Console::stdout("Start importing\n\r");

        $findAllAccessPointRequest = $soapRequest;//\Yii::$app->controller->module->findAllAccessPointRequest;

        //GetProfilesSOAP
        $actionFindAllAccessPoint = \Yii::$app->controller->module->findAllAccessPoint;

        $opts = array(
            'http' => array(
                'user_agent' => 'PHPSoapClient'
            )
        );

        Console::stdout("#1 Create context request\n\r");


        $context = stream_context_create($opts);

        /**@var $SoapClient \SoapClient * */
        $SoapClient = new \SoapClient($wsdl, array(
                'cache_wsdl' => WSDL_CACHE_NONE,
                'trace' => true, //serve per __getLastResponse()
                //'allow_url_fopen' => 1,
                'stream_context' => $context,
                'use' => SOAP_LITERAL,
            )
        );

        $paramsToString = Json::encode($params);

        Console::stdout("#2 Connecting to: {$actionFindAllAccessPoint} for {$paramsToString} \n\r");

        $res = $SoapClient->{$actionFindAllAccessPoint}($findAllAccessPointRequest);

        Console::stdout("#3 Saving request\n\r");
        $request = $SoapClient->__getLastRequest();
        file_put_contents(LOG_DIR . 'request.xml', $request);


        Console::stdout("#4 Saving response\n\r");
        $response = $SoapClient->__getLastResponse();
        file_put_contents(LOG_DIR . 'response.xml', $response);

        if (property_exists($res->GetProfilesSOAPResult, 'profile')) {
            $profiles = $res->GetProfilesSOAPResult->profile;
            if (is_array($profiles)) {
                return $res->GetProfilesSOAPResult;
            } else {
                $returnCollections = new \stdClass();

                $returnCollections->profile = [
                    $res->GetProfilesSOAPResult->profile
                ];
                return $returnCollections;


            }
        }


        return [];

    }


    public function fields()
    {
        return [
            'company_certifications_list' => function ($model) {
                return Json::encode($model->podXml->company->certifications, true);
            },
            'company_country_key' => function ($model) {
                return @$model->podXml->company->country->key;
            },
            'company_country_label' => function ($model) {
                return @$model->podXml->company->country->label;
            },
            'company_experience' => function ($model) {
                return @$model->podXml->company->experience;
            },
            'company_kind' => function ($model) {
                return @$model->podXml->company->kind;
            },
            'company_languages_list' => function ($model) {
                return @Json::encode($model->podXml->company->languages->language, true);
            },
            'company_since' => function ($model) {
                return @$model->podXml->company->since;
            },
            'company_transnational' => function ($model) {
                return @$model->podXml->company->transnational;
            },
            'company_turnover' => function ($model) {
                return @$model->podXml->company->turnover;
            },
            'contact_consortium' => function ($model) {
                return @$model->podXml->contact->consortium;
            },
            'contact_consortiumcountry_key' => function ($model) {
                return @$model->podXml->contact->consortiumcountry->key;
            },
            'contact_consortiumcountry_label' => function ($model) {
                return @$model->podXml->contact->consortiumcountry->label;
            },
            'contact_email' => function ($model) {
                return @$model->podXml->contact->email;
            },
            'contact_fullname' => function ($model) {
                return @$model->podXml->contact->fullname;
            },
            'contact_organization' => function ($model) {
                return @$model->podXml->contact->organization;
            },
            'contact_organizationcountry_key' => function ($model) {
                return @$model->podXml->contact->organizationcountry->key;
            },
            'contact_organizationcountry_label' => function ($model) {
                return @$model->podXml->contact->organizationcountry->label;
            },
            'contact_partnerid' => function ($model) {
                return @$model->podXml->contact->partnerid;
            },
            'contact_phone' => function ($model) {
                return @$model->podXml->contact->phone;
            },
            'content_description' => function ($model) {
                return @$model->podXml->content->description;
            },
            'content_summary' => function ($model) {
                return @$model->podXml->content->summary;
            },
            'content_title' => function ($model) {
                return @$model->podXml->content->title;
            },
            'cooperation_exploitation_list' => function ($model) {
                return @Json::encode(@$model->podXml->cooperation->exploitation->list, true);
            },
            'cooperation_ipr_comment' => function ($model) {
                return @$model->podXml->cooperation->ipr->comment;
            },
            'cooperation_ipr_status' => function ($model) {
                return @$model->podXml->cooperation->ipr->status;
            },
            'cooperation_partner_area' => function ($model) {
                return @$model->podXml->cooperation->partner->area;
            },
            'cooperation_partner_sought' => function ($model) {
                return @$model->podXml->cooperation->partner->sought;
            },
            'cooperation_partner_task' => function ($model) {
                return @$model->podXml->cooperation->partner->task;
            },
            'cooperation_plusvalue' => function ($model) {
                return @$model->podXml->cooperation->plusvalue;
            },
            'cooperation_stagedev_comment' => function ($model) {
                return @$model->podXml->cooperation->stagedev->comment;
            },
            'cooperation_stagedev_stage' => function ($model) {
                return @$model->podXml->cooperation->stagedev->stage;
            },
            'datum_deadline' => function ($model) {
                return @$model->podXml->datum->deadline;
            },
            'datum_submit' => function ($model) {
                return @$model->podXml->datum->submit;
            },
            'datum_update' => function ($model) {
                return @$model->podXml->datum->update;
            },
            'reference_external' => function ($model) {
                return @$model->podXml->reference->external;
            },
            'reference_internal' => function ($model) {
                return @$model->podXml->reference->internal;
            },
            'reference_type' => function ($model) {
                return @$model->podXml->reference->type;
            },
        ];
    }

    public function getAttachments()
    {
        $attachListFilepaths = [];
        $k = 0;

        if (@$this->podXml->files && @$this->podXml->files->profileFile) {

            foreach ($this->podXml->files->profileFile as $i => $file) {


                if (isset($file->name)) {
                    $name = isset($file->name) ? $file->name : $file->caption;
                    $dirPath = LOG_DIR .
                        $this->podXml->reference->external .
                        DIRECTORY_SEPARATOR .
                        $k++;

                    FileHelper::createDirectory($dirPath, 0777);

                    $filepath = $dirPath . DIRECTORY_SEPARATOR . $name;

                    file_put_contents($filepath, $file->data);

                } else {
                    $dirPath = LOG_DIR .
                        $this->podXml->reference->external .
                        DIRECTORY_SEPARATOR .
                        $k++;

                    FileHelper::createDirectory($dirPath, 0777);

                    $name = isset($this->podXml->files->profileFile->name) ? $this->podXml->files->profileFile->name : $this->podXml->files->profileFile->caption;
                    if(!empty($name)){
                        $filepath = $dirPath . DIRECTORY_SEPARATOR . $name;

                        file_put_contents($filepath, $this->podXml->files->profileFile->data);
                    }
                }

                $attachListFilepaths[$k] = $filepath;
            }
        }

        return $attachListFilepaths;
    }

    /**
     * @return TagEen[]
     */
    public function getTagsMarkets()
    {

        $tagsMarkets = [];

        if ($this->hasTags('markets', 'market')) {
            $tagsMarkets = $this->getTagList('markets', 'market');
        }


        return $tagsMarkets;
    }


    private function hasTags($type, $tagName)
    {
        if (@$this->podXml->keyword->{$type} && @$this->podXml->keyword->{$type}->{$tagName}) {
            return true;
        }

        return false;
    }

    private function getTagList($type, $tagName)
    {
        $k = 0;
        $tagList = [];

        foreach ($this->podXml->keyword->{$type}->{$tagName} as $i => $tag) {
            if (isset($tag->key)) {
                $key = isset($tag->key) ? $tag->key : null;
                $label = isset($tag->label) ? $tag->label : null;


            } else {
                $key = isset($this->podXml->keyword->{$type}->{$tagName}->key) ? $this->podXml->keyword->{$type}->{$tagName}->key : null;
                $label = isset($this->podXml->keyword->{$type}->{$tagName}->label) ? $this->podXml->keyword->{$type}->{$tagName}->label : null;
            }

            $tagParams = [
                'codice' => $key,
                'nome' => $label,
            ];
            $TagObj = new TagEen($tagParams);

            $tagList[$k] = $TagObj;
            $k++;

        }
        return $tagList;

    }

    /**
     * @return TagEen[]
     */
    public function getTagsNaces()
    {

        $tagsNaces = [];

        if ($this->hasTags('naces', 'nace')) {
            $tagsNaces = $this->getTagList('naces', 'nace');
        }
        return $tagsNaces;
    }

    /**
     * @return TagEen[]
     */
    public function getTagsTechnologies()
    {

        $tagsTechnologies = [];

        if ($this->hasTags('technologies', 'technology')) {
            $tagsTechnologies = $this->getTagList('technologies', 'technology');
        }
        return $tagsTechnologies;
    }

}