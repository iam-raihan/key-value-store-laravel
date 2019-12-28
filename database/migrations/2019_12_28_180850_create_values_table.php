<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('values', function (Blueprint $table) {
            $table->bigIncrements('id');

            /**
             * 'key' type is not specified in the task instruction
             * so assuming that it can be both string or integer
             * but I understand int comparisons are faster than varchar comparisons
             */
            $table->string('key');

            $table->string('value');
            $table->dateTime('expires_at');
            // $table->timestamps(); // any table should have timestamps but avoiding for now

            /**
             * indexes are great for read operations but degrades insert/update/delete performance as well
             * so in real projects, I'll also pay attention to the queries and ratio of read/write operations
             * before adding multiple index or multi-column index
             * I'll also inspect/ensure index usage by 'EXPLAIN' statement
             */
            $table->unique('key');
            // $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('values');
    }
}
