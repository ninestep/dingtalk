<?php

namespace Shenhou\Dingtalk;

use Exception;

class Prpcrypt
{
    public $key;

    function __construct($k)
    {
        $this->key = base64_decode($k . "=");
    }

    public function encrypt($text, $corpid)
    {

        try {
            //获得16位随机字符串，填充到明文之前
            $random = $this->getRandomStr();
            $text = $random . pack("N", strlen($text)) . $text . $corpid;
            $iv = substr($this->key, 0, 16);

            // 网络字节序
            // $size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
            // $module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');

            //使用自定义的填充方式对明文进行补位填充
            $text = $this->encode($text);
            // mcrypt_generic_init($module, $this->key, $iv);
            // //加密
            // $encrypted = mcrypt_generic($module, $text);
            // mcrypt_generic_deinit($module);
            // mcrypt_module_close($module);


            $encrypted = openssl_encrypt($text, 'AES-256-CBC', $this->key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $iv);

            //print(base64_encode($encrypted));
            //使用BASE64对加密后的字符串进行编码
            return array(0, base64_encode($encrypted));
        } catch (Exception $e) {
            return array(900007, null);
        }
    }

    public function decrypt($encrypted, $corpid)
    {

        try {
            $ciphertext_dec = base64_decode($encrypted);
            // $module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
            $iv = substr($this->key, 0, 16);
            // mcrypt_generic_init($module, $this->key, $iv);

            // $decrypted = mdecrypt_generic($module, $ciphertext_dec);
            // mcrypt_generic_deinit($module);
            // mcrypt_module_close($module);

            $decrypted = openssl_decrypt($ciphertext_dec, 'AES-256-CBC', $this->key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $iv);


            // return $decrypted;
        } catch (Exception $e) {
            return array(900008, null);
        }


        try {
            //去除补位字符
            $result = $this->decode($decrypted);
            //去除16位随机字符串,网络字节序和AppId
            if (strlen($result) < 16)
                return "";
            $content = substr($result, 16, strlen($result));
            $len_list = unpack("N", substr($content, 0, 4));
            $xml_len = $len_list[1];
            $xml_content = substr($content, 4, $xml_len);
            $from_corpid = substr($content, $xml_len + 4);
        } catch (Exception $e) {
            return array(900008, null);
        }
        if ($from_corpid != $corpid)
            return array(900010, null);


        return array(0, $xml_content);

    }

    function getRandomStr()
    {

        $str = "";
        $str_pol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($str_pol) - 1;
        for ($i = 0; $i < 16; $i++) {
            $str .= $str_pol[mt_rand(0, $max)];
        }
        return $str;
    }

    function encode($text)
    {
        $text_length = strlen($text);
        $amount_to_pad = 32 - ($text_length % 32);
        if ($amount_to_pad == 0) {
            $amount_to_pad = 32;
        }
        $pad_chr = chr($amount_to_pad);
        $tmp = "";
        for ($index = 0; $index < $amount_to_pad; $index++) {
            $tmp .= $pad_chr;
        }
        return $text . $tmp;
    }

    function decode($text)
    {
        $pad = ord(substr($text, -1));
        if ($pad < 1 || $pad > 32) {
            $pad = 0;
        }
        return substr($text, 0, (strlen($text) - $pad));
    }

}