<?php

/**
 * This file is part of CommuneChatbot.
 *
 * @link     https://github.com/thirdgerb/chatbot
 * @document https://github.com/thirdgerb/chatbot/blob/master/README.md
 * @contact  <thirdgerb@gmail.com>
 * @license  https://github.com/thirdgerb/chatbot/blob/master/LICENSE
 */

namespace Commune\Shell\Blueprint\Render;

use Commune\Message\Blueprint\ConvoMsg;
use Commune\Message\Blueprint\Reaction\ReactionMsg;

/**
 * @author thirdgerb <thirdgerb@gmail.com>
 */
interface Template
{

    /**
     * 将一个 Reaction 消息 渲染成多条 ConvoMsg
     * @param ReactionMsg $message
     * @return ConvoMsg[]
     */
    public function render(ReactionMsg $message) : array;

}