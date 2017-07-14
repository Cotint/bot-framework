<?php
/**
 * Created by PhpStorm.
 * User: m.mesripour
 * Date: 2016-11-24
 * Time: 11:36 AM
 */

namespace model;

use League\Container\Container;
use MongoDB\Collection;


class MainModel
{
    public $container;

    /**
     * MainModel constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $collection
     * @return Collection
     */
    public function mongo(string $collection): Collection
    {
        return $this->container->get('mongo')->{$collection};
    }
}