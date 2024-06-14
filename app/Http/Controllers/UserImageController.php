<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserImageResource;
use App\Models\User;
use App\Models\UserImage;
use App\Models\UserImageComment;
use App\Models\UserImageLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserImageController extends Controller
{
    public function addImage(Request $request)
    {
        $request->validate([
            'image' => 'required|string', // Validate as a string since it's base64 encoded
        ]);
        $user = auth()->user();

        $encodedString = $request->input('image');
        if (preg_match('/^data:image\/(\w+);base64,/', $encodedString, $matches)) {
            $encodedString = preg_replace('/^data:image\/(\w+);base64,/', '', $encodedString);
            $mimeType = $matches[1];
        } else {
            return response()->json(['error' => 'Invalid data URL'], 400);
        }

        $imageData = base64_decode($encodedString);
        $imageName = 'user_images/' . uniqid() . '.' . $mimeType;

        Storage::disk('public')->put($imageName, $imageData);

        $userImage = new UserImage();
        $userImage->user_id = $user->id;
        $userImage->image = $imageName;
        $userImage->save();

        return response()->json(['message' => 'Image uploaded successfully', 'data' => $userImage], 201);
    }


    public function getAllImages($id)
    {
        $user = User::findOrFail($id);
        $images = UserImageResource::collection($user->images);

        return response()->json(['data' => $images], 200);
    }

    public function getImage($id, $imageId)
    {
        $user = User::findOrFail($id);
        $image = UserImageResource::make($user->images()->findOrFail($imageId));

        return response()->json(['data' => $image], 200);
    }
    public function likeImage(Request $request, $imageId)
    {
        $user = auth()->user();


        $like = new UserImageLike();
        $like->user_id = $user->id;
        $like->image_id = $imageId;
        $like->save();

        return response()->json(['message' => 'Image liked successfully'], 201);
    }

    public function unlikeImage(Request $request, $imageId)
    {
       $user = auth()->user();

        $like = UserImageLike::where('user_id', $user->id)
            ->where('image_id', $imageId)
            ->firstOrFail();
        $like->delete();

        return response()->json(['message' => 'Image unliked successfully'], 200);
    }

    public function addComment(Request $request, $imageId)
    {
        $request->validate([
            
            'comment' => 'required|string|max:1000',
        ]);

        $user = auth()->user();
        $comment = new UserImageComment();
        $comment->user_id = $user->id;
        $comment->image_id = $imageId;
        $comment->comment = $request->comment;
        $comment->likes = 0;
        $comment->save();

        return response()->json(['message' => 'Comment added successfully', 'data' => $comment], 201);
    }

    public function getComments($imageId)
    {
        $comments = UserImageComment::where('image_id', $imageId)->get();

        return response()->json(['data' => $comments], 200);
    }
}
