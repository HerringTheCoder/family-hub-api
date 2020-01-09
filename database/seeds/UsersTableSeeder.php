<?php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\User;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class, 20)->create();
        $data =
            [
                [
                    'id' => 1,
                    'login' => 'admin',
                    'password' => Hash::make('secret'),
                    'email' => 'admin@example.com',
                    'email_verified_at' => \Carbon\Carbon::createFromDate(2000,01,01)->toDateTimeString(),
                ]
            ];
        foreach ($data as $row) {
            $model = User::firstOrNew(["id" => $row["id"]]);
            if($model->id!==1) {
                Bouncer::assign('user')->to($model);
            }
            else {
                Bouncer::assign('admin')->to($model);
            }
            $model->fill($row);
            $model->save();
        }
    }
}