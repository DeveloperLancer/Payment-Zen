<?php declare(strict_types=1);
/**
 * @author Jakub Gniecki <kubuspl@onet.eu>
 * @copyright
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace DevLancer\Payment\API\Zen;


use DevLancer\Payment\API\Zen\Container\NotificationContainer;

class PaymentNotification
{
    public const SUCCESS_STATUS = 'ACCEPTED';

    private NotificationContainer $container;

    public function __construct(NotificationContainer $container)
    {
        $this->container = $container;
    }

    public function isSuccessful(): bool
    {
        if (!$this->checkSign())
            return false;

        return $this->getStatus() == self::SUCCESS_STATUS;
    }

    public function checkSign(): bool
    {
        return ((string) $this->container) == $this->container->getSignature();
    }

    public function getStatus()
    {
        return $this->container->getStatus();
    }

    public function getContainer(): NotificationContainer
    {
        return $this->container;
    }
}