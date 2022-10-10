### Backend 環境構築

- .env.example ファイルの中身をもとに.env ファイルを作成する

```bash

# backendディレクトリを作る
$ mkdir backend

# Dockerコンテナをビルドする
$ docker compose build

# Dockerコンテナを立ち上げる
$ docker compose up -d

# APPコンテナに入る
$ docker compose exec app bash

# composerアップデート
$ composer update

# ファイルの権限設定を変更
$ chmod -R 777 storage

```

## コマンドリファレンス

```bash
# dockerコンテナ作成
$ docker compose build
# dockerコンテナ起動
$ docker compose up -d
# dockerコンテナ終了
$ docker compose down
# appコンテナへのログイン(Laravelコマンドを入力するコンテナ)
$ docker compose exec app bash
# dbコンテナへのログイン(DBを確認するコンテナ)
$ docker compose exec db bash
# dbコンテナ内でのroot使用
$ mysql -u root -p
# dbコンテナ内でのデータベースの使用(my_dbは自分がenvで設定したDBname)
$ use my_db
# コンテナから抜ける
$ exit
```
