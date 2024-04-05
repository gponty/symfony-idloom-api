<?php declare(strict_types=1);

namespace Gponty\IdLoomBundle;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class IdLoomApi
{
    private HttpClientInterface $client;
    private ?string $apiKey = null;

    public function __construct(private readonly string $idLoomUrl)
    {
        $this->client = HttpClient::create();
    }

    public function setApiKey(string $apiKey): void
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function request(string $method, string $endpoint, array $options = []): array
    {
        $retour = [];
        try {
            $response = $this->client->request($method, $this->idLoomUrl.$endpoint, [
                'headers' => [
                    'Authorization' => 'Bearer '.$this->apiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'query' => $options,
            ]);

            if ($response->getStatusCode() >= 300) {
                $retour['success'] = false;
                $retour['message'] = $response->getContent(false);
                $retour['data'] = [];
            } else {
                $retour['success'] = true;
                $retour['message'] = '';

                $data = \json_decode($response->getContent(false), true);
                if (!\is_array($data)) {
                    $retour['success'] = false;
                    $retour['message'] = 'Error decoding JSON response';
                } else {
                    $retour['data'] = $data['data'];
                }
            }
        } catch (TransportExceptionInterface $e) {
            $retour['success'] = false;
            $retour['message'] = $e->getMessage();
        }

        return $retour;
    }

    public function getAllAttendees(array $options = []): array
    {
        $attendeeAvailable = true;
        if (!isset($options['page'])) {
            $options['page'] = 1;
        }

        $data = [];

        while ($attendeeAvailable) {
            $retour = $this->request('GET', '/attendees', $options);

            if ($retour['success']) {
                $attendees = $retour['data'];
                $data = \array_merge($data, $attendees);
                if (0 === \count($attendees)) {
                    $attendeeAvailable = false;
                }
                \sleep(1);
            } else {
                return $retour;
            }
            ++$options['page'];
        }

        return ['success' => true, 'data' => $data];
    }

    public function getAllInvoices(array $options = []): array
    {
        $invoiceAvailable = true;
        if (!isset($options['page'])) {
            $options['page'] = 1;
        }

        $data = [];

        while ($invoiceAvailable) {
            $retour = $this->request('GET', '/invoices', $options);

            if ($retour['success']) {
                $invoices = $retour['data'];
                $data = \array_merge($data, $invoices);
                if (0 === \count($invoices)) {
                    $invoiceAvailable = false;
                }
                \sleep(1);
            } else {
                return $retour;
            }
            ++$options['page'];
        }

        return ['success' => true, 'data' => $data];
    }

    public function getAllTransactions(array $options = []): array
    {
        $transactionAvailable = true;

        $retour = $this->request('GET', '/transactions', $options);

        if ($retour['success']) {
            $transactions = $retour['data'];
            \sleep(1);
        } else {
            return $retour;
        }

        return ['success' => true, 'data' => $transactions];
    }

    public function getAllCreditNotes(array $options = []): array
    {
        $creditNotesAvailable = true;
        if (!isset($options['page'])) {
            $options['page'] = 1;
        }

        $data = [];

        while ($creditNotesAvailable) {
            $retour = $this->request('GET', '/credit-notes', $options);

            if ($retour['success']) {
                $creditNotes = $retour['data'];
                $data = \array_merge($data, $creditNotes);
                if (0 === \count($creditNotes)) {
                    $creditNotesAvailable = false;
                }
                \sleep(1);
            } else {
                return $retour;
            }
            ++$options['page'];
        }

        return ['success' => true, 'data' => $data];
    }
}
