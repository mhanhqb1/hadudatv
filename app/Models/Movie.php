<?php

namespace App\Models;

use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Movie extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function users() {
        return $this->belongsToMany(User::class, 'movie_user')->withTimestamps();
    }

    public function reviews() {
        return $this->hasMany(Review::class);
    }

    public function genres() {
        return $this->belongsToMany(Genre::class, 'genre_movie')
            ->withTimestamps();
    }

    public function celebs() {
        return $this->belongsToMany(Celeb::class, 'celeb_movie')
            ->withPivot('character_name')
            ->withTimestamps();
    }

    public function getDurationAttribute() {
        return CarbonInterval::minutes($this->attributes['duration'])->cascade()->forHumans(['short' => true]);
    }

    public function getRawDurationAttribute() {
        return $this->attributes['duration'];
    }

    public static function apiCall($url, $header = []) {
        $curl = curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        );
        if (!empty($header)) {
            $options[CURLOPT_HTTPHEADER] = $header;
        }
        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response, true);
    }
}
