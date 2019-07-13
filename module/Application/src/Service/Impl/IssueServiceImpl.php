<?php
declare(strict_types=1);


namespace Application\Service\Impl;


use Application\Entity\Issues;
use Application\Service\IssueService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Zend\Paginator\Paginator;

class IssueServiceImpl implements IssueService
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * IssueServiceImpl constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getList($sortBy, $orderBy, $page, $size): Paginator
    {
        $sortBy = $this->_normalizeSortBy($sortBy);
        $orderBy = $this->_normalizeOrderBy($orderBy);
        $page = (int)$page;

        $query = $this->entityManager->getRepository(Issues::class)
            ->getIssues($sortBy, $orderBy);

        $adapter = new DoctrineAdapter(new ORMPaginator($query, true));

        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage($size);
        $paginator->setCurrentPageNumber($page);

        return $paginator;
    }


    private function _normalizeSortBy($sortBy)
    {
        $result = "createdAt";
        $valid = ["id", "userName", "userEmail", "status"];
        if (in_array($sortBy, $valid)) {
            $result = $sortBy;
        }
        return $result;
    }

    private function _normalizeOrderBy($orderBy)
    {
        $result = "DESC";
        $valid = ["ASC", "DESC"];
        if (in_array($orderBy, $valid)) {
            $result = $orderBy;
        }
        return $result;
    }


    public function create(Issues $entity)
    {
        $entity->setStatus(false)
            ->setCreatedAt(new \DateTime());

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity;
    }
}
