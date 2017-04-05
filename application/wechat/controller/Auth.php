<?php
/**
 * 微信授权控制器
 */
namespace app\wechat\controller;

use think\Request;
use think\Config;
use GuzzleHttp\Client;
use app\wechat\model\Accesstoken;
use app\wechat\model\Userwechatauth;

class Auth
{
    public function __construct()
    {
        // 初始化请求客户端
        $this->client = new Client([
            'timeout' => 20.0,
            //https认证
            'verify' => __DIR__ . '/../cacert/cacert.pem'
        ]);
    }

    /**
     * 获取access_token
     * @param $config_app_config
     */
    public function getAccessToken($config_app_config)
    {
        $data_at = Accesstoken::all();
        $result = $data_at[sizeof($data_at) - 1];
        if (!is_null($data_at)) {
            $miss_time = time() - strtotime($result->getAttr('time'));
            if ($miss_time < 7000) {
//                var_dump($miss_time);
                return $result->getAttr('value');
            } else {
                $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . Config::get('appid' . $config_app_config) . '&secret=' . Config::get('appsecret' . $config_app_config);
                $response = $this->client->get($url);
                $access_token = json_decode((string)$response->getBody())->access_token;
                $data['value'] = $access_token;
                $data['time'] = date('Y-m-d H:i:s');
                Accesstoken::create($data);
                return $access_token;
            }
        }
    }

    /**
     * 以snsapi_userinfo为scope发起的网页授权，是用来获取用户的基本信息的。但这种授权需要用户手动同意，
     * 并且由于用户同意过，所以无须关注，就可在授权后获取该用户的基本信息。
     */
    public function snsapiUserinfo()
    {
        $code = Request::instance()->get('code');
        if (is_null($code)) {
            exit;
        }
        //    用code获取access__token
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . Config::get('appid') . '&secret=' . Config::get('appsecret') . '&code=' . $code . '&grant_type=authorization_code';
        $response = $this->client->get($url);
        $data = json_decode((string)$response->getBody());
        $access_token = $data->access_token;
        $refresh_token = $data->refresh_token;
        $openid_gi = $data->openid;
        $scope = $data->scope;
        //    用access__token获取用户信息
        $url_getuserinfo = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $access_token . '&openid=' . $openid_gi . '&lang=zh_CN ';
        if ($scope == 'snsapi_userinfo') {
            $response_userinfo = $this->client->get($url_getuserinfo);
            $userinfo = json_decode((string)$response_userinfo->getBody());
            $openid2 = $userinfo->openid;
            $nickname = $userinfo->nickname;
            $sex = $userinfo->sex;
            $province = $userinfo->province;
            $city = $userinfo->city;
            $country = $userinfo->country;
            $headimgurl = $userinfo->headimgurl;
//            $privilege = $userinfo->privilege;
            $post_time = date('Y-m-d H:i:s');
            $user_data = Userwechatauth::get(['openid_gi' => $openid_gi]);
//            var_dump($user_data);
            $data = [
                'access_token' => $access_token,
                'refresh_token' => $refresh_token,
                'openid_gi' => $openid_gi,
                'openid' => $openid2,
                'nickname' => $nickname,
                'sex' => $sex,
                'province' => $province,
                'city' => $city,
                'country' => $country,
                'headimgurl' => $headimgurl,
                'post_time' => $post_time
            ];
            if (is_null($user_data)) {
                Userwechatauth::create($data);
            } else {
                $data['id'] = $user_data->id;
                Userwechatauth::update($data);
            }
            return [
                'openid_gi' => $openid_gi,
                'openid' => $openid2,
                'nickname' => $nickname,
                'sex' => $sex,
                'province' => $province,
                'city' => $city,
                'country' => $country,
                'headimgurl' => $headimgurl,
            ];
        }
    }

    /**
     * 以snsapi_base为scope发起的网页授权，是用来获取进入页面的用户的openid的，并且是静默授权并自动跳转到回调页的。
     * 用户感知的就是直接进入了回调页
     */
    public function snsapiBase()
    {
        $code = Request::instance()->get('code');
        if (is_null($code)) {
            exit;
        }
        //    用code获取access__token
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . Config::get('appid') . '&secret=' . Config::get('appsecret') . '&code=' . $code . '&grant_type=authorization_code';
        $response = $this->client->get($url);
        $data = json_decode((string)$response->getBody());
//        $access_token = $data->access_token;
//        $refresh_token = $data->refresh_token;
        $openid_gi = $data->openid;
//        $scope = $data->scope;
        return $openid_gi;
    }
}