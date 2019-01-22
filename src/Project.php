<?php
/**
 * @link https://docs.agora.io/cn/Interactive%20Broadcast/dashboard_restful_live?platform=All_Platforms
 * User: qbhy
 * Date: 2019-01-22
 * Time: 23:01
 */

namespace Qbhy\Agora;

class Project extends Api
{
    /**
     * 获取所有项目
     *
     * @return array
     */
    public function all()
    {
        return $this->request('GET', '/projects');
    }

    /**
     * 获取单个项目
     *
     * @param string      $id
     * @param string|null $name
     * @param bool        $first
     *
     * @return array
     */
    public function find($id, $name = null, bool $first = true)
    {
        $result = $this->request('GET', '/project', compact('id', 'name'));

        if ($first && isset($result['projects']) && count($result['projects']) > 0) {
            return $result['projects'][0];
        }

        return $result;
    }

    /**
     * 创建项目
     *
     * @param string $name
     * @param bool   $enableSignKey
     *
     * @return array
     */
    public function create(string $name, bool $enableSignKey = true)
    {
        return $this->request('POST', '/project', [
            'name'            => $name,
            'enable_sign_key' => $enableSignKey,
        ]);
    }

    /**
     * 禁用或启用项目
     *
     * @param string $id
     * @param int    $status
     *
     * @return array
     */
    public function setState(string $id, int $status)
    {
        return $this->request('POST', '/project/project_status', compact('id', 'status'));
    }

    /**
     * 删除项目
     *
     * @param string $id
     *
     * @return array
     */
    public function delete(string $id)
    {
        return $this->request('DELETE', '/project', compact('id'));
    }
}