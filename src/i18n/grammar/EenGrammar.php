<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\een\i18n\grammar
 * @category   CategoryName
 */

namespace open20\amos\een\i18n\grammar;

use open20\amos\core\interfaces\ModelGrammarInterface;
use open20\amos\een\AmosEen;

/**
 * Class EenGrammar
 * @package open20\amos\een\i18n\grammar
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
