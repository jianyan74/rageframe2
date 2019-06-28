<?php
namespace services\wechat;

use Yii;
use common\components\Service;
use common\enums\StatusEnum;
use common\models\wechat\Rule;
use common\models\wechat\RuleKeyword;
use common\helpers\AddonHelper;
use common\helpers\ExecuteHelper;
use common\helpers\ArrayHelper;
use EasyWeChat\Kernel\Messages\Text;
use EasyWeChat\Kernel\Messages\Image;
use EasyWeChat\Kernel\Messages\Video;
use EasyWeChat\Kernel\Messages\Voice;
use EasyWeChat\Kernel\Messages\News;
use EasyWeChat\Kernel\Messages\NewsItem;

/**
 * Class RuleKeywordService
 * @package services\wechat
 * @author jianyan74 <751393839@qq.com>
 */
class RuleKeywordService extends Service
{
    /**
     * 关键字查询匹配
     *
     * @param $content
     * @return bool|mixed
     * @throws \yii\web\NotFoundHttpException
     */
    public function match($content)
    {
        $keyword = RuleKeyword::find()->where(['or',
            ['and', '{{type}} = :typeMatch', '{{content}} = :content'], // 直接匹配关键字
            ['and', '{{type}} = :typeInclude', 'INSTR(:content, {{content}}) > 0'], // 包含关键字
            ['and', '{{type}} = :typeRegular', ' :content REGEXP {{content}}'], // 正则匹配关键字
        ])->addParams([
            ':content' => $content,
            ':typeMatch' => RuleKeyword::TYPE_MATCH,
            ':typeInclude' => RuleKeyword::TYPE_INCLUDE,
            ':typeRegular' => RuleKeyword::TYPE_REGULAR
        ])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->orderBy('sort desc,id desc')
            ->one();

        if ($keyword) {
            // 查询直接接管的
            $takeKeyword = RuleKeyword::find()
                ->where(['type' => RuleKeyword::TYPE_TAKE, 'status' => StatusEnum::ENABLED])
                ->andFilterWhere(['>', 'sort', $keyword->sort])
                ->orderBy('sort desc, id desc')
                ->one();
            $takeKeyword && $keyword = $takeKeyword;

            // 历史消息记录
            Yii::$app->params['msgHistory'] = ArrayHelper::merge(Yii::$app->params['msgHistory'], [
                'keyword_id' => $keyword->id,
                'rule_id' => $keyword->rule_id,
                'module' => $keyword->module,
            ]);

            /* @var $model Rule */
            $model = Rule::find()
                ->where(['id' => $keyword->rule_id])
                ->one();

            switch ($keyword->module) {
                // 文字回复
                case  Rule::RULE_MODULE_TEXT :
                    return new Text($model->data);
                    break;
                // 图文回复
                case  Rule::RULE_MODULE_NEWS :
                    $news = $model->news;
                    $newsList = [];
                    if (!$news) return false;
                    foreach ($news as $vo) {
                        $newsList[] = new NewsItem([
                            'title' => $vo['title'],
                            'description' => $vo['digest'],
                            'url' => $vo['media_url'],
                            'image' => $vo['thumb_url'],
                        ]);
                    }

                    return new News($newsList);
                    break;
                // 图片回复
                case  Rule::RULE_MODULE_IMAGE :
                    return new Image($model->data);
                    break;
                // 视频回复
                case Rule::RULE_MODULE_VIDEO :
                    return new Video($model->data, [
                        'title' => $model->attachment->title,
                        'description' => $model->attachment->description,
                    ]);
                    break;
                // 语音回复
                case Rule::RULE_MODULE_VOICE :
                    return new Voice($model->data);
                    break;
                // 自定义接口回复
                case Rule::RULE_MODULE_USER_API :
                    if ($apiContent = Yii::$app->services->wechatRule->getApiData($model, Yii::$app->services->wechatMessage->getMessage())) {
                        return $apiContent;
                    }

                    return $model->default;
                    break;
                // 模块回复
                case Rule::RULE_MODULE_ADDON :
                    Yii::$app->params['msgHistory']['addons_name'] = $model->data;

                    $class = AddonHelper::getAddonMessage($model->data);
                    return ExecuteHelper::map($class, 'run', Yii::$app->services->wechatMessage->getMessage());
                    break;
                default :
                    return false;
                    break;
            }
        }

        return false;
    }


    /**
     * 验证是否有直接接管
     *
     * @param $ruleKeyword
     * @return bool
     */
    public function verifyTake($ruleKeyword)
    {
        foreach ($ruleKeyword as $item) {
            if ($item->type == RuleKeyword::TYPE_TAKE) {
                return true;
            }
        }

        return false;
    }
    
    /**
     * 更新关键字
     *
     * @param $rule
     * @param $ruleKeywords
     * @param $defaultRuleKeywords
     * @throws \yii\db\Exception
     */
    public function update($rule, $ruleKeywords, $defaultRuleKeywords)
    {
        // 判断是否有直接接管
        if (!isset($ruleKeywords[RuleKeyword::TYPE_TAKE])) {
            RuleKeyword::deleteAll(['rule_id' => $rule->id, 'type' => RuleKeyword::TYPE_TAKE]);
        }

        // 给关键字赋值默认值
        foreach (RuleKeyword::$typeExplain as $key => $value) {
            !isset($ruleKeywords[$key]) && $ruleKeywords[$key] = [];
        }

        $rows = [];

        $merchant_id = Yii::$app->services->merchant->getId();
        foreach ($ruleKeywords as $key => &$vo) {
            // 去重
            $keyword = array_unique($vo);

            // 删除不存在的关键字
            if ($diff = array_diff($defaultRuleKeywords[$key], $keyword)) {
                RuleKeyword::deleteAll(['and', ['rule_id' => $rule->id], ['type' => $key], ['in', 'content', array_values($diff)]]);
            }
            // 判断是否有更改不更改直接不插入
            if (empty($keyword = array_diff($keyword, $defaultRuleKeywords[$key]))) {
                $keyword = [];
            }

            // 插入数据
            foreach ($keyword as $content) {
                $rows[] = [$rule->id, $rule->module, $content, $rule->sort, $rule->status, $key, $merchant_id];
            }
        }

        // 插入数据
        $field = ['rule_id', 'module', 'content', 'sort', 'status', 'type', 'merchant_id'];
        !empty($rows) && Yii::$app->db->createCommand()->batchInsert(RuleKeyword::tableName(), $field, $rows)->execute();
    }

    /**
     * @param string $fields
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getList($fields = 'id, content')
    {
        return RuleKeyword::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->select($fields)
            ->asArray()
            ->all();
    }

    /**
     * 获取规则关键字类别
     *
     * @param array $ruleKeyword
     * @return array
     */
    public function getType($ruleKeyword)
    {
        !$ruleKeyword && $ruleKeyword = [];

        // 关键字列表
        $ruleKeywords = [
            RuleKeyword::TYPE_MATCH => [],
            RuleKeyword::TYPE_REGULAR => [],
            RuleKeyword::TYPE_INCLUDE => [],
            RuleKeyword::TYPE_TAKE => [],
        ];

        foreach ($ruleKeyword as $value) {
            $ruleKeywords[$value['type']][] = $value['content'];
        }

        return $ruleKeywords;
    }
}