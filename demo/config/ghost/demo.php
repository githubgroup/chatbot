<?php

use Commune\Blueprint\Kernel\Handlers\GhostRequestHandler;
use Commune\Blueprint\NLU\NLUService;
use Commune\Framework;
use Commune\Components;
use Commune\Ghost\Predefined\Join\JoinCmd;
use Commune\Kernel\GhostCmd;
use Commune\Ghost\IGhostConfig;
use Commune\Ghost\Providers as GhostProviders;
use Commune\Ghost\Predefined\Intent\Navigation;
use Commune\Kernel\Handlers\IGhostRequestHandler;
use Commune\Blueprint\Kernel\Protocols\GhostRequest;
use Commune\NLU\NLUServiceProvider;
use Commune\Components\Demo\Maze\Maze;


return new IGhostConfig([
    'id' => 'demo',

    'name' => 'demo',

    'providers' => [

        /* proc service */

        // mind set
        GhostProviders\MindsetServiceProvider::class,

        /* req service */

        // runtime driver
        Framework\Providers\RuntimeDriverDemoProvider::class,

        // clone service
        GhostProviders\ClonerServiceProvider::class,

        // nlu service
        NLUServiceProvider::class,
    ],

    'options' => [],

    'components' => [
        // 测试用例
        Components\Demo\DemoComponent::class,
        // tree 测试用例
        Components\Tree\TreeComponent::class,

        // Markdown 组件
        Components\Markdown\MarkdownComponent::class,

        // heed fallback
        Components\HeedFallback\HeedFallbackComponent::class,

        // SpaCy-NLU
        Components\SpaCyNLU\SpaCyNLUComponent::class,
    ],

    // request Protocols
    'Protocols' => [
        [
            'Protocol' => GhostRequest::class,
            // interface
            'interface' => GhostRequestHandler::class,
            // 默认的
            'default' => IGhostRequestHandler::class,
        ],
    ],

    // 用户命令
    'userCommands' => [
        GhostCmd\GhostHelpCmd::class,
        GhostCmd\User\HelloCmd::class,
        GhostCmd\User\WhoCmd::class,
        GhostCmd\User\QuitCmd::class,
        GhostCmd\User\CancelCmd::class,
        GhostCmd\User\BackCmd::class,
        GhostCmd\User\RepeatCmd::class,
        GhostCmd\User\RestartCmd::class,
        GhostCmd\User\HomeCmd::class,
        JoinCmd::class,
    ],

    // 管理员命令
    'superCommands' => [
        GhostCmd\GhostHelpCmd::class,
        GhostCmd\Super\SpyCmd::class,
        GhostCmd\Super\ScopeCmd::class,
        GhostCmd\Super\ProcessCmd::class,
        GhostCmd\Super\IntentCmd::class,
        GhostCmd\Super\RedirectCmd::class,
        GhostCmd\Super\SceneCmd::class,
        GhostCmd\Super\WhereCmd::class,
    ],

    'comprehendPipes' => [
        NLUService::class,
    ],

    'mindPsr4Registers' => [
    ],

    // session
    'sessionExpire' => 3600,
    'sessionLockerExpire' => 3,
    'maxRedirectTimes' => 255,
    'maxRequestFailTimes' => 3,
    'mindsetCacheExpire' => 600,
    'maxBacktrace' => 3,
    'defaultContextName' => Components\Demo\Contexts\DemoHome::genUcl()->encode(),
    'sceneContextNames' => [
        Maze::genUcl()->encode(),
        'md.demo.*',
    ],
    'defaultHeedFallback' =>[
        Components\HeedFallback\Action\HeedFallback::class,
    ],

    'globalContextRoutes' => [
        // 其实也可以用 __name(), 但下面这个方法才最本质.
        Navigation\CancelInt::genUcl()->encode(),
        Navigation\RepeatInt::genUcl()->encode(),
        Navigation\QuitInt::genUcl()->encode(),
        Navigation\HomeInt::genUcl()->encode(),
        Navigation\BackwardInt::genUcl()->encode(),
        Navigation\RestartInt::genUcl()->encode(),
        Navigation\WrongInt::genUcl()->encode(),
    ]

]);
