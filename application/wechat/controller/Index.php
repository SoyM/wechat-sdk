<?php
/**
 * 微信主控制器
 */

namespace app\wehcat\controller;

define("TOKEN", "TOKEN");
class Index
{
    /**
     * 主接口
     * 需微信服务器验证，请使用注释掉的代码完成验证
     */
    public function api()
    {
//        判断是否处于微信验证服务器地址有效性的模式
        if (isset($_GET["echostr"]) && isset($_GET["signature"]) && isset($_GET["timestamp"]) && isset($_GET["nonce"])) {
            $this->valid();
            exit;
        }
//        正常模式
        $main = new Main();
        $main->soyM();
    }

    /**
     *微信-验证服务器地址的有效性
     */
    private function valid()
    {
        $echoStr = $_GET["echostr"];
        //valid signature , option
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if ($tmpStr == $signature) {
            echo $echoStr;
            exit;
        }
    }
}