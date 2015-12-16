<?php
/**
 * Create Boneyard base tables.
 *
 * Copyright 2012-2015 Horde LLC (http://www.horde.org/)
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.horde.org/licenses/gpl.
 *
 * @author  Ralf Lang <lang@b1-systems.de>
 * @category Horde
 * @package  Boneyard
 */
class BoneyardBaseTables extends Horde_Db_Migration_Base
{
    /**
     * Upgrade
     */
    public function up()
    {
        $t = $this->createTable('boneyard_items', array('autoincrementKey' => 'item_id'));
        $t->column('item_owner', 'string', array('limit' => 255, 'null' => false));
        $t->column('item_data', 'string', array('limit' => 64, 'null' => false));
        $t->end();

        $this->addIndex('boneyard_items', array('item_owner'));
    }

    /**
     * Downgrade
     */
    public function down()
    {
        $this->dropTable('boneyard_items');
    }
}
