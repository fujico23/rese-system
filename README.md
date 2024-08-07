# **Rese(リーズ) -飲食店予約サービスシステム-**

### Rese(リーズ)は飲食店の予約サービスシステムです。直感的で、分かりやすい予約システムを実現しています。

![rese_top 2024-05-24 132938](https://github.com/fujico23/rese-system/assets/141387611/b52d9c45-c7ad-43f6-a851-3ba662e40819)

## **作成した目的**

### **現状の問題**

- 外部の飲食店予約サービスは手数料を取られる
- 機能や画面が複雑で使いづらい

### **解決方法**

- 自社で予約サービスを持つ
- スマートフォン操作に慣れている 20~30 代の社会人が直感的に操作できるシステム

## **アプリケーション URL**

### ローカル環境：http://localhost

### 本番環境：http://54.199.144.62/

## **他のリポジトリ**

- git@github.com:fujico23/rese-system.git 　より git clone

## **機能一覧**

#### 注意！！

###### ※role_id:1 は管理者、role_id:2 は店舗代表者、role_id:3 は利用者権限を付与

###### ※role_id によってハンバーガーメニューのリンク数と種類が変わり、管理画面や店舗編集画面への遷移を管理している

### 【会員登録ページ】

- 会員登録ページで新規ユーザー登録
- 会員登録後、入力したメールアドレス宛に認証メールが送信される　(※後述の「メール機能の注意」参照)
- 会員登録後、飲食店一覧ページに遷移する
- 会員登録後、デフォルトで role_id:3 が付与される

### 【ログインページ】

- ログイン後、飲食店一覧ページへ遷移

### 【飲食店一覧ページ】 ゲスト含めた全ユーザー閲覧可能(権限によって一部サービスの利用制限あり)

- 飲食店一覧閲覧
- 飲食店お気に入り登録の追加と削除(ゲストはログイン・メール未認証ユーザーは verify-email に遷移)
- ログインユーザーかつメール未認証ユーザーの場合、メール認証を促すモーダルウィンドウを表示
- 飲食店検索機能(エリア・ジャンル・店舗で検索)
- 予約した店舗は予約翌日になると優先的に先頭に表示され、ペンマークのアイコンが表示され、レビューを促す

#### 追加機能

- 店舗一覧ソート機能：ランダム（選択する度にショップの並び順が不規則に変わる）、評価が高い順、評価が低い順（評価はページアクセス時に毎回取得）
- 評価が一件もないショップは評価が高い順、評価が低い順のどちらの場合でも最後尾に表示される

### 【飲食店詳細ページ】 ゲスト含めた全ユーザー閲覧可能(権限によって一部サービスの利用制限あり)

#### **■ レビュー機能**

- 該当店舗のレビュー一覧リンク設定
- 「口コミを投稿する」表示の条件は「ログインユーザーが閲覧中の店舗に予約中」、且つ「reservations テーブルの status が『予約済み』」、かつ、「reservations テーブルの reservation_data が翌日」
- レビューを記述すると status が「口コミ済み」に変更されリンクは非表示
- 店舗側で status を意図的に変更した場合(キャンセルされた予約に対してレビュー書き込みを阻止するため「キャンセル」にする等)もリンクは非表示
- 予約翌日の AM11:00 にレビューリマインダーメール送信(※後述の「メール機能の注意」参照)

#### 追加機能

- 口コミは「テキスト・★・画像」で構成し、テキスト：400 文字以内、画像：jpeg.png のみアップロード、星：選択式　とする
- 一店舗に対し一件以上の口コミを追加することはできない
- 口コミの編集はユーザー自身が追加した口コミを編集でき、編集画面は前回の口コミの入力値を保持される
- 口コミの削除はユーザー自身が追加した口コミを削除でき、かつ管理者は全ての口コミを削除できる

#### **■ 予約機能**

- 予約機能(ゲストはログインページ・メール未認証ユーザーは verify-email に遷移)
- 予約情報が不十分の場合、エラーメッセージ表示
- 予約後、遷移した done ページから Mypage に遷移し予約確認

#### **■ 予約リマインダー機能**

- 予約当日 AM09:00 にリマインダーメール送信
- リマインダーメールに店舗側で詳細を確認する QR コードを添付　(※後述の「メール機能の注意」参照)

#### **■Stripe 機能**

- 予約データ送信後、カード情報を入力するフォームに遷移
- 支払いが完了すると Mypage の payment 項目が「paid」に変更されるが、支払い完了する前に別のページに遷移した場合「unpaid」になる

###### ※テストモードでの実装のためカード番号：4242424242424242 にてご利用下さい

#### **TAKEOUT**

- ログインユーザーの場合、TAKEOUT リンク表示(ただし、メール未認証ユーザーの場合 verify-email ファイルに遷移)
- ページ遷移すると、各店舗の TAKEOUT メニューが表示。会計ボタンを押すとカード情報を入力するフォーム表示

###### ※テストモードでの実装のためカード番号：4242424242424242 にてご利用下さい

### 【Mypage ページ】 ゲストは閲覧不可、メール認証未実施ユーザーは verify_email ページに遷移

- ユーザーの予約情報とお気に入り店舗表示
- 予約情報を削除・編集可能
- お気に入り店舗の削除機能

### 【管理画面】 role_id:1 のみ閲覧可

#### **admin 画面**

- アプリケーションに登録されているユーザーの一覧が表示される
- 全ユーザー宛に一斉メールを送信(※後述の「メール機能の注意」参照)

#### **user_details 画面**

- admin 画面から、各ユーザーの詳細ページに遷移可能
- 権限を変更可能
- 店舗代表者に昇格した場合、代表店舗を選択出来るフォームが表示される
- チェーン店やグループ店舗を複数掛け持つことを考慮し、複数選択可能。選択した後は shop_users テーブルにデータが保存される。
- 店舗代表者から利用者に降格した場合、代表店舗を選択出来るフォームが消え、同時に与えられていた代表店舗のデータが shop_users テーブルから削除される
- 個別ユーザー宛にメール送信機能　(※後述の「メール機能の注意」参照)
- クーポン付メール送信機能。クーポンは QR コード読み取りで常に有効期限は「2 ヶ月後」に設定

#### **shop_create 画面**

- 新たに店舗を登録する場合、shop_name,area_name,genre_name は必須項目設定
- 作成した店舗はデフォルトでは非公開設定

#### 追加機能　 CSV インポート機能

- 管理ユーザーは CSV をインポートすることで店舗情報を追加出来る
- CSV ファイルの記述方法
- Excel ファイルを開く
- 1 列目にカラムを記述：area_id genre_id shop_name description is_active views image_url 　 2 列目 →1 2 Sample Shop This is a sample description 0 0 https://drive.google.com/file/d/1_cmfuDkQW1KAMkglBfoDic9oRCarb_nS/view　 3 列目 →2 3 Another Shop Another description. 0 0 https://drive.google.com/file/d/1QfFUT7AXm3KwR9lxPkWv_gdmtRb0TFXL/view?usp=drive_link
- 名前をつけて保存（CSV UTF-8(コンマ区切り)を選択)

