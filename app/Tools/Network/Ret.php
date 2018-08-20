<?php
namespace Core\Tools\Network;;

define('SUCCESS_RET', TRUE);
define('FAIL_RET', FALSE);

class Ret
{
    public $result;
    public $data;
    public $flag;

    function __construct($result = TRUE, $data = '', $flag = NULL)
    {
        $this->result = $result;
        $this->data = $data;
        $this->flag = $flag;
    }

    function __toString()
    {
        return json_encode([
            'result' => $this->result,
            'data' => $this->data,
            'flag' => $this->flag,
        ]);
    }

    public function getMessage()
    {
        return $this->data;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getResult()
    {
        return $this->result;
    }

    public static function responseError($message, $flag = NULL)
    {
        self::responseJSON(FAIL_RET, $message, $flag);
    }

    public static function responseData($data)
    {
        self::responseJSON(SUCCESS_RET, $data);
    }

    public static function responseJSON($result, $data, $flag = NULL)
    {
        header("Access-Control-Allow-Origin:*");
        header("content-type:application/json; charset=uft-8");
        $ret = ['result' => $result, 'flag' => $flag];
        if ($result == SUCCESS_RET) {
            $ret['data'] = $data;
        } else {
            $ret['message'] = $data;
        }
        echo json_encode($ret);
        exit();
    }
}
