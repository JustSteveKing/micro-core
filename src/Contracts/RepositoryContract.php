<?php

declare(strict_types=1);

namespace JustSteveKing\Micro\Contracts;

use Countable;

interface RepositoryContract extends Countable
{
    public function find(int|string $id);

    public function get();

    public function save();

    public function update();

    public function delete();
}
