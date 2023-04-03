<?php declare(strict_types=1);
/**
 * @author Jakub Gniecki <kubuspl@onet.eu>
 * @copyright
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace DevLancer\Payment\API\Zen;


use DevLancer\Payment\API\Zen\Container\CheckoutContainer;
use DevLancer\Payment\Exception\InvalidArgumentException;
use DevLancer\Payment\Transfer;
use GuzzleHttp\Exception\RequestException;

class RequestCheckout extends Transfer
{
    /**
     * Adres produkcyjny dla API
     *
     * @var string
     */
    public const PROD_REQUEST_URL = "https://secure.zen.com/api/checkouts";


    /**
     * @inheritDoc
     */
    public function getRequestUrl(): string
    {
        return self::PROD_REQUEST_URL;
    }

    /**
     * @inheritDoc
     * @throws InvalidArgumentException
     */
    public function sendRequest($data): void
    {
        if (!$data instanceof CheckoutContainer)
            throw new InvalidArgumentException(sprintf("Argument #1 (%s) must by of type %s, %s given", '$data', CheckoutContainer::class, (is_object($data)? get_class($data) : gettype($data))));

        try {
            $response = $this->httpClient->request('post', $this->getRequestUrl(), [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => $data->getApiKey()
                ],
                'body' => (string)$data
            ]);

            $this->fromRequest = $response;
        } catch (RequestException $exception) {
            $this->fromRequest = $exception->getResponse();
        }
    }

    /**
     * @inheritDoc
     */
    public function isError(): bool
    {
        return $this->getResponse()->getStatusCode() != 201;
    }
}