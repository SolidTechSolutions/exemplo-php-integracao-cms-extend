<?php
declare(strict_types=1);

/**
 * [EN]    Upgrades an existing CMS/CAdES signature to a higher baseline level via the
 *         SolidSign API. Proxies the request/response as-is.
 *         Setup: composer install && cp .env.example .env  (edit .env)
 *         Run:   php -S 0.0.0.0:8097 -t public
 *         POST http://localhost:8097/api/cms/extend/form
 *
 * [PT-BR] Faz upgrade de uma assinatura CMS/CAdES existente para um nível de baseline
 *         maior via SolidSign API.
 */

require __DIR__ . '/../vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use GuzzleHttp\Client;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

$app = AppFactory::create();
$client = new Client(['http_errors' => false]);

$app->post('/api/cms/extend/form', function (Request $request, Response $response) use ($client) {
    $params = (array) $request->getParsedBody();
    $uploadedFiles = $request->getUploadedFiles();

    $multipart = [];
    $list = $uploadedFiles['document'] ?? [];
    if (!is_array($list)) $list = [$list];
    foreach ($list as $i => $uf) {
        if ($uf->getError() === UPLOAD_ERR_OK) {
            $multipart[] = ['name' => "document[$i]", 'contents' => $uf->getStream(), 'filename' => $uf->getClientFilename()];
        }
    }

    $multipart[] = ['name' => 'targetLevel', 'contents' => $params['targetLevel'] ?? ''];
    if (!empty($params['signatureIndex'])) {
        $multipart[] = ['name' => 'signatureIndex', 'contents' => $params['signatureIndex']];
    }

    $baseUrl = rtrim($params['baseUrl'] ?? ($_ENV['SOLIDSIGN_BASE_URL'] ?? ''), '/');
    $auth = $params['authorization'] ?? ($_ENV['SOLIDSIGN_AUTHORIZATION'] ?? '');

    $apiResponse = $client->post("$baseUrl/solidsign/dsig/extending/cms/extend", [
        'headers' => ['Authorization' => $auth],
        'multipart' => $multipart,
    ]);

    $response->getBody()->write((string) $apiResponse->getBody());
    return $response->withStatus($apiResponse->getStatusCode())->withHeader('Content-Type', 'application/json');
});

$app->run();
