<?php

declare(strict_types=1);

namespace app\components\Messenger;

use app\common\Contact\Phone;
use app\components\Messenger\Exceptions\CouldNotSendSmsException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Override;

readonly class SmsPilotMessenger implements SmsMessenger
{
    public function __construct(private Client $client, private string $apiKey)
    {
    }

    /**
     * @throws CouldNotSendSmsException|GuzzleException
     */
    #[Override]
    public function send(Phone $phone, string $message): void
    {
        $response = $this->client->get('', [
            'query' => [
                'send' => $message,
                'to' => $phone->getE164Format(false),
                'apikey' => $this->apiKey,
                'format' => 'json',
            ]
        ]);

        $body = json_decode((string) $response->getBody(), true);

        if (isset($body['error'])) {
            throw new CouldNotSendSmsException($body['error']['description']);
        }
    }

    /**
     * @param Phone[] $phones
     * @throws CouldNotSendSmsException
     * @throws GuzzleException
     */
    #[Override]
    public function sendBatch(array $phones, string $message): void
    {
        $response = $this->client->post('', [
            'json' => [
                'send' => $message,
                'to' => $this->buildRecipientsStringForBatchSend($phones),
                'apikey' => $this->apiKey,
                'format' => 'json',
            ]
        ]);

        $body = json_decode((string) $response->getBody(), true);

        if (isset($body['error'])) {
            throw new CouldNotSendSmsException($body['error']['description']);
        }
    }

    /**
     * @param Phone[] $phones
     */
    private function buildRecipientsStringForBatchSend(array $phones): string
    {
        return implode(',', array_map(static fn(Phone $phone) => $phone->getE164Format(false), $phones));
    }
}