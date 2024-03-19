<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Info(
 * title="SpotLink API", 
 * version="1.0", 
 * description="API para acortar y redirigir URLs",
 * )
 * @OA\Server(
 *  url="http://localhost:8000",
 * description="Servidor Local"
 * )
 * @OA\Server(
 *  url="https://api.spotlink.gonzch.com",
 *  description="Servidor QA"
 * )
 * @OA\Tag(
 *     name="API Auth",
 *     description="API para autenticar y administrar la sesión de usuarios"
 * )
 * @OA\SecurityScheme(
 *     type="http",
 *     in="header",
 *     securityScheme="Authorization",
 *     name="Authorization",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Bearer token"
 * )
 */
class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Autenticar un usuario
     * @OA\Post (
     *     path="/api/login",
     *     tags={"API Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", example="email@domain.com"),
     *             @OA\Property(property="password", type="string", example="abc123")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *            @OA\Property(property="status", type="string", example="success"),
     *            @OA\Property(property="user", type="object",
     *            @OA\Property(property="id", type="integer"),
     *            @OA\Property(property="name", type="string"),
     *            @OA\Property(property="email", type="string"),
     *            @OA\Property(property="email_verified_at", type="string"),
     *            @OA\Property(property="created_at", type="string"),
     *            @OA\Property(property="updated_at", type="string")
     *         ),
     *         @OA\Property(property="authorisation", type="object",
     *            @OA\Property(property="token", type="string"),
     *            @OA\Property(property="type", type="string")
     *          )
     *        )
     *     ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *          )
     *      )
     * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();
        return response()->json([
            'status' => 'success',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    /**
     * Método para cerrar la sesión de un usuario
     * @OA\Post (
     *     path="/api/logout",
     *     tags={"API Auth"},
     *     security={{ "Authorization": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Successfully logged out")
     *        )
     *     ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *          )
     *      )
     * )
     */
    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    /**
     * Método para refrescar el token de un usuario
     * @OA\Post (
     *     path="/api/refresh",
     *     tags={"API Auth"},
     *     security={{ "Authorization": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *            @OA\Property(property="status", type="string", example="success"),
     *            @OA\Property(property="user", type="object",
     *            @OA\Property(property="id", type="integer"),
     *            @OA\Property(property="name", type="string"),
     *            @OA\Property(property="email", type="string"),
     *            @OA\Property(property="email_verified_at", type="string"),
     *            @OA\Property(property="created_at", type="string"),
     *            @OA\Property(property="updated_at", type="string")
     *         ),
     *         @OA\Property(property="authorisation", type="object",
     *              @OA\Property(property="token", type="string"),
     *              @OA\Property(property="type", type="string")
     *          )
     *        )
     *     ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error"),
     *          )
     *      )
     * )
     */
    public function refresh()
    {
        print(gettype(Auth::user()));
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

    /**
     * Obtener información del usuario autenticado
     * @OA\Post (
     *     path="/api/me",
     *     tags={"API Auth"},
     *     security={{ "Authorization": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *            @OA\Property(property="id", type="integer"),
     *            @OA\Property(property="name", type="string"),
     *            @OA\Property(property="email", type="string"),
     *            @OA\Property(property="email_verified_at", type="string"),
     *            @OA\Property(property="created_at", type="string"),
     *            @OA\Property(property="updated_at", type="string")
     *        )
     *     ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="error")
     *          )
     *      )
     * )
     */
    public function me()
    {
        return response()->json(auth()->user());
    }
}
