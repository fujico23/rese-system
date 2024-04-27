# **Rese(リーズ) -飲食店予約サービスシステム**
- Rese(リーズ)は飲食店の予約サービスシステムです。直感的で、分かりやすい予約システムを実現しています。

<--- トップ画像の画像 --->
## **作成した目的**
### **現状の問題**
- 外部の飲食店予約サービスは手数料を取られるので自社で予約サービスを持ちたい
- 機能や画面が複雑で使いづらい
###** 解決方法**
- スマートフォン操作に慣れている20~30代の社会人が直感的に操作できるよう、分かりやすいアプリケーションを目指す

## **アプリケーションURL**
### ローカル環境
#### http://localhost

###  本番環境
#### http://IPアドレス
###### ※テストテスト

## **他のリポジトリ**
- git@github.com:fujico23/rese-system.git　よりgit clone

## **機能一覧**
### 会員登録
- 会員登録ページで新規ユーザー登録
- 会員登録と同時に入力したメールアドレス宛に確認メールが送信される認証機能
###### ※後述の「他に記載することがあれば記述する」もご確認下さい
### ログインページ
- ログイン後、飲食店一覧ページへ遷移
- ログイン後のログアウト
- RememberMe機能実装
### 飲食店一覧ページ
- 会員登録していないゲストユーザーも閲覧可能
- 飲食店一覧閲覧
- 飲食店お気に入り登録の追加と削除(メール未認証ユーザーはメール認証を促すメッセージが出ます)
- 飲食店検索機能(エリア・ジャンル・店舗で検索)
- ログインユーザーで予約済みの店舗がある場合、予約している店舗から表示される(口コミがしやすくするように)。更に予約済み店舗は「予約済み」と表示される
### 飲食店詳細ページ
##### 店舗情報閲覧
- ログインユーザーが予約している店舗の場合、reservationsテーブルのstatusが「予約済み」の場合、予約の翌日にレビューを記述出来るリンクが表示される。レビューを記述するとstatusが「口コミ済み」に変更されリンクが表示されない。
- 該当店舗のレビュー一覧を表示出来るリンクを設置。
##### 予約機能
- 店舗情報と予約フォーム掲載
- 予約機能(メール未認証ユーザーは予約ボタンを押すとログイン画面に遷移します)
- 予約情報が不十分の場合、エラーが発生する
- 予約後、doneページに遷移し、戻るボタンを押すと飲食店一覧ページへ遷移
### Mypageページ
- 予約店舗とお気に入り登録済みのページ一覧確認
- 予約した情報を削除・編集可能
- お気に入り登録の削除可能
- 予約の削除・編集をする際は、間違いないかを促すダイヤログが表示される

### 管理画面
###### 前提として、role_id:1は管理者、role_id:2は店舗代表者、role_id:3は利用者権限を与えています
###### ※後述の「他に記載することがあれば記述する」もご確認下さい
##### admin画面
- 管理者の場合、左上のハンバーガーメニューにadminリンクが表示され、管理者画面に遷移出来る
- アプリケーションに登録されているユーザーの一覧が表示される
##### user_details画面
- admin画面から、各ユーザーの詳細ページに遷移可能
- role_id1:管理者、role_id2:店舗代表者、role_id3:利用者に設定されおり、権限を変更することが出来る
- 店舗代表者に昇格した場合、代表店舗を選択出来るフォームが表示される
- チェーン店やグループ店舗を複数掛け持つことも考慮され、複数選択可能。選択した後はshop_usersテーブルにデータが保存される。
- 店舗代表者から利用者に降格した場合、代表店舗を選択出来るフォームが消え、同時に与えられていた代表店舗のデータがshop_usersテーブルから削除される
##### shop_create画面
- 管理者の場合、左上のハンバーガーメニューにshop createリンクが表示され、新店舗の登録画面に遷移出来る
- 新たに店舗を登録する場合、shop_name,area_name,genre_nameは必須項目として入力するよう設定
##### shop_management画面
- 店舗代表者の場合、左上のハンバーガーメニューにshop managementリンクが表示され、店舗の編集画面に遷移出来る
- 代表店舗一覧が表示され、店舗名、エリア、ジャンル、店舗説明を編集できる
- 新たに画像を追加したい場合、ファイルを選択し、storageに保存出来、削除した画像があれば、チェックボックで選択し、storageからも削除することが出来る
##### shop_reservation_confirm画面
- 店舗代表者の場合、左上のハンバーガーメニューにshop reservation confirmリンクが表示され、店舗の予約画面に遷移出来る
- 代表店舗一覧が表示され、予約情報が一覧表示されている
- もし連絡なしに来店が無かった予約情報については「キャンセル」というstatusに変更され、レビューの書き込みが出来ないように処理することが出来る

