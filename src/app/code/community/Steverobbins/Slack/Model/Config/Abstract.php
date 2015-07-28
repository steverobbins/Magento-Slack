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
class Steverobbins_Slack_Model_Config_Abstract extends Varien_Object
{
    const XML_PATH_CONFIG_SECTION = 'slack';

    /**
     * Gets sdm_lyris config
     *
     * @param string $field
     *
     * @return string|array
     */
    public function getConfig($field)
    {
        return $this->_getConfig($field);
    }

    /**
     * Update a config setting
     *
     * @param string  $field
     * @param mixed   $data
     * @param string  $scope
     * @param integer $scopeId
     *
     * @return Steverobbins_Slack_Model_Config_Abstract
     */
    public function setConfig($field, $data, $scope = 'default', $scopeId = 0)
    {
        Mage::getModel('core/config')->saveConfig(
            $this->getConfigPath($field),
            is_array($data) ? serialize($data) : $data,
            $scope,
            $scopeId
        );
        return $this;
    }

    /**
     * Gets sdm_lyris config flag
     *
     * @param string $field
     *
     * @return boolean
     */
    public function getConfigFlag($field)
    {
        return $this->_getConfig($field, true);
    }

    /**
     * Get the path for this config
     *
     * @param string $field
     *
     * @return string
     */
    public function getConfigPath($field)
    {
        return sprintf(
            '%s/%s/%s',
            self::XML_PATH_CONFIG_SECTION,
            $this->getGroup(),
            $field
        );
    }

    /**
     * Gets config value
     *
     * @param string  $field
     * @param boolean $flag
     *
     * @return string|array|boolean|null
     */
    protected function _getConfig($field, $flag = false)
    {
        $configPath = $this->getConfigPath($field);
        $scope = $this->getScope();
        if (isset($scope['website'])) {
            $value = Mage::app()->getWebsite($scope['website'])
                ->getConfig($configPath);
            $node = Mage::getConfig()->getNode('default/' . $configPath);
            if (!empty($node['backend_model']) && !empty($value)) {
                $backend = Mage::getModel((string) $node['backend_model']);
                $backend->setPath($configPath)->setValue($value)->afterLoad();
                $value = $backend->getValue();
            }
        } elseif (isset($scope['store'])) {
            $value = Mage::app()->getStore($scope['store'])
                ->getConfig($configPath);
        } else {
            if ($flag) {
                return Mage::getStoreConfigFlag($configPath);
            }
            return Mage::getStoreConfig($configPath);
        }
        if ($flag) {
            return !empty($value) && 'false' !== $value;
        }
        return $value;
    }
}
