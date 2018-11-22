<?php
namespace api\controllers;

use Yii;
use common\helpers\ResultDataHelper;
use common\helpers\FileHelper;

/**
 * Class WebHookController
 * @package api\controllers
 */
class WebHookController extends OffAuthController
{
    public $modelClass = '';

    /**
     * webhook 码云自动更新
     *
     * 注意: 需要开启 shell_exec 函数
     * @return bool|string
     */
    public function actionGitee()
    {
        if (!($data = json_decode(Yii::$app->request->rawBody, true)) && !Yii::$app->request->isPost)
        {
            return ResultDataHelper::api(422, '请提交正确的格式信息');
        }

        $savePath = Yii::$app->debris->config('webhook_gitee_save_path');
        $gitPath = Yii::$app->debris->config('webhook_gitee_git_path');

        if ($data['password'] != Yii::$app->debris->config('webhook_gitee_password'))
        {
            return ResultDataHelper::api(422, 'webhook密码验证错误');
        }

        if ($data['ref'] == 'refs/heads/master' && $data['total_commits_count'] > 0)
        {
            $resLogPath = Yii::getAlias('@runtime') . '/webhook/' . date('Y-m-d') . '/';
            FileHelper::mkdirs($resLogPath);

            // 写入日志到log文件中
            shell_exec("cd {$savePath} && git pull {$gitPath} >> {$resLogPath}out.txt 2>&1");
            $resLog = PHP_EOL . "start -------- " . date('Y-m-d H:i:s') .  PHP_EOL;
            $resLog .= $data['user_name'] . ' 在 ' . date('Y-m-d H:i:s') . ' 向 ' . $data['repository']['name'] . ' 项目的 ' . $data['ref'] . ' 分支push了 ' . $data['total_commits_count'] . ' 个commit：'. PHP_EOL;
            $resLog .= 'end -------------------------' . PHP_EOL;
            file_put_contents($resLogPath . "webhook.log", $resLog, FILE_APPEND);

            // 记录码云提交的日志
            $giteeLog = PHP_EOL . "start -------- " . date('Y-m-d H:i:s') .  PHP_EOL;
            $giteeLog .= json_encode($data) . PHP_EOL;
            $giteeLog .= "end --------" . PHP_EOL;
            file_put_contents($resLogPath . "giteeData.log", $giteeLog, FILE_APPEND);

            return '拉取成功';
        }

        return ResultDataHelper::api(422, '未有新版本更新');
    }
}