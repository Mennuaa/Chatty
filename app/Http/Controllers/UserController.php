<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Http\Resources\ConversationResource;
use App\Http\Resources\UserResource;
use App\Models\Message;
use App\Models\Story;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function anketa(Request $request){
        $user = auth()->user();
        $user->update($request->all());
        $user->save();
        return response()->json(UserResource::make($user), 200);

    }

    public function getUsers()
    {
        $user = auth()->user();

        // Get all users except the logged-in user with pagination
        $users = User::where('id', '!=', $user->id)->paginate(10);

        return response()->json($users);
    }

    public function search(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response($response, 400);
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
            $encodedString = $validated['image'];
            if (preg_match('/^data:([\w\/]+);base64,/', $encodedString, $matches)) {
                $encodedString = preg_replace('/^data:([\w\/]+);base64,/', '', $encodedString);
                $mimeType = $matches[1];
            } else {
                return response()->json(['error' => 'Invalid data URL'], 400);
            }

            $mediaData = base64_decode($encodedString);
            $mediaExtension = 'png';
            $mediaName = 'user_media/' . uniqid() . '.' . $mediaExtension;

            Storage::disk('public')->put($mediaName, $mediaData);

            $user->image = $mediaName;
        }

        $user->save();

        return response()->json([
            'message' => 'User updated successfully.',
            'user' => $user
        ]);
    }

    public function getConversations()
    {
        $user = auth()->user();
        $conversations = ConversationResource::collection($user->conversations()->paginate(10));
        return response()->json($conversations);
    }
    public function profile($id)
    {
        $user = UserResource::make(User::findOrFail($id));

        return response()->json($user);
    }
    public function createStory(Request $request)
    {

        $request->validate([

            'type' => 'required|string|in:image,video',
            'story' => 'required|string',
        ]);

        // Create a new story
        $story = new Story();
        $story->user_id = Auth::id();

        $story->type = $request->input('type');


        if ($request->filled('story')) {
            $encodedString = $request->input('story');
            if (preg_match('/^data:([\w\/]+);base64,/', $encodedString, $matches)) {
                $encodedString = preg_replace('/^data:([\w\/]+);base64,/', '', $encodedString);
                $mimeType = $matches[1];
            } else {
                return response()->json(['error' => 'Invalid data URL'], 400);
            }

            $mediaData = base64_decode($encodedString);
            $mediaExtension = $story->type === 'image' ? 'png' : 'mp4';
            $mediaName = 'user_media/' . uniqid() . '.' . $mediaExtension;

            Storage::disk('public')->put($mediaName, $mediaData);

            $story->story = $mediaName;
        }

        $story->save();

        // Return a response
        return response()->json([
            'success' => true,
            'message' => 'Story created successfully',
            'story' => $story,
        ], 201);
    }

    public function getUserStories(Request $request)
    {
        $userId = $request->input('user_id');
        $stories = Story::where('user_id', $userId)->get();

        return response()->json([
            'success' => true,
            'stories' => $stories,
        ], 200);
    }

    public function getStoryById($id)
    {
        $story = Story::find($id);

        if (!$story) {
            return response()->json([
                'success' => false,
                'message' => 'Story not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'story' => $story,
        ], 200);
    }
}
