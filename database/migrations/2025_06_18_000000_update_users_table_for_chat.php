<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTableForChat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop existing columns that we don't need
            $table->dropColumn(['email_verified_at', 'password', 'remember_token']);
            
            // Modify existing columns
            $table->string('email')->nullable()->change();
            
            // Add new columns
            $table->string('avatar')->nullable();
            $table->string('type');
            $table->string('open_id');
            $table->string('phone')->nullable();
            $table->text('description')->nullable();
            $table->string('token')->nullable();
            $table->string('access_token')->nullable();
            $table->timestamp('expire_date')->nullable();
            $table->boolean('online')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove the new columns
            $table->dropColumn([
                'avatar',
                'type',
                'open_id',
                'phone',
                'description',
                'token',
                'access_token',
                'expire_date',
                'online'
            ]);
            
            // Restore original columns
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('email')->unique()->change();
        });
    }
}
