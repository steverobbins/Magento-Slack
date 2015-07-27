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
class Steverobbins_Slack_Block_Adminhtml_System_Config_Channels
    extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    /**
     * Set template to itself
     *
     * @return Steverobbins_Slack_Block_Adminhtml_System_Config_Channels
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (!$this->getTemplate()) {
            $this->setTemplate('steverobbins/slack/system/config/channels.phtml');
        }
        return $this;
    }

    /**
     * Renderm html
     *
     * @param Varien_Data_Form_Element_Abstract $element
     *
     * @return string
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $originalData = $element->getOriginalData();
        $this->addData(array(
            'button_label' => Mage::helper('slack')->__($originalData['button_label']),
            'html_id'      => $element->getHtmlId(),
            'ajax_url'     => Mage::getSingleton('adminhtml/url')->getUrl('*/slack_system_config_channels/get'),
            'list_html'  => $this->getLayout()->createBlock('slack/adminhtml_system_config_channels_list')->toHtml()
        ));
        return $this->_toHtml();
    }
}
