<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\Form\IssueForm;
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

    /**
     * Вывод всех задач
     * @return ViewModel
     */
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

        // Для сортировки
        $model->setVariables([
            "cId" => $this->_createIconAndClass("id", $sortBy, $orderBy),
            "cStatus" => $this->_createIconAndClass("status", $sortBy, $orderBy),
            "cUserName" => $this->_createIconAndClass("userName", $sortBy, $orderBy),
            "cUserEmail" => $this->_createIconAndClass("userEmail", $sortBy, $orderBy),
        ]);

        //
        return $model;
    }

    /**
     * Вспомагательный метод для создание иконки при сортировки
     * @param $current
     * @param $sortBy
     * @param $orderBy
     * @return array
     */
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

    /**
     * Создание задач
     * @return \Zend\Http\Response|ViewModel
     */
    public function createAction()
    {
        $form = new IssueForm();

        if ($this->getRequest()->isPost()) {
            // Fill in the form with POST data
            $data = $this->params()->fromPost();
            $form->setData($data);

            // Validate form
            if ($form->isValid()) {
                // Get filtered and validated data
                $data = $form->getData();
                $issue = $this->issueService->create($data);

                $this->flashMessenger()->addInfoMessage(sprintf("Новая задача <a href=\"%s\">#%s</a> успешно добавлена",
                    $this->url()->fromRoute("edit", ["id" => $issue->getId()]), $issue->getId()));

                // Redirect to "Thank You" page
                return $this->redirect()->toRoute('home');
            }
        }

        return new ViewModel([
            'form' => $form
        ]);
    }

    /**
     */
    public function editAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id <= 0) {
            $this->getResponse()->setStatusCode(404);
            return false;
        }

        // Find the by ID
        $issue = $this->issueService->findOneById($id);
        if ($issue == null) {
            $this->getResponse()->setStatusCode(404);
            return false;
        }

        $form = new IssueForm();
        $form->bind($issue);

        if ($this->getRequest()->isPost()) {
            // Get POST data.
            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->issueService->edit($issue);

                $this->flashMessenger()->addInfoMessage(sprintf("Задача <a href=\"%s\">#%s</a> успешно отредактирована",
                    $this->url()->fromRoute("edit", ["id" => $issue->getId()]), $issue->getId()));
                // Redirect
                return $this->redirect()->toRoute('home');
            }
        }

        return new ViewModel([
            'form' => $form,
            'id' => $issue->getId()
        ]);
    }
}

















