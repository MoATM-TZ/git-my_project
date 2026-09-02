<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    /** Full search page with filters — was the legacy search.php. */
    public function index(Request $request)
    {
        $query = Resource::published()->with('user:id,name');

        $term = trim((string) $request->query('q', ''));
        $category = (string) $request->query('category', '');
        $sort = (string) $request->query('sort', 'recent');

        if ($term !== '') {
            $this->applyTerm($query, $term);
        }

        if ($category !== '' && array_key_exists($category, Resource::CATEGORIES)) {
            $query->where('category', $category);
        }

        if ($sort === 'popular') {
            $query->orderByDesc('download_count');
        } elseif ($sort === 'title') {
            $query->orderBy('title');
        } else {
            $query->latest();
        }

        $results = $query->paginate(12)->withQueryString();

        return view('search.index', [
            'results'  => $results,
            'term'     => $term,
            'category' => $category,
            'sort'     => $sort,
        ]);
    }

    /**
     * Lightweight JSON suggestions for the navbar live-search box — was
     * search_live.php. Returns at most 8 published matches.
     */
    public function live(Request $request)
    {
        $term = trim((string) $request->query('q', ''));

        if ($term === '') {
            return response()->json(['results' => []]);
        }

        $query = Resource::published()->with('user:id,name');
        $this->applyTerm($query, $term);

        $results = $query
            ->limit(8)
            ->get()
            ->map(function (Resource $resource) {
                return [
                    'title'    => $resource->title,
                    'category' => Resource::CATEGORIES[$resource->category] ?? $resource->category,
                    'author'   => optional($resource->user)->name,
                    'url'      => route('resources.show', $resource),
                ];
            });

        return response()->json(['results' => $results]);
    }

    /** Search across title, description and original file name. */
    private function applyTerm(Builder $query, string $term)
    {
        $like = '%'.str_replace('%', '\%', $term).'%';

        $query->where(function (Builder $inner) use ($like) {
            $inner->where('title', 'like', $like)
                ->orWhere('description', 'like', $like)
                ->orWhere('original_name', 'like', $like);
        });
    }
}
