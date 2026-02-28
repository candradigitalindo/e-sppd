<?php

namespace App\Repositories\Interfaces;

interface UnitKerjaRepositoryInterface extends BaseRepositoryInterface
{
    public function getTree();
    public function getByParent(?string $parentId);
    public function getRoots();
}
