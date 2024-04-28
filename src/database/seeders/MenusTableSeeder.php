<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Menu::create([
            'shop_id' => '1',
            'menu_name' => 'マグロづくし',
            'menu_description' => '新鮮な大トロの寿司',
            'price' => '1500'
        ]);
        Menu::create([
            'shop_id' => '1',
            'menu_name' => 'サーモンロール',
            'menu_description' => 'サーモンとアボカドが入ったロール寿司',
            'price' => 1200
        ]);
        Menu::create([
            'shop_id' => '2',
            'menu_name' => 'カルビ',
            'menu_description' => '上質なカルビ肉',
            'price' => 2500
        ]);
        Menu::create([
            'shop_id' => '2',
            'menu_name' => 'ハラミ',
            'menu_description' => 'ジューシーなハラミ肉',
            'price' => 2000
        ]);
        Menu::create([
            'shop_id' => '3',
            'menu_name' => '唐揚げ',
            'menu_description' => 'ジューシーでサクサクの唐揚げ',
            'price' => 800
        ]);
        Menu::create([
            'shop_id' => '3',
            'menu_name' => '刺身盛り合わせ',
            'menu_description' => '新鮮な魚介類の盛り合わせ',
            'price' => 1500
        ]);
        Menu::create([
            'shop_id' => '4',
            'menu_name' => 'カルボナーラ',
            'menu_description' => 'ベーコンと卵のクリームパスタ',
            'price' => 1800
        ]);
        Menu::create([
            'shop_id' => '4',
            'menu_name' => 'マルゲリータピザ',
            'menu_description' => 'トマトソースとモッツァレラチーズのピザ',
            'price' => 1200
        ]);
        Menu::create([
            'shop_id' => '5',
            'menu_name' => '味噌ラーメン',
            'menu_description' => '濃厚な味噌スープのラーメン',
            'price' => 1000
        ]);
        Menu::create([
            'shop_id' => '5',
            'menu_name' => '塩ラーメン',
            'menu_description' => 'さっぱりとした塩味のラーメン',
            'price' => 900
        ]);
        Menu::create([
            'shop_id' => '6',
            'menu_name' => 'ハラミステーキ',
            'menu_description' => '特製のタレで味付けしたハラミステーキ',
            'price' => 2800
        ]);
        Menu::create([
            'shop_id' => '6',
            'menu_name' => 'カルビ丼',
            'menu_description' => '焼肉のカルビを丼ぶりで提供',
            'price' => 1500
        ]);
        Menu::create([
            'shop_id' => '7',
            'menu_name' => 'リゾット',
            'menu_description' => '季節の野菜を使ったリゾット',
            'price' => 1600
        ]);
        Menu::create([
            'shop_id' => '7',
            'menu_name' => 'オリーブオイルのパスタ',
            'menu_description' => 'フレッシュトマトとオリーブオイルのパスタ',
            'price' => 1400
        ]);
        Menu::create([
            'shop_id' => '8',
            'menu_name' => 'とんこつラーメン',
            'menu_description' => '濃厚なとんこつスープのラーメン',
            'price' => 1100
        ]);
        Menu::create([
            'shop_id' => '8',
            'menu_name' => '醤油ラーメン',
            'menu_description' => '日本の伝統的な醤油味のラーメン',
            'price' => 950
        ]);
        Menu::create([
            'shop_id' => '9',
            'menu_name' => '焼き鳥',
            'menu_description' => '香ばしく焼き上げた焼き鳥',
            'price' => 600
        ]);
        Menu::create([
            'shop_id' => '10',
            'menu_name' => 'エビ寿司',
            'menu_description' => 'プリプリのエビが乗った寿司',
            'price' => 1300
        ]);
        Menu::create([
            'shop_id' => '11',
            'menu_name' => 'ハラミステーキ',
            'menu_description' => '特製のタレで味付けしたハラミステーキ',
            'price' => 2800
        ]);
        Menu::create([
            'shop_id' => '12',
            'menu_name' => 'カルビ丼',
            'menu_description' => '焼肉のカルビを丼ぶりで提供',
            'price' => 1500
        ]);
        Menu::create([
            'shop_id' => '13',
            'menu_name' => 'もつ煮込み',
            'menu_description' => '熱々のもつ煮込み',
            'price' => 700
        ]);
        Menu::create([
            'shop_id' => '13',
            'menu_name' => 'アジフライ',
            'menu_description' => 'サクサクのアジフライ',
            'price' => 600
        ]);
        Menu::create([
            'shop_id' => '14',
            'menu_name' => '軍艦巻き',
            'menu_description' => '新鮮なネタが乗った軍艦巻き',
            'price' => 1200
        ]);
        Menu::create([
            'shop_id' => '14',
            'menu_name' => '穴子の寿司',
            'menu_description' => '焼き穴子を使った贅沢な寿司',
            'price' => 1600
        ]);
        Menu::create([
            'shop_id' => '15',
            'menu_name' => '味噌ラーメン',
            'menu_description' => '甘めの味噌スープのラーメン',
            'price' => 950
        ]);
        Menu::create([
            'shop_id' => '15',
            'menu_name' => 'チャーシューメン',
            'menu_description' => 'たっぷりのチャーシューが乗ったラーメン',
            'price' => 1100
        ]);
        Menu::create([
            'shop_id' => '16',
            'menu_name' => 'アジフライ',
            'menu_description' => 'サクサクのアジフライ',
            'price' => 600
        ]);
        Menu::create([
            'shop_id' => '16',
            'menu_name' => '枝豆',
            'menu_description' => 'ビールにぴったりの枝豆',
            'price' => 400
        ]);
        Menu::create([
            'shop_id' => '17',
            'menu_name' => 'ウニ丼',
            'menu_description' => '新鮮なウニがたっぷりの丼ぶり',
            'price' => 2000
        ]);
        Menu::create([
            'shop_id' => '17',
            'menu_name' => 'エビ寿司',
            'menu_description' => 'プリプリのエビが乗った寿司',
            'price' => 1300
        ]);
        Menu::create([
            'shop_id' => '18',
            'menu_name' => 'ハラミステーキ',
            'menu_description' => '特製のタレで味付けしたハラミステーキ',
            'price' => 2800
        ]);
        Menu::create([
            'shop_id' => '18',
            'menu_name' => 'カルビ丼',
            'menu_description' => '焼肉のカルビを丼ぶりで提供',
            'price' => 1500
        ]);
        Menu::create([
            'shop_id' => '19',
            'menu_name' => 'リゾット',
            'menu_description' => '季節の野菜を使ったリゾット',
            'price' => 1600
        ]);
        Menu::create([
            'shop_id' => '19',
            'menu_name' => 'オリーブオイルのパスタ',
            'menu_description' => 'フレッシュトマトとオリーブオイルのパスタ',
            'price' => 1400
        ]);
        Menu::create([
            'shop_id' => '20',
            'menu_name' => '軍艦巻き',
            'menu_description' => '新鮮なネタが乗った軍艦巻き',
            'price' => 1200
        ]);
        Menu::create([
            'shop_id' => '20',
            'menu_name' => '穴子の寿司',
            'menu_description' => '焼き穴子を使った贅沢な寿司',
            'price' => 1600
        ]);
    }
}
