<?php
/**
 * Slack.com Integration
 *
 * PHP Version 5
 *
 * @category  Steverobbins
 * @package   Steverobbins_Slack
 * @author    Steve Robbins <steve@steverobbins.com>
 * @copyright Copyright 2015 Steve Robbins (http://steverobbins.com)
 * @license   http://creativecommons.org/licenses/by/3.0/deed.en_US Creative Commons Attribution 3.0 Unported License
 */

/**
 * Abstract config getter
 */
class Steverobbins_Slack_Model_Config_Settings extends Steverobbins_Slack_Model_Config_Abstract
{
    /**
     * Set the config group
     */
    public function __construct()
    {
        $this->setGroup('settings');
    }

    /**
     * Determines if the module is active
     *
     * @return boolean
     */
    public function isActive()
    {
        return $this->getConfigFlag('active');
    }

    /**
     * Gets the API url
     *
     * @return string
     */
    public function getApiUrl()
    {
        return $this->getConfig('api_url');
    }

    /**
     * Gets the API token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->getConfig('token');
    }

    /**
     * Gets bot name
     *
     * @return string
     */
    public function getBotName()
    {
        return $this->getConfig('bot_name');
    }

    /**
     * Gets channels by identifier and name
     *
     * @return array
     */
    public function getChannels()
    {
        return $this->getConfig('channels');
    }

    /**
     * Gets channels by identifier and name
     *
     * @param array $channels
     *
     * @return array
     */
    public function setChannels(array $channels)
    {
        return $this->setConfig('channels', $channels);
    }
}
