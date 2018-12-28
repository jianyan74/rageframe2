<?php

use yii\db\Migration;

class m181228_025313_sys_addons_auth_item extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%sys_addons_auth_item}}', [
            'addons_name' => 'varchar(40) NOT NULL DEFAULT \'\' COMMENT \'插件名称\'',
            'route' => 'varchar(64) NOT NULL DEFAULT \'\' COMMENT \'插件路由\'',
            'description' => 'text NULL',
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='系统_插件_权限表'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfArticle','route'=>'article-single/index','description'=>'单页管理']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfArticle','route'=>'article-single/edit','description'=>'单页编辑']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfArticle','route'=>'article-single/ajax-update','description'=>'单页状态修改']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfArticle','route'=>'article-single/delete','description'=>'单页删除']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfArticle','route'=>'article/index','description'=>'文章首页']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfArticle','route'=>'article/edit','description'=>'文章编辑']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfArticle','route'=>'article/ajax-update','description'=>'文章状态修改']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfArticle','route'=>'article/hide','description'=>'文章删除']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfArticle','route'=>'article-cate/index','description'=>'文章分类首页']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfArticle','route'=>'article-cate/ajax-edit','description'=>'文章分类编辑']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfArticle','route'=>'article-cate/ajax-update','description'=>'文章分类状态修改']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfArticle','route'=>'article-cate/delete','description'=>'文章分类删除']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfArticle','route'=>'article-tag/index','description'=>'文章标签首页']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfArticle','route'=>'article-tag/ajax-edit','description'=>'文章标签编辑']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfArticle','route'=>'article-tag/ajax-update','description'=>'文章标签状态修改']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfArticle','route'=>'article-tag/delete','description'=>'文章标签删除']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfArticle','route'=>'article/recycle','description'=>'回收站']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfArticle','route'=>'article/show','description'=>'回收站还原']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfArticle','route'=>'article/delete','description'=>'回收站删除']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfExample','route'=>'curd/index','description'=>'Curd首页']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfExample','route'=>'curd/edit','description'=>'Curd编辑']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfExample','route'=>'curd/ajax-update','description'=>'Curd状态修改']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfExample','route'=>'curd/export','description'=>'Curd导出']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfExample','route'=>'curd/delete','description'=>'Curd删除']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfExample','route'=>'grid-curd/index','description'=>'Grid首页']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfExample','route'=>'grid-curd/edit','description'=>'Grid编辑']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfExample','route'=>'grid-curd/ajax-update','description'=>'Grid状态修改']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfExample','route'=>'grid-curd/delete','description'=>'Grid删除']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfExample','route'=>'mongo-db-curd/index','description'=>'MongoDb首页']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfExample','route'=>'mongo-db-curd/edit','description'=>'MongoDb编辑']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfExample','route'=>'mongo-db-curd/ajax-update','description'=>'MongoDb状态修改']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfExample','route'=>'mongo-db-curd/delete','description'=>'MongoDb删除']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfExample','route'=>'elastic-search/index','description'=>'ES首页']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfExample','route'=>'elastic-search/edit','description'=>'ES编辑']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfExample','route'=>'elastic-search/ajax-update','description'=>'ES状态修改']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfExample','route'=>'elastic-search/delete','description'=>'ES删除']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfExample','route'=>'xunsearch/index','description'=>'Xunsearch首页']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfExample','route'=>'xunsearch/edit','description'=>'Xunsearch编辑']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfExample','route'=>'xunsearch/ajax-update','description'=>'Xunsearch状态修改']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfExample','route'=>'xunsearch/delete','description'=>'Xunsearch删除']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfExample','route'=>'queue/index','description'=>'消息队列']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfExample','route'=>'cate/index','description'=>'分类首页']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfExample','route'=>'cate/ajax-edit','description'=>'分类编辑']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfExample','route'=>'cate/ajax-update','description'=>'分类状态修改']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfExample','route'=>'cate/delete','description'=>'分类删除']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfExample','route'=>'video/cut-image','description'=>'截取视频指定帧']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfExample','route'=>'excel/index','description'=>'excel导入数据']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfSignShoppingDay','route'=>'award/index','description'=>'奖品管理']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfSignShoppingDay','route'=>'award/edit','description'=>'奖品编辑']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfSignShoppingDay','route'=>'award/ajax-update','description'=>'奖品状态修改']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfSignShoppingDay','route'=>'award/delete','description'=>'奖品删除']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfSignShoppingDay','route'=>'record/index','description'=>'中奖记录']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfSignShoppingDay','route'=>'record/export','description'=>'中奖导出']);
        $this->insert('{{%sys_addons_auth_item}}',['addons_name'=>'RfSignShoppingDay','route'=>'user/index','description'=>'用户管理']);
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%sys_addons_auth_item}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

