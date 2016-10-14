<?php

namespace App\Helpers\Production;

use App\Helpers\MetaInformationHelperInterface;
use App\Services\MetaInformationServiceInterface;
use App\Repositories\SiteConfigurationRepositoryInterface;
use App\Repositories\ImageRepositoryInterface;
use URLHelper as URLHelperFacade;

class MetaInformationHelper implements MetaInformationHelperInterface
{
    /** @var \App\Services\MetaInformationServiceInterface */
    protected $metaInformationService;

    /** @var  \App\Repositories\SiteConfigurationRepositoryInterface */
    protected $siteConfigurationRepository;

    /** @var  \App\Repositories\ImageRepositoryInterface */
    protected $imageRepository;

    public function __construct(
        MetaInformationServiceInterface $metaInformationService,
        SiteConfigurationRepositoryInterface $siteConfigurationRepository,
        ImageRepositoryInterface $imageRepository
    ) {
        $this->metaInformationService = $metaInformationService;
        $this->siteConfigurationRepository = $siteConfigurationRepository;
        $this->imageRepository = $imageRepository;
    }

    public function getMetaKeywords($keywords = null)
    {
        if (!empty($keywords)) {
            return $keywords;
        }
        $siteConfiguration = $this->getSiteConfiguration();
        if (!empty($siteConfiguration)) {
            return $siteConfiguration->keywords;
        }

        return '';
    }

    public function getMetaDescription($description = null)
    {
        if (!empty($description)) {
            return $description;
        }
        $siteConfiguration = $this->getSiteConfiguration();
        if (!empty($siteConfiguration)) {
            return $siteConfiguration->description;
        }

        return '';
    }

    public function getTitle($title = null)
    {
        $siteConfiguration = $this->getSiteConfiguration();
        $postfix = !empty($siteConfiguration) ? $siteConfiguration->title : '';
        if (empty($title)) {
            return $postfix;
        }

        return $title.' | '.$postfix;
    }

    public function getOGPImage($url = null)
    {
        if (empty($url)) {
            $siteConfiguration = $this->getSiteConfiguration();
            $siteConfiguration->load('ogpImage');
            $image = $siteConfiguration->ogpImage;
            if (empty($image)) {
                $image = $this->imageRepository->getBlankModel();
            }

            return $image->getThumbnailUrl(1200, 628);
        }

        return $url;
    }

    public function getTwitterCardImage($url = null)
    {
        if (empty($url)) {
            $siteConfiguration = $this->getSiteConfiguration();
            $siteConfiguration->load('ogpImage');
            $image = $siteConfiguration->ogpImage;
            if (empty($image)) {
                $image = $this->imageRepository->getBlankModel();
            }

            return $image->getThumbnailUrl(1024, 512);
        }

        return $url;
    }

    public function getUrl($url = null)
    {
        if (empty($url)) {
            $url = URLHelperFacade::canonicalizeHost(action('Media\IndexController@index'));
        }

        return $url;
    }

    protected function getSiteConfiguration()
    {
        $locale = \App::getLocale();

        return $this->siteConfigurationRepository->findByLocale($locale);
    }
}
