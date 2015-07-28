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
 *
 * @SuppressWarnings(PHPMD.NumberOfChildren)
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
        Mage::helper('slack')->log($fields, null, 'slack.log');
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
        Mage::helper('slack')->log($url, null, 'slack.log');
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
        Mage::helper('slack')->log($result, null, 'slack.log');
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
        $params = $this->_prepareParams();
        $result = $this->_post($name, $params);
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
     * Prepare parameters to send to API
     *
     * @return array
     */
    protected function _prepareParams()
    {
        $this->setToken(Mage::getSingleton('slack/config_settings')->getToken());
        $this->_convertChannels();
        $params = $this->getData();
        unset($params['method']);
        return $params;
    }

    /**
     * Convert channel name into identifier
     *
     * @return void
     */
    protected function _convertChannels()
    {
        $channelConfig = Mage::getSingleton('slack/config_settings')->getChannels();
        if (is_array($channelConfig)) {
            $channelConfig = array_flip($channelConfig);
        }
        if ($this->hasChannels()) {
            $channels = $this->getChannels();
            if (!is_array($channels)) {
                $channels = array($channels);
            }
            foreach ($channels as $key => $channel) {
                if (isset($channelConfig[$channel])) {
                    $channels[$key] = $channelConfig[$channel];
                } else {
                    unset($channels[$key]);
                }
            }
            $this->setChannels($channels);
        }
        if ($this->hasChannel()) {
            $channel = $this->getChannel();
            if (isset($channelConfig[$channel])) {
                $this->setChannel($channelConfig[$channel]);
            } else {
                $this->unsChannel();
            }
        }
    }
}
