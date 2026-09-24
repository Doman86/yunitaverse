<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Models\Activity;
use App\Models\Favorite;
use App\Models\Journey;
use App\Models\Memory;
use App\Models\Moment;
use App\Models\ModNote;
use App\Models\ModPhoto;
use App\Models\ModPlaylist;
use App\Models\ModSurprise;
use App\Models\ModThingToDo;
use App\Models\Note;
use App\Models\Soundtrack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ContentController extends Controller
{
    /**
     * Type => [model, admin.* label key, editable fields].
     * Labels are translation keys so they follow the active locale.
     */
    public const REGISTRY = [
        'moments' => [Moment::class, 'moments', ['title', 'caption', 'description', 'image', 'date', 'category', 'status', 'is_featured']],
        'journeys' => [Journey::class, 'journey', ['title', 'caption', 'description', 'image', 'date', 'year', 'category', 'status', 'is_featured', 'is_active']],
        'achievements' => [Achievement::class, 'achievements', ['title', 'caption', 'description', 'image', 'date', 'status']],
        'activities' => [Activity::class, 'activities', ['title', 'caption', 'description', 'image', 'date', 'category', 'status']],
        'favorites' => [Favorite::class, 'favorites', ['title', 'caption', 'description', 'image', 'category', 'status']],
        'notes' => [Note::class, 'archive_notes', ['title', 'caption', 'content', 'mood', 'image', 'date', 'status']],
        'memories' => [Memory::class, 'memories', ['title', 'caption', 'content', 'image', 'date', 'status']],
        'mod-things' => [ModThingToDo::class, 'things_to_do', ['title', 'caption', 'description', 'mood', 'image', 'link', 'date', 'category', 'status', 'order']],
        'mod-notes' => [ModNote::class, 'mod_notes', ['title', 'caption', 'content', 'mood', 'image', 'date', 'status']],
        'mod-photos' => [ModPhoto::class, 'photos', ['title', 'caption', 'description', 'image', 'mood', 'date', 'category', 'status']],
        'mod-playlists' => [ModPlaylist::class, 'mod_playlists', ['title', 'caption', 'description', 'spotify_url', 'mood', 'category', 'status']],
        'mod-surprises' => [ModSurprise::class, 'surprises', ['title', 'caption', 'content', 'image', 'link', 'mood', 'type', 'status']],
        'soundtracks' => [Soundtrack::class, 'soundtrack', ['title', 'caption', 'description', 'spotify_url', 'kind', 'category', 'status']],
    ];

    public const MOODS = ['good', 'normal', 'sad', 'all'];

    public function index(Request $request, string $type): View
    {
        [$model, $label, $fields] = $this->resolve($type);

        $query = $model::query()->latest('created_at');

        if ($search = trim((string) $request->query('q'))) {
            $query->where(fn ($q) => $q
                ->where('title', 'like', "%{$search}%")
                ->orWhere('caption', 'like', "%{$search}%")
                ->orWhere('content', 'like', "%{$search}%"));
        }

        if ($status = $request->query('status')) {
            if (in_array($status, ['draft', 'published'], true)) {
                $query->where('status', $status);
            }
        }

        if ($mood = $request->query('mood')) {
            if (in_array($mood, self::MOODS, true) && in_array('mood', $fields, true)) {
                $query->where('mood', $mood);
            }
        }

        return view('manage.content.index', [
            'type' => $type,
            'label' => $label,
            'fields' => $fields,
            'items' => $query->get(),
            'q' => $search,
            'fStatus' => $status,
            'fMood' => $mood,
            'moods' => self::MOODS,
        ]);
    }

    public function create(string $type): View
    {
        [$model, $label, $fields] = $this->resolve($type);

        return view('manage.content.form', [
            'type' => $type,
            'label' => $label,
            'fields' => $fields,
            'item' => null,
            'moods' => self::MOODS,
            'favoriteCategories' => Favorite::CATEGORIES,
            'surpriseTypes' => ModSurprise::TYPES,
        ]);
    }

    public function store(Request $request, string $type)
    {
        [$model, $label, $fields] = $this->resolve($type);

        $data = $this->validateFor($request, $fields);
        $data['created_by'] = $request->user()->id;

        $model::create($data);

        return redirect()->route('manage.content.index', $type)->with('success', __('flash.created', ['type' => $label]));
    }

    public function edit(string $type, int $id): View
    {
        [$model, $label, $fields] = $this->resolve($type);

        return view('manage.content.form', [
            'type' => $type,
            'label' => $label,
            'fields' => $fields,
            'item' => $model::findOrFail($id),
            'moods' => self::MOODS,
            'favoriteCategories' => Favorite::CATEGORIES,
            'surpriseTypes' => ModSurprise::TYPES,
        ]);
    }

    public function update(Request $request, string $type, int $id)
    {
        [$model, $label, $fields] = $this->resolve($type);

        $item = $model::findOrFail($id);
        $data = $this->validateFor($request, $fields);

        $item->update($data);

        return redirect()->route('manage.content.index', $type)->with('success', __('flash.updated', ['type' => $label]));
    }

    public function destroy(string $type, int $id)
    {
        [$model, $label] = $this->resolve($type);

        $item = $model::findOrFail($id);

        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }

        $item->delete();

        return redirect()->route('manage.content.index', $type)->with('success', __('flash.deleted', ['type' => $label]));
    }

    public function toggle(string $type, int $id)
    {
        [$model, $label] = $this->resolve($type);

        $item = $model::findOrFail($id);
        $item->update(['status' => $item->status === 'published' ? 'draft' : 'published']);

        return back()->with('success', __('flash.status_toggled', [
            'type' => $label,
            'status' => __('flash.status_' . $item->status),
        ]));
    }

    private function resolve(string $type): array
    {
        $entry = self::REGISTRY[$type] ?? null;

        abort_unless($entry !== null, 404);

        // Resolve the label through the translator so it follows the active locale.
        [$model, $labelKey, $fields] = $entry;

        return [$model, __("admin.{$labelKey}"), $fields];
    }

    private function validateFor(Request $request, array $fields): array
    {
        $rules = [];

        foreach ($fields as $field) {
            if (in_array($field, ['created_by', 'status'], true)) {
                continue;
            }

            $rules[$field] = match ($field) {
                'title' => ['required', 'string', 'max:255'],
                'caption' => ['nullable', 'string', 'max:255'],
                'description', 'content' => ['nullable', 'string'],
                'image' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
                'link', 'spotify_url' => ['nullable', 'url', 'max:500'],
                'date' => ['nullable', 'date'],
                'year' => ['nullable', 'integer', 'min:1990', 'max:2100'],
                'category' => ['nullable', 'string', 'max:100'],
                'mood' => ['nullable', 'in:' . implode(',', self::MOODS)],
                'type' => ['nullable', 'in:' . implode(',', ModSurprise::TYPES)],
                'kind' => ['nullable', 'in:' . implode(',', Soundtrack::KINDS)],
                'order' => ['nullable', 'integer', 'min:0'],
                'is_featured', 'is_active' => ['nullable', 'boolean'],
                default => ['nullable', 'string', 'max:255'],
            };
        }

        $rules['status'] = ['required', 'in:draft,published'];

        $data = $request->validate($rules);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('content', 'public');
        }

        foreach (['is_featured', 'is_active'] as $flag) {
            if (array_key_exists($flag, $rules) && ! isset($data[$flag])) {
                $data[$flag] = false;
            }
        }

        // Surprise type guess for convenience when admin omits it.
        if (in_array('type', $fields, true) && empty($data['type'])) {
            $data['type'] = $this->guessSurpriseType($data);
        }

        return $data;
    }

    private function guessSurpriseType(array $data): string
    {
        if (! empty($data['link']) && str_contains($data['link'], 'open.spotify.com')) {
            return 'music';
        }

        if (! empty($data['image'])) {
            return 'photo';
        }

        return 'quote';
    }
}
