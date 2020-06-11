<?php

namespace App\MessageHandler;

use App\Message\AddPonkaToImage;
use App\Photo\PhotoFileManager;
use App\Photo\PhotoPonkaficator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class AddPonkaToImageHandler implements MessageHandlerInterface
{
    private $ponkaficator;
    private $photoManager;
    private $em;

    public function __construct(PhotoPonkaficator $ponkaficator, PhotoFileManager $photoManager, EntityManagerInterface $em)
    {
        $this->ponkaficator = $ponkaficator;
        $this->photoManager = $photoManager;
        $this->em = $em;
    }

    public function __invoke(AddPonkaToImage $message)
    {
        $imagePost = $message->getImagePost();
        $updatedContents = $this->ponkaficator->ponkafy(
            $this->photoManager->read($imagePost->getFilename())
        );
        $this->photoManager->update($imagePost->getFilename(), $updatedContents);
        $imagePost->markAsPonkaAdded();

        $this->em->flush();
    }
}