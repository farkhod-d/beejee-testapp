<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: xxx
 * Date: 13.07.2019
 * Time: 9:55
 */

namespace Application\Service;

use Zend\Paginator\Paginator;

interface IssueService
{
    public function getList($sortBy, $orderBy, $page, $size): Paginator;
}
