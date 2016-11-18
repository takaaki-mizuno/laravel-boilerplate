<?php

namespace App\Services\Production;

use App\Repositories\FileRepositoryInterface;
use App\Repositories\ImageRepositoryInterface;
use App\Services\FileUploadServiceInterface;
use App\Services\ImageServiceInterface;
use Aws\S3\S3Client;

class FileUploadService extends BaseService implements FileUploadServiceInterface
{
    const IMAGE_ID_SESSION_KEY = 'image-id-session-key';

    /** @var \App\Repositories\FileRepositoryInterface */
    protected $fileRepository;

    /** @var \App\Repositories\ImageRepositoryInterface */
    protected $imageRepository;

    /** @var \App\Services\ImageServiceInterface */
    protected $imageService;

    public function __construct(
        FileRepositoryInterface $fileRepository,
        ImageRepositoryInterface $imageRepository,
        ImageServiceInterface $imageService
    ) {
        $this->fileRepository = $fileRepository;
        $this->imageRepository = $imageRepository;
        $this->imageService = $imageService;
    }

    /**
     * @param string $categoryType
     * @param string $text
     * @param string $mediaType
     * @param array  $metaInputs
     *
     * @return \App\Models\Image|\App\Models\File|null
     */
    public function uploadFromText($categoryType, $text, $mediaType, $metaInputs)
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'upload');
        $handle = fopen($tempFile, 'w');
        fwrite($handle, $text);
        fclose($handle);

        $file = $this->upload($categoryType, $tempFile, $mediaType, $metaInputs);

        unlink($handle);

        return $file;
    }

    /**
     * @param string $categoryType
     * @param string $path
     * @param string $mediaType
     * @param array  $metaInputs
     *
     * @return \App\Models\Image|\App\Models\File|null
     */
    public function upload($categoryType, $path, $mediaType, $metaInputs)
    {
        $conf = config('file.categories.'.$categoryType);
        if (empty($conf)) {
            return null;
        }

        $acceptableFileList = config('file.acceptable.'.$conf['type']);
        if (!array_key_exists($mediaType, $acceptableFileList)) {
            return null;
        }
        $ext = array_get($acceptableFileList, $mediaType);

        $model = null;
        switch ($conf['type']) {
            case 'image':
                $model = $this->uploadImage($conf, $ext, $categoryType, $path, $mediaType, $metaInputs);
                break;
            case 'file':
                $model = $this->uploadFile($conf, $ext, $categoryType, $path, $mediaType, $metaInputs);
                break;
        }

        return $model;
    }

    /**
     * @param \App\Models\Image|\App\Models\File $model
     *
     * @return bool|null
     */
    public function delete($model)
    {
        $bucket = $model->s3_bucket;
        $region = $model->s3_region;
        $key = $model->s3_key;

        if (empty($key)) {
            return true;
        }

        $this->deleteS3($region, $bucket, $key);

        $categoryType = $model->file_category_type;
        $conf = config('file.categories.'.$categoryType);
        if (empty($conf)) {
            return false;
        }

        switch ($conf['type']) {
            case 'image':
                foreach (array_get($conf, 'thumbnails', []) as $thumbnail) {
                    $thumbnailKey = $this->getThumbnailKeyFromKey($key, $thumbnail);
                    if (!empty($thumbnailKey)) {
                        $this->deleteS3($region, $bucket, $thumbnailKey);
                    }
                }
                break;
            case 'file':
                break;
        }

        return true;
    }

    /**
     *
     */
    public function resetImageIdSession()
    {
        \Session::put(self::IMAGE_ID_SESSION_KEY, []);
    }

    /**
     * @param int $imageId
     */
    public function addImageIdToSession($imageId)
    {
        $sessionIds = \Session::get(self::IMAGE_ID_SESSION_KEY, []);
        array_push($sessionIds, intval($imageId));
        \Session::put(self::IMAGE_ID_SESSION_KEY, array_values($sessionIds));
    }

    /**
     * @param int $imageId
     */
    public function removeImageIdFromSession($imageId)
    {
        $sessionIds = \Session::get(self::IMAGE_ID_SESSION_KEY, []);
        $pos = array_search(intval($imageId), $sessionIds);
        if ($pos !== false) {
            unset($sessionIds[$pos]);
            \Session::put(self::IMAGE_ID_SESSION_KEY, array_values($sessionIds));
        }
    }

    /**
     * @return array
     */
    public function getImageIdsFromSession()
    {
        return \Session::get(self::IMAGE_ID_SESSION_KEY, []);
    }

    /**
     * @param int $imageId
     *
     * @return bool
     */
    public function hasImageIdInSession($imageId)
    {
        $sessionIds = \Session::get(self::IMAGE_ID_SESSION_KEY, []);

        return in_array(intval($imageId), $sessionIds);
    }

    /**
     * @param string $key
     * @param array  $size
     *
     * @return null|string
     */
    private function getThumbnailKeyFromKey($key, $size)
    {
        if (preg_match('/^(.+?)\.([^\.]+)$/', $key, $match)) {
            return $match[1].'_'.$size[0].'_'.$size[1].'.'.$match[2];
        }

        return null;
    }

    /**
     * @param string      $seed
     * @param string|null $postFix
     * @param string|null $ext
     *
     * @return string
     */
    private function generateFileName($seed, $postFix, $ext)
    {
        $filename = md5($seed);
        if (!empty($postFix)) {
            $filename .= '_'.$postFix;
        }
        if (!empty($ext)) {
            $filename .= '.'.$ext;
        }

        return $filename;
    }

    /**
     * @param array  $conf
     * @param string $ext
     * @param int    $categoryType
     * @param string $path
     * @param string $mediaType
     * @param array  $metaInputs
     *
     * @return \App\Models\File|null
     */
    private function uploadFile($conf, $ext, $categoryType, $path, $mediaType, $metaInputs)
    {
        $fileSize = filesize($path);
        $bucket = $this->decideBucket($conf['buckets']);
        $region = array_get($conf, 'region', 'ap-northeast-1');
        $seed = array_get($conf, 'seed_prefix', '').time().rand();
        $key = $this->generateFileName($seed, null, $ext);
        $url = $this->uploadToS3($path, $region, $bucket, $key, $mediaType);

        /** @var  \App\Models\File|null $file */
        $file = $this->fileRepository->create([
            'url' => $url,
            'title' => array_get($metaInputs, 'title', ''),
            'file_category_type' => $categoryType,
            's3_key' => $key,
            's3_bucket' => $bucket,
            's3_region' => $region,
            's3_extension' => $ext,
            'media_type' => $mediaType,
            'format' => $mediaType,
            'file_size' => $fileSize,
            'is_enabled' => true,
        ]);

        return $file;
    }

    /**
     * @param array  $conf
     * @param string $ext
     * @param int    $categoryType
     * @param string $path
     * @param string $mediaType
     * @param array  $metaInputs
     *
     * @return \App\Models\Image|null
     */
    private function uploadImage($conf, $ext, $categoryType, $path, $mediaType, $metaInputs)
    {
        $dstPath = $path.'.converted';
        $format = array_get($conf, 'format', 'jpeg');
        $size = $this->imageService->convert($path, $dstPath, $format, array_get($conf, 'size'));
        if (!file_exists($dstPath)) {
            return null;
        }

        $fileSize = filesize($dstPath);
        $bucket = $this->decideBucket($conf['buckets']);
        $region = array_get($conf, 'region', 'ap-northeast-1');
        $seed = array_get($conf, 'seed_prefix', '').time().rand();
        $key = $this->generateFileName($seed, null, $ext);
        $url = $this->uploadToS3($dstPath, $region, $bucket, $key, 'image/'.$format);

        /** @var  \App\Models\Image|null $image */
        $image = $this->imageRepository->create([
            'url' => $url,
            'title' => array_get($metaInputs, 'title', ''),
            'file_category_type' => $categoryType,
            'entity_type' => array_get($metaInputs, 'entityType', ''),
            'entity_id' => array_get($metaInputs, 'entityId', ''),
            's3_key' => $key,
            's3_bucket' => $bucket,
            's3_region' => $region,
            's3_extension' => $ext,
            'media_type' => $mediaType,
            'format' => $mediaType,
            'file_size' => $fileSize,
            'width' => array_get($size, 'width', 0),
            'height' => array_get($size, 'height', 0),
            'is_enabled' => true,
        ]);

        foreach (array_get($conf, 'thumbnails', []) as $thumbnail) {
            $this->imageService->convert($path, $dstPath, $format, $thumbnail, true);
            $thumbnailKey = $this->getThumbnailKeyFromKey($key, $thumbnail);
            $this->uploadToS3($dstPath, $region, $bucket, $thumbnailKey, 'image/'.$format);
        }

        return $image;
    }

    /**
     * @param string $path
     * @param string $region
     * @param string $bucket
     * @param string $key
     * @param string $mediaType
     *
     * @return null|string
     */
    private function uploadToS3($path, $region, $bucket, $key, $mediaType = 'binary/octet-stream')
    {
        $client = $this->getS3Client($region);

        if (!file_exists($path)) {
            return null;
        }

        $client->putObject([
            'Bucket' => $bucket,
            'Key' => $key,
            'SourceFile' => $path,
            'ContentType' => $mediaType,
            'ACL' => 'public-read',
        ]);

        unlink($path);

        return $client->getObjectUrl($bucket, $key);
    }

    /**
     * @param array $candidates
     *
     * @return string
     */
    private function decideBucket($candidates)
    {
        $pos = ord(time() % 10) % count($candidates);

        return $candidates[$pos];
    }

    /**
     * @param string $region
     * @param string $bucket
     * @param string $key
     */
    private function deleteS3($region, $bucket, $key)
    {
        $client = $this->getS3Client($region);

        $client->deleteObject([
            'Bucket' => $bucket,
            'Key' => $key,
        ]);
    }

    /**
     * @param string $region
     *
     * @return S3Client
     */
    private function getS3Client($region)
    {
        $config = config('aws');

        return new S3Client([
            'credentials' => [
                'key' => array_get($config, 'key'),
                'secret' => array_get($config, 'secret'),
            ],
            'region' => $region,
            'version' => 'latest',
        ]);
    }
}
