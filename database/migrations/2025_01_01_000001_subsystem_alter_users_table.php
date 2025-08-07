<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('id', 'ID');
            // add new fields
            $table->string('family')->nullable()->after('name');
            $table->unsignedSmallInteger('countryCode')->after('family');
            $table->string('mobile', 20)->unique()->after('family');
            $table->string('companyName')->nullable();
            $table->enum('gender', ['none', 'male', 'female',])->default('none');
            $table->bigInteger('balance')->nullable();
            $table->string('nationalCode', 20)->nullable();
            $table->string('phone', 20)->nullable();
            $table->bigInteger('birthDate')->nullable();
            $table->string('avatarSID')->nullable();
            $table->enum('status', [
                'waitingForSetProfile',
                'active',
                'banned',
                'deactive',
            ])->default('waitingForSetProfile')->index();
            $table->unsignedBigInteger('refereeUserID')->nullable();
            $table->string('referralCode')->nullable();
            $table->integer('referredUsersCount')->nullable();
            $table->integer('score')->default(0);
            $table->bigInteger('registerDate');
            $table->bigInteger('lastActivity');
            $table->bigInteger('deleted')->nullable()->index();
            $table->string('twoFAPassword')->nullable()->after('password');

            // change existing columns
            $table->string('name')->nullable()->change(); //change name to default empty
            $table->string('password')->nullable()->change(); //change password to default empty
            $table->string('email')->nullable()->change();
            $table->dropUnique('users_email_unique');
            $table->dropTimestamps();
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
            $table->dropColumn([
                'countryCode',
                'mobile',
                'family',
                'companyName',
                'gender',
                'nationalCode',
                'phone',
                'birthDate',
                'avatarSID',
                'balance',
                'status',
                'refereeUserID',
                'referredUsersCount',
                'referralCode',
                'registerDate',
                'lastActivity',
                'suspended',
            ]);

            $table->string('name')->change(); //change name to nullbale
            $table->string('password')->change(); //change password to nullbale

            $table->timestamps();
        });
    }
};
