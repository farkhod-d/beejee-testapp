<?php
declare(strict_types=1);


namespace Application\Repository;


use Application\Entity\Issues;
use Doctrine\ORM\EntityRepository;

class IssueRepository extends EntityRepository
{
    public function getIssues($sortBy, $orderBy)
    {
        $entityManager = $this->getEntityManager();
        $builder = $entityManager->createQueryBuilder();

        $builder->select('t')
            ->from(Issues::class, 't')
            ->orderBy(sprintf("t.%s", $sortBy), $orderBy);

        $query = $builder->getQuery();
        return $query;
    }
}
