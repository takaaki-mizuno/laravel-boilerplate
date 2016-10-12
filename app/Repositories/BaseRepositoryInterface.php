<?php

namespace App\Repositories;

interface BaseRepositoryInterface
{
    /**
     * Get Empty Array or Traversable Object.
     *
     * @return \App\Models\Base[]|\Traversable|array
     */
    public function getEmptyList();

    /**
     * Get All Models.
     *
     * @param string $order
     * @param string $direction
     *
     * @return \App\Models\Base[]|\Traversable|array
     */
    public function all($order = null, $direction = null);

    /**
     * Get All Enabled Models.
     *
     * @param string $order
     * @param string $direction
     *
     * @return \App\Models\Base[]|\Traversable|array
     */
    public function allEnabled($order = null, $direction = null);

    /**
     * Get Models with Order.
     *
     * @param string $order
     * @param string $direction
     * @param int    $offset
     * @param int    $limit
     *
     * @return \App\Models\Base[]|\Traversable|array
     */
    public function get($order, $direction, $offset, $limit);

    /**
     * Get Models with Order.
     *
     * @param string $order
     * @param string $direction
     * @param int    $offset
     * @param int    $limit
     *
     * @return \App\Models\Base[]|\Traversable|array
     */
    public function getEnabled($order, $direction, $offset, $limit);

    /**
     * @return int
     */
    public function count();

    /**
     * @return int
     */
    public function countEnabled();

    /**
     * @return string
     */
    public function getModelClassName();

    /**
     * Get Empty Array or Traversable Object.
     *
     * @return \Illuminate\Database\Eloquent\Model;
     */
    public function getBlankModel();

    /**
     * Get a rule for Validator.
     *
     * @return array
     */
    public function rules();

    /**
     * Get a messages for Validator.
     *
     * @return array
     */
    public function messages();

    /**
     * Get a validator.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator(array $data);

    /**
     * @param \App\Models\Base[] $models
     *
     * @return array mixed
     */
    public function getAPIArray($models);

    /**
     * @param \Illuminate\Support\Collection $collection
     * @param string                         $value
     * @param string|null                    $key
     *
     * @return \Illuminate\Support\Collection
     */
    public function pluck($collection, $value, $key = null);
}
