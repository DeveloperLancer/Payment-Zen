<?php declare(strict_types=1);
/**
 * @author Jakub Gniecki <kubuspl@onet.eu>
 * @copyright
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace DevLancer\Payment\API\Zen\Helper;

/**
 *
 */
class Customer
{
    /**
     * @var string|null
     */
    private ?string $id;
    /**
     * @var string|null
     */
    private ?string $firstName;
    /**
     * @var string|null
     */
    private ?string $lastName;
    /**
     * @var string|null
     */
    private ?string $email;

    /**
     * @param string|null $id
     * @param string|null $firstName
     * @param string|null $lastName
     * @param string|null $email
     */
    public function __construct(?string $id = null, ?string $firstName = null, ?string $lastName = null, ?string $email = null)
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
    }


    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     */
    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string|null $firstName
     */
    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string|null $lastName
     */
    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $args = [
            'id' => $this->getId(),
            'firstName' => $this->getFirstName(),
            'lastName' => $this->getLastName(),
            'email' =>  $this->getEmail(),
        ];

        return \json_encode($args);
    }
}