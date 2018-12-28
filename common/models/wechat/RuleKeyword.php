<?php
namespace common\models\wechat;

use Yii;
use common\helpers\AddonHelper;
use common\helpers\ExecuteHelper;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use EasyWeChat\Kernel\Messages\Text;
use EasyWeChat\Kernel\Messages\Image;
use EasyWeChat\Kernel\Messages\Video;
use EasyWeChat\Kernel\Messages\Voice;
use EasyWeChat\Kernel\Messages\News;
use EasyWeChat\Kernel\Messages\NewsItem;

/**
 * This is the model class for table "{{%wechat_rule_keyword}}".
 *
 * @property string $id
 * @property string $rule_id 规则ID
 * @property string $module 模块名
 * @property string $content 内容
 * @property int $type 类别
 * @property int $sort 优先级
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 */
class RuleKeyword extends \yii\db\ActiveRecord
{
    const TYPE_MATCH = 1;
    const TYPE_INCLUDE = 2;
    const TYPE_REGULAR = 3;
    const TYPE_TAKE = 4;

    /**
     * @var array
     */
    public static $typeExplain = [
        self::TYPE_MATCH => '直接匹配关键字',
        self::TYPE_INCLUDE => '正则表达式',
        self::TYPE_REGULAR => '包含关键字',
        self::TYPE_TAKE => '直接接管',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%wechat_rule_keyword}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rule_id', 'type', 'sort', 'status'], 'integer'],
            [['module', 'content'], 'required'],
            [['module'], 'string', 'max' => 50],
            [['content'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'rule_id' => '规则ID',
            'module' => '处理的模块',
            'content' => '内容',
            'type' => '类别',
            'sort' => '排序',
            'status' => '状态',
        ];
    }

