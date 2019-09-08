<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('role_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });


        //INSERT A DEFAULT ADMIN THAT WILL BE ABLE TO LOGIN AT FIRST TIME

        \DB::table('users')->insert(
            [
                'role_id' => 1,
                'email' => 'admin@internation.com',
                'email_verified_at' => date('Y-m-d H:i:s', time()),
                'name' => 'John Doe',
                'password' => \Hash::make('admin123')
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
