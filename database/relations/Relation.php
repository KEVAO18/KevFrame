<?php

namespace App\Database\Relations;

use App\Models\Model;

interface Relation
{
    public function setParent(Model $parent);
    public function getResults();
}