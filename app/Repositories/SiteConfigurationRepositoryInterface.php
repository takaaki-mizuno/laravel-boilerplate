<?php

namespace App\Repositories;

interface SiteConfigurationRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * Find Site Configuration by locale.
     *
     * @param string $locale
     *
     * @return \App\Models\SiteConfiguration
     */
    public function findByLocale($locale);
}
