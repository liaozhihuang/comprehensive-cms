<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateTableMenuGroup extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table  =  $this->table('menu_group',array('comment'=>'菜单分组','engine'=>'InnoDB'));
        $table->addColumn('group_name', 'string',array('limit'  =>  100,'comment' =>'名称','default'=>''))
              ->addColumn('group_sign', 'string',array('limit'  =>  30,'comment' =>'标识','default'=>''))
              ->addColumn('add_time', 'string',array('limit'  =>  15,'comment' =>'添加时间','default'=>0))
              ->addColumn('update_time', 'string',array('limit'  =>  15,'comment' =>'编辑时间','default'=>0))
              ->addColumn('delete_time', 'string',array('limit'  =>  15,'comment' =>'删除时间','null'=>true))
              ->addIndex('group_name')
              ->addIndex('group_sign')
              ->create();
    }
}
