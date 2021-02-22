<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateTableMenu extends Migrator
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
        $table  =  $this->table('menu',array('comment'=>'栏目菜单','engine'=>'InnoDB'));
        $table->addColumn('menu_name', 'string',array('limit'  =>  80,'comment' =>'栏目名称','default'=>''))
              ->addColumn('menu_sign', 'string',array('limit'  => 255,'comment' =>'标识','default'=>''))
              ->addColumn('pid', 'integer',array('limit'  => 11,'comment' =>'上级，默认0','default'=>0))
              ->addColumn('sort', 'integer',array('limit'  =>  11,'comment' =>'排序','default'=>0))
              ->addColumn('img_url', 'string',array('limit'  =>  255,'comment' =>'图标','default'=>''))
              ->addColumn('recommend', 'integer',array('limit'  => 1,'comment' =>'是否推荐','default'=>0))
              ->addColumn('status', 'integer',array('limit'  =>  1,'comment' =>'状态','default'=>1))
              ->addColumn('add_time', 'string',array('limit'  =>  15,'comment' =>'添加时间','default'=>''))
              ->addColumn('update_time', 'string',array('limit'  =>  15,'comment' =>'修改时间','default'=>''))
              ->addColumn('delete_time', 'string',array('limit'  =>  15,'comment' =>'删除时间','null'=>true))
              ->addIndex('pid')
              ->addIndex('menu_sign')
              ->addIndex('menu_name')
              ->addIndex('status')
              ->create();
    }
}
