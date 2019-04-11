<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

/**
 * open a connection and a channel, and declare the queue from w/c we're going to consume
 */
$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

/**
 * We declare queue because we want to make sure the queue exists before we try to consume messages from it.
 */
$channel->queue_declare('hello', false, false, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";

/**
 * The callback is where we process the message we receive from the publisher
 * Of course this wont work if the routing key sent by the publisher is not match to our routing key that we 
 * declare here which is 'hello'
 */
$callback = function ($msg) {
    echo ' [x] Received ', $msg->body, "\n";
};


$channel->basic_consume('hello', '', false, true, false, false, $callback);

while (count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();