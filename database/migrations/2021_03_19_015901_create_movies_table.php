<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('synopsis');
            $table->year('year')->nullable();
            $table->string('poster')->nullable();
            $table->string('banner')->nullable();
            $table->string('trailer')->nullable();
            $table->string('duration')->nullable();
            $table->string('imdb_id', 30)->nullable();
            $table->text('stream_url')->nullable();
            $table->string('language')->nullable();
            $table->date('release_date')->nullable();
            $table->date('imdb_crawl_at')->nullable();
            $table->boolean('is_hot')->default(0);
            $table->boolean('is_comming_soon')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movies');
    }
}
