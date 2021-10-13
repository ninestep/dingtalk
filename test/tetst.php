<?php
//require_once '../vendor/autoload.php';
//use GuzzleHttp\Exception\GuzzleException;
use Psr\Cache\InvalidArgumentException;
use Shenhou\Dingtalk\DingTalkException;
function des3_encrypt($str,$des_key="",$des_iv="")
{
    return base64_encode(openssl_encrypt($str, 'des-ede3-cbc', $des_key, OPENSSL_RAW_DATA, $des_iv));
}
$other_token = 'YWJjZGVmZ2hpamtsbW5vcHFy';
$username_other = 'miaozeng';
$username_key = des3_encrypt($username_other,$other_token,1234);
$username_key = str_replace("+","%2B",$username_key);
$username_key = str_replace("=","%3D",$username_key);
$url =  'http://59.48.228.167:8765/Global/KK/SSO.aspx?sso='.$username_key.'&returnurl=/default.aspx' ."\n";
echo $url;
//$client = new Client([
//    'timeout' => 30,
//    'allow_redirects' => false,
//]);
//$res = $client->request('POST', $url,
//    [
//        'headers' => ['Content-Type' => 'application/json'],
//    ]);
//print_r($res);

//$username_key = openssl_encrypt($username_other,'des-ede3-ecb',$other_token,0);
//$username_key = TripleDES::encrypt($username_other,$other_token);
//$username_key = base64_encode($username_key);
//$username_key = str_replace("+","%2B",$username_key);
//$username_key = str_replace("=","%3D",$username_key);
//echo $username_key."\n";
function simple_encrypt($string,$key) {

    //加密方法
    $cipher_alg = MCRYPT_3DES;
    //初始化向量来增加安全性
    $iv = 1234;

    //开始加密
    $encrypted_string = mcrypt_encrypt($cipher_alg, $key, $string, MCRYPT_MODE_ECB, $iv);
    return base64_encode($encrypted_string);//转化成16进制
//        return $encrypted_string;
}


function simple_decrypt($string,$key="helloufu123") {
    $string = base64_decode($string);


    //加密方法
    $cipher_alg = MCRYPT_TRIPLEDES;
    //初始化向量来增加安全性
    $iv = mcrypt_create_iv(mcrypt_get_iv_size($cipher_alg,MCRYPT_MODE_ECB), MCRYPT_RAND);

    //开始解密
    $decrypted_string = mcrypt_decrypt($cipher_alg, $key, $string, MCRYPT_MODE_ECB, $iv);
    return trim($decrypted_string);
}
class TripleDES{
    public static function encrypt($str,$key){
        $str = self::pkcs5_pad($str, 8);
        if (strlen($str) % 8) {
            $str = str_pad($str,
                strlen($str) + 8 - strlen($str) % 8, "\0");
        }
        $sign = openssl_encrypt (
            $str,
            'DES-EDE3' ,
            $key,
            OPENSSL_RAW_DATA | OPENSSL_NO_PADDING ,
            ''
        );

        return strtoupper(bin2hex($sign));
    }

    private static function pkcs5_pad($text, $blocksize) {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }
}