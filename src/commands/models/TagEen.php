<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos-proposte-collaborazione-een
 * @category   CategoryName
 */

namespace lispa\amos\een\commands\models;


use lispa\amos\tag\models\Tag;
use yii\base\Model;
use yii\helpers\Console;

class TagEen extends Model
{
    public $id;
    public $codice;
    public $nome;

    private $root_id = 3; //Ambiti Tecnologici

    public function getId()
    {
        $pad = 0;
        $codiceTag = $this->codice;
        $lunghezza = strlen($codiceTag);
        if($lunghezza < 3 ){
            $pad = 3;
        }elseif($lunghezza > 3 && $lunghezza < 6 ){
            $pad = 6;
        }elseif($lunghezza > 6 && $lunghezza < 9 ){
            $pad = 9;
        }elseif($lunghezza > 9 && $lunghezza < 12 ){
            $pad = 12;
        }
        $codiceTag = str_pad($codiceTag, $pad , "0", STR_PAD_LEFT);


        $tagAmosQ = Tag::find()
            ->andWhere([
                'root' => $this->root_id,
                'codice' => $codiceTag,
                'nome_en' => $this->nome,
            ]);
        if($tagAmosQ->count() == 0){
            $codiceTag = substr($codiceTag, 1);
            $tagAmosQ = Tag::find()
                ->andWhere([
                    'root' => $this->root_id,
                    'codice' => $codiceTag,
                    'nome_en' => $this->nome,
                ]);
        }
        if ($tagAmosQ->count() == 1) {
            $tagAmosObj = $tagAmosQ->asArray()->all();

            if (isset($tagAmosObj[0]['id'])) {
                return $tagAmosObj[0]['id'];
            }
        }
        if ($tagAmosQ->count() > 1) {
            Console::stdout("IL TAG {$this->codice} TROVATO {$tagAmosQ->count()} VOLTE!!");
        }
        return null;
    }

}