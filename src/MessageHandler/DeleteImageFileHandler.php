<?php

namespace App\MessageHandler;

use App\Message\DeleteImageFile;
use App\Photo\PhotoFileManager;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class DeleteImageFileHandler implements MessageHandlerInterface
{
    private $photoManager;

    public function __construct(PhotoFileManager $photoManager)
    {
        $this->photoManager = $photoManager;
    }

    public function __invoke(DeleteImageFile $message)
    {
        $filename = $message->getFilename();

        $this->photoManager->deleteImage($filename);
    }
}