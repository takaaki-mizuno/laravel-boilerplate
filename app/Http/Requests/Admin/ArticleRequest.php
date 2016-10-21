<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;
use App\Repositories\ArticleRepositoryInterface;

class ArticleRequest extends BaseRequest
{
    /** @var \App\Repositories\ArticleRepositoryInterface */
    protected $articleRepository;

    public function __construct(ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

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
        return $this->articleRepository->rules();
    }

    public function messages()
    {
        return $this->articleRepository->messages();
    }
}
