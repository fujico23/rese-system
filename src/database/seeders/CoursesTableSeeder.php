<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;

class CoursesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Course::create([
            'shop_id' => '1',
            'course_name' => 'マグロづくしコース',
            'course_description' => '新鮮な大トロの寿司がメイン',
            'price' => 5000
        ]);
        Course::create([
            'shop_id' => '1',
            'course_name' => 'シンプルコース',
            'course_description' => 'シンプルながらも満足いただけるお勧めコース',
            'price' => 3500
        ]);
        Course::create([
            'shop_id' => '2',
            'course_name' => '厳選和牛付きコース',
            'course_description' => 'A5ランク黒毛和牛を選択いただけます',
            'price' => 5000
        ]);
        Course::create([
            'shop_id' => '2',
            'course_name' => 'シンプル食べ放題コース',
            'course_description' => 'カルビ・ハラミ・タン塩など人気のお肉が食べ放題です',
            'price' => 4000
        ]);
        Course::create([
            'shop_id' => '3',
            'course_name' => '女性も男性も大満足単品飲み放題 120分',
            'course_description' => '飲み放題のみのコースとなります',
            'price' => 2000
        ]);
        Course::create([
            'shop_id' => '3',
            'course_name' => '2次会にぴったり!5種盛り串等4品+飲み放題付',
            'course_description' => '幹事様も安心の分かりやすいコースです',
            'price' => 3000
        ]);
        Course::create([
            'shop_id' => '4',
            'course_name' => '呑めるおつまみコース(お料理6品)',
            'course_description' => 'お酒との相性抜群の料理をご用意したコースです',
            'price' => 5500
        ]);
        Course::create([
            'shop_id' => '4',
            'course_name' => 'シンプルコース(お料理4品)',
            'course_description' => 'トマトソースとモッツァレラチーズのピザがメインです',
            'price' => 1200
        ]);
        Course::create([
            'shop_id' => '5',
            'course_name' => '飲み放題付5000円',
            'course_description' => '濃厚スープの豚骨系ラーメン他3品',
            'price' => 5000
        ]);
        Course::create([
            'shop_id' => '5',
            'course_name' => '席のみのご予約',
            'course_description' => '店内でご注文下さい',
            'price' => 0
        ]);
        Course::create([
            'shop_id' => '6',
            'course_name' => 'シンプルコース(飲み放題付)5000円コース',
            'course_description' => '特製のタレで味付けしたハラミステーキがお勧めです',
            'price' => 5000
        ]);
        Course::create([
            'shop_id' => '6',
            'course_name' => '贅沢コース(飲み放題付)7000円コース',
            'course_description' => '当店自慢のA5ランク牛が選択できます',
            'price' => 7000
        ]);
        Course::create([
            'shop_id' => '7',
            'course_name' => '贅沢パーティーコース_デザート付',
            'course_description' => 'ちょっとしたパーティーにお勧めです',
            'price' => 7000
        ]);
        Course::create([
            'shop_id' => '7',
            'course_name' => 'カジュアルコース',
            'course_description' => 'リーズナブルなコース(お料理のみです)',
            'price' => 3000
        ]);
        Course::create([
            'shop_id' => '8',
            'course_name' => '大特価!せんべろセット',
            'course_description' => 'ドリンク2杯とおつまみ更に餃子付き',
            'price' => 1100
        ]);
        Course::create([
            'shop_id' => '8',
            'course_name' => '席のみのご予約',
            'course_description' => '店内でご注文下さい',
            'price' => 0
        ]);
        Course::create([
            'shop_id' => '9',
            'course_name' => '焼き鳥盛り合わせと当店お勧めおでんのコース',
            'course_description' => '香ばしく焼き上げた焼き鳥とじっくり煮込んだおでんです',
            'price' => 3500
        ]);
        Course::create([
            'shop_id' => '10',
            'course_name' => '4000円コース',
            'course_description' => '法事、宴会承ります',
            'price' => 4000
        ]);
        Course::create([
            'shop_id' => '11',
            'course_name' => '食べ放題!120分コース 3000円',
            'course_description' => 'リーズナブルな価格設定です',
            'price' => 3000
        ]);
        Course::create([
            'shop_id' => '12',
            'course_name' => '堪能コース',
            'course_description' => '色々食べたいですよね!そんなときにはこのコースですよ!',
            'price' => 5000
        ]);
        Course::create([
            'shop_id' => '13',
            'course_name' => '【2時間飲み放題付】3000円コース',
            'course_description' => 'お得にお手軽にご利用いただけるコースとなっています',
            'price' => 3000
        ]);
        Course::create([
            'shop_id' => '13',
            'course_name' => '【2時間飲み放題付】5500円コース',
            'course_description' => '季節のお料理をたっぷりご堪能いただけます',
            'price' => 5500
        ]);
        Course::create([
            'shop_id' => '14',
            'course_name' => '2時間飲み放題付コース',
            'course_description' => '飲み放題は8名様以上からご予約可能です',
            'price' => 6000
        ]);
        Course::create([
            'shop_id' => '14',
            'course_name' => '寿司がメインの寿司コース',
            'course_description' => 'その時の新鮮なネタを提供致します',
            'price' => 4000
        ]);
        Course::create([
            'shop_id' => '15',
            'course_name' => '1日100杯限定:幻の味噌ラーメン',
            'course_description' => '予約をしないと食べられないラーメンです',
            'price' => 1200
        ]);
        Course::create([
            'shop_id' => '15',
            'course_name' => '席のみのご予約',
            'course_description' => '店内でご注文下さい',
            'price' => 0
        ]);
        Course::create([
            'shop_id' => '16',
            'course_name' => '刺身3種盛り合わせと金賞受賞の手羽先唐揚げコース＋飲み放題付4000円',
            'course_description' => 'ご宴会や飲み会、誕生日のお祝いにぴったりなコースです',
            'price' => 4000
        ]);
        Course::create([
            'shop_id' => '16',
            'course_name' => '馬刺しや天ぷら盛り合わせ等9品コース',
            'course_description' => '厳選された馬刺しと揚げたて天ぷらがお楽しみ出来ます',
            'price' => 6000
        ]);
        Course::create([
            'shop_id' => '17',
            'course_name' => '全7品、お料理3000円と飲み放題2000円で5000円コース',
            'course_description' => 'お料理3000円コースに2H飲み放題2000円をお付けしたコースです',
            'price' => 2000
        ]);
        Course::create([
            'shop_id' => '17',
            'course_name' => 'お料理全て個別盛り!全7品3000円コース',
            'course_description' => 'お料理のみの3000円コースです',
            'price' => 3000
        ]);
        Course::create([
            'shop_id' => '18',
            'course_name' => 'ハラミステーキコース',
            'course_description' => '特製のタレで味付けしたハラミステーキがメインです',
            'price' => 4000
        ]);
        Course::create([
            'shop_id' => '18',
            'course_name' => 'お勧めホルモンコース3000円',
            'course_description' => '当店自慢のホルモンをお楽しみ頂けます',
            'price' => 3000
        ]);
        Course::create([
            'shop_id' => '19',
            'course_name' => '大会優勝ピザを楽しむコース',
            'course_description' => '第1回ピザ職人選手権で優勝した職人が焼き上げたピザが楽しめます',
            'price' => 5000
        ]);
        Course::create([
            'shop_id' => '20',
            'course_name' => '【新年会・歓送迎会プラン】満足料理3800円コース',
            'course_description' => '2000円で90分の飲み放題をおつけできます!',
            'price' => 3800
        ]);
        Course::create([
            'shop_id' => '20',
            'course_name' => '【全てコミコミ飲み放題プラン】旬素材・銘々料理6,600円フリー',
            'course_description' => '各種宴会にお勧めです',
            'price' => 6600
        ]);
    }
}
