<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Activity;
use App\Models\Memory;
use App\Models\Moment;
use App\Models\ModNote;
use App\Models\ModPhoto;
use App\Models\ModPlaylist;
use App\Models\ModSurprise;
use App\Models\ModThingToDo;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MySpaceController extends Controller
{
    /**
     * Registry of content types Yunita can manage in her space.
     */
    public const TYPES = [
        'moments' => Moment::class,
        'notes' => Note::class,
        'activities' => Activity::class,
        'memories' => Memory::class,
        'mod-things' => ModThingToDo::class,
        'mod-notes' => ModNote::class,
        'mod-photos' => ModPhoto::class,
        'mod-playlists' => ModPlaylist::class,
        'mod-surprises' => ModSurprise::class,
        'achievements' => Achievement::class,
    ];

    public function index(): View
    {
        $user = request()->user();

        $counts = collect(self::TYPES)->mapWithKeys(fn (string $model, string $type) => [
            $type => $model::query()->ownedBy($user)->count(),
        ]);

        return view('my-space.index', [
            'counts' => $counts,
        ]);
    }

    public function type(string $type): View
    {
        [$model, $fields] = $this->resolve($type);

        $items = $model::query()
            ->ownedBy(request()->user())
            ->when(method_exists($model, 'scopePublished'), fn ($q) => $q->latest('created_at'))
            ->get();

        return view('my-space.type', [
            'type' => $type,
            'label' => $this->label($type),
            'fields' => $fields,
            'items' => $items,
        ]);
    }

    public function create(string $type): View
    {
        [$model, $fields] = $this->resolve($type);

        return view('my-space.form', [
            'type' => $type,
            'label' => $this->label($type),
            'fields' => $fields,
            'item' => null,
            'moods' => ['good', 'normal', 'sad', 'all'],
        ]);
    }

    public function store(Request $request, string $type)
    {
        [$model, $fields] = $this->resolve($type);

        $data = $this->validateFields($request, $fields);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('my-space', 'public');
        }

        if (is_a($model, ModSurprise::class, true)) {
            $data['type'] = $this->guessSurpriseType($data);
        }

        $request->user()->{$this->relationFor($type)}()->create($data);

        return redirect()
            ->route('my-space.type', $type)
            ->with('success', $this->label($type) . ' ditambahkan.');
    }

    public function edit(string $type, int $id): View
    {
        [$model, $fields] = $this->resolve($type);

        $item = $model::query()->ownedBy(request()->user())->findOrFail($id);

        return view('my-space.form', [
            'type' => $type,
            'label' => $this->label($type),
            'fields' => $fields,
            'item' => $item,
            'moods' => ['good', 'normal', 'sad', 'all'],
        ]);
    }

    public function update(Request $request, string $type, int $id)
    {
        [$model, $fields] = $this->resolve($type);

        $item = $model::query()->ownedBy(request()->user())->findOrFail($id);

        $data = $this->validateFields($request, $fields);

        if ($request->hasFile('image')) {
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
            $data['image'] = $request->file('image')->store('my-space', 'public');
        }

        if (is_a($model, ModSurprise::class, true)) {
            $data['type'] = $this->guessSurpriseType(array_merge($item->only(['type', 'spotify_url', 'link']), $data));
        }

        $item->update($data);

        return redirect()
            ->route('my-space.type', $type)
            ->with('success', $this->label($type) . ' diperbarui.');
    }

    public function destroy(string $type, int $id)
    {
        [$model] = $this->resolve($type);

        $item = $model::query()->ownedBy(request()->user())->findOrFail($id);

        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }

        $item->delete();

        return redirect()
            ->route('my-space.type', $type)
            ->with('success', $this->label($type) . ' dihapus.');
    }

    /**
     * Only allow known types, always resolve through the registry.
     */
    private function resolve(string $type): array
    {
        $model = self::TYPES[$type] ?? null;

        abort_unless($model !== null, 404);

        return [$model, $this->fieldsFor($type)];
    }

    private function label(string $type): string
    {
        return match ($type) {
            'mod-things' => 'Thing To Do',
            'mod-notes' => 'MOD Note',
            'mod-photos' => 'MOD Photo',
            'mod-playlists' => 'Playlist',
            'mod-surprises' => 'Surprise',
            default => ucfirst(rtrim($type, 's')),
        };
    }

    private function relationFor(string $type): string
    {
        return match ($type) {
            'mod-things' => 'modThingsToDo',
            'mod-notes' => 'modNotes',
            default => rtrim($type, 's') . 's',
        };
    }

    private function fieldsFor(string $type): array
    {
        // Shared: every type supports title/caption/content-ish fields + status + image.
        $base = [
            ['name' => 'title', 'label' => 'Title', 'type' => 'text', 'required' => true],
            ['name' => 'caption', 'label' => 'Caption', 'type' => 'text', 'required' => false],
        ];

        return match ($type) {
            'mod-things' => array_merge($base, [
                ['name' => 'description', 'label' => 'Description', 'type' => 'textarea', 'required' => false],
                ['name' => 'mood', 'label' => 'Mood', 'type' => 'select', 'options' => ['good', 'normal', 'sad', 'all']],
                ['name' => 'link', 'label' => 'Link (optional)', 'type' => 'url', 'required' => false],
            ]),
            'mod-notes', 'notes' => [
                ['name' => 'title', 'label' => 'Title', 'type' => 'text', 'required' => false],
                ['name' => 'caption', 'label' => 'Caption', 'type' => 'text', 'required' => false],
                ['name' => 'content', 'label' => 'Note', 'type' => 'textarea', 'required' => true],
                ['name' => 'mood', 'label' => 'Mood', 'type' => 'select', 'options' => ['good', 'normal', 'sad', 'all']],
            ],
            'mod-photos' => [
                ['name' => 'title', 'label' => 'Title', 'type' => 'text', 'required' => false],
                ['name' => 'caption', 'label' => 'Caption', 'type' => 'text', 'required' => false],
                ['name' => 'description', 'label' => 'Description', 'type' => 'textarea', 'required' => false],
                ['name' => 'mood', 'label' => 'Mood', 'type' => 'select', 'options' => ['good', 'normal', 'sad', 'all']],
                ['name' => 'image', 'label' => 'Photo', 'type' => 'file', 'required' => true],
            ],
            'mod-playlists' => [
                ['name' => 'title', 'label' => 'Title', 'type' => 'text', 'required' => true],
                ['name' => 'caption', 'label' => 'Caption', 'type' => 'text', 'required' => false],
                ['name' => 'description', 'label' => 'Description', 'type' => 'textarea', 'required' => false],
                ['name' => 'spotify_url', 'label' => 'Spotify URL', 'type' => 'url', 'required' => true],
                ['name' => 'mood', 'label' => 'Mood', 'type' => 'select', 'options' => ['good', 'normal', 'sad', 'all']],
            ],
            'mod-surprises' => [
                ['name' => 'title', 'label' => 'Title', 'type' => 'text', 'required' => false],
                ['name' => 'caption', 'label' => 'Caption', 'type' => 'text', 'required' => false],
                ['name' => 'content', 'label' => 'Content', 'type' => 'textarea', 'required' => false],
                ['name' => 'link', 'label' => 'Link / Spotify URL (optional)', 'type' => 'url', 'required' => false],
                ['name' => 'mood', 'label' => 'Mood', 'type' => 'select', 'options' => ['good', 'normal', 'sad', 'all']],
            ],
            default => array_merge($base, [
                ['name' => 'description', 'label' => 'Description', 'type' => 'textarea', 'required' => false],
                ['name' => 'date', 'label' => 'Date', 'type' => 'date', 'required' => false],
            ]),
        };
    }

    /**
     * Dynamic validation from the field definitions, with safety rules.
     */
    private function validateFields(Request $request, array $fields): array
    {
        $rules = [];

        foreach ($fields as $field) {
            $rule = ['nullable'];

            if ($field['required']) {
                $rule = $field['type'] === 'file' ? ['required', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'] : ['required'];
            }

            if ($field['type'] === 'file') {
                $rule = ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'];
            }

            if (in_array($field['name'], ['title', 'caption'], true) && ! in_array('required', $rule, true)) {
                $rule[] = 'string';
                $rule[] = 'max:255';
            }

            if ($field['name'] === 'mood') {
                $rule[] = 'in:good,normal,sad,all';
            }

            if (in_array($field['name'], ['link', 'spotify_url'], true)) {
                $rule[] = 'url';
                $rule[] = 'max:500';
            }

            $rules[$field['name']] = $rule;
        }

        $rules['status'] = ['nullable', 'in:draft,published'];
        $rules['date'] = ['nullable', 'date'];

        $data = $request->validate($rules);

        $data['status'] ??= 'published';

        // Only keep keys that exist on the table columns the model allows.
        return collect($data)->only(collect($fields)->pluck('name')->push('status', 'date')->all())->all();
    }

    private function guessSurpriseType(array $data): string
    {
        if (! empty($data['link']) && str_contains($data['link'], 'open.spotify.com')) {
            return 'music';
        }

        if (! empty($data['image'])) {
            return 'photo';
        }

        $content = mb_strtolower((string) ($data['content'] ?? ''));

        if (str_contains($content, 'ingat') || str_contains($content, 'remember')) {
            return 'memory';
        }

        return str_contains($content, "\n") ? 'note' : 'quote';
    }
}
