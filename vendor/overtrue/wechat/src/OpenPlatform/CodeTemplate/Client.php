<?php

/*
 * This file is part of the overtrue/wechat.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EasyWeChat\OpenPlatform\CodeTemplate;

use EasyWeChat\Kernel\BaseClient;

/**
 * Class Client.
 *
 * @author caikeal <caiyuezhang@gmail.com>
 */
class Client extends BaseClient
{
    /**
     * 获取草稿箱内的所有临时代码草稿
     *
     * @return mixed
     */
    public function getDrafts()
    {
        return $this->httpGet('wxa/gettemplatedraftlist');
    }

    /**
     * 将草稿箱的草稿选为小程序代码模版.
     *
     * @param int $draftId
     *
     * @return mixed
     */
    public function createFromDraft(int $draftId)
    {
        $params = [
            'draft_id' => $draftId,
        ];

        return $this->httpPostJson('wxa/addtotemplate', $params);
    }

    /**
     * 获取代码模版库中的所有小程序代码模版.
     *
     * @return mixed
     */
    public function list()
    {
        return $this->httpGet('wxa/gettemplatelist');
    }

    /**
     * 删除指定小程序代码模版.
     *
     * @param $templateId
     *
     * @return mixed
     */
    public function delete($templateId)
    {
        $params = [
            'template_id' => $templateId,
        ];

        return $this->httpPostJson('wxa/deletetemplate', $params);
    }
}
