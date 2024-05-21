# **Rese(リーズ) -飲食店予約サービスシステム-**
### Rese(リーズ)は飲食店の予約サービスシステムです。直感的で、分かりやすい予約システムを実現しています。

<--- トップ画像の画像 --->
## **作成した目的**
### **現状の問題**
- 外部の飲食店予約サービスは手数料を取られる
- 機能や画面が複雑で使いづらい
### **解決方法**
- 自社で予約サービスを持つ
- スマートフォン操作に慣れている20~30代の社会人が直感的に操作できるシステム

## **アプリケーションURL**
### ローカル環境
#### http://localhost

###  本番環境
#### http://54.199.144.62/

## **他のリポジトリ**
- git@github.com:fujico23/rese-system.git　よりgit clone

## **機能一覧**
#### 注意！！
###### ※role_id:1は管理者、role_id:2は店舗代表者、role_id:3は利用者権限を与えている
###### ※role_idによってハンバーガーメニューのリンク数と種類が変わり、管理画面や店舗編集画面への遷移を管理している
###### ※メール機能は後述「メール機能の注意点」を確認

### 【会員登録ページ】
- 会員登録ページで新規ユーザー登録
- 会員登録後、入力したメールアドレス宛に認証メールが送信される
- 会員登録後、飲食店一覧ページに遷移する
- 会員登録後、デフォルトでrole_id:3が付与される
###### ※後述の「メール機能の注意」もご確認下さい
### 【ログインページ】
- ログイン後、飲食店一覧ページへ遷移
- RememberMe機能実装
### 【飲食店一覧ページ】 ゲスト含めた全ユーザー閲覧可能(権限によって一部サービスの利用制限あり)
- 飲食店一覧閲覧
- 飲食店お気に入り登録の追加と削除(ゲストはログイン・メール未認証ユーザーはverify-emailに遷移)
- ログインユーザーかつメール未認証ユーザーの場合、メール認証を促すモーダルウィンドウが表示される
- 飲食店検索機能(エリア・ジャンル・店舗で検索)
- 予約した店舗は予約翌日になると優先的に先頭に表示され、ペンマークのアイコンが表示され、レビューを促す
### 【飲食店詳細ページ】 ゲスト含めた全ユーザー閲覧可能(権限によって一部サービスの利用制限あり)
#### **レビュー機能**
- 該当店舗のレビュー一覧リンク配置
- 「ログインユーザーが閲覧中の店舗に予約中」、且つ「reservationsテーブルのstatusが『予約済み』」、且つ、「予約日の翌日」という条件で「レビュー書き込み」リンクを表示。レビューを記述するとstatusが「口コミ済み」に変更されリンクは非表示。店舗側でstatusを意図的に変更した場合(キャンセルされた予約に対してレビュー書き込みを阻止するため「キャンセル」にする等)もリンクは非表示。
- 予約翌日のAM11:00にリマインダーメール送信

#### **予約機能**
- 予約機能(ゲストはログインページ・メール未認証ユーザーはverify-emailに遷移)
- 予約完了後、サンクスページ表示
- 予約情報が不十分の場合、エラーメッセージ表示
- 予約後、遷移したdoneページからMypageに遷移し予約が確認出来る挙動にしている
- 予約データ送信後、カード情報を入力するフォームに遷移。支払いが完了するとMypageにてpayment項目が「paid」に変更されるが、支払い完了する前に別のページに遷移した場合「unpaid」になる
###### ※テストモードでの実装のためカード番号：4242424242424242にてご利用下さい
- 予約当日AM09:00にリマインダーメール送信。予約確認用にmypageに遷移するQRコード添付

#### **Stripe(TAKEOUT)機能**
- ログインユーザーの場合、TAKEOUTリンク表示(ただし、メール未認証ユーザーの場合verify-emailファイルに遷移)
- ページ遷移すると、各店舗のTAKEOUTメニューが表示。会計ボタンを押すとカード情報を入力するフォームが表示。
###### ※テストモードでの実装のためカード番号：4242424242424242にてご利用下さい

### 【Mypageページ】 ゲストは閲覧不可、メール認証未実施ユーザーはverify_emailページに遷移
- ユーザーの予約情報とお気に入り店舗表示
- 予約情報を削除・編集可能
- お気に入り店舗の削除機能

### 【管理画面】 role_id:1のみ閲覧可
#### **admin画面**
- アプリケーションに登録されているユーザーの一覧が表示される
- 全ユーザー宛に一斉メールを送信
#### **user_details画面**
- admin画面から、各ユーザーの詳細ページに遷移可能
- 権限を変更可能
- 店舗代表者に昇格した場合、代表店舗を選択出来るフォームが表示される
- チェーン店やグループ店舗を複数掛け持つことも考慮され、複数選択可能。選択した後はshop_usersテーブルにデータが保存される。
- 店舗代表者から利用者に降格した場合、代表店舗を選択出来るフォームが消え、同時に与えられていた代表店舗のデータがshop_usersテーブルから削除される
- 個別ユーザー宛にメール送信機能
- クーポン付メール送信機能。クーポンはQRコード読み取り
#### **shop_create画面**
- 新たに店舗を登録する場合、shop_name,area_name,genre_nameは必須項目として入力するよう設定
### 【店舗編集画面】 role_id:2のみ閲覧可
#### **shop_management画面**
- 代表店舗一覧が表示され、店舗名、エリア、ジャンル、店舗説明を編集できる
- 新たに画像を追加したい場合、ファイルを選択し、storage(本番環境ではS3)に保存出来、削除した画像があれば、チェックボックで選択し、storageからも削除することが出来る
###### ※後述の「以下のファイルをコメントアウトしている「本番環境の場合」に変更」をご確認下さい
#### **shop_reservation_confirm画面**
- 代表店舗一覧が表示され、予約情報が一覧表示されている
- もし連絡なしに来店が無かった予約情報については「キャンセル」というstatusに変更され、レビューの書き込みが出来ないように処理することが出来る

