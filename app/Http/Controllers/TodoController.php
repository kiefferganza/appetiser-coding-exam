<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTodoRequest;
use App\Models\Todo;
use App\Services\AuthService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    public function __construct(private Todo $todo, private Auth $auth)
    {
    }
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
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $todoList = $this->todo::where('user_id', $this->auth::id());
        if($request->has('sortType')){
            $sortType = json_decode($request['sortType'], true);
            $todoList->orderBy($sortType['column'], $sortType['value']);
        } else {
            $todoList->orderBy('created_at', 'desc');
        }

        return response()->json($todoList->paginate(10),
            200);
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
    $todo = $this->todo->create([
            'title' => $request['title'],
            'description' => $request['description'],
            'task_priority' => $request['task_priority'],
            'due_at' => $request['due_at'],
            'user_id' => $this->auth::id(),
        ]);

        return response()->json([
            'message' => 'Success',
            'data' => $todo
        ], 200);
    }

    /**
     * @OA\Get(
     *      path="/todo/{id}",
     *      operationId="getTodoById",
     *      tags={"Todo"},
     *      summary="Get todo information",
     *      description="Returns todo data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Todo id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="[]")
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
    public function show($id): \Illuminate\Http\JsonResponse
    {
        $todo = $this->todo::where('id',$id)->firstOrFail();

        if($todo['user_id'] !== $this->auth::id()) return response()->json(['Unauthenticated'], 401);

        return response()->json([
            'message' => 'Success',
            'data' => $todo
        ]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\JsonResponse
     */

    /**
     * @OA\Put(
     *      path="api/todos/{id}",
     *      operationId="updateTodo",
     *      tags={"Todo"},
     *      summary="Update existing todo",
     *      description="Returns updated todo data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Todo id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=202,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="[]")
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     */

    public function update(StoreTodoRequest $request, $id): \Illuminate\Http\JsonResponse
    {
        $todo = $this->todo::where('id',$id)->firstOrFail();
        if($todo['user_id'] !== $this->auth::id()) return response()->json(['message' => 'Unauthenticated'], 401);

        $todo->update([
            'title' => $request['title'],
            'description' => $request['description'],
            'task_priority' => $request['task_priority'],
            'due_at' => $request['due_at'],
        ]);
        return response()->json([
            'message' =>'Success',
            'data'=>$todo
        ],200);
    }
    public function completeTask($id) {
        $todo = $this->todo::where('id',$id)->firstOrFail();

        $todo->update([
            'date_completed' => Carbon::now()
        ]);

        return response()->json([
            'message' =>'Success',
            'data'=>$todo
        ],200);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\JsonResponse
     */

    /**
     * @OA\Delete(
     *      path="/projects/{todo}",
     *      operationId="deleteTodo",
     *      tags={"Todo"},
     *      summary="Delete existing todo",
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="Todo id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     */
    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $todo = $this->todo::where('id',$id)->firstOrFail();
        if($todo['user_id'] !== $this->auth::id()) return response()->json(['message' => 'Unauthenticated'], 401);

        $todo->delete();

        return response()->json([
            'message' => 'Success'
        ],200);
    }
}
