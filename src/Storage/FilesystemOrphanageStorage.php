<?php

namespace App\Storage;

use InvalidArgumentException;
use RuntimeException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use function dirname;

class FilesystemOrphanageStorage
{
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $directory;

    public function __construct(SessionInterface $session, string $directory, string $type)
    {
        $this->session = $session;
        $this->directory = $directory;
        $this->type = $type;
    }

    /**
     * @param File $file
     *
     * @return File
     */
    public function upload($file, string $name)
    {
        if (!$this->session->isStarted()) {
            throw new RuntimeException('You need a running session in order to run the Orphanage.');
        }

        return $this->abstractUpload($file, $name, $this->getPath());
    }

    /**
     * @return Finder
     */
    public function getFiles(): Finder
    {
        $finder = new Finder();

        try {
            $finder->in($this->getFindPath())->files();
        } catch (InvalidArgumentException $e) {
            //catch non-existing directory exception.
            //This can happen if getFiles is called and no file has yet been uploaded

            //push empty array into the finder so we can emulate no files found
            $finder->append([]);
        }

        return $finder;
    }

    /**
     * @return string
     */
    protected function getPath(): string
    {
        return sprintf('%s/%s', $this->session->getId(), $this->type);
    }

    /**
     * @return string
     */
    protected function getFindPath(): string
    {
        return sprintf('%s/%s', $this->directory, $this->getPath());
    }

    /**
     * @param $file
     * @param string $name
     * @param string|null $path
     * @return mixed|File
     */
    protected function abstractUpload($file, string $name, string $path = null)
    {
        $path = null === $path ? $name : sprintf('%s/%s', $path, $name);
        $path = sprintf('%s/%s', $this->directory, $path);

        // now that we have the correct path, compute the correct name
        // and target directory
        $targetName = basename($path);
        $targetDir = dirname($path);

        if ($file instanceof UploadedFile) {
            $file = $file->move($targetDir, $targetName);
        }

        return $file;
    }

    public function uploadFiles(array $files = null): array
    {
        try {
            if (null === $files) {
                $files = $this->getFiles();
            }

            $return = [];

            foreach ($files as $file) {

                dump(ltrim(str_replace($this->getFindPath(), '', $file), '/'));
                dump(new File($file->getPathname()));
                dump(new UploadedFile($file->getPathname(), $file->getPathname()));
                //$return[] = $this->storage->upload(
                //new FilesystemFile(new File($file->getPathname())),
                // ltrim(str_replace($this->getFindPath(), '', $file), '/'));
            }

            return $return;
        } catch (\Exception $e) {
            return [];
        }
    }
}
