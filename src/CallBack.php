<?php


namespace Shenhou\Dingtalk;


use Exception;
use GuzzleHttp\Exception\GuzzleException;

class CallBack
{
    private $token;
    private $aesKey;
    private $corpId;

    /**
     * CallBack constructor.
     * @param string $token 钉钉开放平台上，开发者设置的token
     * @param string $aesKey 钉钉开放台上，开发者设置的EncodingAESKey
     * @param string $ownerKey 企业自建应用-事件订阅, 使用appKey
     *                       企业自建应用-注册回调地址, 使用corpId
     *                       第三方企业应用, 使用suiteKey
     */
    public function __construct(string $token, string $aesKey, string $ownerKey)
    {
        $this->token = $token;
        $this->aesKey = $aesKey;
        $this->corpId = $ownerKey;
    }

    /**
     * 注册回调事件
     * @param array $call_back_tag 注册的事件类型。
     * @param string $url 接收事件回调的url，必须是公网可以访问的url地址。
     * @return array
     * @throws GuzzleException
     */
    public function register(array $call_back_tag, string $url): array
    {
        $uri = '/call_back/register_call_back';
        return common::requestPost($uri, [
            'call_back_tag' => json_encode($call_back_tag),
            'token' => $this->token,
            'aes_key' => $this->aesKey,
            'url' => $url
        ]);
    }

    /**
     * 加密回调信息
     * @param string $plain 加密数据
     * @param int $timeStamp 时间戳
     * @param string $nonce 随机字符串
     * @return false|string
     * @throws DingTalkException
     */
    public function encrypt(string $plain, int $timeStamp, string $nonce)
    {
        $pc = new Prpcrypt($this->aesKey);

        $array = $pc->encrypt($plain, $this->corpId);
        $ret = $array[0];
        if ($ret != 0) {
            //return $ret;
            // return ['ErrorCode'=>$ret, 'data' => ''];
            throw new DingTalkException('AES加密错误');
        }

        if ($timeStamp == null) {
            $timeStamp = time();
        }
        $encrypt = $array[1];

        $array = $this->getSHA1($this->token, $timeStamp, $nonce, $encrypt);
        $ret = $array[0];
        if ($ret != 0) {
            //return $ret;
            throw new DingTalkException('ComputeSignatureError');
        }
        $signature = $array[1];

        return json_encode(array(
            "msg_signature" => $signature,
            "encrypt" => $encrypt,
            "timeStamp" => $timeStamp,
            "nonce" => $nonce
        ));
    }

    /**
     * 解密回调信息
     * @param string $signature 消息体签名
     * @param string $nonce 随机字符串
     * @param string $encrypt 加密信息
     * @param int|null $timeStamp 时间戳
     * @return mixed
     * @throws DingTalkException
     */
    public function decrypt(string $signature, string $nonce, string $encrypt, int $timeStamp = null): array
    {
        if (strlen($this->aesKey) != 43) {
            //return ErrorCode::$IllegalAesKey;
            // return ['ErrorCode'=>ErrorCode::$IllegalAesKey, 'data' => ''];
            throw new DingTalkException('IllegalAesKey');
        }

        $pc = new Prpcrypt($this->aesKey);

        if ($timeStamp == null) {
            $timeStamp = time();
        }

        $array =$this->getSHA1($this->token, $timeStamp, $nonce, $encrypt);
        $ret = $array[0];

        if ($ret != 0) {
            //return $ret;
            // return ['ErrorCode'=>$ret, 'data' => ''];
            throw new DingTalkException('ComputeSignatureError');
        }

        $verifySignature = $array[1];
        if ($verifySignature != $signature) {
            //return ErrorCode::$ValidateSignatureError;
            //return ['ErrorCode'=>ErrorCode::$ValidateSignatureError, 'data' => ''];
            throw new DingTalkException('ValidateSignatureError');
        }

        $result = $pc->decrypt($encrypt, $this->corpId);

        if ($result[0] != 0) {
            //return $result[0];
            // return ['ErrorCode'=>$result[0], 'data' => ''];
            throw new DingTalkException('DecryptAESError');
        }
        return json_decode($result[1],true);

    }
    private function getSHA1($token, $timestamp, $nonce, $encrypt_msg): array
    {
        try {
            $array = array($encrypt_msg, $token, $timestamp, $nonce);
            sort($array, SORT_STRING);
            $str = implode($array);
            return array(0, sha1($str));
        } catch (Exception $e) {
            return array(900006, null);
        }
    }
}