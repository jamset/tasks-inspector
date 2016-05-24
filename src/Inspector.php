<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 28.10.15
 * Time: 14:38
 */
namespace TasksInspector;

use PHPMailerAdapter\Interfaces\Mail;
use PHPMailerAdapter\Mailer;
use TasksInspector\Inventory\Exceptions\InspectorInvalidArgument;
use TasksInspector\Inventory\ExecutionDto;
use TasksInspector\Inventory\InspectionDto;
use TasksInspector\Inventory\InspectorConstants;
use Monolog\Logger;

class Inspector
{

    /**
     * @var int
     */
    protected $createdTasksNumber;

    /**
     * @var array
     */
    protected $createdTasks = [];

    /**
     * @var int
     */
    protected $createdTasksBeforeHandling;

    /**
     * @var int
     */
    protected $correctlyExecuted = 0;

    /**
     * @var array
     */
    protected $executedWithError = [];

    /**
     * @var Mail
     */
    protected $mailer;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var string
     */
    protected $loggerPostfix;

    /**
     * @var string
     */
    protected $moduleName;

    /**
     * @var bool
     */
    protected $allTasksComplete = true;

    /**
     * @var string
     */
    protected $attentionMethod = InspectorConstants::BOTH;

    /**
     * @var bool
     */
    protected $mustDie = false;

    public function __construct()
    {
        $this->mailer = new Mailer();
        $this->loggerPostfix = " | " . ($this->moduleName) ?: "default inspector.";

        return null;
    }

    public function inspect()
    {
        $inspectionDto = new InspectionDto();
        $inspectionMessage = '';

        if ($this->createdTasksBeforeHandling !== ($this->correctlyExecuted + count($this->executedWithError))) {
            $this->allTasksComplete = false;

            $inspectionMessage .= "Not all tasks complete: "
                . " created (before handling): " . $this->createdTasksBeforeHandling
                . " correctlyExecuted: " . serialize($this->getCorrectlyExecuted())
                . " executedWithError: " . serialize($this->getExecutedWithError())
                . $this->loggerPostfix;
        }

        $this->logger->notice("CreatedTasks before handling: " . serialize($this->createdTasksBeforeHandling));
        $this->logger->notice("CreatedTasks after handling: " . serialize($this->createdTasksNumber));
        $this->logger->notice("CorrectlyExecuted: " . serialize($this->getCorrectlyExecuted()));
        $this->logger->notice("Executed with error: " . serialize($this->executedWithError));

        if (!empty($this->executedWithError)) {
            switch ($this->attentionMethod) {
                case(InspectorConstants::LOG):
                    $this->makeAttentionLog($inspectionMessage);
                    break;
                case(InspectorConstants::MAIL):
                    $this->sendAttentionMail($inspectionMessage);
                    break;
                default:
                    $this->makeAttentionLog($inspectionMessage);
                    //$this->sendAttentionMail($inspectionMessage);
            }
        }

        $inspectionDto->setInspectionMessage($inspectionMessage);

        return $inspectionDto;
    }

    public function sendAttentionMail($inspectionMessage)
    {
        $this->getMailer()->setBody($inspectionMessage . " | " . serialize($this->executedWithError));
        $sentResult = $this->getMailer()->send();

        $this->logger->alert("Mail from " .
            $this->getMailer()->getFrom()
            . " TO: " . serialize($this->getMailer()->getAllRecipientAddresses())
            . " with subject " . $this->getMailer()->getSubject()
            . " contain " . serialize($this->getMailer()->getBody()) . " with result  " . serialize($sentResult) . " was sent.");

        return null;
    }

    protected function makeAttentionLog($inspectionMessage)
    {
        if (!$this->allTasksComplete) {
            $this->logger->error("Inspection message: " . $inspectionMessage);
        }

        $this->logger->warning("Executed with Error: " . serialize($this->executedWithError));

        return null;
    }

    /**
     * @param $taskData string
     * @return null
     */
    public function checkTaskDataType($taskData)
    {
        if (!($taskData instanceof ExecutionDto)) {
            throw new InspectorInvalidArgument("Received not ExecutionDto: " . serialize($taskData));
        }

        return null;
    }

    /**
     * @return int
     */
    public function getCreatedTasksNumber()
    {
        return $this->createdTasksNumber;
    }

    /**
     * @param int $createdTasksNumber
     */
    public function setCreatedTasksNumber($createdTasksNumber)
    {
        $this->createdTasksNumber = $createdTasksNumber;
    }

    /**
     * @return array
     */
    public function getExecutedWithError()
    {
        return $this->executedWithError;
    }

    /**
     * @param array $executedWithError
     */
    public function setExecutedWithError(ExecutionDto $executedWithError)
    {
        $this->executedWithError[] = $executedWithError;
    }

    /**
     * @return Mail
     */
    public function getMailer()
    {
        return $this->mailer;
    }

    /**
     * @param Mail $mailer
     */
    public function setMailer($mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @return Logger
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param Logger $logger
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return string
     */
    public function getModuleName()
    {
        return $this->moduleName;
    }

    /**
     * @param string $moduleName
     */
    public function setModuleName($moduleName)
    {
        $this->moduleName = $moduleName;
    }

    /**
     * @return string
     */
    public function getAttentionMethod()
    {
        return $this->attentionMethod;
    }

    /**
     * @param string $attentionMethod
     */
    public function setAttentionMethod($attentionMethod)
    {
        $this->attentionMethod = $attentionMethod;
    }

    /**
     * @return array
     */
    public function getCreatedTasks()
    {
        return $this->createdTasks;
    }

    /**
     * @param array $createdTasks
     */
    public function setCreatedTasks($key = null, $createdTask)
    {
        if ($key) {
            $this->createdTasks[$key] = $createdTask;
        } else {
            $this->createdTasks[] = $createdTask;
        }

        return $this;
    }

    public function unsetCreatedTask($key)
    {
        if (isset($this->createdTasks[$key])) {
            unset($this->createdTasks[$key]);
        }

        return null;
    }

    /**
     * @return int
     */
    public function getCorrectlyExecuted()
    {
        return $this->correctlyExecuted;
    }

    /**
     * @return null
     */
    public function incrementCorrectlyExecuted()
    {
        $this->correctlyExecuted++;

        return null;
    }


    /**
     * @return string
     */
    public function getLoggerPostfix()
    {
        return $this->loggerPostfix;
    }

    /**
     * @param string $loggerPostfix
     */
    public function setLoggerPostfix($loggerPostfix)
    {
        $this->loggerPostfix = $loggerPostfix;
    }


    /**
     * @return int
     */
    public function getCreatedTasksBeforeHandling()
    {
        return $this->createdTasksBeforeHandling;
    }

    /**
     * @param int $createdTasksBeforeHandling
     */
    public function setCreatedTasksBeforeHandling($createdTasksBeforeHandling)
    {
        $this->createdTasksBeforeHandling = $createdTasksBeforeHandling;
    }

    /**
     * @return boolean
     */
    public function isMustDie()
    {
        return $this->mustDie;
    }

    /**
     * @param boolean $mustDie
     */
    public function setMustDie($mustDie)
    {
        $this->mustDie = $mustDie;
    }

}
