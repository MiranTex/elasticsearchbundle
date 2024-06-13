<?php

namespace Bundles\ElasticsearchBundle\Interface;

interface Arrayable
{
    public function toArray(): array;

    public function getClassID(): string;
}