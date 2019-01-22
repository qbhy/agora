<?php
/**
 * User: qbhy
 * Date: 2019-01-22
 * Time: 23:35
 */

namespace Qbhy\Agora;

class KickingRule extends Api
{
    /**
     * 创建规则
     *
     * @param string $appid
     * @param array  $optional
     *
     * @return array
     */
    public function create(string $appid, array $optional = [])
    {
        return $this->request('POST', '/kicking-rule', array_merge(compact('appid'), $optional));
    }

    /**
     * 获取规则列表
     *
     * @param string $appid
     *
     * @return array
     */
    public function all(string $appid)
    {
        return $this->request('GET', '/kicking-rule', compact('appid'));
    }

    /**
     * 更新规则时间
     *
     * @param string $appid
     * @param string $id
     * @param string $time
     *
     * @return array
     */
    public function update(string $appid, string $id, string $time)
    {
        return $this->request('PUT', '/kicking-rule', compact('appid', 'id', 'time'));
    }

    /**
     * 删除规则
     *
     * @param string $appid
     * @param string $id
     *
     * @return array
     */
    public function delete(string $appid, string $id)
    {
        return $this->request('DELETE', '/kicking-rule', compact('appid', 'id'));
    }


}