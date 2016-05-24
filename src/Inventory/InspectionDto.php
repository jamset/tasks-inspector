<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 28.10.15
 * Time: 17:45
 */
namespace TasksInspector\Inventory;

class InspectionDto
{
    /**
     * @var string
     */
    protected $inspectionMessage;

    /**
     * @var array
     */
    //protected $executedWithErrorTasksId = [];

    /**
     * @return array

    public function getExecutedWithErrorTasksId()
     * {
     * return $this->executedWithErrorTasksId;
     * }
     *
     * /**
     * @param array $executedWithErrorTasksId

    public function setExecutedWithErrorTasksId($executedWithErrorTasksId)
     * {
     * $this->executedWithErrorTasksId = $executedWithErrorTasksId;
     * }*/

    /**
     * @return string
     */
    public function getInspectionMessage()
    {
        return $this->inspectionMessage;
    }

    /**
     * @param string $inspectionMessage
     */
    public function setInspectionMessage($inspectionMessage)
    {
        $this->inspectionMessage = $inspectionMessage;
    }


}