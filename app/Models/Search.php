<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Search extends Model
{
    protected $table = "search_history";
    protected $fillable = [
        "name",
        "email",
        "questions",
        "difficulty",
        "type"
    ];
}
