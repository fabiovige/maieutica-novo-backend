<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    protected $userService;
        
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    
    public function index(Request $request)
    {
        try {
            $filters = $request->only([
                'name', 'email', 'role', 'phone', 'cep', 
                'address', 'number', 'complement', 'neighborhood', 
                'city', 'state'
            ]);

            $perPage = $request->input('per_page', 10);
            $users = $this->userService->getUsers($filters, $perPage);
            return response()->json($users);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to fetch users.'], 500);
        }
    }

    public function store(UserRequest $request)
    {
        try {
            $user = User::create($request->all());
            return response()->json($user, 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to create user.'], 500);
        }
    }

    public function show(string $id)
    {
        try {
            $user = User::findOrFail($id);
            return response()->json($user);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found.'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to fetch user.'], 500);
        }
    }

    public function update(UserRequest $request, string $id)
    {
        try {
            Log::info('Request Data:', $request->all());
            $user = User::findOrFail($id);
            $user->update($request->all());
            return response()->json($user);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found.'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to update user.'], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json(null, 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found.'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to delete user.'], 500);
        }
    }
}
