<?php

namespace barrelstrength\sproutforms\migrations;

use barrelstrength\sproutbasereports\migrations\m180307_042132_craft3_schema_changes;
use craft\db\Migration;

/**
 * m180412_000000_sproutforms_sprout_email migration.
 */
class m180417_000000_sproutforms_craft3_schema_changes extends Migration
{
    /**
     * @inheritdoc
     * @throws \yii\base\NotSupportedException
     */
    public function safeUp(): bool
    {
        $migration = new m180307_042132_craft3_schema_changes();

        ob_start();
        $migration->safeUp();
        ob_end_clean();

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        echo "m180417_000000_sproutforms_craft3_schema_changes cannot be reverted.\n";
        return false;
    }
}
