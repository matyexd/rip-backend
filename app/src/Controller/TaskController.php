<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\TaskRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/api')]
class TaskController extends AbstractController
{
    #[Route('/task', name: 'get_task_list', methods: 'GET')]
    public function index(TaskRepository $repo, ManagerRegistry $doctrine, #[CurrentUser] UserInterface $user): JsonResponse
    {
        $userId = $user->getId();

        $list = $repo->findBy(['user_id' => $userId]);

        return $this->json([
            'data' => $list,
            'id' => $user->getId()
        ]);
    }

    #[Route('/task', name: 'create_task', methods: 'POST')]
    public function createTask(TaskRepository $repo, Request $request, #[CurrentUser] UserInterface $user): JsonResponse
    {
        $task = new Task();
        $task->setContent($request->request->get('content'));
        $task->setIsDone(false);
        $task->setUserId($user->getId());

        $repo->add($task, true);

        return $this->json([
            'data' => $task,
            'message' => 'Задача успешно сохранена!',
        ]);
    }

    #[Route('/task', name: 'edit_task', methods: 'PUT')]
    public function editTask(TaskRepository $repo, Request $request, #[CurrentUser] UserInterface $user): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!array_key_exists('id', $data)) return $this->json([
            'message' => 'Вы не передали обязательные параметры',
        ], 400);

        $task = $repo->find($data['id']);
        if ((!$task instanceof Task) or ($task->getUserId() != $user->getId())) return $this->json([
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
    public function deleteTask($id, TaskRepository $repo, #[CurrentUser] UserInterface $user): JsonResponse
    {
        $task = $repo->find($id);
        if ((!$task instanceof Task) or ($task->getUserId() != $user->getId())) return $this->json([
            'message' => 'Задача не найдена',
        ], 400);

        $repo->remove($task, true);

        return $this->json([
            'data' => $task,
            'message' => 'Задача успешно удалена!',
        ]);
    }
}
