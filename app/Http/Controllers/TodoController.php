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

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $todoList = $this->todo::where('user_id', $this->auth::id())->with('tag','files');

        if($request->has('search')) {
            $searchKey = $request['search'];
            $todoList->whereLike('title', $searchKey);
        }

        if($request->has('sortType')){
            $sortType = json_decode($request['sortType'], true);
            $todoList->orderBy($sortType['column'], $sortType['value']);
        } else {
            $todoList->orderBy('created_at', 'desc');
        }

        return response()->json($todoList->paginate(10),
            200);
    }

    public function store(StoreTodoRequest $request): array|\Illuminate\Http\JsonResponse
    {
    $todo = $this->todo->create([
            'title' => $request['title'],
            'description' => $request['description'],
            'task_priority' => $request['task_priority'],
            'due_at' => $request['due_at'],
            'user_id' => $this->auth::id(),
        ]);

    $tagID = [];
    foreach ($request['tag'] as $tag) {
        $tagID[] = $tag['value'];
    }

        $todo->tag()->sync($tagID);

        return response()->json([
            'message' => 'Success',
            'data' => $todo::where('id', $todo->id)->with('tag')->first()

        ], 200);
    }


    public function show($id): \Illuminate\Http\JsonResponse
    {
        $todo = $this->todo::where('id',$id)->firstOrFail();

        if($todo['user_id'] !== $this->auth::id()) return response()->json(['Unauthenticated'], 401);

        return response()->json([
            'message' => 'Success',
            'data' => $todo
        ]);
    }

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

        $tagID = [];
        foreach ($request['tag'] as $tag) {
            $tagID[] = $tag['value'];
        }

        $todo->tag()->sync($tagID);

        return response()->json([
            'message' => 'Success',
            'data'=> $todo::where('id', $todo->id)->with('tag', 'files')->first()
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
    public function archiveTask($id) {
        $todo = $this->todo::where('id',$id)->firstOrFail();

        $archiveValue = null;

        if(!$todo->archived_at) {
            $archiveValue = Carbon::now();
        }

        $todo->update([
            'archived_at' => $archiveValue
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
