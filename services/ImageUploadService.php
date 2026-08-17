<?php

declare(strict_types=1);

namespace app\services;

use Yii;
use yii\web\UploadedFile;
use yii\helpers\BaseFileHelper;

class ImageUploadService
{
    private string $uploadPath;

    public function __construct()
    {
        $this->uploadPath = Yii::getAlias('@webroot/uploads/covers/');
    }

    public function upload(?UploadedFile $file): ?string
    {
        if ($file === null) {
            return null;
        }

        BaseFileHelper::createDirectory($this->uploadPath);
        $fileName = uniqid('cover_', true) . '.' . $file->extension;

        if ($file->saveAs($this->uploadPath . $fileName)) {
            return $fileName;
        }
        return null;
    }

    public function delete(?string $fileName): void
    {
        if ($fileName && file_exists($this->uploadPath . $fileName)) {
            @unlink($this->uploadPath . $fileName);
        }
    }
}
