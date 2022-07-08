<?php

namespace Shenhou\Dingtalk\topapi\ocr;

use Shenhou\Dingtalk\DingTalk;

class Structured
{
    /**
     * 调用本接口进行OCR文字识别。即识别一张图片上的文字。
     * @param string $type 识别图片类型：
                        idcard：身份证
                        invoice：营业执照增值税发票:
                        blicense：营业执照
                        bank_card：银行卡
                        car_no：车牌
                        car_invoice：机动车发票
                        driving_license：驾驶证
                        vehicle_license：行驶证
                        train_ticket：火车票
                        quota_invoice：定额发票
                        taxi_ticket：出租车发票
                        air_itinerary：机票行程单
                        approval_table：审批表单
                        roster：花名册
     * @param string $image_url 识别图片地址，最大长度：1000。
     * @return array 返回识别结果
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Shenhou\Dingtalk\DingTalkException
     */
    public function recognize(string $type, string $image_url)
    {
        $res = DingTalk::requestPost('/topapi/ocr/structured/recognize', [
            'type' => $type,
            'image_url' => $image_url,
        ]);
        return $res['result'];
    }
}