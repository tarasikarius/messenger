<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Messenger\Transport\InMemoryTransport;

class ImagePostControllerTest extends WebTestCase
{
    public function testCreate()
    {
        $client = static::createClient();
        $uploadedFile = new UploadedFile(__DIR__.'/../fixtures/jake.png', 'jake.png');

        $client->request('POST', '/api/images', [], [
            'file' => $uploadedFile,
        ]);

        $this->assertResponseIsSuccessful();

        /** @var InMemoryTransport $transport */
        $transport = self::$container->get('messenger.transport.async_priority_high');
        $this->assertCount(q, $transport->get());
    }
}