### リマインダーメール
- 








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

- composer require laravel/fortify
- php artisan vendor:publish --provider="Laravel\Fortify\FortifyServiceProvider"

- composer require laravel-lang/lang:~7.0 --dev
- cp -r ./vendor/laravel-lang/lang/src/ja ./resources/lang/

- composer require "ext-gd:*" --ignore-platform-reqs
- composer require simplesoftwareio/simple-qrcode --ignore-platform-req=ext-gd

- php artisan migrate
#### seederファイル実行コマンド
- php artisan db:seed --class=GenresTableSeeder
- php artisan db:seed --class=AreasTableSeeder
- php artisan db:seed --class=ShopsTableSeeder
- php artisan db:seed --class=ImagesTableSeeder
- php artisan db:seed --class=RolesTableSeeder
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
- DB_CONNECTION=mysql
- DB_HOST=mysql
- DB_PORT=3306
- DB_DATABASE=RDSのデータベース名に変更
- DB_USERNAME=RDSのユーザー名に変更
- DB_PASSWORD=RDSのパスワードに変更

- MAIL_MAILER=ses
- MAIL_HOST=email-smtp.ap-northeast-1.amazonaws.com
- MAIL_PORT=587
- MAIL_USERNAME=SESのSMTP認証情報から作成したIAMのSMTPユーザー名
- MAIL_PASSWORD=SESのSMTP認証情報から作成したIAMのSMTPパスワード
- MAIL_ENCRYPTION=tls
- MAIL_FROM_ADDRESS=SESで認証済みメールアドレス
- MAIL_FROM_NAME="${APP_NAME}"

- AWS_ACCESS_KEY_ID=SESのSMTP認証情報から作成したIAMで作成したアクセスキー
- AWS_SECRET_ACCESS_KEY=SESのSMTP認証情報から作成したIAMで作成したシークレットアクセスキー
- AWS_DEFAULT_REGION=ap-northeast-1

## **テーブル設計**
![](./table.drawio.svg)

## **ER図**
![](./er.drawio.svg)

## **他に記載することがあれば記述する**

### 社員一覧ページ(/list)へアクセス出来るユーザー
#### 開発環境(docker)
- メールアドレス：test1@example.com パスワード：11111111 (user_id:1 管理者太郎)  role_id1:管理者権限　※メールアドレス認証済みの為、確認メールは送信されません
- メールアドレス：test2@example.com パスワード：22222222 (user_id:2 店舗代表者次郎) role_id2:店舗代表者権限  ※メールアドレス認証済みの為、確認メールは送信されません
- メールアドレス：test3@example.com パスワード：33333333 (user_id:3 利用者三郎) role_id3:利用者権限
#### 本番感環境(AWS)
- メールアドレス：k03c347b@yahoo.co.jp  パスワード：1111111111 (user_id:26 一倉さやか)※メールアドレス認証済みの為、確認メールは送信されません

### 新規登録時にメールによって本人確認を行う機能について
#### 全体に於ける注意点
- MustVerifyEmailインターフェースを実装しているため、登録したメールアドレスに送られる確認メールで承認して初めてログインができます。
#### 開発環境(docker)での確認メール送信注意点
- 確認メール受信確認場所 Mailhog http://localhost:8025/
#### 本番環境(AWS)での確認メール送信注意点
- SESにて実装。「ドメイン取得はなし」案件の為、送信元は自身のメールアドレスにて実装。その為DKIM等の処理が出来ず、送信された確認メールが迷惑メールBOXに入っている可能性あり。gmailドメインで実装可能。キャリアメールでは送信出来ない可能性あり。

### スプレッドシート
- https://docs.google.com/spreadsheets/d/1vAhH-4A8FhBXRyyACtWOd465NaMvdYcw5LArtchS9us/edit#gid=1270192593

