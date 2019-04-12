<?php

namespace lispa\amos\een\assets;

use yii\web\AssetBundle;

/**
 * Class ProposteCollaborazioneEenAsset
 * @package lispa\amos\een\assets
 */
class ProposteCollaborazioneEenAsset extends AssetBundle
{
    public $sourcePath = '@vendor/lispa/amos-proposte-collaborazione-een/src/assets/web';

    public $js = [
    ];

    public $css = [
        'less/proposte-een.less',
    ];

    public $depends = [

    ];


    public function init()
    {
        $moduleL = \Yii::$app->getModule('layout');
        if(!empty($moduleL))
        { $this->depends [] = 'lispa\amos\layout\assets\BaseAsset'; }
        else
        { $this->depends [] = 'lispa\amos\core\views\assets\AmosCoreAsset'; }
        parent::init();
    }
}