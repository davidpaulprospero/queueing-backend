<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideosController extends Controller
{
    public function index()
    {
        $videos = Video::orderBy('published_at')->get();

        $data = [];

        foreach ($videos as $video) {
            $data[] = [
                'id' => $video->id,
                'title' => $video->title,
                'path' => url('/storage/' . $video->path),
                'created_at' => $video->created_at,
            ];
        }

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'video' => 'required|file|mimes:mp4,mov,avi',
            'title' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $path = $request->file('video')->store('public/videos');

        $video = new Video();
        $video->title = $request->input('title');
        $video->description = $request->input('description');
        $video->path = str_replace('public/', '', $path);
        $video->published_at = now(); // Set the current date and time as the publish date
        $video->save();

        return response()->json([
            'message' => 'Video uploaded successfully',
        ]);
    }

    public function show($id)
    {
        $video = Video::findOrFail($id);

        $data = [
            'id' => $video->id,
            'title' => $video->title,
            'description' => $video->description,
            'path' => url('/storage/' . $video->path),
            'created_at' => $video->created_at,
        ];

        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $video = Video::findOrFail($id);

        $request->validate([
            'video' => 'nullable|file|mimes:mp4,mov,avi',
            'title' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('video')) {
            $file = $request->file('video');
            $path = $file->store('public/videos');
            $video->path = str_replace('public/', '', $path);
        }

        if ($request->has('title')) {
            $video->title = $request->input('title');
        }

        if ($request->has('description')) {
            $video->description = $request->input('description');
        }

        $video->save();

        return response()->json($video);
    }

    public function destroy($id)
    {
        $video = Video::findOrFail($id);
        Storage::delete($video->path);
        $video->delete();

        return response()->json([
            'message' => 'Video deleted successfully',
        ]);
    }


    public function showForm()
    {
        $videos = Video::orderBy('published_at')->get();

        return view('backend.admin.display.video', compact('videos'));
    }

    public function showUpdateForm($id = null)
    {
        $videos = Video::orderBy('published_at')->get();
        $video = null;

        if ($id) {
            $video = Video::findOrFail($id);
        }

        return view('backend.admin.display.update', compact('videos', 'video'));
    }

}
