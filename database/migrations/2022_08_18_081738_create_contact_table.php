<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact', function (Blueprint $table) {
            $table->id();
            $table->string('name')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->string('email')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->string('subject')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->string('phone')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->text('message')->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('contact');
    }
};
