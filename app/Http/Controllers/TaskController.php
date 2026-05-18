<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Cache;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $page = $request->query('page', 1);

        $tasks = Cache::remember(
            "tasks_page_$page",
            60,
            function () {
                return Task::paginate(20);
            },
        );

        return TaskResource::collection($tasks);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task): TaskResource
    {
        $task = Cache::remember(
            "task_$task->id",
            60,
            fn () => Task::findOrFail($task->id),
        );

        return new TaskResource($task);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        $task = Task::create($request->validated());

        Cache::flush();

        return (new TaskResource($task))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task): TaskResource
    {
        $task->update($request->validated());

        Cache::forget("task_$task->id");
        Cache::flush();

        return new TaskResource($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task): HttpResponse
    {
        Cache::forget("task_$task->id");
        Cache::flush();

        $task->delete();

        return response()->noContent();
    }
}
