<?php
namespace JPush;
use InvalidArgumentException;

class DevicePayload {
    private $client;

    /**
     * DevicePayload constructor.
     * @param $client JPush
     */
    public function __construct($client)
    {
        $this->client = $client;
    }

    public function getDevices($registrationId) {
        $url = $this->client->makeURL('device') . $registrationId;
        return Http::get($this->client, $url);
    }

    public function updateAlias($registration_id, $alias) {
        return $this->updateDevice($registration_id, $alias);
    }
    public function addTags($registration_id, $tags) {
        $tags = is_array($tags) ? $tags : array($tags);
        return $this->updateDevice($registration_id, null, null, $tags);
    }
    public function removeTags($registration_id, $tags) {
        $tags = is_array($tags) ? $tags : array($tags);
        return $this->updateDevice($registration_id, null, null, null, $tags);
    }
    public function updateMoblie($registration_id, $mobile) {
        return $this->updateDevice($registration_id, null, $mobile);
    }

    public function clearMobile($registrationId) {
        $url = $this->client->makeURL('device') . $registrationId;
        return Http::post($this->client, $url, ['mobile' => '']);
    }

    public function clearTags($registrationId) {
        $url = $this->client->makeURL('device') . $registrationId;
        return Http::post($this->client, $url, ['tags' => '']);
    }

    public function updateDevice($registrationId, $alias = null, $mobile = null, $addTags = null, $removeTags = null) {
        $payload = array();
        if (!is_string($registrationId)) {
            throw new InvalidArgumentException('Invalid registration_id');
        }

        $aliasIsNull = is_null($alias);
        $mobileIsNull = is_null($mobile);
        $addTagsIsNull = is_null($addTags);
        $removeTagsIsNull = is_null($removeTags);

        if ($aliasIsNull && $addTagsIsNull && $removeTagsIsNull && $mobileIsNull) {
            throw new InvalidArgumentException("alias, addTags, removeTags not all null");
        }

        if (!$aliasIsNull) {
            if (is_string($alias)) {
                $payload['alias'] = $alias;
            } else {
                throw new InvalidArgumentException("Invalid alias string");
            }
        }

        if (!$mobileIsNull) {
            if (is_string($mobile)) {
                $payload['mobile'] = $mobile;
            } else {
                throw new InvalidArgumentException("Invalid mobile string");
            }
        }

        $tags = array();

        if (!$addTagsIsNull) {
            if (is_array($addTags)) {
                $tags['add'] = $addTags;
            } else {
                throw new InvalidArgumentException("Invalid addTags array");
            }
        }

        if (!$removeTagsIsNull) {
            if (is_array($removeTags)) {
                $tags['remove'] = $removeTags;
            } else {
                throw new InvalidArgumentException("Invalid removeTags array");
            }
        }

        if (count($tags) > 0) {
            $payload['tags'] = $tags;
        }

        $url = $this->client->makeURL('device') . $registrationId;
        return Http::post($this->client, $url, $payload);
    }

    public function getTags() {
        $url = $this->client->makeURL('tag');
        return Http::get($this->client, $url);
    }

    public function isDeviceInTag($registrationId, $tag) {
        if (!is_string($registrationId)) {
            throw new InvalidArgumentException("Invalid registration_id");
        }

        if (!is_string($tag)) {
            throw new InvalidArgumentException("Invalid tag");
        }
        $url = $this->client->makeURL('tag') . $tag . '/registration_ids/' . $registrationId;
        return Http::get($this->client, $url);
    }

    public function addDevicesToTag($tag, $addDevices) {
        $device = is_array($addDevices) ? $addDevices : array($addDevices);
        return $this->updateTag($tag, $device, null);
    }
    public function removeDevicesFromTag($tag, $removeDevices) {
        $device = is_array($removeDevices) ? $removeDevices : array($removeDevices);
        return $this->updateTag($tag, null, $device);
    }
    public function updateTag($tag, $addDevices = null, $removeDevices = null) {
        if (!is_string($tag)) {
            throw new InvalidArgumentException("Invalid tag");
        }

        $addDevicesIsNull = is_null($addDevices);
        $removeDevicesIsNull = is_null($removeDevices);

        if ($addDevicesIsNull && $removeDevicesIsNull) {
            throw new InvalidArgumentException("Either or both addDevices and removeDevices must be set.");
        }

        $registrationId = array();

        if (!$addDevicesIsNull) {
            if (is_array($addDevices)) {
                $registrationId['add'] = $addDevices;
            } else {
                throw new InvalidArgumentException("Invalid addDevices");
            }
        }

        if (!$removeDevicesIsNull) {
            if (is_array($removeDevices)) {
                $registrationId['remove'] = $removeDevices;
            } else {
                throw new InvalidArgumentException("Invalid removeDevices");
            }
        }

        $url = $this->client->makeURL('tag') . $tag;
        $payload = array('registration_ids'=>$registrationId);
        return Http::post($this->client, $url, $payload);
    }

    public function deleteTag($tag) {
        if (!is_string($tag)) {
            throw new InvalidArgumentException("Invalid tag");
        }
        $url = $this->client->makeURL('tag') . $tag;
        return Http::delete($this->client, $url);
    }

    public function getAliasDevices($alias, $platform = null) {
        if (!is_string($alias)) {
            throw new InvalidArgumentException("Invalid alias");
        }

        $url = $this->client->makeURL('alias') . $alias;

        if (!is_null($platform)) {
            if (is_array($platform)) {
                $isFirst = true;
                foreach($platform as $item) {
                    if ($isFirst) {
                        $url = $url . '?platform=' . $item;
                        $isFirst = false;
                    } else {
                        $url = $url . ',' . $item;
                    }
                }
            } else if (is_string($platform)) {
                $url = $url . '?platform=' . $platform;
            } else {
                throw new InvalidArgumentException("Invalid platform");
            }
        }
        return Http::get($this->client, $url);
    }

    public function deleteAlias($alias) {
        if (!is_string($alias)) {
            throw new InvalidArgumentException("Invalid alias");
        }
        $url = $this->client->makeURL('alias') . $alias;
        return Http::delete($this->client, $url);
    }

    public function getDevicesStatus($registrationId) {
        if (!is_array($registrationId) && !is_string($registrationId)) {
            throw new InvalidArgumentException('Invalid registration_id');
        }

        if (is_string($registrationId)) {
            $registrationId = explode(',', $registrationId);
        }

        $payload = array();
        if (count($registrationId) <= 0) {
            throw new InvalidArgumentException('Invalid registration_id');
        }
        $payload['registration_ids'] = $registrationId;
        $url = $this->client->makeURL('device') . 'status';
        return Http::post($this->client, $url, $payload);
    }
}
