<?php

use yii\db\Migration;

/**
 * Handles adding active to table `categories`.
 */
class m160922_061541_add_active_column_to_categories_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('categories', 'active', $this->boolean()->notNull()->defaultValue(true));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('categories', 'active');
    }
}
