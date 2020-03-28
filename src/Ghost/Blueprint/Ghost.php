<?php

/**
 * This file is part of CommuneChatbot.
 *
 * @link     https://github.com/thirdgerb/chatbot
 * @document https://github.com/thirdgerb/chatbot/blob/master/README.md
 * @contact  <thirdgerb@gmail.com>
 * @license  https://github.com/thirdgerb/chatbot/blob/master/LICENSE
 */

namespace Commune\Ghost\Blueprint;

use Commune\Framework\Blueprint\App;
use Commune\Ghost\Blueprint\Kernels\ApiKernel;
use Commune\Ghost\Blueprint\Kernels\AsyncKernel;
use Commune\Ghost\Blueprint\Kernels\MessageKernel;

/**
 * @author thirdgerb <thirdgerb@gmail.com>
 *
 *
 * @property-read string $chatbotName               机器人的名称
 *
 */
interface Ghost extends App
{
    public function getChatbotName() : string;

    public function getApiKernel() : ApiKernel;

    public function getAsyncKernel() : AsyncKernel;

    public function getMessageKernel() : MessageKernel;
}