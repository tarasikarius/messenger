<?php

namespace App\MessageHandler\Command;

use App\Message\Command\DeleteImagePost;
use App\Message\Event\ImagePostDeletedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class DeleteImagePostHandler implements MessageSubscriberInterface
{
    private $eventBus;
    private $em;

    public function __construct(MessageBusInterface $eventBus, EntityManagerInterface $em)
    {
        $this->eventBus = $eventBus;
        $this->em = $em;
    }

    public function __invoke($message)
    {
        $imagePost = $message->getImagePost();

        $this->em->remove($imagePost);
        $this->em->flush();

        $this->eventBus->dispatch(new ImagePostDeletedEvent($imagePost->getFilename()));
    }

    public static function getHandledMessages(): iterable
    {
        yield DeleteImagePost::class => [
            'method' => '__invoke',
            'priority' => 10,
//            'from_transport' => 'async',
        ];
    }
}