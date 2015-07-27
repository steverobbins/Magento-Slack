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
 * Auth API method
 */
class Steverobbins_Slack_Model_Api_Auth extends Steverobbins_Slack_Model_Api_Abstract
{
    /**
     * Set method data
     */
    public function __construct()
    {
        $this->setMethod('auth');
    }
}
