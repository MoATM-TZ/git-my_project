<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreResourceRequest;
use App\Models\Resource;
use App\Services\ReputationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ResourceController extends Controller
{
    /**
     * @var \App\Services\ReputationService
     */
    protected $reputation;

    public function __construct(ReputationService $reputation)
    {
        $this->reputation = $reputation;
    }

    /** Upload form — was the legacy upload.php. */
    public function create(): View
    {
        return view('resources.create');
    }

    /**
     * Persist an uploaded learning material.
     *
     * Replaces the hand-rolled file writes in upload_process.php: the file is
     * stored on the "public" disk (storage/app/public/uploads) via the Storage
     * facade, and the uploader earns reputation points.
     */
    public function store(StoreResourceRequest $request): RedirectResponse
    {
        /** @var \Illuminate\Http\UploadedFile $file */
        $file = $request->file('file');

        $path = $file->store('uploads', 'public');

        $resource = DB::transaction(function () use ($request, $file, $path) {
            $resource = $request->user()->resources()->create([
                'title'         => $request->validated('title'),
                'description'   => $request->validated('description'),
                'category'      => $request->validated('category'),
                'file_path'     => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime_type'     => $file->getClientMimeType(),
                'size_bytes'    => $file->getSize(),
                'status'        => 'published',
            ]);

            $this->reputation->forUpload($resource);

            return $resource;
        });

        return redirect()
            ->route('resources.show', $resource)
            ->with('status', 'Resource uploaded successfully. You earned +10 reputation!');
    }

    /** Resource detail page — was the legacy resource-detail.php. */
    public function show(Resource $resource): View
    {
        if ($resource->status !== 'published') {
            abort(404);
        }

        $resource->increment('views_count');

        $resource->load([
            'user:id,name,reputation_points',
            'comments.user:id,name',
            'votes:resource_id,user_id,value',
        ]);

        $user = auth()->user();
        $myVote = $user ? $resource->votes->firstWhere('user_id', $user->id) : null;

        $upvotes = $resource->votes->where('value', 1)->count();
        $downvotes = $resource->votes->where('value', -1)->count();

        return view('resources.show', [
            'resource'  => $resource,
            'comments'  => $resource->comments()->with('user:id,name')->latest()->get(),
            'myVote'    => $myVote,
            'upvotes'   => $upvotes,
            'downvotes' => $downvotes,
        ]);
    }

    /**
     * Stream the stored file for download.
     *
     * Replaces download_resource.php; uses Storage::disk('public') so the file
     * never needs to be copied into the web root.
     */
    public function download(Resource $resource): StreamedResponse
    {
        if ($resource->status !== 'published') {
            abort(404);
        }

        abort_unless(Storage::disk('public')->exists($resource->file_path), 404);

        $resource->increment('download_count');

        return Storage::disk('public')->download(
            $resource->file_path,
            $resource->original_name,
        );
    }

    /** Owner removes a resource and its stored file. */
    public function destroy(Request $request, Resource $resource): RedirectResponse
    {
        abort_unless($request->user()->id === $resource->user_id, 403);

        Storage::disk('public')->delete($resource->file_path);

        $resource->delete();

        return redirect()
            ->route('home')
            ->with('status', 'Resource deleted.');
    }
}
