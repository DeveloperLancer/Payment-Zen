<?php declare(strict_types=1);
/**
 * @author Jakub Gniecki <kubuspl@onet.eu>
 * @copyright
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace DevLancer\Payment\API\Zen\Container;


use DevLancer\Payment\Exception\InvalidCurrencyException;
use DevLancer\Payment\Helper\Currency;

class NotificationContainer
{
    private string $ipnSecret;
    private ?string $type = null;
    private ?string $transactionId = null;
    private string $merchantTransactionId;
    private float $amount;
    private Currency $currency;
    private string $status;
    private string $hash;
    private ?string $signature = null;
    private array $paymentMethod = [];
    private array $customer = [];
    private ?string $securityStatus = null;
    private array $riskData = [];
    private ?string $email = null;

    /**
     * @param string $ipnSecret
     * @param string $merchantTransactionId
     * @param string|Currency $currency
     * @param float $amount
     * @param string $status
     * @param string $hash
     * @throws InvalidCurrencyException
     */
    public function __construct(string $ipnSecret, string $merchantTransactionId, string|Currency $currency, float $amount, string $status, string $hash)
    {
        $this->ipnSecret = $ipnSecret;
        $this->merchantTransactionId = $merchantTransactionId;

        $this->amount = $amount;
        $this->status = $status;
        $this->hash = $hash;

        if (!$currency instanceof Currency)
            $currency = new Currency($currency);

        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return self::hash($this->getMerchantTransactionId(), (string) $this->getCurrency(), $this->getAmount(), $this->getStatus(), $this->getIpnSecret());
    }

    /**
     * @return string
     */
    public function getIpnSecret(): string
    {
        return $this->ipnSecret;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @return string|null
     */
    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }

    /**
     * @return string
     */
    public function getMerchantTransactionId(): string
    {
        return $this->merchantTransactionId;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @return Currency
     */
    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * @return string|null
     */
    public function getSignature(): ?string
    {
        return $this->signature;
    }

    /**
     * @return array
     */
    public function getPaymentMethod(): array
    {
        return $this->paymentMethod;
    }

    /**
     * @return array
     */
    public function getCustomer(): array
    {
        return $this->customer;
    }

    /**
     * @return string|null
     */
    public function getSecurityStatus(): ?string
    {
        return $this->securityStatus;
    }

    /**
     * @return array
     */
    public function getRiskData(): array
    {
        return $this->riskData;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @param string $transactionId
     */
    public function setTransactionId(string $transactionId): void
    {
        $this->transactionId = $transactionId;
    }

    /**
     * @param string $signature
     */
    public function setSignature(string $signature): void
    {
        $this->signature = $signature;
    }

    /**
     * @param array $paymentMethod
     */
    public function setPaymentMethod(array $paymentMethod): void
    {
        $this->paymentMethod = $paymentMethod;
    }

    /**
     * @param array $customer
     */
    public function setCustomer(array $customer): void
    {
        $this->customer = $customer;
    }

    /**
     * @param string $securityStatus
     */
    public function setSecurityStatus(string $securityStatus): void
    {
        $this->securityStatus = $securityStatus;
    }

    /**
     * @param array $riskData
     */
    public function setRiskData(array $riskData): void
    {
        $this->riskData = $riskData;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @param string $merchantTransactionId
     * @param string $currency
     * @param float $amount
     * @param string $status
     * @param string $ipnSecret
     * @return string
     */
    public static function hash(string $merchantTransactionId, string $currency, float $amount, string $status, string $ipnSecret): string
    {
        $amount = sprintf("%0.2f", $amount);

        $args = [
            $merchantTransactionId,
            $currency,
            $amount,
            $status,
            $ipnSecret
        ];

        return strtoupper(hash("sha256", implode("", $args)));
    }
}