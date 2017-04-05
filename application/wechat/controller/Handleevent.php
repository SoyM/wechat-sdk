<?php
/**
 * Event控制器
 */

namespace app\wehcat\controller;

use app\wechat\model\Templatesendfb;

class Handleevent
{
    public function handleEvent($object)
    {
        switch ($object->Event) {
            case "TEMPLATESENDJOBFINISH":
                $ToUserName = $object->ToUserName;
                $FromUserName = $object->FromUserName;
                $CreateTime = $object->CreateTime;
                $MsgType = $object->MsgType;
                $Event = $object->Event;
                $MsgID = $object->MsgID;
                $Status = $object->Status;
                $data = [
                    "ToUserName" => $ToUserName,
                    "FromUserName" => $FromUserName,
                    "CreateTime" => $CreateTime,
                    "MsgType" => $MsgType,
                    "Event" => $Event,
                    "MsgID" => $MsgID,
                    "Status" => $Status,
                    "post_time" => date('Y-m-d H:i:s')
                ];
                Templatesendfb::create($data);
                break;
//            case "CLICK":
//                if ($object->EventKey) {
//                    $content = $object->EventKey;
//                }
//                break;
            default :
                return '';
        }
//        $resultStr = $this->responseText($object, $content);
//        return $resultStr;
    }

    public function responseText($object, $content, $flag = 0)
    {
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[text]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    <FuncFlag>%d</FuncFlag>
                    </xml>";
        $resultStr = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content, $flag);
        return $resultStr;
    }
}