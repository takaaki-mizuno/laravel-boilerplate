<?php namespace App\Repositories;

interface BaseRepositoryInterface
{

    /**
     * Get Empty Array or Traversable Object
     *
     * @return \App\Models\Base[]|\Traversable|array
     */
    public function getEmptyList();

    /**
     * Get All Models
     *
     * @return \App\Models\Base[]|\Traversable|array
     */
    public function all();

    /**
     * Get All Enabled Models
     *
     * @return \App\Models\Base[]|\Traversable|array
     */
    public function allEnabled();

    /**
     * Get Models with Order
     *
     * @param  string $order
     * @param  string $direction
     * @param  integer $offset
     * @param  integer $limit
     * @return \App\Models\Base[]|\Traversable|array
     */
    public function get($order, $direction, $offset, $limit);

    /**
     * Get Models with Order
     *
     * @param  string $order
     * @param  string $direction
     * @param  integer $offset
     * @param  integer $limit
     * @return \App\Models\Base[]|\Traversable|array
     */
    public function getEnabled($order, $direction, $offset, $limit);

    /**
     * @return integer
     */
    public function count();

    /**
     * @return string
     */
    public function getModelClassName();

    /**
     * Get Empty Array or Traversable Object
     *
     * @return \Illuminate\Database\Eloquent\Model;
     */
    public function getBlankModel();

    /**
     * Get a rule for Validator
     *
     * @return array
     */
    public function rules();

    /**
     * Get a messages for Validator
     *
     * @return array
     */
    public function messages();

    /**
     * Get a validator
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator(array $data);


    /**
     * @param  \App\Models\Base[] $models
     * @return array mixed
     */
    public function getAPIArray($models);

}