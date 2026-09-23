<?php

namespace App\Models;

class ModPlaylist extends Content
{
    protected $table = 'mod_playlists';

    /**
     * Convert any open.spotify.com URL into its embed form.
     */
    public function embedUrl(): string
    {
        return str_replace('open.spotify.com/', 'open.spotify.com/embed/', $this->spotify_url);
    }
}
