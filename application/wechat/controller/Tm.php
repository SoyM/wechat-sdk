<?php
/**
 * 模板信息控制器(TemplateMessage)
 */
namespace app\wechat\controller;

use GuzzleHttp\Client;

class Tm
{
    public function __construct()
    {
        // 初始化请求客户端
        $this->client = new Client([
            // 设置默认请求的超时选项
            'timeout' => 20.0,
            //https认证
            'verify' => __DIR__ . '/../cacert/cacert.pem'
        ]);
    }

    /**
     *发送模板消息
     * @param $openid
     * @param $template_id
     * @param $data
     * @return string
     */
    public function send($openid, $template_id, $data)
    {
        $auth = new Auth();
        $response = $this->client->post('https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' .
            $auth->getAccessToken(''),
            ['json' => [
                "touser" => $openid,
                "template_id" => $template_id,
//                "url"=>"http://weixin.qq.com/download",
                "data" => $data
            ]]
        );
        $result = ((string)$response->getBody());
        return $result;
    }

//    /**
//     *设置所属行业（一个月一次）
//     */
//    private function apiSetIndustry()
//    {
//        $auth = new Auth();
//        $response = $this->client->post('https://api.weixin.qq.com/cgi-bin/template/api_set_industry?access_token=' .
//            $auth->getAccessToken(''),
//            ['json' => [
//                "industry_id1" => 10,
//                "industry_id2" => 39
//            ]]);
//        var_dump((string)$response->getBody());
//    }

    /**
     *获取设置的行业信息
     */
    public function getIndustry()
    {
        $auth = new Auth();
        $response = $this->client->get('https://api.weixin.qq.com/cgi-bin/template/get_industry?access_token=' .
            $auth->getAccessToken(''));
        var_dump((string)$response->getBody());
    }

//    /**
//     *获得模板ID
//     * 从行业模板库选择模板到帐号后台，获得模板ID的过程可在MP中完成。为方便第三方开发者，提供通过接口调用的方式来获取模板ID
//     */
//    private function apiAddTemplate()
//    {
//        $auth = new Auth();
//        $response = $this->client->post('https://api.weixin.qq.com/cgi-bin/template/api_add_template?access_token=' .
//            $auth->getAccessToken(''),
//            ['json' => [
//
//            ]]
//        );
//        var_dump((string)$response->getBody());
//    }

    /**
     *获取设置的行业信息
     */
    public function getAllPrivateTemplate()
    {
        $auth = new Auth();
        $response = $this->client->get('https://api.weixin.qq.com/cgi-bin/template/get_all_private_template?access_token=' .
            $auth->getAccessToken(''));
        var_dump((string)$response->getBody());
    }
}