<?php
/**
 *   _____                    _____
 *  / ____|                  / ____|
 * | |  __  ___ _ __   ___  | |     ___  _ __ ___  _ __ ___   ___ _ __ ___ ___
 * | | |_ |/ _ \ '_ \ / _ \ | |    / _ \| '_ ` _ \| '_ ` _ \ / _ \ '__/ __/ _ \
 * | |__| |  __/ | | |  __/ | |___| (_) | | | | | | | | | | |  __/ | | (_|  __/
 *  \_____|\___|_| |_|\___|  \_____\___/|_| |_| |_|_| |_| |_|\___|_|  \___\___|
 *
 * User: paulcanning
 * Date: 2019-05-02
 * Time: 13:05
 */

namespace Magento\Braintree\Plugin;

use Magento\Braintree\Model\Adapter\BraintreeAdapter;
use Magento\Vault\Api\Data\PaymentTokenInterface;
use Magento\Vault\Api\PaymentTokenRepositoryInterface;
use Psr\Log\LoggerInterface;

/**
 * Class DeleteStoredPaymentPlugin
 * @package Magento\Braintree\Plugin
 * @author Paul Canning <paul.canning@gene.co.uk>
 */
class DeleteStoredPaymentPlugin
{
    /**
     * @var BraintreeAdapter
     */
    private $braintreeAdapter;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * DeleteStoredPaymentPlugin constructor
     *
     * @param BraintreeAdapter $braintreeAdapter
     * @param LoggerInterface $logger
     */
    public function __construct(
        BraintreeAdapter $braintreeAdapter,
        LoggerInterface $logger
    ) {
        $this->braintreeAdapter = $braintreeAdapter;
        $this->logger = $logger;
    }

    /**
     * @param PaymentTokenRepositoryInterface $subject
     * @param PaymentTokenInterface $paymentToken
     * @return bool|null
     */
    public function beforeDelete(PaymentTokenRepositoryInterface $subject, PaymentTokenInterface $paymentToken)
    {
        $token = $paymentToken->getGatewayToken();

        if ($this->braintreeAdapter->deletePaymentMethod($token)) {
            $this->logger->debug('vault payment deleted ' . $token);
            return null;
        }

        return false;
    }
}
