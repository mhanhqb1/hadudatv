<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CelebMovie extends Model
{
    use HasFactory;

    public $table = "celeb_movie";

    protected $fillable = ['celeb_id', 'movie_id', 'character_name'];
}
