<?php declare(strict_types=1);
/**
 * @author Jakub Gniecki <kubuspl@onet.eu>
 * @copyright
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace DevLancer\Payment\API\Zen;

use DevLancer\Payment\API\Zen\Container\CheckoutContainer;
use DevLancer\Payment\API\Zen\Container\NotificationContainer;
use DevLancer\Payment\Helper\TestModeTrait;
use DevLancer\Payment\TransferInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Gotowy interfejs do obsługi płatności Zen
 */
class Zen
{
    use TestModeTrait;

    private ?ResponseInterface $response = null;

    public function generatePayment(CheckoutContainer $container, ?TransferInterface $request = null): ?Checkout
    {
        if (!$request) {
            $request = new RequestCheckout();
            $request->setTestMode($this->testMode);
        }

        $request->sendRequest($container);
        $response = $request->getResponse();
        $this->response = $response;
        if ($request->isError())
            return null;

        $data = \json_decode((string) $response->getBody(), true);
        return new Checkout($data);
    }

    public function paymentNotification(NotificationContainer $container, bool $printResponse = false): PaymentNotification
    {
        if ($printResponse) {
            header('HTTP/1.1 200 OK');
            header('Content-Type: application/json');

            $response = [
                'status' => 'ok'
            ];
            echo \json_encode($response);
        }

        return new PaymentNotification($container);
    }

    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }
}