### 【店舗編集画面】 role_id:2 のみ閲覧可

#### **shop_management 画面**

- 代表店舗一覧が表示され、店舗名、エリア、ジャンル、店舗説明を編集
- 新たに画像を追加したい場合、ファイルを選択し、LocalStorage(本番環境では S3)に保存。削除したい画像は、チェックボックで選択し、storage から削除
- 「公開」ボックスにチェックを入れると飲食店一覧ページに表示される

###### ※後述の「以下のファイルをコメントアウトしている「本番環境の場合」をご確認下さい

#### **shop_reservation_confirm 画面**

- 代表店舗一覧が表示され、予約情報が一覧表示されている
- 「連絡なしに来店が無かった予約」や「不適切な口コミ」が発生した場合は、status を「キャンセル」変更することでレビューの書き込みの阻止や、既に書き込み済みのレビューを表示させないよう処理

## **使用技術(実行環境)**

- Laravel 8.x
- PHP 7.4.9-fpm
- MySQL 8.0.26
- nginx 1.21.1

## **Laravel 環境構築**

- docker-compose up -d --build
- docker-compose exec php bash
- composer install
- .env.example ファイルから.env を作成し、環境変数を変更
- php artisan key:generate
- php artisan storage:link (開発環境でのシンボリックリンク作成コマンド)
- php artisan schedule:work & (開発環境でのタスクスケジューラー動作確認コマンド)

#### パッケージのインストール

