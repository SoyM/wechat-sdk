<?php
/**
 * Text控制器
 */

namespace app\wechat\controller;


class Handletext
{
    public function handleText($postObj)
    {
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $keyword = trim($postObj->Content);
        $time = time();
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    </xml>";
        if (!empty($keyword)) {
            $msgType = "text";
            if ($keyword == "你好") {
                $contentStr = "hello";
            } else {
                $contentStr = "功能开发中-SoyM";
            }
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            return $resultStr;
        } else {
            return "Input something...";
        }
    }
}