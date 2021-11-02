<?php

namespace App\Process\Shared\File;

use League\Flysystem\FilesystemOperator;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileProcess
{
    const VISIBILITY_PUBLIC = 'public';

    public function __construct(private FilesystemOperator $defaultStorage, private LoggerInterface $logger, private $projectDigitalOceanStoragePath)
    {
    }

    /**
     * @throws \League\Flysystem\FilesystemException
     */
    public function uploadFile(UploadedFile $file, string $prefix, string $visibility): string
    {
        $fileName = \sprintf('%s%s/%s.%s', $this->projectDigitalOceanStoragePath,$prefix, \sha1(\uniqid()), $file->guessExtension());

        try{
            $this->defaultStorage->writeStream(
                $fileName,
                \fopen($file->getPathname(), 'r'),
                ['visibility' => $visibility]
            );

        }catch (\Exception $e){
            dd($e);

        }

        return $fileName;
    }

    public function deleteFile(?string $path): void
    {
        try {
            if (null !== $path) {
                $this->defaultStorage->delete($path);
            }
        } catch (\Exception $e) {
            $this->logger->warning(\sprintf('File %s not found in the storage', $path));
        }
    }

}