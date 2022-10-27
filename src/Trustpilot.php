<?php
/**
 * Trustpilot.php
 * @author huangbinbin
 * @date   2022/10/27 10:29
 */

namespace Crasp\Trustpilot;

use Crasp\Trustpilot\Api\Authorize;
use Crasp\Trustpilot\Api\Search;

class Trustpilot
{
    /**
     * @var array
     * @author huangbinbin
     * @date   2022/10/27 10:31
     */
    private $config;
    /**
     * @var string[]
     * @author huangbinbin
     * @date   2022/10/27 11:06
     */
    private $validateKeys = [
        'email',
        'password',
        'client_id',
        'key',
        'business_id',
    ];
    /**
     * @var mixed
     * @author huangbinbin
     * @date   2022/10/27 11:53
     */
    private mixed $token;

    /**
     * @throws \Exception
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
        $this->validate();;
        $this->getToken();
    }

    /**
     * @author huangbinbin
     * @date   2022/10/27 11:07
     */
    public function validate()
    {
        foreach ($this->validateKeys as  $validateKey) {
            if (!in_array($validateKey, \array_keys($this->config)) || !$this->config[$validateKey]) {
                throw new \Exception('缺少配置' . $validateKey);
            }
        }
    }

    /**
     * @throws \Exception
     * @author huangbinbin
     * @date   2022/10/27 11:53
     */
    public function getToken()
    {
        try {
            $token = (new Authorize($this->config))->login();
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }

        $this->token = $token;
    }

    /**
     * @param int   $page
     * @param int   $limit
     * @param array $filter
     *
     * @throws \Exception
     * @author huangbinbin
     * @date   2022/10/27 11:53
     */
    public function search(int $page = 1, int $limit = 10, array $filter = [])
    {
        $search = new Search($this->token, $this->config);

        return $search->search($page, $limit, $filter);
    }
}