## **使用技術(実行環境)**
- Laravel 8.x
- PHP 7.4.9-fpm
- MySQL 8.0.26
- nginx 1.21.1

## **Laravel環境構築**
- docker-compose up -d --build
- docker-compose exec php bash
- composer install
- .env.exampleファイルから.envを作成し、環境変数を変更
- php artisan key:generate
- php artisan storage:link (開発環境でのシンボリックリンク作成コマンド)
- php artisan  schedule:work & (開発環境でのタスクスケジュール実行コマンド)

#### パッケージのインストール
- composer require laravel/fortify
- php artisan vendor:publish --provider="Laravel\Fortify\FortifyServiceProvider"
- composer require laravel-lang/lang:~7.0 --dev
- cp -r ./vendor/laravel-lang/lang/src/ja ./resources/lang/
- composer require "ext-gd:*" --ignore-platform-reqs
- composer require simplesoftwareio/simple-qrcode
- composer require laravel/cashier

#### データ生成
###### migrate実行コマンド
- php artisan migrate
###### seederファイル実行コマンド
- php artisan db:seed --class=GenresTableSeeder
- php artisan db:seed --class=AreasTableSeeder
- php artisan db:seed --class=ShopsTableSeeder
- php artisan db:seed --class=ImagesTableSeeder
- php artisan db:seed --class=RolesTableSeeder
- php artisan db:seed --class=MenusTableSeeder
- php artisan db:seed --class=CoursesTableSeeder
- php artisan db:seed --class=UsersTableSeeder

## **環境変数**
### 開発環境(ローカルマシーンにDockerで環境構築)
- DB_CONNECTION=mysql
- DB_HOST=mysql
- DB_PORT=3306
- DB_DATABASE=laravel_db
- DB_USERNAME=laravel_user
- DB_PASSWORD=laravel_pass

- MAIL_FROM_ADDRESS=test@example.co.jp

### 本番環境(AWS EC2,RDSインスタンスにて構築)
###### ■RDS
- DB_CONNECTION=mysql
- DB_HOST=mysql
- DB_PORT=3306
- DB_DATABASE=RDSのデータベース名
- DB_USERNAME=RDSのユーザー名
- DB_PASSWORD=RDSのパスワード

###### ■S3
- FILESYSTEM_DRIVER=s3
- AWS_ACCESS_KEY_ID=SESのSMTP認証情報から作成したIAMで作成したアクセスキー
- AWS_SECRET_ACCESS_KEY=SESのSMTP認証情報から作成したIAMで作成したシークレットアクセスキー
- AWS_DEFAULT_REGION=ap-northeast-1
- AWS_BUCKET=//S3のバケット名
- AWS_USE_PATH_STYLE_ENDPOINT=false
###### ※以下のファイルをコメントアウトしている「本番環境の場合」に変更
- index.blade.php
- details.blade.php
- mypage.blade.php
- ManagementController
- AdminMailController

###### ■SES
- MAIL_MAILER=ses
- MAIL_HOST=email-smtp.ap-northeast-1.amazonaws.com
- MAIL_PORT=587
- MAIL_USERNAME=SESのSMTP認証情報から作成したIAMのSMTPユーザー名
- MAIL_PASSWORD=SESのSMTP認証情報から作成したIAMのSMTPパスワード
- MAIL_ENCRYPTION=tls
- MAIL_FROM_ADDRESS=SESで認証済みメールアドレス
- MAIL_FROM_NAME="${APP_NAME}"

## **テーブル設計**
![](./table.drawio.svg)

## **ER図**
![](./er.drawio.svg)

## **他に記載することがあれば記述する**

### php artisan db:seed --class=UsersTableSeeder実行で挿入されるユーザー
- メールアドレス：test1@example.com パスワード：11111111 (user_id:1 管理者太郎)  role_id1:管理者権限　※メールアドレス認証済みの為、確認メールは送信されません
- メールアドレス：test2@example.com パスワード：22222222 (user_id:2 店舗代表者次郎) role_id2:店舗代表者権限  ※メールアドレス認証済みの為、確認メールは送信されません
- メールアドレス：test3@example.com パスワード：33333333 (user_id:3 利用者三郎) role_id3:利用者権限

### メール機能の注意点
#### 確認メール
- MustVerifyEmailインターフェースを実装しているため、登録したメールアドレスに送られる確認メールで承認しないと一部サービスの利用が制限されます。
#### 開発環境(docker)での確認メール送信注意点
- 確認メール受信確認場所 Mailhog http://localhost:8025/
#### 本番環境(AWS)での確認メール送信注意点
- SESにて実装。「ドメイン取得はなし」案件の為、送信元は自身のメールアドレスにて実装。その為DKIM等の処理が出来ず、送信された確認メールが迷惑メールBOXに入っている可能性あり。gmailドメインで実装可能。キャリアメール、outlookでは送信出来ない可能性あり。また、いくつかのメールにはQRコードが添付されているが、QRコードはpng形式の画像データの為、画像が表示される設定に変更してご確認下さい。

### スプレッドシート
- https://docs.google.com/spreadsheets/d/1vAhH-4A8FhBXRyyACtWOd465NaMvdYcw5LArtchS9us/edit#gid=265853827

