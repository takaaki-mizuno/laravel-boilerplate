<?php namespace App\Repositories;

interface SingleKeyModelRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @return array
     */
    public function getPrimaryKey();

    /**
     * @param  integer $id
     * @return \App\Models\Base|null
     */
    public function find($id);

    /**
     * @param array $ids
     * @param string|null $order
     * @param string|null $direction
     * @param int|null $offset
     * @param int|null $limit
     * @return \App\Models\Base[]
     */
    public function getByIds($ids, $order = null, $direction = null, $offset = null, $limit = null);

    /**
     * @param  array $input
     * @return \App\Models\Base
     */
    public function create($input);

    /**
     * @param  \App\Models\Base $model
     * @param  array $input
     * @return \App\Models\Base
     */
    public function update($model, $input);

    /**
     * @param  \App\Models\Base $model
     * @return \App\Models\Base
     */
    public function save($model);

    /**
     * @param  \App\Models\Base $model
     * @return boolean
     */
    public function delete($model);

}