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
 * System config block for channels
 */
class Steverobbins_Slack_Block_Adminhtml_System_Config_Channels_List
    extends Mage_Adminhtml_Block_Template
{
    /**
     * Set template to itself
     *
     * @return Steverobbins_Slack_Block_Adminhtml_System_Config_Channels_List
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (!$this->getTemplate()) {
            $this->setTemplate('steverobbins/slack/system/config/channels/list.phtml');
        }
        return $this;
    }

    /**
     * Get channel array
     *
     * @return array
     */
    protected function getChannels()
    {
        return Mage::getSingleton('slack/config_settings')->getChannels();
    }
}
