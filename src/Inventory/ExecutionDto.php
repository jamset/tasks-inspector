<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 28.10.15
 * Time: 14:37
 */
namespace TasksInspector\Inventory;

class ExecutionDto
{

    /**
     * @var bool
     */
    protected $errorExist = false;

    /**
     * @var string
     */
    protected $errorMessage;

    /**
     * @var string
     */
    protected $executionMessage;

    /**
     * @var int | string
     */
    protected $taskId;

    /**
     * @var bool
     */
    protected $criticalError = false;

    /**
     * @return boolean
     */
    public function isCriticalError()
    {
        return $this->criticalError;
    }

    /**
     * @param boolean $criticalError
     */
    public function setCriticalError($criticalError)
    {
        $this->errorExist = true;
        $this->criticalError = $criticalError;
    }

    /**
     * @return int|string
     */
    public function getTaskId()
    {
        return $this->taskId;
    }

    /**
     * @param int|string $taskId
     */
    public function setTaskId($taskId)
    {
        $this->taskId = $taskId;
    }

    /**
     * @return string
     */
    public function getExecutionMessage()
    {
        return $this->executionMessage;
    }

    /**
     * @param string $executionMessage
     */
    public function setExecutionMessage($executionMessage)
    {
        $this->executionMessage = $executionMessage;
    }

    /**
     * @return boolean
     */
    public function isErrorExist()
    {
        return $this->errorExist;
    }

    /**
     * @param boolean $errorExist
     */
    public function setErrorExist($errorExist)
    {
        $this->errorExist = $errorExist;
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * @param string $errorMessage
     */
    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;
    }

}