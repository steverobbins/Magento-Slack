Magento Slack Integration
===

---

Work in progress

---

This module won't post anything to Slack on it's own.  It's just a wrapper for your own integrations.  You would still need to write the `order_place_after` observer to chat when an order is created, for example.

# Config

![image](https://i.imgur.com/Fyr3sHg.png)

* Create a bot under "Integrations" in the Slack admin
* Enable and add your token
* Save
* Click <kbd>Get Channels</kbd> to collect your channels and their identifiers

# API Usage

[Review the methods in Slack's API](https://api.slack.com/methods).

Invoke with:

```
$arguments = array('foo' => 'bar');
Mage::getModel('slack/api_<method>')-><action>($arguments);
```

For instance:

### [Chat "Hello World" to the #general Channel](https://api.slack.com/methods/chat.postMessage)

```
Mage::getModel('slack/api_chat')->postMessage(array(
    'channel' => 'general',
    'text'    => 'Hello World!'
));
```

Notice you use the channel name, not it's identifier.

### [Upload a File](https://api.slack.com/methods/files.upload)

```
Mage::getModel('slack/api_files')->upload(array(
    'channels' => array('general', 'random'),
    'content'  => file_get_contents($someFile)
));
```