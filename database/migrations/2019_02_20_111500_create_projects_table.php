<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('status');
            $table->string('version');
            $table->string('secretKey')->default(substr(preg_replace('/[^\w]/','',base64_encode(sha1(rand(0, 100000)))),0,12));
            $table->string('accessUser')->default(substr(preg_replace('/[^\w]/','',base64_encode(sha1(rand(0, 100000)))),0,12));
            $table->string('accessPass')->default(substr(preg_replace('/[^\w]/','',base64_encode(sha1(rand(0, 100000)))),0,12));
            $table->text('changelogs')->nullable(); // -> Stored as JSON
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
        Schema::dropIfExists('projects');
    }
}
