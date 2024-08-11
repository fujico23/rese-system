# **Rese(リーズ) -飲食店予約サービスシステム-**

### Rese(リーズ)は飲食店の予約サービスシステムです。直感的で、分かりやすい予約システムを実現しています。

![rese_top 2024-05-24 132938](https://github.com/fujico23/rese-system/assets/141387611/b52d9c45-c7ad-43f6-a851-3ba662e40819)

## 1. 作成した目的

### 外部の飲食店予約サービスは手数料が発生しているため自社で予約サービスを持つ。更に機能や画面の複雑さを解消するためスマートフォン操作に慣れている若年層が直感的に操作できるように開発を進めていく

## 2. アプリケーション URL とリポジトリ

### ⅰ. アプリケーション URL

#### ローカル環境：[http://localhost](http://localhost)

### ~~本番環境：http://54.199.144.62/~~

###### ※今回は AWS の環境を考慮しない

### ⅱ. GitHub

#### git@github.com:fujico23/rese-system.git 　より git clone

## 3. 機能一覧

###### ※role_id:1 は管理者、role_id:2 は店舗代表者、role_id:3 は利用者権限を付与

###### ※role_id によってハンバーガーメニューのリンク数と種類が変わり、管理画面や店舗編集画面への遷移を管理している

### 今回 Pro 入会テストで追加した機能要件は以下のリンクをご確認ください

- [「店舗一覧ソート機能」](#店舗一覧ソート機能)

- [「口コミ機能」](#口コミ機能)

- [「CSV インポート機能」](#csv-インポート機能)

### 【会員登録ページ】

---

#### `機能1`：会員登録

- メールアドレスとパスワードを入力して会員登録

#### `追加実装項目`：認証

#### `追加実装項目`：バリデーション（認証時）

- 会員登録後、入力したメールアドレス宛に認証メールが送信される※後述の[「メール機能の注意」](#ⅱ-メール機能の注意点)参照
- 会員登録後、飲食店一覧ページに遷移する

##### < その他の機能 >

- 会員登録後、デフォルトで role_id:3 が付与される

### 【ログインページ】

---

#### `機能2`：ログイン

- 会員登録済みのメールアドレスとパスワードを入力してログイン
- ログイン後、トップページへ遷移

### 【飲食店一覧ページ】 ゲスト含めた全ユーザー閲覧可能(権限によって一部サービスの利用制限あり)

---

### 店舗一覧ソート機能

- 店舗一覧ソート機能：ランダム（選択する度にショップの並び順が不規則に変わる）、評価が高い順、評価が低い順（評価はページアクセス時に毎回取得）
- 評価が一件もないショップは評価が高い順、評価が低い順のどちらの場合でも最後尾に表示される

#### `機能3`：ログアウト

- ロゴ「Rese」をクリックし、「Logout」ボタンでログアウト

#### `機能4`：飲食店一覧取得

#### `機能5`：飲食店お気に入り追加・削除

#### `機能6`：エリア・ジャンル・店名で検索する

##### < その他の機能 >

- 飲食店お気に入り登録の追加と削除(ゲストはログイン・メール未認証ユーザーは verify-email に遷移)
- ログインユーザーかつメール未認証ユーザーの場合、メール認証を促すモーダルウィンドウを表示
- 利用者権限を持つ一般ユーザーが予約した店舗は~~予約翌日になると優先的に先頭に表示され~~、ペンマークのアイコンが表示されレビューを促す

###### ※店舗一覧ソート機能を優先したため、予約翌日に先頭に表示させる機能は削除

### 【飲食店詳細ページ】 ゲスト含めた全ユーザー閲覧可能(権限によって一部サービスの利用制限あり)

---

### 口コミ機能

#### 新規口コミ追加

- 利用者権限を持つ一般ユーザーは店舗に対し口コミを追加することができる※「口コミを投稿する」リンクを通過出来る条件は後述の[「`追加実装項目`：評価機能」](#追加実装項目評価機能)をご確認ください
- 口コミは「テキスト・星(1 ～ 5)・画像」で構成され、テキスト：400 文字以内、星(1 ～ 5)：選択式、画像：jpeg.png のみアップロードとする
- 1 店舗に対し 1 件以上の口コミを追加することはできない

#### 口コミ編集

- 利用者権限を持つ一般ユーザー自身が追加した口コミを編集できる
- 編集画面で前回の口コミの入力値を保持することができる

##### < その他の機能 >

- テキスト・星(1 ～ 5)は「口コミを編集する」ボタン、画像の削除は「選択した画像を削除」ボタン、画像のアップロードは「画像をアップロードする」ボタンで編集。画像のアップロードはドラッグ＆ドロップ可能。

#### 口コミ削除

- 利用者権限を持つ一般ユーザー自身が追加した口コミを削除できる
- 管理者権限を持つ管理ユーザーは全ての口コミを削除できる
- 利用者権限を持つ一般ユーザーが削除出来る口コミは自身が投稿したもののみ

#### `機能7`：飲食店詳細取得

#### `機能8`：飲食店予約情報追加

#### `追加実装項目`：評価機能

- 「全ての口コミ情報」をクリックすると該当店舗に対する口コミ一覧が表示される
- 「口コミを投稿する」リンクを通過出来る条件

##### ・~~どの権限であってもログインユーザーの場合~~、利用者権限を持つ一般ユーザー(role_id:3)がログインしている

###### ※Pro 入会テストの「口コミ機能」を優先したため、利用者権限の一般ユーザーのみが「口コミを投稿する」リンクが表示されるように変更

##### ・該当の店舗に予約をしていて、reservations テーブルの status：『予約済み』、reservation_data：予約当日以降」

###### ※Pro 入会テストの「口コミ機能」を優先したため、該当の店舗に対し、既に同一の一般ユーザーが予約の履歴があり、口コミをしている(status:『口コミ済み』のレコードがある)場合、「1 店舗に対し 1 件以上の口コミを追加することができない」という条件に倣い、口コミの投稿は出来ないように変更

##### < その他の機能 >

- ~~レビュー~~口コミを記述すると status が「口コミ済み」に変更されリンクは非表示
- 店舗側で status を意図的に変更した場合(キャンセルされた予約に対して~~レビュー書き込み~~口コミ投稿を阻止するため「キャンセル」にする等)もリンクは非表示
- 予約翌日の AM11:00 にレビューリマインダーメール送信※後述の[「メール機能の注意」](#ⅱ-メール機能の注意点)参照

#### `追加実装項目`：バリデーション（予約時）

- 予約情報が不十分の場合、エラーメッセージ表示
- 予約後、遷移した done ページから Mypage に遷移し予約確認

#### `追加実装項目`：リマインダー

- 予約当日 AM09:00 にリマインダーメール送信

#### `追加実装項目`：QR コード

- リマインダーメールに店舗側で詳細を確認する QR コードを添付　※後述の[「メール機能の注意」](#ⅱ-メール機能の注意点)参照

#### `追加実装項目`：決済機能

- 予約データ送信後、カード情報を入力する stripe オブジェクトを表示
- 支払いが完了すると Mypage の payment 項目が「paid」に変更されるが、支払い完了する前に別のページに遷移した場合「unpaid」になる

###### ※テストモードでの実装のためカード番号：4242424242424242 にてご利用下さい

#### **TAKEOUT**

- ログインユーザーの場合、TAKEOUT リンク表示(ただし、メール未認証ユーザーの場合 verify-email ファイルに遷移)
- ページ遷移すると、各店舗の TAKEOUT メニューが表示。会計ボタンを押すとカード情報を入力するフォーム表示

###### ※テストモードでの実装のためカード番号：4242424242424242 にてご利用下さい

### 【Mypage ページ】 ゲストは閲覧不可、メール認証未実施ユーザーは verify_email ページに遷移

---

#### `機能9`：ユーザー飲食店お気に入り一覧取得

#### `機能10`：ユーザー飲食店予約情報取得

#### `機能11`：飲食店予約情報削除

#### `追加実装項目`：予約変更機能

### **【管理画面】 role_id:1 のみ閲覧可**

---

#### `追加実装項目`：メール送信

- アプリケーションに登録されているユーザーの一覧が表示される
- 全ユーザー宛に一斉メールを送信※後述の[「メール機能の注意」](#ⅱ-メール機能の注意点)参照

### **【user_details】 画面**

#### `追加実装項目`：管理画面

- admin 画面から、各ユーザーの詳細ページに遷移可能
- 権限を変更可能

##### < その他の機能 >

- 店舗代表者に昇格した場合、代表店舗を選択出来るフォームが表示される
- チェーン店やグループ店舗を複数掛け持つことを考慮し、複数選択可能。選択した後は shop_users テーブルにデータが保存される。
- 店舗代表者から利用者に降格した場合、代表店舗を選択出来るフォームが消え、同時に与えられていた代表店舗のデータが shop_users テーブルから削除される
- 個別ユーザー宛にメール送信機能※後述の[「メール機能の注意」](#ⅱ-メール機能の注意点)参照
- クーポン付メール送信機能。クーポンは QR コード読み取りで常に有効期限は「2 ヶ月後」に設定

#### **【shop_create】 role_id:1 のみ閲覧可**

### CSV インポート機能

- 管理ユーザーは CSV をインポートすることで店舗情報を追加出来る

#### CSV ファイルの記述方法

- ⅰ．[Google スプレッドシート](https://docs.google.com/spreadsheets/d/1vAhH-4A8FhBXRyyACtWOd465NaMvdYcw5LArtchS9us/edit#gid=265853827)を開く
- ⅱ．以下の画像の手順で CSV ファイルを生成する
  ![Frame 3手順](https://github.com/user-attachments/assets/dc0669d9-9094-4c58-80a8-961b14029ae2)
  - a．「CSV テスト」はバリデーションに引っかからず店舗データを取得できるファイル
    ![Frame 2正規テスト](https://github.com/user-attachments/assets/ca846012-9ed0-41c9-9603-f09c26d1c14d)
  - b．「CSV テストバリデーション確認用」はバリデーションを確認するためのファイル
    ![Frame 1バリデーション](https://github.com/user-attachments/assets/b1841d63-abc2-49f5-a806-e6595734e7fc)
- ⅲ．管理者ユーザーでログインし、ハンバーガーメニューより「Shop Create」リンクをクリック※参照：[管理者ユーザー(role_id:1)のパスワードとメールアドレス](#ⅰ-userstableseeder-の初期ユーザーデーター)
  ![Frame 1csvImport](https://github.com/user-attachments/assets/760bf0c6-315e-40f3-9c72-1fb8af077064)

##### < その他の機能 >

- 新たに店舗を登録する場合、shop_name,area_name,genre_name,description は必須項目設定
- 作成した店舗はデフォルトでは非公開設定

### 【店舗編集画面】 role_id:2 のみ閲覧可

---

#### **shop_management 画面**

#### `追加実装項目`：ストレージ

- 新たに画像を追加したい場合、ファイルを選択し、LocalStorage(本番環境では S3)に保存。削除したい画像は、チェックボックで選択し、storage から削除

##### < その他の機能 >

- 代表店舗一覧が表示され、店舗名、エリア、ジャンル、店舗説明を編集
- 「公開」ボックスにチェックを入れると飲食店一覧ページに表示される

###### ※後述の「以下のファイルをコメントアウトしている「本番環境の場合」をご確認下さい

#### **shop_reservation_confirm 画面**

#### `追加実装項目`：店舗代表者が予約情報確認画面

- 代表店舗一覧が表示され、予約情報が一覧表示されている

##### < その他の機能 >

- 「連絡なしに来店が無かった予約」や「不適切な口コミ」が発生した場合は、status を「キャンセル」変更することでレビューの書き込みの阻止や、既に書き込み済みのレビューを表示させないよう処理

## 4. 実行環境

### i. 使用技術

- Laravel 8.x
- PHP 7.4.9-fpm
- MySQL 8.0.26
- nginx 1.21.1

### ⅱ. パッケージ

- composer require laravel/fortify
- composer require laravel-lang/lang:~7.0 --dev
- composer require laravel/cashier
- composer require simplesoftwareio/simple-qrcode

## 5. 環境構築（Laravel)

### i. コマンドの実行

**※GitHub にて新しくリモートリポジトリを作成した前提です**

#### a. リポジトリの設定

```bash
git clone git@github.com:fujico23/rese-system.git
cd rese-system
git remote set-url origin 作成したリポジトリの url
git remote -v #任意。作成したリポジトリのurlが表示されていれば成功
```

#### ※エラーが発生する場合は以下のコマンドを実行してください

```bash
sudo chmod -R 777 *
```

#### Docker の設定

```bash
docker-compose up -d --build
```

#### Laravel のパッケージのインストール

```bash
docker-compose exec php bash
composer install
```

#### .env ファイルの生成　※.env ファイルは後述[「ⅲ,環境変数 a.開発環境」](#a-開発環境)に沿って環境変数を変更して下さい

```bash
cp .env.example .env
```

#### 各種設定

```bash
docker-compose exec php bash
```

#### アプリケーションキー生成

```bash
php artisan key:generate
```

#### 初期データ生成

```bash
php artisan migrate:reset
php artisan migrate
php artisan db:seed
```

#### シンボリックリンク生成

```bash
php artisan storage:link
```

#### ※.env ファイルを修正した後は以下のコマンドを実行してください

```bash
php artisan config:clear
```

### ⅲ. 環境変数

#### a. 開発環境

```bash
APP_NAME=rese
APP_ENV=local
APP_KEY=base64:XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX #php artisan key:generate後に自動的に挿入される
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass

FILESYSTEM_DRIVER=local

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=test@example.co.jp
MAIL_FROM_NAME="${APP_NAME}"
```

#### c. 本番環境(AWS EC2,RDS,S3,SES)

```bash
APP_NAME=coachtech-flea-market-app
APP_ENV=production
APP_DEBUG=false
APP_URL=http://54.199.90.159/

DB_CONNECTION=mysql
DB_HOST=#RDS のエンドポイント
DB_PORT=3306
DB_DATABASE=#RDS のデータベース名
DB_USERNAME=#RDS のユーザー名
DB_PASSWORD=#RDS のパスワード

FILESYSTEM_DRIVER=s3

MAIL_MAILER=ses
MAIL_HOST=email-smtp.ap-northeast-1.amazonaws.com
MAIL_PORT=587
MAIL_USERNAME=#SES の SMTP 認証情報から作成した IAM の SMTP ユーザー名
MAIL_PASSWORD=#SES の SMTP 認証情報から作成した IAM の SMTP パスワード
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=#SES で認証済みメールアドレス
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=#IAMユーザー作成時に取得したアクセスキー
AWS_SECRET_ACCESS_KEY=#IAMユーザー作成時に取得したシークレットアクセスキー
AWS_DEFAULT_REGION=ap-northeast-1
AWS_BUCKET=#S3 のバケット名
AWS_USE_PATH_STYLE_ENDPOINT=false
```

### ⅳ. 動作確認

[http://localhost](http://localhost)にアクセス出来る確認し、アプリケーションが表示されていれば成功。

###### ※以下のファイルをコメントアウトしている「本番環境の場合」に変更

- index.blade.php
- details.blade.php
- mypage.blade.php
- ManagementController
- AdminMailController
- ReservationReminder.php

## 6. テーブル設計

![](./table.drawio.svg)

## 7. ER 図

![](./er.drawio.svg)

## 8. その他

### ⅰ. `UsersTableSeeder` の初期ユーザーデーター

- メールアドレス：test1@example.com パスワード：11111111 (user_id:1 管理者太郎) role_id1:管理者権限　※メールアドレス認証済みの為、認証メールは送信されません
- メールアドレス：test2@example.com パスワード：22222222 (user_id:2 店舗代表者次郎) role_id2:店舗代表者権限 ※メールアドレス認証済みの為、認証メールは送信されません
- メールアドレス：test3@example.com パスワード：33333333 (user_id:3 利用者三郎) role_id3:利用者権限

### ⅱ. メール機能の注意点

#### a. 確認メール

- MustVerifyEmail インターフェースを実装しているため、登録したメールアドレスに送られる確認メールで承認しないと一部サービスの利用が制限されます。

#### b. 開発環境(Mailhog)でのメール機能注意点

- 確認メール受信確認場所 Mailhog [http://localhost:8025/](http://localhost:8025/)

#### c. 本番環境(AWS SES)でのメール機能注意点

- 「ドメイン取得はなし」案件の為、送信元は自身のメールアドレスにて実装。その為 DKIM 等の処理が出来ず、送信された確認メールが迷惑メール BOX に入っている可能性あり
- gmail ドメインで動作確認し、送信可能（キャリアメール、outlook では送信不可能）
- いくつかのメール機能には QR コードが添付されているが、QR コードは png 形式の画像データの為、ご利用の環境で画像が表示される設定に変更してご確認頂く必要あり(環境によってはセキュリティによって QR コードが文字化けしてしまう可能性あり)

### ⅲ. スプレッドシート

- https://docs.google.com/spreadsheets/d/1vAhH-4A8FhBXRyyACtWOd465NaMvdYcw5LArtchS9us/edit#gid=265853827
