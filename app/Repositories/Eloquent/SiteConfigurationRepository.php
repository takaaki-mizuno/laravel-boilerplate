<?php

namespace App\Repositories\Eloquent;

use App\Repositories\SiteConfigurationRepositoryInterface;
use App\Models\SiteConfiguration;

class SiteConfigurationRepository extends SingleKeyModelRepository implements SiteConfigurationRepositoryInterface
{
    protected $cacheEnabled = true;

    protected $cachePrefix = 'site-configuration';

    protected $cacheLifeTime = 1440; // Minutes

    public function getBlankModel()
    {
        return new SiteConfiguration();
    }

    public function rules()
    {
        return [
            'name' => 'required|max:88',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => trans('validation.repositories.site_configuration.name.required'),
        ];
    }

    public function findByLocale($locale)
    {
        if ($this->cacheEnabled) {
            $data = \Cache::get($this->getLocaleCacheKey($locale));
            if (!empty($data)) {
                return $data;
            }
        }

        $siteConfiguration = SiteConfiguration::whereLocale($locale)->first();
        if (!empty($siteConfiguration)) {
            if ($this->cacheEnabled) {
                \Cache::put($this->getLocaleCacheKey($locale), $siteConfiguration, $this->cacheLifeTime);
            }

            return $siteConfiguration;
        }
        $siteConfiguration = SiteConfiguration::whereLocale(config('app.locale'))->first();
        if (!empty($siteConfiguration)) {
            if ($this->cacheEnabled) {
                \Cache::put($this->getLocaleCacheKey($locale), $siteConfiguration, $this->cacheLifeTime);
            }

            return $siteConfiguration;
        }
        $siteConfiguration = SiteConfiguration::orderBy('id', 'asc')->first();

        if ($this->cacheEnabled) {
            \Cache::put($this->getLocaleCacheKey($locale), $siteConfiguration, $this->cacheLifeTime);
        }

        return $siteConfiguration;
    }

    private function getLocaleCacheKey($locale)
    {
        return implode('-', [$this->cachePrefix, $locale]);
    }

    public function update($model, $input)
    {
        if ($this->cacheEnabled) {
            \Cache::forget($this->getLocaleCacheKey($model->locale));
        }

        return parent::update($model, $input);
    }
}
