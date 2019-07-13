<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\Service\IssueService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    /**
     * @var IssueService
     */
    private $issueService;

    /**
     * IndexController constructor.
     * @param IssueService $issueService
     */
    public function __construct(IssueService $issueService)
    {
        $this->issueService = $issueService;
    }

    public function indexAction()
    {
        $page = $this->params()->fromQuery('page', 1);
        $sortBy = $this->params()->fromQuery('sortBy', "id");
        $orderBy = $this->params()->fromQuery('orderBy', "DESC");

        $paginator = $this->issueService->getList($sortBy, $orderBy, $page, 3);

        $model = new ViewModel();
        $model->setVariable("paginator", $paginator);
        $model->setVariable("sortBy", $sortBy);
        $model->setVariable("orderBy", $orderBy);

        $model->setVariables([
            "cId" => $this->_createIconAndClass("id", $sortBy, $orderBy),
            "cStatus" => $this->_createIconAndClass("status", $sortBy, $orderBy),
            "cUserName" => $this->_createIconAndClass("userName", $sortBy, $orderBy),
            "cUserEmail" => $this->_createIconAndClass("userEmail", $sortBy, $orderBy),
        ]);

        return $model;
    }



    private function _createIconAndClass($current, $sortBy, $orderBy)
    {
        $data = [
            "icon" => "fas fa-sort",
            "class" => "text-muted",
        ];

        if ($current == $sortBy) {
            $data['class'] = "text-secondary";
            if ($orderBy == "ASC") {
                $data['icon'] = "fas fa-sort-amount-up";
            } else {
                $data['icon'] = "fas fa-sort-amount-down";
            }
        }

        return $data;
    }




}
