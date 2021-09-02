<?php


namespace Shenhou\Tests;

require __DIR__.'/../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Shenhou\Dingtalk\DingTalkException;

class downTest
{

    public $uri = 'http://lippi-space-zjk.oss-cn-zhangjiakou.aliyuncs.com/yundisk/hwEHAqR4bHN4A6d5dW5kaXNrBM4hB9zBBc0D4AbN3gcHzmEl-Kc.xlsx?Expires=1630569414&OSSAccessKeyId=LTAIjmWpzHta71rc&Signature=Z5n1SF61RAb3cde2VcCVNvLz5ug%3D';

    public function download()
    {
        try {
            $client = new Client();
            $response = $client->request('get', $this->uri, ['sink' => './1.xlsx']);
            if ($response->getStatusCode() == 200) {
                return 1;
            } else {
                throw new DingTalkException('下载失败');
            }
        } catch (GuzzleException $e) {
            throw new DingTalkException($e->getMessage());
        }
    }
}

$down = new downTest();
$down->download();