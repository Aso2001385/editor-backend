### 環境構築

```bash
# windowsの方はgitbashで実行ください。

$ cd <クローンしたディレクトリ>
$ make init
```

## コマンドリファレンス

```bash
# dockerコンテナ起動
$ make up
# dockerコンテナ終了
$ make down
# phpコンテナへのログイン(コンテナ内部でartisanコマンド等打つほうが便利です)
$ make app
# migrate
$ make migrate
# php artisan migrate:refresh --seed （このコマンドは無茶苦茶打ちます）
$ make fresh-seed
# テスト実行（全件実行となります。個別実行したい場合は、コンテナ内部でパス指定して実行ください）
$ make test

# そのほかのコマンドはMakefileを参照ください。
```
