<?php
declare(strict_types=1);

namespace Application\Controller;

use Application\Entity\Issues;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use restapi\Controller\ApiController;
use Zend\Paginator\Paginator;
use Zend\View\Model\JsonModel;

// use Application\Entity\Book;
// use Application\Entity\Repository\BookRepository;
// use Application\Form\BookForm;
// use Application\InputFilter\FormBookFilter;


class IssueController extends ApiController
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * IssueController constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getList()
    {
        // Set the HTTP status code. By default, it is set to 200
        $this->httpStatusCode = 200;

        $page = $this->params()->fromQuery('page', 1);
        $sortBy = $this->params()->fromQuery('sort_by', "i.id");
        $orderBy = $this->params()->fromQuery('order_by', "DESC");

        $query = $this->entityManager->getRepository(Issues::class)
            ->getIssues($sortBy, $orderBy);

        $adapter = new DoctrineAdapter(new ORMPaginator($query, true));


        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(3);
        $paginator->setCurrentPageNumber($page);

        // var_dump(iterator_to_array($paginator->getIterator()));
        // $pages = $paginator->getPages();
        // var_dump($pages);
        // exit;

        $items = [];
        foreach ($paginator as $item) {
            /** @var Issues $item */
            $items[] = $item;
            //var_dump($item);
            // exit;
        }

        // Set the response
        $this->apiResponse['items'] = $items;
        $this->apiResponse['total'] = $paginator->getTotalItemCount();

        return new JsonModel($items);

        //return $this->createResponse();
        //return new JsonModel();
    }

    public function get($id)
    {
        $this->apiResponse['id'] = $id;
        return $this->createResponse();
    }


}
