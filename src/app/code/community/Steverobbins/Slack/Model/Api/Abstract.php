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
 * Abstract model for Slack api actions
 */
class Steverobbins_Slack_Model_Api_Abstract extends Varien_Object
{
    /**
     * Make a post request
     *
     * @param string $action
     * @param array  $fields
     *
     * @return stdClass
     */
    protected function _post($action, array $fields)
    {
        $fields['token'] = Mage::getSingleton('slack/config_settings')->getToken();
        Mage::log($fields, null, 'slack.log');
        return $this->_request(
            Mage::getSingleton('slack/config_settings')->getApiUrl() . $this->getMethod() . '.' . $action,
            array(
                CURLOPT_POST       => count($fields),
                CURLOPT_POSTFIELDS => http_build_query($fields)
            )
        );
    }

    /**
     * Make a curl request
     *
     * @param string $url
     * @param array  $options
     *
     * @return stdClass
     */
    protected function _request($url, array $options = array())
    {
        Mage::log($url, null, 'slack.log');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        foreach ($options as $key => $value) {
            curl_setopt($ch, $key, $value);
        }
        $response     = curl_exec($ch);
        $result       = new stdClass;
        $result->code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $headerSize   = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        curl_close($ch);
        $result->header = $this->_parseHeader(substr($response, 0, $headerSize));
        $responseBody = substr($response, $headerSize);
        $result->body = Mage::helper('core')->jsonDecode($responseBody);
        Mage::log($result, null, 'slack.log');
        return $result;
    }

    /**
     * Make the headers received from the curl request more readable
     *
     * @param string $rawData
     *
     * @return array
     */
    protected function _parseHeader($rawData)
    {
        $data = array();
        foreach (explode("\n", trim($rawData)) as $line) {
            $bits = explode(': ', $line);
            if (count($bits) > 1) {
                $key = $bits[0];
                unset($bits[0]);
                $data[$key] = trim(implode(': ', $bits));
            }
        }
        return $data;
    }

    /**
     * API caller
     *
     * @param string $name
     * @param array  $args
     *
     * @return mixed
     */
    public function __call($name, $args)
    {
        switch (substr($name, 0, 3)) {
            case 'get':
            case 'set':
            case 'uns':
            case 'has':
                return parent::__call($name, $args);
        }
        if (!Mage::getSingleton('slack/config_settings')->isActive()) {
            return;
        }
        $args = $this->_prepareChannels($args);
        $result = $this->_post($name, $args);
        if ($result->code != 200) {
            Mage::throwException('Failed to connect to Slack API');
        } elseif (!$result->body['ok']) {
            Mage::throwException($result->body['error']);
        }
        unset($result->body['ok']);
        foreach ($result->body as $key => $value) {
            $this->setData($key, $value);
        }
        return $this;
    }

    /**
     * Convert channel name into identifier
     *
     * @param array $args
     *
     * @return array
     */
    protected function _prepareChannels($args)
    {
        $args = isset($args[0]) ? $args[0] : array();
        $channels = Mage::getSingleton('slack/config_settings')->getChannels();
        if (is_array($channels)) {
            $channels = array_flip($channels);
        }
        if (isset($args['channels'])) {
            if (!is_array($args['channels'])) {
                $args['channels'] = array($args['channels']);
            }
            foreach ($args['channels'] as $key => $channel) {
                if (isset($channels[$channel])) {
                    $args['channels'][$key] = $channels[$channel];
                } else {
                    unset($args['channels'][$key]);
                }
            }
            $args['channels'] = implode(',', $args['channels']);
        }
        if (isset($args['channel'])) {
            if (isset($channels[$args['channel']])) {
                $args['channel'] = $channels[$args['channel']];
            }
        }
        return $args;
    }
}
