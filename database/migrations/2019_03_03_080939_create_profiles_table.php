<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('contact_no', 16);
            $table->string('id_no', 255);
            $table->string('id_path', 255)->nullable();
            $table->string('guardian_name', 255)->nullable();
            $table->string('guardian_contact_no', 16)->nullable();
            $table->string('guardian_email', 255)->nullable();
            $table->string('guardian_id_no', 255)->nullable();
            $table->string('school_of_attendance', 255)->nullable()->index();
            $table->string('career_interests')->nullable();
            $table->string('dietary_requirements')->nullable();
            $table->boolean('tc_accepted');

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
        Schema::dropIfExists('profiles');
    }
}
