<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->string('abbreviation')->nullable(false);
            $table->float('grams')->nullable(false);
            $table->softDeletes();
            $table->timestamp('created_at')->nullable(false)->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->nullable(false)->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('units');
    }
};