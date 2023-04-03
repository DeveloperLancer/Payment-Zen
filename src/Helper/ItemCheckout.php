<?php declare(strict_types=1);
/**
 * @author Jakub Gniecki <kubuspl@onet.eu>
 * @copyright
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace DevLancer\Payment\API\Zen\Helper;

class ItemCheckout
{
    private string $name;
    private float $price;
    private int $quantity;
    private float $lineAmountTotal;
    private ?string $code = null;
    private ?string $category = null;

    /**
     * @param string $name
     * @param float $price
     * @param int $quantity
     * @param float $lineAmountTotal
     */
    public function __construct(string $name, float $price, int $quantity, float $lineAmountTotal)
    {
        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->lineAmountTotal = $lineAmountTotal;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * @param string $category
     */
    public function setCategory(string $category): void
    {
        $this->category = $category;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @return float
     */
    public function getLineAmountTotal(): float
    {
        return $this->lineAmountTotal;
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @return string|null
     */
    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function __toString()
    {
        $args = [
            'code' => $this->getCode(),
            'category' => $this->getCategory(),
            'name' => $this->getName(),
            'price' => $this->getPrice(),
            'quantity' => $this->getQuantity(),
            'lineAmountTotal' => $this->getLineAmountTotal()
        ];

        return \json_encode($args);
    }
}
