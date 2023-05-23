<?php

use yii\db\Migration;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m180406_180713_order_widget_een_partenership_proposal extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
       $this->update('amos_widgets', ['default_order' => 10], ['classname' => 'open20\amos\een\widgets\icons\WidgetIconEenAll', 'module' => 'een']);
       $this->update('amos_widgets', ['default_order' => 20], ['classname' => 'open20\amos\een\widgets\icons\WidgetIconEen', 'module' => 'een']);
       $this->update('amos_widgets', ['default_order' => 30], ['classname' => 'open20\amos\een\widgets\icons\WidgetIconEenArchived', 'module' => 'een']);

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->update('amos_widgets', ['default_order' => 20], ['classname' => 'open20\amos\een\widgets\icons\WidgetIconEenAll', 'module' => 'een']);
        $this->update('amos_widgets', ['default_order' => 10], ['classname' => 'open20\amos\een\widgets\icons\WidgetIconEen', 'module' => 'een']);
        $this->update('amos_widgets', ['default_order' => 30], ['classname' => 'open20\amos\een\widgets\icons\WidgetIconEenArchived', 'module' => 'een']);
    }
}
