<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: xxx
 * Date: 13.07.2019
 * Time: 9:55
 */

namespace Application\Service;

use Application\Entity\Issues;
use Zend\Paginator\Paginator;

interface IssueService
{
    /**
     * @param $sortBy
     * @param $orderBy
     * @param $page
     * @param $size
     * @return Paginator
     */
    public function getList($sortBy, $orderBy, $page, $size): Paginator;

    /**
     * @param Issues $entity
     * @return Issues
     */
    public function create(Issues $entity);

    /**
     * @param $id
     * @return Issues
     */
    public function findOneById($id);

    /**
     * @param Issues $entity
     * @return Issues
     */
    public function edit(Issues $entity);
}
