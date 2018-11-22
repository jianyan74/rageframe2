<?php

use yii\db\Migration;

class m181122_095053_sys_addons_binding extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%sys_addons_binding}}', [
            'id' => 'int(11) NOT NULL AUTO_INCREMENT COMMENT \'主键\'',
            'addons_name' => 'varchar(30) NOT NULL DEFAULT \'\' COMMENT \'插件名称\'',
            'entry' => 'varchar(10) NOT NULL DEFAULT \'\' COMMENT \'入口类别[menu,cover]\'',
            'title' => 'varchar(50) NOT NULL DEFAULT \'\' COMMENT \'名称\'',
            'route' => 'varchar(30) NOT NULL DEFAULT \'\' COMMENT \'路由\'',
            'icon' => 'varchar(50) NULL DEFAULT \'\' COMMENT \'图标\'',
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='系统_插件菜单表'");
        
        /* 索引设置 */
        $this->createIndex('addons_name','{{%sys_addons_binding}}','addons_name',0);
        
        
        /* 表数据 */
        $this->insert('{{%sys_addons_binding}}',['id'=>'706','addons_name'=>'RfSignShoppingDay','entry'=>'menu','title'=>'奖品管理','route'=>'award/index','icon'=>'']);
        $this->insert('{{%sys_addons_binding}}',['id'=>'707','addons_name'=>'RfSignShoppingDay','entry'=>'menu','title'=>'中奖记录','route'=>'record/index','icon'=>'']);
        $this->insert('{{%sys_addons_binding}}',['id'=>'708','addons_name'=>'RfSignShoppingDay','entry'=>'menu','title'=>'用户管理','route'=>'user/index','icon'=>'']);
        $this->insert('{{%sys_addons_binding}}',['id'=>'709','addons_name'=>'RfSignShoppingDay','entry'=>'cover','title'=>'首页导航','route'=>'index/index','icon'=>'']);
        $this->insert('{{%sys_addons_binding}}',['id'=>'710','addons_name'=>'RfArticle','entry'=>'menu','title'=>'单页管理','route'=>'article-single/index','icon'=>'fa fa-pencil-square-o']);
        $this->insert('{{%sys_addons_binding}}',['id'=>'711','addons_name'=>'RfArticle','entry'=>'menu','title'=>'文章管理','route'=>'article/index','icon'=>'fa fa-list']);
        $this->insert('{{%sys_addons_binding}}',['id'=>'712','addons_name'=>'RfArticle','entry'=>'menu','title'=>'文章分类','route'=>'article-cate/index','icon'=>'']);
        $this->insert('{{%sys_addons_binding}}',['id'=>'713','addons_name'=>'RfArticle','entry'=>'menu','title'=>'文章标签','route'=>'article-tag/index','icon'=>'fa fa-tags']);
        $this->insert('{{%sys_addons_binding}}',['id'=>'714','addons_name'=>'RfArticle','entry'=>'menu','title'=>'回收站','route'=>'article/recycle','icon'=>'fa fa-trash']);
        $this->insert('{{%sys_addons_binding}}',['id'=>'715','addons_name'=>'RfArticle','entry'=>'cover','title'=>'首页入口','route'=>'index/index','icon'=>'']);
        $this->insert('{{%sys_addons_binding}}',['id'=>'716','addons_name'=>'RfExample','entry'=>'menu','title'=>'Curd','route'=>'curd/index','icon'=>'']);
        $this->insert('{{%sys_addons_binding}}',['id'=>'717','addons_name'=>'RfExample','entry'=>'menu','title'=>'Curd For Grid','route'=>'grid-curd/index','icon'=>'']);
        $this->insert('{{%sys_addons_binding}}',['id'=>'718','addons_name'=>'RfExample','entry'=>'menu','title'=>'MongoDb Curd','route'=>'mongo-db-curd/index','icon'=>'']);
        $this->insert('{{%sys_addons_binding}}',['id'=>'719','addons_name'=>'RfExample','entry'=>'menu','title'=>'Elasticsearch','route'=>'elastic-search/index','icon'=>'']);
        $this->insert('{{%sys_addons_binding}}',['id'=>'720','addons_name'=>'RfExample','entry'=>'menu','title'=>'Xunsearch','route'=>'xunsearch/index','icon'=>'']);
        $this->insert('{{%sys_addons_binding}}',['id'=>'721','addons_name'=>'RfExample','entry'=>'menu','title'=>'消息队列','route'=>'queue/index','icon'=>'']);
        $this->insert('{{%sys_addons_binding}}',['id'=>'722','addons_name'=>'RfExample','entry'=>'menu','title'=>'无限级分类','route'=>'cate/index','icon'=>'']);
        $this->insert('{{%sys_addons_binding}}',['id'=>'723','addons_name'=>'RfExample','entry'=>'menu','title'=>'截取视频指定帧','route'=>'video/cut-image','icon'=>'']);
        $this->insert('{{%sys_addons_binding}}',['id'=>'724','addons_name'=>'RfExample','entry'=>'menu','title'=>'Excel导入数据','route'=>'excel/index','icon'=>'']);
        $this->insert('{{%sys_addons_binding}}',['id'=>'725','addons_name'=>'RfExample','entry'=>'cover','title'=>'首页导航','route'=>'index/index','icon'=>'']);
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%sys_addons_binding}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

