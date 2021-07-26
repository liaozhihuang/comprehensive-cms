<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateTableAd extends Migrator
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
        $table  =  $this->table('ad',array('comment'=>'广告','engine'=>'InnoDB'));
        $table->addColumn('ad_name', 'string',array('limit'  =>  100,'comment' =>'名称','default'=>''))
              ->addColumn('group_id', 'string',array('limit'  =>  30,'comment' =>'所属分组id','default'=>''))
              ->addColumn('img_url', 'string',array('limit'  =>  255,'comment' =>'图片地址','default'=>''))
              ->addColumn('sort', 'string',array('limit'  =>  11,'comment' =>'排序','default'=>''))
              ->addColumn('status', 'string',array('limit'  =>  11,'comment' =>'状态','default'=>''))
              ->addColumn('link', 'string',array('limit'  =>  255,'comment' =>'链接','default'=>''))
              ->addColumn('add_time', 'string',array('limit'  =>  15,'comment' =>'添加时间','default'=>0))
              ->addColumn('delete_time', 'string',array('limit'  =>  15,'comment' =>'删除时间','null'=>true))
              ->addIndex('group_id')
              ->create();
    }
}
