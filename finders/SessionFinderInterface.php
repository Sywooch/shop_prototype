<?php

namespace app\finders;

use app\collections\SessionCollectionInterface;

interface SessionFinderInterface
{
    public function find();
    public function load($data, $formName);
    public function setCollection(SessionCollectionInterface $collection);
}
