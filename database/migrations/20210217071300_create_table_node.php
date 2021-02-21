<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateTableNode extends Migrator
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
        $table  =  $this->table('node',array('comment'=>'节点','engine'=>'InnoDB'));
        $table->addColumn('node_name', 'string',array('limit'  =>  100,'comment' =>'节点名称','default'=>'','null'=>true))
              ->addColumn('node_path', 'string',array('limit'  =>  60,'comment' =>'节点路径','default'=>'','null'=>true))
              ->addColumn('reception_path', 'string',array('limit'  =>  60,'comment' =>'前端节点','default'=>'','null'=>true))
              ->addColumn('is_menu', 'integer',array('limit'  =>  1,'comment' =>'是否是菜单项 1不是 2是','default'=>1))
              ->addColumn('type_id', 'integer',array('limit'  =>  11,'comment' =>'父级节点id','default'=>0))
              ->addColumn('style', 'string',array('limit'  =>  155,'comment' =>'菜单样式','default'=>'','null'=>true))
              ->addIndex('node_path')
              ->create();
    }
}
