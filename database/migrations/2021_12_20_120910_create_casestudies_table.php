<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCasestudiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('casestudies', function (Blueprint $table) {
            $table->id();
            $table->string('Clientname');
            $table->string('caseStudyName');
            $table->string('Client_company_logo');
            $table->longText('description');
            $table->integer('BitrixMapId')->nullable();
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
        Schema::dropIfExists('casestudies');
    }
}
