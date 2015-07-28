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
 * Controller for ajax system config channel getter
 */
class Steverobbins_Slack_Adminhtml_Slack_System_Config_ChannelsController
    extends Mage_Adminhtml_Controller_Action
{
    /**
     * ACL
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return true;
    }

    /**
     * Get channel listing from API
     *
     * @return void
     */
    public function getAction()
    {
        $response = array(
            'success' => 0
        );
        $channels = array();
        try {
            $list = Mage::getModel('slack/api_channels')->list();
            foreach ($list->getChannels() as $channel) {
                $channels[$channel['id']] = $channel['name'];
            }
            Mage::getSingleton('slack/config_settings')->setChannels($channels);
            $response = array(
                'success' => 1,
                'html'    => $this->getLayout()->createBlock('slack/adminhtml_system_config_channels_list')->toHtml()
            );
        } catch (Exception $e) {
            Mage::logException($e);
        }
        $this->getResponse()->clearHeaders()->setHeader('Content-type', ' application/json', true);
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
    }
}
