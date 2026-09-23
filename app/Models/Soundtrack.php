<?php

namespace App\Models;

class Soundtrack extends Content
{
    protected $table = 'soundtracks';

    public const KINDS = ['playlist', 'track'];

    public function embedUrl(): string
    {
        return str_replace('open.spotify.com/', 'open.spotify.com/embed/', $this->spotify_url);
    }
}
