<?php

/**
 * This file is part of CommuneChatbot.
 *
 * @link     https://github.com/thirdgerb/chatbot
 * @document https://github.com/thirdgerb/chatbot/blob/master/README.md
 * @contact  <thirdgerb@gmail.com>
 * @license  https://github.com/thirdgerb/chatbot/blob/master/LICENSE
 */

namespace Commune\Contracts\Trans;


/**
 * @author thirdgerb <thirdgerb@gmail.com>
 */
interface Translatable
{
    public function getTransTemp() : string;

    public function getSlots() : array;
}