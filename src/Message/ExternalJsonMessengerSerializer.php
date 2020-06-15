<?php

namespace App\Message;

use App\Message\Command\LogEmoji;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\MessageDecodingFailedException;
use Symfony\Component\Messenger\Stamp\BusNameStamp;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

class ExternalJsonMessengerSerializer implements SerializerInterface
{
    public function encode(Envelope $envelope): array
    {
        throw new \Exception('Transport & serializer not meant for sending messages');
    }

    public function decode(array $encodedEnvelope): Envelope
    {
        $body = $encodedEnvelope['body'];
        $headers = $encodedEnvelope['headers'];

        $data = json_decode($body, true);
        if (null === $data) {
            throw new MessageDecodingFailedException('Invalid JSON');
        }

        if (!isset($headers['type'])) {
            throw new MessageDecodingFailedException('Missing "type" header');
        }

        switch ($headers['type']) {
            case 'emoji':
                $envelope = $this->createLogEmojiEnvelope($data);
                break;
            default:
                throw new MessageDecodingFailedException(sprintf('Invalid type "%s"', $headers['type']));
        }

        // in case of redelivery, unserialize any stamps
        $stamps = [];
        if (isset($headers['stamps'])) {
            $stamps = unserialize($headers['stamps']);
        }
        $envelope->with(... $stamps);

        return $envelope;
    }

    private function createLogEmojiEnvelope(array $data): Envelope
    {
        if (!isset($data['emoji'])) {
            throw new MessageDecodingFailedException('Missing the emoji key!');
        }

        $message = new LogEmoji($data['emoji']);
        $envelope = new Envelope($message);

        // needed only if you need this to be sent through the non-default bus
        $envelope->with(new BusNameStamp('command.bus'));

        return $envelope;
    }
}