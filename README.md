# Symfony IdLoom Api

This is a Symfony 6 Bundle helps you to use idloom API v4 : https://idloom.events/docs/.

## Installation

**1** Add to composer.json to the `require` key

``` shell
    composer require gponty/symfony-idloom-api-bundle
```

## Usage

Inject the service in your controller :

``` php
    public function __construct(readonly IdLoomApiService $idLoomApiService)
    { }
```

Use the service :

``` php
    $query = '{
              boards(ids: 123456789) {
                id
                name
                groups {
                  id
                  title
                items(limit: 100, page:1) {
                    id
                    name
                }
                }
              }
            }';
    $response = $this->idLoomApiService->request($query);
```

It's not possible to put api key in .env file because you can have different keys for different events.

## License

This bundle is under the MIT license. See the complete license in the bundle.