- composer require laravel/fortify
- php artisan vendor:publish --provider="Laravel\Fortify\FortifyServiceProvider"
- composer require laravel-lang/lang:~7.0 --dev
- cp -r ./vendor/laravel-lang/lang/src/ja ./resources/lang/
- composer require "ext-gd:\*" --ignore-platform-reqs
- composer require simplesoftwareio/simple-qrcode
- composer require laravel/cashier

#### データ生成

###### migrate 実行コマンド

- php artisan migrate

###### seeder ファイル実行コマンド

- php artisan db:seed

## **環境変数**

### 開発環境(ローカルマシーンに Docker で環境構築)

- DB_CONNECTION=mysql
- DB_HOST=mysql
- DB_PORT=3306
- DB_DATABASE=laravel_db
- DB_USERNAME=laravel_user
- DB_PASSWORD=laravel_pass

- MAIL_FROM_ADDRESS=test@example.co.jp

### 本番環境(AWS EC2,RDS インスタンスにて構築)

###### ■RDS

- DB_CONNECTION=mysql
- DB_HOST=mysql
- DB_PORT=3306
- DB_DATABASE=RDS のデータベース名
- DB_USERNAME=RDS のユーザー名
- DB_PASSWORD=RDS のパスワード

###### ■S3

- FILESYSTEM_DRIVER=s3
- AWS_ACCESS_KEY_ID=SES の SMTP 認証情報から作成した IAM で作成したアクセスキー
- AWS_SECRET_ACCESS_KEY=SES の SMTP 認証情報から作成した IAM で作成したシークレットアクセスキー
- AWS_DEFAULT_REGION=ap-northeast-1
- AWS_BUCKET=//S3 のバケット名
- AWS_USE_PATH_STYLE_ENDPOINT=false

###### ※以下のファイルをコメントアウトしている「本番環境の場合」に変更

- index.blade.php
- details.blade.php
- mypage.blade.php
- ManagementController
- AdminMailController
- ReservationReminder.php

###### ■SES

- MAIL_MAILER=ses
- MAIL_HOST=email-smtp.ap-northeast-1.amazonaws.com
- MAIL_PORT=587
- MAIL_USERNAME=SES の SMTP 認証情報から作成した IAM の SMTP ユーザー名
- MAIL_PASSWORD=SES の SMTP 認証情報から作成した IAM の SMTP パスワード
- MAIL_ENCRYPTION=tls
- MAIL_FROM_ADDRESS=SES で認証済みメールアドレス
- MAIL_FROM_NAME="${APP_NAME}"

## **テーブル設計**

![](./table.drawio.svg)

## **ER 図**

![](./er.drawio.svg)

## **他に記載することがあれば記述する**

### php artisan db:seed --class=UsersTableSeeder 実行で挿入されるユーザー

- メールアドレス：test1@example.com パスワード：11111111 (user_id:1 管理者太郎) role_id1:管理者権限　※メールアドレス認証済みの為、認証メールは送信されません
- メールアドレス：test2@example.com パスワード：22222222 (user_id:2 店舗代表者次郎) role_id2:店舗代表者権限 ※メールアドレス認証済みの為、認証メールは送信されません
- メールアドレス：test3@example.com パスワード：33333333 (user_id:3 利用者三郎) role_id3:利用者権限

### メール機能の注意点

#### 確認メール

- MustVerifyEmail インターフェースを実装しているため、登録したメールアドレスに送られる確認メールで承認しないと一部サービスの利用が制限されます。

#### 開発環境(docker Mailhog)でのメール機能注意点

- 確認メール受信確認場所 Mailhog http://localhost:8025/

#### 本番環境(AWS SES)でのメール機能注意点

- 「ドメイン取得はなし」案件の為、送信元は自身のメールアドレスにて実装。その為 DKIM 等の処理が出来ず、送信された確認メールが迷惑メール BOX に入っている可能性あり
- gmail ドメインで動作確認し、送信可能（キャリアメール、outlook では送信不可能）
- いくつかのメール機能には QR コードが添付されているが、QR コードは png 形式の画像データの為、ご利用の環境で画像が表示される設定に変更してご確認頂く必要あり(環境によってはセキュリティによって QR コードが文字化けしてしまう可能性あり)

### スプレッドシート

- https://docs.google.com/spreadsheets/d/1vAhH-4A8FhBXRyyACtWOd465NaMvdYcw5LArtchS9us/edit#gid=265853827
