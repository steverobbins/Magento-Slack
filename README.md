Magento Slack Integration
===

[![Build Status](https://travis-ci.org/steverobbins/Magento-Slack.svg?branch=master)](https://travis-ci.org/steverobbins/Magento-Slack)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/steverobbins/Magento-Slack/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/steverobbins/Magento-Slack/?branch=master)

\*Note: This module doesn't do much on it's own.  It's just an api wrapper for integrations you write.  You can use it chat to your Slack channel when: an order is placed, and exception is logged, an account is created, etc.

# Config

![image](https://i.imgur.com/77M0Vjt.png)

* In Slack: Create a bot under "Integrations" in the 
* In Magento: Under **System > Config > Services > Slack**, enable the module and add your token
* Click <kbd>Save</kbd>
* Click <kbd>Get Channels</kbd> to collect your channels and their identifiers

# API Usage

[Review the methods in Slack's API](https://api.slack.com/methods).

Invoke with:

```
Mage::getModel('slack/api_<method>')
	->setFooArg('bar')
	-><action>();
```

For instance:

### [Chat "Hello World" to the #general Channel](https://api.slack.com/methods/chat.postMessage)

```
$chat = Mage::getModel('slack/api_chat');

$chat->setChannel('general')
    ->setText('Hello World');

$chat->postMessage();

var_dump($chat->getData());
```

Notice you use the channel name, not it's identifier.

### [Upload a File](https://api.slack.com/methods/files.upload)

```
Mage::getModel('slack/api_files')
    ->setChannels(array('general', 'random'))
    ->setContent(file_get_contents($someFile))
    ->upload();
```

etc.
