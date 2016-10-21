<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;
use App\Repositories\ImageRepositoryInterface;

class ImageRequest extends BaseRequest
{
    /** @var \App\Repositories\ImageRepositoryInterface */
    protected $imageRepository;

    public function __construct(ImageRepositoryInterface $imageRepository)
    {
        $this->imageRepository = $imageRepository;
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
        return $this->imageRepository->rules();
    }

    public function messages()
    {
        return $this->imageRepository->messages();
    }
}
