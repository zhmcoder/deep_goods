<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeepGoodsSeeder extends Seeder
{
    /**
     * 运行数据库填充
     */
    public function run()
    {
        $this->call('DeepGoodsSeeder');
        $this->command->info('deep goods seeder start!');

        $path = './deep_goods.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('deep goods seeder end!');
    }
}
