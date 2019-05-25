<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\authclient;

use Yii;
use yii\base\Component;
use yii\di\Instance;
use yii\web\Session;

/**
 * SessionStateStorage provides Auth client state storage based on web session.
 *
 * @see StateStorageInterface
 * @see Session
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 2.1
 */
class SessionStateStorage extends Component implements StateStorageInterface
{
    /**
     * @var Session|array|string session object or the application component ID of the session object to be used.
     *
     * After the SessionStateStorage object is created, if you want to change this property,
     * you should only assign it with a session object.
     *
     * If not set - application 'session' component will be used, but only, if it is available (e.g. in web application),
     * otherwise - no session will be used and no data saving will be performed.
     */
    public $session;


    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        if ($this->session === null) {
            if (Yii::$app->has('session')) {
                $this->session = Yii::$app->get('session');
            }
        } else {
            $this->session = Instance::ensure($this->session, Session::className());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value)
    {
        if ($this->session !== null) {
            $this->session->set($key, $value);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        if ($this->session !== null) {
            return $this->session->get($key);
        }
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function remove($key)
    {
        if ($this->session !== null) {
            $this->session->remove($key);
        }
        return true;
    }
}