<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sector', 255)->nullable();
            $table->string('career', 255)->nullable();
            $table->longText('description')->nullable();
            $table->unsignedInteger('location_id')->nullable();
            $table->string('company', 255)->nullable();
            $table->string('company_code', 255)->nullable();
            $table->longText('address')->nullable();
            $table->string('website', 255)->nullable();
            $table->json('job_mentor')->nullable();
            $table->json('backup_job_mentor')->nullable();
            $table->json('hr_contact')->nullable();
            $table->json('availability')->nullable();
            $table->integer('total_days')->nullable();
            $table->string('indemnity_file', 255)->nullable();
            $table->string('arrival_time')->nullable();
            $table->string('collection_time')->nullable();
            $table->string('max_applicants')->nullable();
            $table->timestamps();

            $table->foreign('location_id')->references('id')->on('locations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
    }
}
