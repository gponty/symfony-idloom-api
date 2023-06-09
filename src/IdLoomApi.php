<?php declare(strict_types=1);

namespace Gponty\IdLoomBundle;

use Symfony\Component\HttpClient\HttpClient;
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

    public function setApiKey($apiKey): void
    {
        $this->apiKey = $apiKey;
    }

    public function getAttendees($eventUid, $dateFrom = null)
    {
        $query = [];
        $query['event_uid'] = $eventUid;
        if (null !== $dateFrom) {
            $query['date_from'] = $dateFrom;
        }

        $page = 1;
        $attendeeAvailable = true;

        $data = [];
        $retour = [];
        try {
            while ($attendeeAvailable) {
                $query['page'] = $page++;

                $response = $this->client->request('GET', $this->idLoomUrl.'attendees', [
                    'headers' => [
                        'Authorization' => 'Bearer '.$this->apiKey,
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                    ],
                    'query' => $query,
                ]);

                if ($response->getStatusCode() >= 300) {
                    $retour['success'] = false;
                    $retour['message'] = $response->getContent(false);
                    $retour['data'] = [];
                    $attendeeAvailable = false;
                } else {
                    $retour['success'] = true;
                    $attendees = json_decode($response->getContent(false), true)['data'];
                    $data = array_merge($data, $attendees);
                    if (0 === count($attendees)) {
                        $attendeeAvailable = false;
                    }
                }
            }
            if ($retour['success']) {
                $retour['success'] = true;
                $retour['message'] = '';
                $retour['data'] = $data;
            }
        } catch (TransportExceptionInterface $e) {
            $retour['success'] = false;
            $retour['message'] = $e->getMessage();
        }

        return $retour;
    }

    public function getInvoices($eventUid, $last): array
    {
        $query = [];
        $query['event_uid'] = $eventUid;
        if (null !== $last) {
            $query['last'] = $last;
        }

        $page = 1;
        $invoiceAvailable = true;

        $data = [];
        $retour = [];
        try {
            while ($invoiceAvailable) {
                $query['page'] = $page++;

                $response = $this->client->request('GET', $this->idLoomUrl.'invoices', [
                    'headers' => [
                        'Authorization' => 'Bearer '.$this->apiKey,
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                    ],
                    'query' => $query,
                ]);

                if ($response->getStatusCode() >= 300) {
                    $retour['success'] = false;
                    $retour['message'] = $response->getContent(false);
                    $retour['data'] = [];
                    $invoiceAvailable = false;
                } else {
                    $retour['success'] = true;
                    $invoices = json_decode($response->getContent(false), true)['data'];
                    $data = array_merge($data, $invoices);
                    if (0 === count($invoices)) {
                        $invoiceAvailable = false;
                    }
                }
            }

            if ($retour['success']) {
                $retour['success'] = true;
                $retour['message'] = '';
                $retour['data'] = $data;
            }
        } catch (TransportExceptionInterface $e) {
            $retour['success'] = false;
            $retour['message'] = $e->getMessage();
        }

        return $retour;
    }

    public function getAttendee($attendeeUid): array
    {
        $query = [];
        $query['guest_uid'] = $attendeeUid;

        $retour = [];
        try {
            $response = $this->client->request('GET', $this->idLoomUrl.'attendees', [
                'headers' => [
                    'Authorization' => 'Bearer '.$this->apiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'query' => $query,
            ]);

            if ($response->getStatusCode() >= 300) {
                $retour['success'] = false;
                $retour['message'] = $response->getContent(false);
                $retour['data'] = [];
            } else {
                $retour['success'] = true;
                $retour['message'] = '';

                $data = json_decode($response->getContent(false), true);

                $retour['data'] = $data['data'];
            }
        } catch (TransportExceptionInterface $e) {
            $retour['success'] = false;
            $retour['message'] = $e->getMessage();
        }

        return $retour;
    }

    public function getOptions($eventUid)
    {
        $query = [];
        $query['event_uid'] = $eventUid;

        $retour = [];
        try {
            $response = $this->client->request('GET', $this->idLoomUrl.'events/options', [
                'headers' => [
                    'Authorization' => 'Bearer '.$this->apiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'query' => $query,
            ]);

            if ($response->getStatusCode() >= 300) {
                $retour['success'] = false;
                $retour['message'] = $response->getContent(false);
                $retour['data'] = [];
            } else {
                $retour['success'] = true;
                $retour['message'] = '';

                $data = json_decode($response->getContent(false), true);

                $retour['data'] = $data['data'];
            }
        } catch (TransportExceptionInterface $e) {
            $retour['success'] = false;
            $retour['message'] = $e->getMessage();
        }

        return $retour;
    }

    public function getCategories($eventUid)
    {
        $query = [];
        $query['event_uid'] = $eventUid;

        $retour = [];
        try {
            $response = $this->client->request('GET', $this->idLoomUrl.'events/categories', [
                'headers' => [
                    'Authorization' => 'Bearer '.$this->apiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'query' => $query,
            ]);

            if ($response->getStatusCode() >= 300) {
                $retour['success'] = false;
                $retour['message'] = $response->getContent(false);
                $retour['data'] = [];
            } else {
                $retour['success'] = true;
                $retour['message'] = '';

                $data = json_decode($response->getContent(false), true);

                $retour['data'] = $data['data'];
            }
        } catch (TransportExceptionInterface $e) {
            $retour['success'] = false;
            $retour['message'] = $e->getMessage();
        }

        return $retour;
    }
}
