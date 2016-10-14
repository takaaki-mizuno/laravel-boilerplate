<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;
use App\Repositories\SiteConfigurationRepositoryInterface;

class SiteConfigurationRequest extends BaseRequest
{
    /** @var \App\Repositories\SiteConfigurationRepositoryInterface */
    protected $siteConfigurationRepository;

    public function __construct(SiteConfigurationRepositoryInterface $siteConfigurationRepository)
    {
        $this->siteConfigurationRepository = $siteConfigurationRepository;
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
        return $this->siteConfigurationRepository->rules();
    }

    public function messages()
    {
        return $this->siteConfigurationRepository->messages();
    }
}
