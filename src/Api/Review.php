<?php
/**
 * Review.php
 * @author huangbinbin
 * @date   2023/2/7 15:36
 */

namespace Crasp\Trustpilot\Api;


use Crasp\Trustpilot\Traits\HasHttpRequest;

class Review
{
    use HasHttpRequest;

    /**
     * @var string|string
     * @author huangbinbin
     * @date   2022/10/27 11:35
     */
    private string $token;


    private string $replyUrl = "https://api.trustpilot.com/v1/private/reviews/%s/reply";

    /**
     * Search constructor.
     *
     * @param string $token
     * @param array  $config
     */
    public function __construct(string $token, array $config)
    {
        $this->token = $token;
        $this->config = $config;
    }

    /**
     * 回复评论/更新回复
     *
     * @param string $message
     * @param string $id
     *
     * @return bool
     * @throws \Exception
     * @author huangbinbin
     * @date   2023/2/7 15:40
     */
    public function reply(string $message,string $id): bool
    {
        if (!$id || !$message) {
            throw new \Exception('参数错误');
        }
        $headers = [
            'Content-Type' => 'application/json',
            'ApiKey'        => $this->config['client_id'],
            'Authorization' => 'Bearer ' . $this->token,
        ];
        $url = sprintf($this->replyUrl,$id);
        try{
             $this->postJson($url, [
                'message' => $message
            ], $headers);
        }catch (\Exception $exception) {
            throw new \Exception('操作异常，请稍后重试');
        }
        return true;
    }

    /**
     * 删除评论
     *
     * @param string $id
     *
     * @return bool
     * @throws \Exception
     * @author huangbinbin
     * @date   2023/2/7 15:47
     */
    public function replyDelete(string $id): bool
    {
        if (!$id) {
            throw new \Exception('参数错误');
        }
        $headers = [
            'ApiKey'        => $this->config['client_id'],
            'Authorization' => 'Bearer ' . $this->token,
        ];
        $url = sprintf($this->replyUrl,$id);
        try{
            $this->delete($url, $headers);
        }catch (\Exception $exception) {
            throw new \Exception('操作异常，请稍后重试');
        }

        return true;
    }
}