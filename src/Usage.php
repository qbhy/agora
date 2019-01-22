<?php
/**
 * User: qbhy
 * Date: 2019-01-22
 * Time: 23:30
 */

namespace Qbhy\Agora;

class Usage extends Api
{
    /**
     * 获取用量数据
     *
     * @param string $from_date
     * @param string $to_data
     * @param        $projects
     *
     * @return array
     */
    public function get(string $from_date, string $to_data, $projects)
    {
        $projects = is_array($projects) ? implode(',', $projects) : $projects;
        return $this->request('GET', '/usage', compact('from_date', 'to_data', 'projects'));
    }
}