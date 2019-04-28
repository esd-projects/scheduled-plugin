<?php
/**
 * Created by PhpStorm.
 * User: 白猫
 * Date: 2019/4/28
 * Time: 14:41
 */

namespace GoSwoole\Plugins\Scheduled\Beans;


use GoSwoole\Plugins\Scheduled\Cron\CronExpression;

class ScheduledTask
{
    const ProcessGroupAll = "all";
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $expression;

    /**
     * @var string
     */
    private $className;

    /**
     * @var string
     */
    private $functionName;

    /**
     * @var string
     */
    private $processGroup;

    /**
     * @var CronExpression
     */
    private $cron;

    /**
     * ScheduledTask constructor.
     * @param $name
     * @param $expression
     * @param $className
     * @param $functionName
     * @param string $processGroup
     */
    public function __construct($name, $expression, $className, $functionName, $processGroup = ScheduledTask::ProcessGroupAll)
    {
        $this->name = $name;
        $this->expression = $expression;
        $this->className = $className;
        $this->functionName = $functionName;
        $this->processGroup = $processGroup;
        $this->cron = CronExpression::factory($expression);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getExpression(): string
    {
        return $this->expression;
    }

    /**
     * @param string $expression
     */
    public function setExpression(string $expression): void
    {
        $this->expression = $expression;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @param string $className
     */
    public function setClassName(string $className): void
    {
        $this->className = $className;
    }

    /**
     * @return string
     */
    public function getFunctionName(): string
    {
        return $this->functionName;
    }

    /**
     * @param string $functionName
     */
    public function setFunctionName(string $functionName): void
    {
        $this->functionName = $functionName;
    }

    /**
     * @return string
     */
    public function getProcessGroup(): string
    {
        return $this->processGroup;
    }

    /**
     * @param string $processGroup
     */
    public function setProcessGroup(string $processGroup): void
    {
        $this->processGroup = $processGroup;
    }

    /**
     * @return CronExpression
     */
    public function getCron(): CronExpression
    {
        return $this->cron;
    }
}