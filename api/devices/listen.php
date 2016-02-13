<?php
require_once 'autoloader.php';

use Pubnub\Pubnub;

// Create Pubnub Object

$pubnub = new Pubnub('pub-c-a9afac0f-597a-4d95-a975-83b16220f02b', 'sub-c-2023456c-d1a2-11e5-bcee-0619f8945a4f', false, false, false, 'IUNDERSTAND.pubnub.com');

// Define Messaging Channel

$channel = $_REQUEST['auth_code'];

echo("\nWaiting for Publish message... Hit CTRL+C to finish.\n");

$pubnub->subscribe($channel, function ($message) {
            print_r($message);
            echo "\r\n";
            
            flush();
            ob_flush();
            
            return true;
        }
);
?>