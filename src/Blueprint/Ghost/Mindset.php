<?php

/**
 * This file is part of CommuneChatbot.
 *
 * @link     https://github.com/thirdgerb/chatbot
 * @document https://github.com/thirdgerb/chatbot/blob/master/README.md
 * @contact  <thirdgerb@gmail.com>
 * @license  https://github.com/thirdgerb/chatbot/blob/master/LICENSE
 */

namespace Commune\Blueprint\Ghost;

use Commune\Blueprint\Ghost\MindReg\ContextReg;
use Commune\Blueprint\Ghost\MindReg\DefRegistry;
use Commune\Blueprint\Ghost\MindReg\EmotionReg;
use Commune\Blueprint\Ghost\MindReg\EntityReg;
use Commune\Blueprint\Ghost\MindReg\IntentReg;
use Commune\Blueprint\Ghost\MindReg\MemoryReg;
use Commune\Blueprint\Ghost\MindReg\StageReg;
use Commune\Blueprint\Ghost\MindReg\SynonymReg;
use Psr\Log\LoggerInterface;

/**
 * 对话机器人的基础思维
 *
 * @author thirdgerb <thirdgerb@gmail.com>
 */
interface Mindset
{

    /**
     * 初始化所有的 Contexts
     *
     * @param LoggerInterface $logger
     */
    public function initContexts(LoggerInterface $logger) : void;

    /**
     * 清空所有的逻辑记忆.
     */
    public function reload() : void;

    /**
     * 上下文语境注册表.
     * @return ContextReg
     */
    public function contextReg() : DefRegistry;

    /**
     * Stage 注册表
     * @return StageReg
     */
    public function stageReg() : DefRegistry;

    /**
     * 意图注册表.
     * @return IntentReg
     */
    public function intentReg() : DefRegistry;

    /**
     * @return MemoryReg
     */
    public function memoryReg() : DefRegistry;

    /**
     * @return EmotionReg
     */
    public function emotionReg() : DefRegistry;

    /**
     * @return EntityReg
     */
    public function entityReg() : DefRegistry;

    /**
     * @return SynonymReg
     */
    public function synonymReg() : DefRegistry;

}