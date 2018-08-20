<?php
namespace App\Tools\Rabbit;

use AMQPConnection;
use AMQPChannel;
use AMQPConnectionException;
use AMQPExchange;
use AMQPQueue;
use AMQPEnvelope;
use ErrorException;
use Exception;
use Config;

class RabbitMQ
{
    private $_conn;
    private $configs;
    private $_channel;
    private $_exchange;
    private $exchangeName;

    protected $_message = '';

    public function __construct()
    {
        $configs = Config::get('mq');
        $this->configs = $configs[$configs['client']];
        $this->connect();
    }

    /**
     * 建立一个链接
     * @return bool
     */
    protected function connect()
    {
        if (!isset($this->_conn)) {
            $this->_conn = new AMQPConnection($this->configs);
        }
        if (!$this->_conn->isConnected()) {
            $this->_conn = new AMQPConnection($this->configs);
            try {
                $connected = $this->_conn->connect();
                if ($connected) {
                    $this->_channel = new AMQPChannel($this->_conn);
                    $this->_exchange = new AMQPExchange($this->_channel);
                }
            } catch (AMQPConnectionException $e) {
                throw new Exception("RabbitMq connect error:" . $e->getMessage() . $e->getCode() . $e->getFile() . $e->getLine());
            }
        }
        return TRUE;
    }

    /**
     * 设置Exchange名称
     * @param $exchangeName
     */
    public function setExchangeName($exchangeName)
    {
        $this->exchangeName = $exchangeName;
    }


    /**
     * @param $exchangeName
     * @param $queueName
     * @param $data
     * @param null $routeKey
     * @return bool
     */
    public function sendToTaskQueue($exchangeName, $queueName, $data, $routeKey = NULL)
    {
        if (![$data] || empty($exchangeName) || empty($queueName)) {
            $this->_message = "[rabbitmq error] please define data, queue name, exchange name";
            return FALSE;
        }

        if (!$this->connect()) {
            return FALSE;
        }

        $message = json_encode($data);

        $routeKey = is_null($routeKey) ? $queueName : $routeKey;
        $this->_exchange->setType(AMQP_EX_TYPE_DIRECT);
        $this->_exchange->setFlags(AMQP_DURABLE);
        $this->_exchange->setName($exchangeName);
        $this->_exchange->declareExchange();
        $queue = new AMQPQueue($this->_channel);
        $queue->setName($queueName);
        $queue->setFlags(AMQP_DURABLE);
        $queue->declareQueue();
        $queue->bind($exchangeName, $routeKey);
        $this->_exchange->publish($message, $routeKey);
        return TRUE;
    }

    public function consumeFromQueue($exchangeName, $queueName, callable $callback, $routeKey = NULL)
    {
        if (empty($exchangeName) || empty($queueName)) {
            $this->_message = "[rabbitmq error] please define queue name, exchange name";
            return FALSE;
        }

        if (!$this->connect()) {
            return FALSE;
        }

        $routeKey = is_null($routeKey) ? $queueName : $routeKey;
        $this->_exchange->setType(AMQP_EX_TYPE_DIRECT);
        $this->_exchange->setFlags(AMQP_DURABLE);
        $this->_exchange->setName($exchangeName);
        $this->_exchange->declareExchange();

        $queue = new AMQPQueue($this->_channel);
        $queue->setName($queueName);
        $queue->setFlags(AMQP_DURABLE);
        $queue->declareQueue();
        $queue->bind($exchangeName, $routeKey);

        while(TRUE){
            $queue->consume($callback, AMQP_AUTOACK);
        }

    }

    public function createWork($exchangeName, $queueName, $callback, $routeKey = NULL, $isQos = TRUE)
    {
        if (!$this->connect()) {
            return FALSE;
        }

        $this->_exchange->setName($exchangeName);
        $this->_exchange->setType(AMQP_EX_TYPE_DIRECT);
        $this->_exchange->setFlags(AMQP_DURABLE);
        $this->_exchange->declareExchange();

        $routeKey = is_null($routeKey) ? $queueName : $routeKey;

        $queue = new AMQPQueue($this->_channel);
        $queue->setName($queueName);
        $queue->setFlags(AMQP_DURABLE);
        $queue->declareQueue();
        $queue->bind($exchangeName, $routeKey);

        echo "Tasks start! \r\n";
        $counter = 0;
        while (TRUE) {
            echo "Error Times:{$counter}\r\n";
            $queue->consume($callback);
            if ($isQos) {
                $this->_channel->qos(0, 1);
            }
            $counter++;
        }
    }

    static public function getBody(AMQPEnvelope $envelope)
    {
        $body = $envelope->getBody();
        if(!json_decode($body)){
            return $body;
        }else{
            return json_decode($body,TRUE);
        }
    }

    public function disconnect()
    {
        $this->_conn->disconnect();
    }

    public function __destruct()
    {
        if (isset($this->_conn)) {
            $this->disconnect();
        }
    }

    public function getMessage()
    {
        return $this->_message;
    }
}
