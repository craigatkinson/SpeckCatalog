<?php

namespace Catalog\Hydrator;

class Category extends AbstractHydrator
{
    protected $nonDbFields = array('categories', 'products');

    public function __construct()
    {
        parent::__construct($this->nonDbFields);
    }
}
