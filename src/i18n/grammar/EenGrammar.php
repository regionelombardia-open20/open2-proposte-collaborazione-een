<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\een\i18n\grammar
 * @category   CategoryName
 */

namespace lispa\amos\een\i18n\grammar;

use lispa\amos\core\interfaces\ModelGrammarInterface;
use lispa\amos\een\AmosEen;

/**
 * Class EenGrammar
 * @package lispa\amos\een\i18n\grammar
 */
class EenGrammar implements ModelGrammarInterface
{
    /**
     * @return string
     */
    public function getModelSingularLabel()
    {
        return AmosEen::t('amoseen', '#een_singular');
    }
    
    /**
     * @inheritdoc
     */
    public function getModelLabel()
    {
        return AmosEen::t('amoseen', '#een_plural');
    }
    
    /**
     * @return mixed
     */
    public function getArticleSingular()
    {
        return AmosEen::t('amoseen', '#article_singular');
    }
    
    /**
     * @return mixed
     */
    public function getArticlePlural()
    {
        return AmosEen::t('amoseen', '#article_plural');
    }
    
    /**
     * @return string
     */
    public function getIndefiniteArticle()
    {
        return AmosEen::t('amoseen', '#article_indefinite');
    }
}
