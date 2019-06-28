<?php
namespace backend\modules\wechat\forms;

use yii\helpers\Json;
use common\helpers\ArrayHelper;
use common\models\wechat\Rule;

/**
 * Class RuleForm
 * @package backend\modules\wechat\models
 * @author jianyan74 <751393839@qq.com>
 */
class RuleForm extends Rule
{
    public $keyword;

    public $text;
    public $image;
    public $news;
    public $video;
    public $voice;

    public $api_url;
    public $default;
    public $cache_time;
    public $description;

    /**
     * @return array
     */
    public function rules()
    {
        $rule = parent::rules();
        $rule[] = [['keyword'], 'required', 'message' => '关键字不能为空'];
        $rule[] = [['cache_time'], 'integer', 'min' => 0];
        $rule[] = [['api_url', 'description', 'api_url'], 'string', 'max' => 255];
        $rule[] = [['default'], 'string', 'max' => 50];
        $rule[] = [['text', 'image', 'news', 'video', 'voice'], 'string'];
        $rule[] = [['name'], 'verifyRequired'];
        return $rule;
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        $labels = [
            'api_url' => '接口地址',
            'description' => '备注说明',
            'default' => '默认回复文字',
            'cache_time' => '缓存时间',
            'text' => '内容',
            'image' => '图片',
            'video' => '视频',
            'voice' => '音频',
            'news' => '图文',
        ];

        return ArrayHelper::merge(parent::attributeLabels(), $labels);
    }

    public function verifyRequired($attribute)
    {
        if ($this->module == Rule::RULE_MODULE_TEXT && !$this->text) {
            $this->addError($attribute, '请在下方填写内容');
        }

        if ($this->module == Rule::RULE_MODULE_IMAGE && !$this->image) {
            $this->addError($attribute, '请在下方选择图片');
        }

        if ($this->module == Rule::RULE_MODULE_VIDEO && !$this->video) {
            $this->addError($attribute, '请在下方选择视频');
        }

        if ($this->module == Rule::RULE_MODULE_VOICE && !$this->voice) {
            $this->addError($attribute, '请在下方选择语音');
        }

        if ($this->module == Rule::RULE_MODULE_NEWS && !$this->news) {
            $this->addError($attribute, '请在下方选择图文');
        }

        if ($this->module == Rule::RULE_MODULE_USER_API && !$this->api_url) {
            $this->addError($attribute, '请在下方选择接口地址');
        }
    }

    public function afterFind()
    {
        if ($this->module == Rule::RULE_MODULE_TEXT) {
            $this->text = $this->data;
        }

        if ($this->module == Rule::RULE_MODULE_IMAGE) {
            $this->image = $this->data;
        }

        if ($this->module == Rule::RULE_MODULE_VIDEO) {
            $this->video = $this->data;
        }

        if ($this->module == Rule::RULE_MODULE_VOICE) {
            $this->voice = $this->data;
        }

        if ($this->module == Rule::RULE_MODULE_NEWS) {
            $this->news = $this->data;
        }

        if ($this->module == Rule::RULE_MODULE_USER_API) {
            $data = Json::decode($this->data);
            $this->api_url = $data['api_url'];
            $this->default = $data['default'];
            $this->cache_time = $data['cache_time'];
            $this->description = $data['description'];
        }

        parent::afterFind();
    }

    public function beforeSave($insert)
    {
        if ($this->module == Rule::RULE_MODULE_TEXT) {
            $this->data = $this->text;
        }

        if ($this->module == Rule::RULE_MODULE_IMAGE) {
            $this->data = $this->image;
        }

        if ($this->module == Rule::RULE_MODULE_VIDEO) {
            $this->data = $this->video;
        }

        if ($this->module == Rule::RULE_MODULE_VOICE) {
            $this->data = $this->voice;
        }

        if ($this->module == Rule::RULE_MODULE_NEWS) {
            $this->data = $this->news;
        }

        if ($this->module == Rule::RULE_MODULE_USER_API) {
            $data = [
                'api_url' => $this->api_url,
                'default' => $this->default,
                'cache_time' => $this->cache_time,
                'description' => $this->description,
            ];

            $this->data = Json::encode($data);
        }

        return parent::beforeSave($insert);
    }
}
