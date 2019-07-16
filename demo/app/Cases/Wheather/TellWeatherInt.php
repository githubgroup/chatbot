<?php


namespace Commune\Demo\App\Cases\Wheather; //拼写错误是历史遗留问题了...


use Commune\Chatbot\App\Callables\Actions\Redirector;
use Commune\Chatbot\App\Intents\ActionIntent;
use Commune\Chatbot\Blueprint\Message\Message;
use Commune\Chatbot\Blueprint\Message\QA\Answer;
use Commune\Chatbot\Blueprint\Message\VerboseMsg;
use Commune\Chatbot\OOHost\Context\Exiting;
use Commune\Chatbot\OOHost\Context\Stage;
use Commune\Chatbot\OOHost\Dialogue\Dialog;
use Commune\Chatbot\OOHost\Directing\Navigator;
use Illuminate\Support\Arr;

/**
 * @property string $city
 * @property string $date
 */
class TellWeatherInt extends ActionIntent
{
    const DESCRIPTION = '查询天气测试用例';

    const SIGNATURE = 'tellWeather
        {city : 请问您想查询哪个城市的天气?}
        {date : 请问您想查询哪天的天气?}
    ';

    // 给NLU用的例句.
    const EXAMPLES = [
        '[今天](date)天气怎么样',
        '我想知道[明天](date)的天气如何',
        '[北京](city)[后天](date)什么天气啊',
        '气温如何',
        '[明天]多少度',
        '[后天](date)什么天气啊',
        '[上海](city)[大后天](date)下雨吗',
        '您知道[广州]的天气吗',
        '会有暴风雨吗',
        '请问[明天](date)下雨吗',
        '[后天](date)多少度啊',
        '[明天](date)是晴天吗',
        '[长沙](date)下雨了吗',
    ];

    protected $cities = [
        '北京', '上海', '长沙', '广州', '西安', '洛阳',
    ];

    protected $wowCities = [
        '暴风城', '奥格瑞玛', '雷霆崖', '铁炉堡',
    ];

    protected $wowRuin = [
        '达纳苏斯', '幽暗城',
    ];

    protected $dateAliases = [
        '今天' => 'today',
        '昨天' => 'yesterday',
        '明天' => 'tomorrow',
        '后天' => '+2 day',
        '大后天' => '+3 day',
        '前天' => '-2 day',
        '周一' => 'monday',
        '周二' => '星期二',
    ];

    public function action(Stage $stageRoute): Navigator
    {
        return $stageRoute->buildTalk()
            ->action(function(Dialog $dialog){

                $city = $this->city;

                if (! $this->doValidateCity($dialog, $city)) {
                    return $dialog->fulfill();
                }

                $date = $this->date;
                $time = $this->fetchTime($date);
                if (!isset($time)) {
                    $dialog->say()
                        ->warning("sorry.. 日期没识别出来");
                    unset($this->date);
                    return $dialog->goStagePipes(['date', 'start']);
                }

                $realDate = date('Y-m-d', $time);

                $this->broadcast($dialog, $city, $realDate);

                return $dialog->goStage('more');
            });
    }


    protected function broadcast(Dialog $dialog, string $city, string $realDate) : void
    {

        $say = $dialog->say([
            'temp' => rand(10, 40),
            'date' => $realDate,
            'city' => $city,
            'weather' => Arr::random([
                '多云', '大雨', '狂风', '风和日丽', '雾霾', '沙尘暴',
            ]),
        ]);

        $say->info(" (为省工作量, 假装调用了api)");

        if (in_array($city, $this->wowRuin)) {
            $say->info(
                "%city% 已经被摧毁了, 我个人猜测%date%的天气是%weather%, 温度%temp%度");
        } elseif (in_array($city, $this->wowCities)) {
            $say->info(
                "%city%在%date%的天气是%weather%, 温度%temp%度, 不信您进游戏里看");
        } else {
            $say->info(
                "%city%在%date%的天气是%weather%, 温度%temp%度 (纯属随机值)");
        }

    }


