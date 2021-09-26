<?php

namespace Minivel;
use Minivel\DB\DBModel;

abstract class UserModel extends DBModel
{
    abstract public function getDisplayName() : string;
}