<?php

namespace App\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class TaskController extends AbstractController
{
    #[Route('/task', name: 'get_task_list', methods: 'GET')]
    public function index(TaskRepository $repo): JsonResponse
    {
        $list = $repo->findAll();

        return $this->json([
            'data' => $list,
        ]);
    }

    #[Route('/task', name: 'create_task', methods: 'POST')]
    public function createTask(TaskRepository $repo, Request $request): JsonResponse
    {
        $task = new Task();
        $task->setContent($request->request->get('content'));
        $task->setIsDone(false);

        $repo->add($task, true);

        return $this->json([
            'data' => $task,
            'message' => 'Задача успешно сохранена!',
        ]);
    }

    #[Route('/task', name: 'edit_task', methods: 'PUT')]
    public function editTask(TaskRepository $repo, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!array_key_exists('id', $data)) return $this->json([
            'message' => 'Вы не передали обязательные параметры',
        ], 400);

        $task = $repo->find($data['id']);
        if (!$task instanceof Task) return $this->json([
            'message' => 'Задача не найдена',
        ], 404);

        if (array_key_exists('content', $data))
            $task->setContent($data['content']);
        if (array_key_exists('isDone', $data))
            $task->setIsDone($data['isDone']);

        $repo->add($task, true);

        return $this->json([
            'data' => $task,
            'message' => 'Задача успешно сохранена!',
        ]);
    }

    #[Route('/task/{id}', name: 'delete_task', methods: 'DELETE')]
    public function deleteTask($id, TaskRepository $repo): JsonResponse
    {
        $task = $repo->find($id);
        if (!$task instanceof Task) return $this->json([
            'message' => 'Задача не найдена',
        ], 404);

        $repo->remove($task, true);

        return $this->json([
            'data' => $task,
            'message' => 'Задача успешно удалена!',
        ]);
    }
}