    public function __onMore(Stage $stage) : Navigator
    {
        return $stage
            ->buildTalk()
            ->askConfirm('还需要了解更多吗(如果需要了解别的城市或日期, 可以直接说出来)?')
            ->wait()
            ->hearing()
            ->isPositive(function(Dialog $dialog){
                $this->city = null;
                $this->date = null;

                return $dialog->restart();
            })
            ->isNegative([Redirector::class, 'fulfill'])
            ->isInstanceOf(
                VerboseMsg::class,
                function(Dialog $dialog, VerboseMsg $msg){
                    $text = $msg->getTrimmedText();
                    // 简单抽取城市
                    $city = $this->matchCity($text);
                    // 简单抽取日期
                    $date = $this->matchDate($text);

                    $restart = false;
                    if (!empty($city)) {
                        $this->city = $city;
                        $restart = true;
                    }

                    if (!empty($date) && $this->fetchTime($date)) {
                        $this->date = $date;
                        $restart = true;
                    }

                    // 输入不需要抽取
                    if ($this->fetchTime($text)) {
                        $this->date = $text;
                        $restart = true;
                    }

                    if ($restart) {
                        return $dialog->restart();
                    }

                    return null;
                }
            )
            ->defaultFallback()
            ->end(function(Dialog $dialog){
                $cities = implode(",", $this->allCities());
                $dialog->say()->info(<<<EOF
sorry, 没明白您的意思. 

测试用例目前没有把精力花在自然语言识别

能识别的日期仅包括:今天,明天,后天,大后天, xx号
能识别的城市仅包括:$cities
EOF
                );

                return $dialog->repeat();
            });


    }

    protected function matchCity(string $text) : ? string
    {
        $cities = $this->allCities();

        foreach($cities as $city) {
            if (strpos($text, $city) !== false) {
                return $city;
            }
        }
        return null;
    }

    protected function allCities() : array
    {
        return array_merge($this->wowCities, $this->wowRuin, $this->cities);
    }

    protected function matchDate(string $text) : ? string
    {
        $dates = array_keys($this->dateAliases);

        foreach($dates as $date) {
            if (strpos($text, $date) !== false) {
                return $date;
            }
        }

        if (preg_match('/(\d+)号|(\d+)日/', $text, $matches)) {
            $date = $matches[1] ?? 0;
            if ($date > 0 && $date < 32) {
                return date('Y-m'). "-$date";
            }
        }

        return null;
    }



    public function __exiting(Exiting $listener): void
    {
    }


    public function __validateCity(Dialog $dialog, Message $message) : ? Navigator
    {
        $text = $message->getTrimmedText();
        return $this->doValidateCity($dialog, $text) ? null : $dialog->repeat();
    }

    protected function doValidateCity(Dialog $dialog, string $city) : bool
    {
        $cities = array_merge($this->wowCities, $this->cities, $this->wowRuin);

        if (in_array($city, $cities)) {
            return true;
        }

        $dialog->say()->warning(
            "sorry, 我们现在只有以下城市的数据:" . implode(',', $cities)
        );

        return false;
    }

    public function __askDate(Dialog $dialog) :  void
    {
        $dialog->say()->askVerbose(
            '请告诉我您想查询的日期, 可用英文, 或者Y-m-d的格式. 中文仅支持明天,后天等常用词',
            [
                '今天',
                '明天',
                '后天'
            ]
        );
    }

    public function __validateDate(Dialog $dialog, Answer $message) : ? Navigator
    {
        $date = $message->toResult();
        $time = $this->fetchTime($date);

        if (isset($time)) {
            return null;
        }

        $dialog->say()->warning('对不起, 日期格式我无法理解...');

        return $dialog->repeat();
    }

    protected function fetchTime(string $date) : ? int
    {
        $word = $this->dateAliases[$date] ?? $date;
        $time = strtotime($word);

        return is_int($time) ? $time : null;
    }

}