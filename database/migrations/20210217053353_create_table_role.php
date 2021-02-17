<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateTableRole extends Migrator
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
        $table  =  $this->table('role',array('comment'=>'后台角色表','engine'=>'InnoDB'));
        $table->addColumn('role_name', 'string',array('limit'  =>  50,'comment' =>'角色名称','default'=>''))
              ->addColumn('add_time', 'string',array('limit'  =>  15,'comment' =>'添加时间','default'=>0))
              ->addIndex('role_name')
              ->create();
    }
}
