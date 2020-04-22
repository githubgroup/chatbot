<?php

/**
 * This file is part of CommuneChatbot.
 *
 * @link     https://github.com/thirdgerb/chatbot
 * @document https://github.com/thirdgerb/chatbot/blob/master/README.md
 * @contact  <thirdgerb@gmail.com>
 * @license  https://github.com/thirdgerb/chatbot/blob/master/LICENSE
 */

namespace Commune\Ghost\Auth;

use Commune\Blueprint\Exceptions\Logic\InvalidClassException;
use Commune\Blueprint\Framework\ReqContainer;
use Commune\Blueprint\Ghost\Auth\Authority;
use Commune\Blueprint\Ghost\Auth\Policy;

/**
 * @author thirdgerb <thirdgerb@gmail.com>
 */
class IAuthority implements Authority
{
    /**
     * @var ReqContainer
     */
    protected $container;

    public function allow(string $policy, array $payload): bool
    {
        if (!is_a($policy, Policy::class, TRUE)) {
            throw new InvalidClassException(
                Policy::class,
                $policy
            );
        }

        // 没有绑定过就算不允许, 严格权限检查.
        if (!$this->container->bound($policy)) {
            return false;
        }

        /**
         * @var Policy $policyIns
         */
        $policyIns = $this->container->make($policy);
        return $policyIns->invoke($payload);
    }


}