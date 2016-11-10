<?php

namespace app\interfaces;

use app\interfaces\VisitorInterface;

interface ClientInterface
{
    public function visit(VisitorInterface $visitor);
}
