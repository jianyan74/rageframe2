<?php

use yii\db\Migration;

class m180921_014940_live_room extends Migration
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
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='直播_房间表'");
        
        /* 索引设置 */
        
        
        /* 表数据 */
        $this->insert('{{%live_room}}',['id'=>'9','member_id'=>'1','title'=>'测试直播','cover'=>'','room_num'=>'0','recommend'=>'0','like_num'=>'0','watch_num'=>'0','max_watch_num'=>'0','sort'=>'0','view'=>'0','push_path'=>'rtmp://video-center.alivecdn.com/live/','push_path_arg'=>'96e6e0a6-5cc3-11e8-90c2-14b31f25e078?vhost=push.yllook.com&auth_key=1527490374-0-0-29a2d4201537baea5880227f9f18617f','pull_path_rtmp'=>'rtmp://push.yllook.com/live/96e6e0a6-5cc3-11e8-90c2-14b31f25e078?auth_key=1527490374-0-0-29a2d4201537baea5880227f9f18617f','pull_path_flv'=>'http://push.yllook.com/live/96e6e0a6-5cc3-11e8-90c2-14b31f25e078.flv?auth_key=1527490374-0-0-28d39f4d53bce7d7cd36ad8e280c8635','pull_path_m3u8'=>'http://push.yllook.com/live/96e6e0a6-5cc3-11e8-90c2-14b31f25e078.m3u8?auth_key=1527490374-0-0-d9bfb2be5766c441f1257885332d6057','start_time'=>'1526885574','end_time'=>'1527058374','status'=>'1','created_at'=>'1526885574','updated_at'=>'1526885575']);
        $this->insert('{{%live_room}}',['id'=>'10','member_id'=>'1','title'=>'测试直播','cover'=>'','room_num'=>'0','recommend'=>'0','like_num'=>'0','watch_num'=>'0','max_watch_num'=>'0','sort'=>'0','view'=>'0','push_path'=>'','push_path_arg'=>'','pull_path_rtmp'=>'','pull_path_flv'=>'','pull_path_m3u8'=>'','start_time'=>'1526887501','end_time'=>'1527060301','status'=>'1','created_at'=>'1526887501','updated_at'=>'1526887501']);
        $this->insert('{{%live_room}}',['id'=>'11','member_id'=>'1','title'=>'测试直播','cover'=>'','room_num'=>'0','recommend'=>'0','like_num'=>'0','watch_num'=>'0','max_watch_num'=>'0','sort'=>'0','view'=>'0','push_path'=>'rtmp://video-center.alivecdn.com/live/','push_path_arg'=>'37dc1612-5cc8-11e8-979d-14b31f25e078?vhost=push.yllook.com&auth_key=1527492362-0-0-8a21701f618fe2bd0a21211cf59b6f51','pull_path_rtmp'=>'rtmp://push.yllook.com/live/37dc1612-5cc8-11e8-979d-14b31f25e078?auth_key=1527492362-0-0-8a21701f618fe2bd0a21211cf59b6f51','pull_path_flv'=>'http://push.yllook.com/live/37dc1612-5cc8-11e8-979d-14b31f25e078.flv?auth_key=1527492362-0-0-3a575be6da7eaf73458c1a131ca2738f','pull_path_m3u8'=>'http://push.yllook.com/live/37dc1612-5cc8-11e8-979d-14b31f25e078.m3u8?auth_key=1527492362-0-0-c47876299b013c9fc3c7c23b9d4c30ae','start_time'=>'1526887562','end_time'=>'1527060362','status'=>'1','created_at'=>'1526887562','updated_at'=>'1526887563']);
        $this->insert('{{%live_room}}',['id'=>'12','member_id'=>'1','title'=>'测试直播','cover'=>'','room_num'=>'0','recommend'=>'0','like_num'=>'0','watch_num'=>'0','max_watch_num'=>'0','sort'=>'0','view'=>'0','push_path'=>'rtmp://video-center.alivecdn.com/live/','push_path_arg'=>'e67b9800-5cc8-11e8-b7e5-14b31f25e078?vhost=push.yllook.com&auth_key=1527492655-0-0-79e72ee0df102b76cd401bf092aa8fc1','pull_path_rtmp'=>'rtmp://push.yllook.com/live/e67b9800-5cc8-11e8-b7e5-14b31f25e078?auth_key=1527492655-0-0-79e72ee0df102b76cd401bf092aa8fc1','pull_path_flv'=>'http://push.yllook.com/live/e67b9800-5cc8-11e8-b7e5-14b31f25e078.flv?auth_key=1527492655-0-0-1e23c49c83a2c9106371578d5333b555','pull_path_m3u8'=>'http://push.yllook.com/live/e67b9800-5cc8-11e8-b7e5-14b31f25e078.m3u8?auth_key=1527492655-0-0-effae64577f930a011c68a57d6973a9b','start_time'=>'1526887855','end_time'=>'1527060655','status'=>'1','created_at'=>'1526887855','updated_at'=>'1526887856']);
        
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

