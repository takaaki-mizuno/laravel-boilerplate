<?php namespace App\Http\Requests\Media;

use App\Http\Requests\Request;

class PagerRequest extends Request
{
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
        $offset = $this->get('offset', 0);
        return $offset >= 0 ? $offset : 0;
    }

    /**
     * @param  int $default
     * @return int
     */
    public function limit($default = 10)
    {
        $limit = $this->get('limit', $default);
        return ($limit > 0 && $limit <= 100) ? $limit : $default;
    }
}
