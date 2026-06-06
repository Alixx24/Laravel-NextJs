<?php

namespace App\Http\Controllers\Admin\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\User\UserResource;
use App\Http\Resources\Admin\User\UserCollection;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new UserCollection(User::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'mobile' => 'required|string|digits:11|unique:users',
            'is_active' => 'required|boolean'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'mobile' => $validated['mobile'],
            'is_active' => $validated['is_active'],
            'mobile_verified_at' => $validated['is_active'] ? now() : null,
            'email_verified_at' => $validated['is_active'] ? now() : null,
            'user_type' => 0,
            'city_id' => null
        ]);

        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'mobile' => 'required|string|digits:11|unique:users,email,' . $user->id,
            'is_active' => 'required|boolean'
        ]);

        $inputs = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'mobile' => $validated['mobile'],
            'is_active' => $validated['is_active'],
            'mobile_verified_at' => $validated['is_active'] ? ($user->mobile_verified_at ?? now()) : null,
            'email_verified_at' => $validated['is_active'] ? ($user->email_verified_at ?? now()) : null,
        ];

        if (!empty($validated['password'])) {
            $inputs['password'] = Hash::make($validated['password']);
        }

        $user->update($inputs);
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return $this->success(null, 'کاربر حذف شد');
    }
}
