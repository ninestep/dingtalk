<?php


namespace Shenhou\Dingtalk\v2;


use AlibabaCloud\SDK\Dingtalk\Vtodo_1_0\Dingtalk;
use AlibabaCloud\SDK\Dingtalk\Vtodo_1_0\Models\CreateTodoTaskHeaders;
use AlibabaCloud\SDK\Dingtalk\Vtodo_1_0\Models\CreateTodoTaskRequest;
use AlibabaCloud\SDK\Dingtalk\Vtodo_1_0\Models\CreateTodoTaskRequest\detailUrl;
use AlibabaCloud\Tea\Utils\Utils\RuntimeOptions;
use Darabonba\OpenApi\Models\Config;
use Shenhou\Dingtalk\DingTalkException;

class Tasks
{
    private $accessToken = '';
    public function __construct($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * 使用 Token 初始化账号Client
     * @return Dingtalk Client
     */
    private static function createClient()
    {
        $config = new Config([]);
        $config->protocol = "https";
        $config->regionId = "central";
        return new Dingtalk($config);
    }

    /**
     * 新增钉钉待办任务
     * @param string $subject 待办标题。
     * @param string $creatorId 创建者的unionId，可通过根据userid获取用户详情接口获取。
     * @param string $sourceId 业务系统侧的唯一标识ID，即业务ID
     * @param string $description 待办备注描述。
     * @param int $dueTime 截止时间，Unix时间戳，单位毫秒。
     * @param array $executorIds 执行者的unionId，可通过根据userid获取用户详情接口获取。
     * @param array $participantIds 参与者的unionId，可通过根据userid获取用户详情接口获取。
     * @param string $appUrl APP端详情页url跳转地址。
     * @param string $pcUrl PC端详情页url跳转地址。
     * @param false $isOnlyShowExecutor 生成的待办是否仅展示在执行者的待办列表中。
     * @param int $priority 优先级，取值：10：较低、20：普通、30：紧急、40：非常紧急
     * @return \AlibabaCloud\SDK\Dingtalk\Vtodo_1_0\Models\CreateTodoTaskResponse
     * @throws DingTalkException
     */
    public function add($subject, $creatorId, $sourceId = '', $description = '', $dueTime = 0, $executorIds = [], $participantIds = [], $appUrl = '', $pcUrl = '', $isOnlyShowExecutor = false, $priority = 20)
    {
        $client = self::createClient();
        $createTodoTaskHeaders = new CreateTodoTaskHeaders([]);
        $createTodoTaskHeaders->xAcsDingtalkAccessToken = $this->accessToken;
        $detailUrl = new detailUrl([
            "appUrl" => $appUrl,
            "pcUrl" => $pcUrl
        ]);
        $createTodoTaskRequest = new CreateTodoTaskRequest([
            "operatorId"=>$creatorId,
            "sourceId" => $sourceId,
            "subject" => $subject,
            "creatorId" => $creatorId,
            "description" => $description,
            "dueTime" => $dueTime,
            "executorIds" => $executorIds,
            "participantIds" => $participantIds,
            "detailUrl" => $detailUrl,
            "isOnlyShowExecutor" => $isOnlyShowExecutor,
            "priority" => $priority
        ]);
        try {
           return $client->createTodoTaskWithOptions(
                $creatorId,
                $createTodoTaskRequest, $createTodoTaskHeaders,
                new RuntimeOptions([]));
        } catch (\Exception $err) {
            throw new DingTalkException($err->getMessage());
        }
    }
}