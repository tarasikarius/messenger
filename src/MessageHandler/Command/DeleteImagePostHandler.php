<?php

namespace App\MessageHandler\Command;

use App\Message\Command\DeleteImagePost;
use App\Message\Event\ImagePostDeletedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class DeleteImagePostHandler implements MessageHandlerInterface
{
    private $eventBus;
    private $em;

    public function __construct(MessageBusInterface $eventBus, EntityManagerInterface $em)
    {
        $this->eventBus = $eventBus;
        $this->em = $em;
    }

    public function __invoke(DeleteImagePost $message)
    {
        $imagePost = $message->getImagePost();

        $this->em->remove($imagePost);
        $this->em->flush();

        $this->eventBus->dispatch(new ImagePostDeletedEvent($imagePost->getFilename()));
    }
}