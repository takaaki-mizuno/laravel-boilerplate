<?php namespace App\Repositories;

interface CompositeKeyModelRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @return array
     */
    public function getPrimaryKeys();

    /**
     * @param array $conditions
     * @return \App\Models\Base|null
     */
    public function find(array $conditions);

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
     * @return boolean
     */
    public function delete($model);

}