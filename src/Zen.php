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

    public function paymentNotification(array|NotificationContainer $container): PaymentNotification
    {
        if (is_array($container)) {
            $data = $container;
            $container = new NotificationContainer(
                $data['apiKey'],
                $data['merchantTransactionId'],
                $data['currency'],
                floatval($data['amount']),
                $data['status'],
                $data['hash']
            );

            $args = [
                'type'              => 'setType',
                'transactionId'     => 'setTransactionId',
                'signature'         => 'setSignature',
                'paymentMethod'     => 'setPaymentMethod',
                'customer'          => 'setCustomer',
                'securityStatus'    => 'setSecurityStatus',
                'riskData'          => 'setRiskData',
                'email'             => 'setEmail'
            ];

            foreach ($args as $name => $method) {
                if (isset($data[$name]))
                    $container->{$method}($data[$name]);
            }
        }

        return new PaymentNotification($container);
    }

    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }
}