    /**
     * 关键字查询匹配
     *
     * @param $content
     * @return bool|mixed
     * @throws \yii\web\NotFoundHttpException
     */
    public static function match($content)
    {
        $keyword = RuleKeyword::find()->where(['or',
            ['and', '{{type}} = :typeMatch', '{{content}} = :content'], // 直接匹配关键字
            ['and', '{{type}} = :typeInclude', 'INSTR(:content, {{content}}) > 0'], // 包含关键字
            ['and', '{{type}} = :typeRegular', ' :content REGEXP {{content}}'], // 正则匹配关键字
        ])->addParams([
            ':content' => $content,
            ':typeMatch' => self::TYPE_MATCH,
            ':typeInclude' => self::TYPE_INCLUDE,
            ':typeRegular' => self::TYPE_REGULAR
        ])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->orderBy('sort desc,id desc')
            ->one();

        if ($keyword)
        {
            // 查询直接接管的
            $takeKeyword = RuleKeyword::find()
                ->where(['type' => self::TYPE_TAKE, 'status' => StatusEnum::ENABLED])
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

            // model 列表
            $modelList = [
                Rule::RULE_MODULE_TEXT => 'common\models\wechat\ReplyText',
                Rule::RULE_MODULE_VIDEO => 'common\models\wechat\ReplyVideo',
                Rule::RULE_MODULE_IMAGES => 'common\models\wechat\ReplyImages',
                Rule::RULE_MODULE_NEWS => 'common\models\wechat\ReplyNews',
                Rule::RULE_MODULE_VOICE => 'common\models\wechat\ReplyVoice',
                Rule::RULE_MODULE_USER_API => 'common\models\wechat\ReplyUserApi',
                Rule::RULE_MODULE_ADDON => 'common\models\wechat\ReplyAddon',
            ];

            /* @var $model \yii\db\ActiveRecord */
            $model = $modelList[$keyword->module]::find()
                ->where(['rule_id' => $keyword->rule_id])
                ->one();

            switch ($keyword->module)
            {
                // 文字回复
                case  Rule::RULE_MODULE_TEXT :
                    return new Text($model->content);
                    break;
                // 图文回复
                case  Rule::RULE_MODULE_NEWS :
                    $news = $model->news;
                    $newsList = [];
                    if (!$news) return false;
                    foreach ($news as $vo)
                    {
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
                case  Rule::RULE_MODULE_IMAGES :
                    return new Image($model->media_id);
                    break;
                // 视频回复
                case Rule::RULE_MODULE_VIDEO :
                    return new Video($model->media_id, [
                        'title' => $model->title,
                        'description' => $model->description,
                    ]);
                    break;
                // 语音回复
                case Rule::RULE_MODULE_VOICE :
                    return new Voice($model->media_id);
                    break;
                // 自定义接口回复
                case Rule::RULE_MODULE_USER_API :
                    if ($apiContent = ReplyUserApi::getApiData($model, Yii::$app->params['wechatMessage']))
                    {
                        return $apiContent;
                    }

                    return $model->default;
                    break;
                // 默认为模块回复
                default :
                    $class = AddonHelper::getAddonMessage($keyword->module);
                    return ExecuteHelper::map($class, 'run', Yii::$app->params['wechatMessage']);
                    break;
            }
        }

        return false;
    }

    /**
     * 获取规则关键字类别
     *
     * @param array $ruleKeyword
     * @return array
     */
    public static function getRuleKeywordsType($ruleKeyword)
    {
        !$ruleKeyword && $ruleKeyword = [];

        // 关键字列表
        $ruleKeywords = [
            self::TYPE_MATCH => [],
            self::TYPE_REGULAR => [],
            self::TYPE_INCLUDE => [],
            self::TYPE_TAKE => [],
        ];

        foreach ($ruleKeyword as $value)
        {
            $ruleKeywords[$value['type']][] = $value['content'];
        }

        return $ruleKeywords;
    }

    /**
     * 更新关键字
     *
     * @param $rule
     * @param $ruleKeywords
     * @param $defaultRuleKeywords
     * @throws \yii\db\Exception
     */
    public static function updateKeywords($rule, $ruleKeywords, $defaultRuleKeywords)
    {
        // 判断是否有直接接管
        if (!isset($ruleKeywords[self::TYPE_TAKE]))
        {
            RuleKeyword::deleteAll(['rule_id' => $rule->id, 'type' => self::TYPE_TAKE]);
        }

        // 给关键字赋值默认值
        foreach (self::$typeExplain as $key => $value)
        {
            !isset($ruleKeywords[$key]) && $ruleKeywords[$key] = [];
        }

        $rows = [];

        foreach ($ruleKeywords as $key => &$vo)
        {
            // 去重
            $keyword = array_unique($vo);

            // 删除不存在的关键字
            if ($diff = array_diff($defaultRuleKeywords[$key], $keyword))
            {
                RuleKeyword::deleteAll(['and', ['rule_id' => $rule->id], ['type' => $key], ['in', 'content', array_values($diff)]]);
            }

            // 判断是否有更改不更改直接不插入
            if (empty($keyword = array_diff($keyword, $defaultRuleKeywords[$key])))
            {
                $keyword = [];
            }

            // 插入数据
            foreach ($keyword as $content)
            {
                $rows[] = [$rule->id, $rule->module, $content, $rule->sort, $rule->status, $key];
            }
        }

        // 插入数据
        $field = ['rule_id', 'module', 'content', 'sort', 'status', 'type'];
        !empty($rows) && Yii::$app->db->createCommand()->batchInsert(RuleKeyword::tableName(), $field, $rows)->execute();
    }

    /**
     * @param string $fields
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getList($fields = 'id, content')
    {
        return self::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->select($fields)
            ->asArray()
            ->all();
    }

    /**
     * 验证是否有直接接管
     *
     * @param $ruleKeyword
     * @return bool
     */
    public static function verifyTake($ruleKeyword)
    {
        foreach ($ruleKeyword as $item)
        {
            if ($item->type == self::TYPE_TAKE)
            {
                return true;
            }
        }

        return false;
    }
}
