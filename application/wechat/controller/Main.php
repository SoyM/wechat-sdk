<?php

/**
 * 微信Main控制器
 */

namespace app\wehcat\controller;


class Main
{
    /*
     * 主功能
     */
    public function soyM()
    {
        $postStr = file_get_contents("php://input");
        if (!empty($postStr)) {
            $post_obj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $msg_type = trim($post_obj->MsgType);
            switch ($msg_type) {
                case "text":
                    return '';
                case "event":
                    $even = new Handleevent();
                    $resultStr = $even->handleEvent($post_obj);
                    break;
                default:
                    return '';
            }
            return $resultStr;
        }
        return '';
    }
}