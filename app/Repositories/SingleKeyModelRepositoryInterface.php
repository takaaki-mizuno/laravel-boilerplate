<?php

namespace App\Repositories;

interface SingleKeyModelRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @return array
     */
    public function getPrimaryKey();

    /**
     * @param int $id
     *
     * @return \App\Models\Base|null
     */
    public function find($id);

    /**
     * @param array       $ids
     * @param string|null $order
     * @param string|null $direction
     * @param bool        $reorder
     *
     * @return \App\Models\Base[]
     */
    public function allByIds($ids, $order = null, $direction = null, $reorder = false);

    /**
     * @param array $ids
     *
     * @return int
     */
    public function countByIds($ids);

    /**
     * @param array       $ids
     * @param string|null $order
     * @param string|null $direction
     * @param int|null    $offset
     * @param int|null    $limit
     *
     * @return \App\Models\Base[]
     */
    public function getByIds($ids, $order = null, $direction = null, $offset = null, $limit = null);

    /**
     * @param array $input
     *
     * @return \App\Models\Base
     */
    public function create($input);

    /**
     * @param \App\Models\Base $model
     * @param array            $input
     *
     * @return \App\Models\Base
     */
    public function update($model, $input);

    /**
     * @param \App\Models\Base $model
     *
     * @return \App\Models\Base
     */
    public function save($model);

    /**
     * @param \App\Models\Base $model
     *
     * @return bool
     */
    public function delete($model);
}
