<?php
/**
 * This is our message publisher (sender)
 * The publisher will connect to RabbitMQ, send a single message request, then exit.
 */

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Connect rabbitMQ to local machine
 */
$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();
$routingKey = 'hello';

$channel->queue_declare($routingKey, false, false, false, false); // declare queue to send message

$msg = new AMQPMessage('Hello World'); // what we will send to the receiver


/**
 * To publish the message
 * 
 * $msg - is what we will send to the receiver
 * the second param is the exchange key.. for now let's leave it blank
 * the routing key is the event key name that will be received by the receiver.
 */
$channel->basic_publish($msg, '', $routingKey); 

echo " [x] Sent 'Hello World!'\n";

$channel->close();
$connection->close();