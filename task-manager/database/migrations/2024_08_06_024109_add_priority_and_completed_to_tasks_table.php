<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{


    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->unsignedBigInteger('priority_id')->nullable()->after('due_date');
            $table->boolean('completed')->default(false)->after('priority_id');

            // Assuming you have a priorities table, add a foreign key constraint
            $table->foreign('priority_id')->references('id')->on('priorities');
        });
    }

    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['priority_id']);
            $table->dropColumn(['priority_id', 'completed']);
        });
    }
};
