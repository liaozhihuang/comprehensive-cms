<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateTableUser extends Migrator
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
        $table  =  $this->table('user',array('comment'=>'后台用户表','engine'=>'InnoDB'));
        $table->addColumn('user_name', 'string',array('limit'  =>  40,'comment' =>'用户名','default'=>''))
              ->addColumn('password', 'string',array('limit'  =>  50,'comment' =>'密码','default'=>''))
              ->addColumn('mobile', 'string',array('limit'  =>  11,'comment' =>'手机号码','default'=>''))
              ->addColumn('head', 'string',array('limit'  =>  255,'comment' =>'头像','default'=>''))
              ->addColumn('login_times', 'integer',array('limit'  =>  255,'comment' =>'登陆次数','default'=>0))
              ->addColumn('last_login_ip', 'string',array('limit'  =>  30,'comment' =>'登陆ip','default'=>0))
              ->addColumn('last_login_time', 'string',array('limit'  =>  30,'comment' =>'最后登录时间','default'=>0))
              ->addColumn('real_name', 'string',array('limit'  =>  50,'comment' =>'真实姓名','default'=>''))
              ->addColumn('status', 'integer',array('limit'  =>  1,'comment' =>'状态','default'=>1))
              ->addColumn('sites', 'integer',array('limit'  =>  11,'comment' =>'站点数量','default'=>1))
              ->addColumn('role', 'integer',array('limit'  =>  11,'comment' =>'用户角色','default'=>0))
              ->addColumn('token','string',array('limit'=>80,'comment'=>'token','null'=>true))
              ->addColumn('add_time', 'string',array('limit'  =>  15,'comment' =>'添加时间','default'=>'0'))
              ->addColumn('delete_time', 'string',array('limit'  =>  15,'comment' =>'删除时间','null'=>true))
              ->addIndex('role', array('type'  =>  'index'))
              ->addIndex('sites', array('type'  =>  'index'))
              ->addIndex('token', array('type'  =>  'index'))
              ->addIndex('user_name', array('type'  =>  'unique'))
              ->create();
    }
}
