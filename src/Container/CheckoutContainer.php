<?php declare(strict_types=1);
/**
 * @author Jakub Gniecki <kubuspl@onet.eu>
 * @copyright
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace DevLancer\Payment\API\Zen\Container;


use DevLancer\Payment\API\Zen\Helper\Address;
use DevLancer\Payment\API\Zen\Helper\BilingAddress;
use DevLancer\Payment\API\Zen\Helper\Customer;
use DevLancer\Payment\API\Zen\Helper\ItemCheckout;
use DevLancer\Payment\API\Zen\Helper\ShippingAddress;
use DevLancer\Payment\Helper\Currency;

class CheckoutContainer
{
    /**
     * @var string
     */
    private string $apiKey;

    /**
     * @var string
     */
    private string $paywallSecret;

    /**
     * @var string
     */
    private string $terminalUuid;

    /**
     * @var float
     */
    private float $amount;

    /**
     * @var Currency
     */
    private Currency $currency;

    /**
     * @var string
     */
    private string $merchantTransactionId;

    /**
     * @var ItemCheckout[]
     */
    private array $items;

    private ?Customer $customer = null;

    /**
     * @var ShippingAddress|null
     */
    private ?ShippingAddress $shippingAddress = null;

    /**
     * @var BilingAddress|null
     */
    private ?BilingAddress $bilingAddress = null;

    /**
     * @var string|null
     */
    private ?string $urlRedirect = null;

    /**
     * @var string|null
     */
    private ?string $urlSuccess = null;

    /**
     * @var string|null
     */
    private ?string $urlFailure = null;

    /**
     * @var string|null
     */
    private ?string $customIpnUrl = null;

    /**
     * @var string|null
     */
    private ?string $language = null;

    /**
     * @param string $apiKey
     * @param string $paywallSecret
     * @param string $terminalUuid
     * @param float $amount
     * @param Currency $currency
     * @param string $merchantTransactionId
     * @param ItemCheckout $item
     */
    public function __construct(string $apiKey, string $paywallSecret, string $terminalUuid, float $amount, Currency $currency, string $merchantTransactionId, ItemCheckout $item)
    {
        $this->apiKey                = $apiKey;
        $this->paywallSecret         = $paywallSecret;
        $this->terminalUuid          = $terminalUuid;
        $this->amount                = $amount;
        $this->currency              = $currency;
        $this->merchantTransactionId = $merchantTransactionId;
        $this->items[]               = $item;
    }

    /**
     * @return Customer|null
     */
    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    /**
     * @param Customer $customer
     */
    public function setCustomer(Customer $customer): void
    {
        $this->customer = $customer;
    }

    /**
     * @return Address|null
     */
    public function getShippingAddress(): ?ShippingAddress
    {
        return $this->shippingAddress;
    }

    /**
     * @param ShippingAddress $shippingAddress
     */
    public function setShippingAddress(ShippingAddress $shippingAddress): void
    {
        $this->shippingAddress = $shippingAddress;
    }

    /**
     * @return BilingAddress|null
     */
    public function getBilingAddress(): ?BilingAddress
    {
        return $this->bilingAddress;
    }

    /**
     * @param BilingAddress $bilingAddress
     */
    public function setBilingAddress(BilingAddress $bilingAddress): void
    {
        $this->bilingAddress = $bilingAddress;
    }

    /**
     * @return string|null
     */
    public function getUrlRedirect(): ?string
    {
        return $this->urlRedirect;
    }

    /**
     * @param string $urlRedirect
     */
    public function setUrlRedirect(string $urlRedirect): void
    {
        $this->urlRedirect = $urlRedirect;
    }

    /**
     * @return string|null
     */
    public function getUrlSuccess(): ?string
    {
        return $this->urlSuccess;
    }

    /**
     * @param string $urlSuccess
     */
    public function setUrlSuccess(string $urlSuccess): void
    {
        $this->urlSuccess = $urlSuccess;
    }

    /**
     * @return string|null
     */
    public function getUrlFailure(): ?string
    {
        return $this->urlFailure;
    }

    /**
     * @param string $urlFailure
     */
    public function setUrlFailure(string $urlFailure): void
    {
        $this->urlFailure = $urlFailure;
    }

    /**
     * @return string|null
     */
    public function getCustomIpnUrl(): ?string
    {
        return $this->customIpnUrl;
    }

    /**
     * @param string $customIpnUrl
     */
    public function setCustomIpnUrl(string $customIpnUrl): void
    {
        $this->customIpnUrl = $customIpnUrl;
    }

    /**
     * @return string
     */
    public function getTerminalUuid(): string
    {
        return $this->terminalUuid;
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
    public function getMerchantTransactionId(): string
    {
        return $this->merchantTransactionId;
    }

    /**
     * @return ItemCheckout[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param ItemCheckout $item
     * @return void
     */
    public function addItem(ItemCheckout $item)
    {
        $this->items[] = $item;
    }

    /**
     * @return string|null
     */
    public function getLanguage(): ?string
    {
        return $this->language;
    }

    /**
     * @param string $language
     */
    public function setLanguage(string $language): void
    {
        $this->language = $language;
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @return string
     */
    public function getPaywallSecret(): string
    {
        return $this->paywallSecret;
    }

    /**
     * @return string
     */
    public function getSignature(): string
    {
        return $this->generateSignature();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $data = $this->serialize();
        $data = $this->removeNullValues($data);
        $data['signature'] = $this->getSignature();
        return json_encode($data);
    }

    /**
     * @return string
     */
    protected function generateSignature(): string
    {
        $data = $this->serialize();
        $data = $this->removeNullValues($data);

        $result = implode("&", $this->parse($data));
        $result .= $this->getPaywallSecret();
        $algo = "sha256";
        $hash = hash($algo, $result);

        return sprintf("%s;%s", $hash, $algo);
    }

    private function serialize(): array
    {
        $args = [
            'terminalUuid' => $this->getTerminalUuid(),
            'amount' => $this->getAmount(),
            'currency' => (string) $this->getCurrency(),
            'merchantTransactionId' => $this->getMerchantTransactionId(),
            'customer' => json_decode((string)$this->getCustomer(), true),
            'shippingAddress' => json_decode((string)$this->getShippingAddress(), true),
            'bilingAddress' => json_decode((string)$this->getBilingAddress(), true),
            'urlFailure' => $this->getUrlFailure(),
            'urlRedirect' => $this->getUrlRedirect(),
            'urlSuccess' => $this->getUrlSuccess(),
            'customIpnUrl' => $this->getCustomIpnUrl(),
            'language' => $this->getLanguage(),
        ];

        $args['items'] = [];
        foreach ($this->getItems() as $item) {
            $item = json_decode((string)$item, true);
            $args['items'][] = $item;
        }

        return $args;
    }

    private function removeNullValues(array $data): array
    {
        foreach ($data as $item => &$value) {
            if (is_null($value)) {
                unset($data[$item]);
                continue;
            }

            if (is_array($value)) {
                $value = $this->removeNullValues($value);

                if ($value == [])
                    unset($data[$item]);
            }
        }

        return $data;
    }

    public static function parse(array $data): array
    {
        $result = [];
        foreach ($data as $key => $item) {
            if (!is_array($item)) {
                $result[] = strtolower(sprintf('%s=%s', $key, $item));
                continue;
            }

            foreach ($item as $index => $value) {
                if (!is_array($value)) {
                    $result[] = strtolower(sprintf('%s.%s=%s', $key, $index, $value));
                    continue;
                }

                foreach ($value as $name => $val)
                    $result[] = strtolower(sprintf('%s[%s].%s=%s', $key, $index, $name, $val));
            }
        }

        sort($result);
        return $result;
    }
}