<?php

namespace App\Http\Requests;

class PaginationRequest extends BaseRequest
{
    public $offset = 0;

    public $limit = 15;

    public $order = 'id';

    public $direction = 'desc';
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    public function messages()
    {
        return [];
    }

    /**
     * @return int
     */
    public function offset()
    {
        $page = $this->get('page', 1);
        $this->offset = ( $page - 1 ) * $this->limit;

        return $this->offset;
    }

    /**
     * @param int $default
     *
     * @return int
     */
    public function limit()
    {
        $this->limit = $this->get('limit', $this->limit);

        return $this->limit;
    }

    public function order()
    {
        $order = $this->get('order', $this->order);

        return $order;
    }

    public function direction()
    {
        $direction = strtolower($this->get('direction', $this->direction));
        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'desc';
        }

        return $direction;
    }
}
