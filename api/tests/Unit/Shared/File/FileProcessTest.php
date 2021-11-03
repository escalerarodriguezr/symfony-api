<?php
declare(strict_types=1);

namespace App\Tests\Unit\Shared\File;

use App\Process\Shared\File\FileProcess;
use League\Flysystem\FilesystemOperator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileProcessTest extends TestCase
{
    private FilesystemOperator | MockObject $defaultStorage;
    private LoggerInterface | MockObject $logger;
    private string $projectDigitalOceanStoragePath;
    private FileProcess $fileProcess;

    public function setUp(): void
    {
        parent::setUp();

        $this->defaultStorage = $this->getMockBuilder(FilesystemOperator::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->logger = $this->getMockBuilder(LoggerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->projectDigitalOceanStoragePath = 'project/symfony_api/';

        $this->fileProcess = new FileProcess($this->defaultStorage, $this->logger, $this->projectDigitalOceanStoragePath);

    }

    public function testFileProcessUploadFile(): void
    {
        $uploadedFile = $this->getMockBuilder(UploadedFile::class)->disableOriginalConstructor()->getMock();
        $uploadedFile->method('getPathname')->willReturn('/tmp');
        $uploadedFile->method('guessExtension')->willReturn('png');
        $prefix = 'avatar';
        $response = $this->fileProcess->uploadFile($uploadedFile, $prefix, FileProcess::VISIBILITY_PUBLIC);

        self::assertIsString($response);
    }

    public function testFileProcessUploadFileWithError(): void
    {
        $uploadedFile = $this->getMockBuilder(UploadedFile::class)->disableOriginalConstructor()->getMock();
        $uploadedFile->method('getPathname')->willReturn('/tmp');
        $uploadedFile->method('guessExtension')->willReturn('png');
        $prefix = 'avatar';

        $this->defaultStorage
            ->expects($this->exactly(1))
            ->method('writeStream')
            ->willThrowException(new \Exception());

        try{
            $response = $this->fileProcess->uploadFile($uploadedFile, $prefix, FileProcess::VISIBILITY_PUBLIC);
        }catch (FileNotFoundException $e){
            self::isInstanceOf(FileNotFoundException::class, $e);
        }

    }


    public function testFileProcessDeleteFile(): void
    {
        $path = $this->projectDigitalOceanStoragePath. '/avatar/123-png';

        $this->defaultStorage
            ->expects($this->exactly(1))
            ->method('delete')
            ->with($path);
        $this->fileProcess->deleteFile($path);
    }

    public function testFileProcessDeleteFileFileNotExist(): void
    {
        $path = $this->projectDigitalOceanStoragePath. '/avatar/123-png';

        $this->defaultStorage
            ->expects($this->exactly(1))
            ->method('delete')
            ->with($path)
            ->willThrowException(new \Exception());

        $this->logger
            ->expects($this->exactly(1))
            ->method('warning')
            ->with($this->isType('string'));

        try{
            $this->fileProcess->deleteFile($path);
        }catch (FileNotFoundException $e){
            self::isInstanceOf(FileNotFoundException::class, $e);
        }
    }
}