<?php

namespace App\MessageHandler\Query;

use App\Message\Query\GetTotalImageCount;
use App\Repository\ImagePostRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GetTotalImageCountHandler implements MessageHandlerInterface
{
    private $imagePostRepository;

    public function __construct(ImagePostRepository $imagePostRepository)
    {
        $this->imagePostRepository = $imagePostRepository;
    }

    public function __invoke(GetTotalImageCount $query)
    {
        return $this->imagePostRepository->count([]);
    }
}