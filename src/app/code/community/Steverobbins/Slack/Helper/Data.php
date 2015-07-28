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
 * Data helper for slack
 */
class Steverobbins_Slack_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Logs to file
     *
     * @param mixed $message
     *
     * @return Steverobbins_Slack_Helper_Data
     */
    public function log($message)
    {
        if (Mage::getSingleton('slack/config_settings')->isDebug()) {
            Mage::log($message, null, 'slack.log');
        }
        return $this;
    }
}
