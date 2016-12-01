<?php

namespace app\finders;

use app\collections\CollectionInterface;

interface FinderInterface
{
    public function find();
    public function load($data, $formName);
    public function setCollection(CollectionInterface $collection);
}
