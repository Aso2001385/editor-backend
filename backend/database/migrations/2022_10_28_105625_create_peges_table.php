<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePegesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('peges', function (Blueprint $table) {
            $table->foreignId('project_id')->constrained('projects');
            $table->integer('number');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('design_id')->constrained('designs');
            $table->string('title', 50)->nullable();
		    $table->text('contents')->nullable();
            $table->unique(['project_id', 'number']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('peges');
    }
}
