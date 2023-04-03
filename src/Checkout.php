<?php declare(strict_types=1);
/**
 * @author Jakub Gniecki <kubuspl@onet.eu>
 * @copyright
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace DevLancer\Payment\API\Zen;

class Checkout
{
    private string $redirectUrl;

    public function __construct(array $data)
    {
        $redirectUrl = $data['redirectUrl'];
        $this->redirectUrl = $redirectUrl;
    }

    /**
     * @return string
     */
    public function getRedirectUrl(): string
    {
        return $this->redirectUrl;
    }
}