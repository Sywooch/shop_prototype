<?php

use yii\db\Migration;

/**
 * Handles adding active to table `subcategory`.
 */
class m160922_064307_add_active_column_to_subcategory_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('subcategory', 'active', $this->boolean()->notNull()->defaultValue(true));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('subcategory', 'active');
    }
}
