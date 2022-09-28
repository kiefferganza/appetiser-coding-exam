<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTodoRequest;
use App\Models\Todo;
use App\Services\AuthService;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function __construct(private Todo $todo,private AuthService $response)
    {}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * @OA\Get (
     * path="/api/todos",
     * operationId="getTodoList",
     * tags={"Todo"},
     * summary="Get Todo List",
     * description="Get User Todos",
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $user = \Auth::id();

        return response()->json($this->todo->all()->where('user_id', $user), 200);

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Http\JsonResponse|string[]
     */

    /**
     * @OA\Post(
     * path="/api/todos/",
     * operationId="Todos",
     * tags={"Todo"},
     * summary="storeTodo",
     * description="Create User Todo",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"title","description", "task_priority", "due_at"},
     *               @OA\Property(property="title", type="text"),
     *               @OA\Property(property="description", type="text"),
     *               @OA\Property(property="task_priority", type="number"),
     *               @OA\Property(property="due_at", type="date")
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Success",
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */

    public function store(StoreTodoRequest $request): array|\Illuminate\Http\JsonResponse
    {
        $this->todo->create([
            'title' => $request['title'],
            'description' => $request['description'],
            'task_priority' => $request['task_priority'],
            'due_at' => $request['due_at'],
            'user_id' => \Auth::id(),
        ]);

        return response()->json([
            'message' => 'Success',
        ], 200);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Todo $todo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Todo $todo)
    {
        //
    }
}
