<?php

/**
 * This file is part of CommuneChatbot.
 *
 * @link     https://github.com/thirdgerb/chatbot
 * @document https://github.com/thirdgerb/chatbot/blob/master/README.md
 * @contact  <thirdgerb@gmail.com>
 * @license  https://github.com/thirdgerb/chatbot/blob/master/LICENSE
 */

namespace Commune\Message\Host;

use Commune\Protocals\HostMsg;
use Commune\Support\Message\AbsMessage;
use Commune\Protocals\HostMsg\IntentMsg;
use Commune\Support\Struct\Struct;


/**
 * @author thirdgerb <thirdgerb@gmail.com>
 *
 * @property-read string $intentName
 * @property-read string $level
 */
class IIntentMsg extends AbsMessage implements IntentMsg
{

    const INTENT_NAME = '';
    const DEFAULT_LEVEL = HostMsg::INFO;

    public function __construct(
        string $intentName,
        array $slots = [],
        string $level = null
    )
    {
        if (!empty($intentName)) {
            $slots['intentName'] = $intentName;
        }

        if (isset($level)) {
            $slots['level'] = $level;
        }

        parent::__construct($slots);
    }


    public static function stub(): array
    {
        $intentStub = static::intentStub();
        $stub = [
            'intentName' => static::INTENT_NAME,
            'level' => static::DEFAULT_LEVEL
        ];
        return $intentStub + $stub;
    }

    public static function intentStub() : array
    {
        return [];
    }


    public static function relations(): array
    {
        return [];
    }

    public static function create(array $data = []): Struct
    {
        return new static(
            $data['id'] ?? '',
            $data,
            $data['level'] ?? null
        );
    }

    public function getRenderId(): string
    {
        return $this->intentName;
    }

    public function getLevel(): string
    {
        return $this->level;
    }

    public function getText(): string
    {
        return $this->intentName;
    }

    public function isEmpty(): bool
    {
        return false;
    }

    public function getIntentName(): string
    {
        return $this->intentName;
    }

    public function getSlots(): array
    {
        $arr = $this->toArray();
        unset($arr['intentName']);
        unset($arr['level']);
        return $arr;
    }


}