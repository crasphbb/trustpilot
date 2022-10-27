<?php
/**
 * Search.php
 * @author huangbinbin
 * @date   2022/10/27 11:32
 */

namespace Crasp\Trustpilot\Api;


use Crasp\Trustpilot\Traits\HasHttpRequest;

class Search
{
    use HasHttpRequest;
    /**
     * @var string|string
     * @author huangbinbin
     * @date   2022/10/27 11:35
     */
    private string $token;


    private string $searchUrl = "https://api.trustpilot.com/v1/private/business-units/%s/statistics/search";

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
     * @param int   $page
     * @param int   $limit
     * @param array $filter
     *
     * @return array|\GuzzleHttp\Promise\PromiseInterface|object|\Overtrue\Http\Support\Collection|\Psr\Http\Message\ResponseInterface|string
     * @throws \Exception
     * @author huangbinbin
     * @date   2022/10/27 11:51
     */
    public function search(int $page = 1, int $limit = 10, array $filter = [])
    {
        $filters = $this->buildSearch($filter);
        $body = array_merge($filters, [
            'limit'  => $limit,
            'offset' => $this->getOffset($page, $limit),
        ]);
        $headers = [
            'ApiKey'        => $this->config['client_id'],
            'Authorization' => 'Bearer ' . $this->token,
        ];
        $url = sprintf($this->searchUrl,$this->config['business_id']);
        $response = $this->postJson($url, $body, $headers);
        if (!isset($response['results'])) {
            throw new \Exception('搜索失败');
        }

        return $response;
    }

    /**
     * @param $filter
     *
     * @return array
     * @author huangbinbin
     * @date   2022/10/27 11:51
     */
    public function buildSearch($filter): array
    {
        $filters = [];
        if (isset($filter['begin_time'])) {
            $filters['fromDate'] = gmdate(DATE_ISO8601, strtotime($filter['begin_time']));
        }
        if (isset($filter['end_time'])) {
            $filters['endDate'] = gmdate(DATE_ISO8601, strtotime($filter['end_time']));
        }
        return $filters;
    }

    /**
     * @param int $page
     * @param int $limit
     *
     * @return float|int
     * @author huangbinbin
     * @date   2022/10/27 11:37
     */
    public function getOffset(int $page = 1, int $limit = 10): float|int
    {
        return ($page - 1) * $limit;
    }

}