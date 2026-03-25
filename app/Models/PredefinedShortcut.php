<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class PredefinedShortcut extends Model
{
    protected $fillable = ['title', 'icon', 'url', 'modal'];

    public function isAdded() {
        return UserShortcut::where('user_id', Auth::id())->where('shortcut_id', $this->id)->exists();
    }
}
