<?php
/**
 * Created by PhpStorm.
 * User: ww
 * Date: 30.10.15
 * Time: 15:57
 */
namespace TasksInspector;

use TasksInspector\Inventory\ExecutionDto;

class InspectionHelper
{
    public static function prepareErrorExecutionDto($taskId, $errorMsg)
    {
        $executionDto = new ExecutionDto();
        $executionDto->setErrorExist(true);
        $executionDto->setTaskId($taskId);
        $executionDto->setErrorMessage($errorMsg);

        return $executionDto;
    }

}