<?php

namespace App\MessageHandler;

use App\Message\DeleteImageFile;
use App\Message\DeleteImagePost;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class DeleteImagePostHandler implements MessageHandlerInterface
{
    private $messageBus;
    private $em;

    public function __construct(MessageBusInterface $messageBus, EntityManagerInterface $em)
    {
        $this->messageBus = $messageBus;
        $this->em = $em;
    }

    public function __invoke(DeleteImagePost $message)
    {
        $imagePost = $message->getImagePost();

        $this->em->remove($imagePost);
        $this->em->flush();

        $this->messageBus->dispatch(new DeleteImageFile($imagePost->getFilename()));
    }
}