<?php

namespace App\MessageHandler;

use App\Message\DeleteImagePost;
use App\Photo\PhotoFileManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class DeleteImagePostHandler implements MessageHandlerInterface
{
    private $photoManager;
    private $em;

    public function __construct(PhotoFileManager $photoManager, EntityManagerInterface $em)
    {
        $this->photoManager = $photoManager;
        $this->em = $em;
    }

    public function __invoke(DeleteImagePost $message)
    {
        $imagePost = $message->getImagePost();
        $this->photoManager->deleteImage($imagePost->getFilename());

        $this->em->remove($imagePost);
        $this->em->flush();
    }
}