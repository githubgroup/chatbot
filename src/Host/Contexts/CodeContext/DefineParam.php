<?php

/**
 * This file is part of CommuneChatbot.
 *
 * @link     https://github.com/thirdgerb/chatbot
 * @document https://github.com/thirdgerb/chatbot/blob/master/README.md
 * @contact  <thirdgerb@gmail.com>
 * @license  https://github.com/thirdgerb/chatbot/blob/master/LICENSE
 */

namespace Commune\Host\Contexts\CodeContext;

use Commune\Blueprint\Ghost\Context\ParamBuilder;

/**
 * @author thirdgerb <thirdgerb@gmail.com>
 */
interface DefineParam
{
    public static function __params(ParamBuilder $param) : ParamBuilder;

}