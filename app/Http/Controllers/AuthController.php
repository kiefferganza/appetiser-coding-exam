<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\AuthService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    function __construct(private AuthService $authService) {

    }
    /**
     * @OA\Post(
     * path="/api/register",
     * operationId="Register",
     * tags={"Auth"},
     * summary="User Register",
     * description="User Register here",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"name","email", "password", "confirm_password"},
     *               @OA\Property(property="name", type="text"),
     *               @OA\Property(property="email", type="text"),
     *               @OA\Property(property="password", type="password"),
     *               @OA\Property(property="confirm_password", type="password")
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Register Successfully",
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Register Successfully",
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function register(Request $request): \Illuminate\Http\JsonResponse
    {

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);

        if($validator->fails()){
            return $this->authService->sendResponse('Validation Error.', (array)$validator->errors());
        }

        $input = $validator->validated();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['access_token'] =  $user->createToken('secret')->plainTextToken;
        $success['name'] =  $user->name;

        return $this->authService->sendResponse($success, (array)'User register successfully.');
    }

    /**
     * @OA\Post(
     * path="/api/login",
     * operationId="Login",
     * tags={"Auth"},
     * summary="User Login",
     * description="User Login here",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"email", "password"},
     *               @OA\Property(property="email", type="text"),
     *               @OA\Property(property="password", type="password"),
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="User login successfully",
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="User login successfully",
     *       ),
     *      @OA\Response(response=400, description="Login Failed"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            $success['token'] =  $user->createToken('secret')->plainTextToken;
            $success['name'] =  $user->name;

            return $this->authService->sendResponse($success, 'User login successfully.');
        }
        else{
            return $this->authService->sendResponse('Login Failed.', ['error'=>'Invalid Credentials']);
        }
    }
}
