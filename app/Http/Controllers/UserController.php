<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Http\Resources\ConversationResource;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function getUsers(){
        $user = auth()->user();
    
        // Get all users except the logged-in user with pagination
        $users = User::where('id', '!=', $user->id)->paginate(10); 
    
        return response()->json($users);
    }

    public function search(Request $request){
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        
        if($validator->fails()){
            $response = [
                'success' => false,
                'message' => $validator->errors()
        ];
            return response($response,400);
        }
        $user = User::where('name', 'like', '%' . $request->name . '%')->where('id', '!=', $user->id)->get();
        return ($user);
    }


    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'string|max:255',
            'email' => 'string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'string|unique:users,phone,' . $user->id,
            'age' => 'integer|nullable',
            'gender' => 'string|nullable',
            'image' => 'string|nullable', 
            'password' => 'string|min:8',
        ]);

        $user->name = $validated['name'] ?? $user->name;
        $user->email = $validated['email'] ?? $user->email;
        $user->phone = $validated['phone'] ?? $user->phone;
        $user->age = $validated['age'] ?? $user->age;
        $user->gender = $validated['gender'] ?? $user->gender;

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        if (!empty($validated['image'])) {
            $imageData = base64_decode($validated['image']);
            $imageName = 'user_images/' . uniqid() . '.png';

            Storage::disk('public')->put($imageName, $imageData);

            $user->image = $imageName;
        }

        $user->save();

        return response()->json([
            'message' => 'User updated successfully.',
            'user' => $user
        ]);
    }

    public function getConversations(){
        $user = auth()->user();
        $conversations = ConversationResource::collection($user->conversations()->paginate(10));
        return response()->json($conversations);
    }

    
    
}
