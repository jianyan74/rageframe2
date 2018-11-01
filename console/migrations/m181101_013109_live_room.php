<?php

use yii\db\Migration;

class m181101_013109_live_room extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');
        
        /* 创建表 */
        $this->createTable('{{%live_room}}', [
            'id' => 'int(11) NOT NULL AUTO_INCREMENT',
            'member_id' => 'int(10) NULL DEFAULT \'0\' COMMENT \'会员id\'',
            'title' => 'varchar(100) NULL DEFAULT \'\' COMMENT \'房间名称\'',
            'cover' => 'varchar(255) NULL DEFAULT \'\' COMMENT \'封面\'',
            'room_num' => 'int(10) unsigned NULL DEFAULT \'0\' COMMENT \'房间号\'',
            'recommend' => 'tinyint(4) unsigned NULL DEFAULT \'0\' COMMENT \'推荐位\'',
            'like_num' => 'int(10) unsigned NULL DEFAULT \'0\' COMMENT \'喜欢人数\'',
            'watch_num' => 'int(10) unsigned NULL DEFAULT \'0\' COMMENT \'观看人数\'',
            'max_watch_num' => 'int(10) unsigned NULL DEFAULT \'0\' COMMENT \'最多观看人数\'',
            'sort' => 'int(10) NULL DEFAULT \'0\' COMMENT \'排序\'',
            'view' => 'int(10) unsigned NULL DEFAULT \'0\' COMMENT \'浏览量\'',
            'push_path' => 'varchar(100) NULL DEFAULT \'\' COMMENT \'推流地址\'',
            'push_path_arg' => 'varchar(200) NULL DEFAULT \'\' COMMENT \'推流变量地址\'',
            'pull_path_rtmp' => 'varchar(200) NULL DEFAULT \'\' COMMENT \'拉流rtmp地址\'',
            'pull_path_flv' => 'varchar(200) NULL DEFAULT \'\' COMMENT \'拉流rtmp地址\'',
            'pull_path_m3u8' => 'varchar(200) NULL DEFAULT \'\' COMMENT \'拉流rtmp地址\'',
            'start_time' => 'int(10) unsigned NULL DEFAULT \'0\' COMMENT \'直播开始时间\'',
            'end_time' => 'int(10) unsigned NULL DEFAULT \'0\' COMMENT \'直播结束时间\'',
            'status' => 'tinyint(4) NOT NULL DEFAULT \'1\' COMMENT \'状态(-1:已删除,0:禁用,1:正常)\'',
            'created_at' => 'int(10) unsigned NULL DEFAULT \'0\' COMMENT \'创建时间\'',
            'updated_at' => 'int(10) unsigned NULL DEFAULT \'0\' COMMENT \'修改时间\'',
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='直播_房间表'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        
        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%live_room}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

