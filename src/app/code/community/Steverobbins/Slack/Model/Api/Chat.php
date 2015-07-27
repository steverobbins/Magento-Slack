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
 * Chat API method
 */
class Steverobbins_Slack_Model_Api_Chat extends Steverobbins_Slack_Model_Api_Abstract
{
    /**
     * Set method data
     */
    public function __construct()
    {
        $this->setMethod('chat');
    }

    /**
     * Add username if not supplied
     *
     * @param array $args
     *
     * @return Steverobbins_Slack_Model_Api_Chat
     */
    public function postMessage(array $args = array())
    {
        if (!isset($args['username'])) {
            $args['username'] = Mage::getSingleton('slack/config_settings')->getBotName();
        }
        return parent::postMessage($args);
    }
}
