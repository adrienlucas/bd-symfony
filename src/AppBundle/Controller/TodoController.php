<?php

namespace AppBundle\Controller;

use Drupal\Core\Database\Connection;
use Drupal\todo\DAO\TodoDAO;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class TodoController extends Controller
{
    public function listAction()
    {
        $this->get('debug.stopwatch')->start('Select todos');
        $todos = $this->get('app.dao.todo')->getAll();
        $this->get('debug.stopwatch')->stop('Select todos');
        return $this->render('todo/list.html.twig', [
            'todos' => $todos
        ]);
    }

    public function detailAction($id)
    {
        $todo = $this->get('app.dao.todo')->get($id);
        if($todo === null) {
            throw new ResourceNotFoundException('Task not found...');
        }

        return $this->render('todo/detail.html.twig', [
            'todo' => $todo
        ]);
    }

    public function createAction(Request $request)
    {
        if(!$request->request->has('title')) {
            throw new \InvalidArgumentException('You should provide a title to create a todo');
        }

        $this->get('app.dao.todo')->create($request->request->get('title'));
        return $this->redirectToRoute('todo.list');
    }

    public function closeAction($id)
    {
        $this->get('app.dao.todo')->close($id);
        return $this->redirectToRoute('todo.list');
    }

    public function deleteAction($id)
    {
        $this->get('app.dao.todo')->delete($id);
        return $this->redirectToRoute('todo.list');
    }

}