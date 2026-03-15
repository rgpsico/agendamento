<?php

// app/Observers/GenericObserver.php
namespace App\Observers;

use App\Helpers\EventLogger;
use Illuminate\Database\Eloquent\Model;

class GenericObserver
{
    protected string $modelName;

    public function __construct(string $modelName)
    {
        $this->modelName = strtolower(class_basename($modelName));
    }

    public function created(Model $model)
    {
        EventLogger::log("{$this->modelName}_created", $model->toArray(), 'admin');
    }

    public function updated(Model $model)
    {
        EventLogger::log("{$this->modelName}_updated", $model->getChanges(), 'admin');
    }

    public function deleted(Model $model)
    {
        EventLogger::log("{$this->modelName}_deleted", ['id' => $model->id], 'admin');
    }
}
