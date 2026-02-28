<?php

namespace App\Repositories\Interfaces;

interface JabatanRepositoryInterface extends BaseRepositoryInterface
{
    public function getByLevel(string $level);
    public function getForDropdown();
}
