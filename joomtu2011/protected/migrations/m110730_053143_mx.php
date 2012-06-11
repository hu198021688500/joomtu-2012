<?php

class m110730_053143_mx extends CDbMigration
{
	public function up()
	{
	}

	public function down()
	{
		echo "m110730_053143_mx does not support migration down.\n";
		return false;
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
		$this->createTable('tbl_news', array(
            'id' => 'pk',
            'title' => 'string NOT NULL',
            'content' => 'text',
        ));
	}

	public function safeDown()
	{
		$this->dropTable('tbl_news');
	}
	*/
}