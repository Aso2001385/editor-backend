# [FRIDAY EDITOR](https://www.fridayeditor.click/)

## FRIDAY EDITORとは
markdown方式で記載された文をhtmlに変換し、自作のデザインを反映させることが出来ます ***Webサイト作成サービス***

## [機能説明&利用手順](https://www.fridayeditor.click/explanation)

## BackEndでの仕様
基礎的なCrudを使用し、DBとの連携を行っている
### 例外的な処理
1. export(ダウンロード)機能  
  Storage操作を行い作成されたProjectのファイルを作成し、  
  その内容をzipファイルに移行し、そのzipファイルをダウンロード
2. MarkDownAPI連携  
  MarkDownの記述をpostで送信することによってHTMLをResponseしてくれるMarkDownAPIの連携


## 使用技術
1. Laravel
2. Docker
3. StopLight


Version:  
- Larvel ver8.75
- PHP ver8.1
- Docker ver20.10.21
- StopLight ver3.1.0

- Node.js v16 & Yarn v1.22 (バージョン管理のために[Volta](https://docs.volta.sh/guide/getting-started)でインストール推奨。Volta を入れれば特に作業不要)
- Make for Windows (windows の方のみ)
  1. http://gnuwin32.sourceforge.net/packages/make.htm にアクセス
  1. [Complete package, except sources]の[Setup]をクリックしてインストーラをダウンロード
  1. インストーラに従い、インストール　※インストール先のパスをコピーしておくとよい
  1. `コピーしていたパス + bin/` を環境変数に追加
- postman https://www.postman.com/downloads/
- DBeaver https://dbeaver.io/download/
  - local からの接続情報は以下です。
    - Host: localhost
    - Port: 33306
    - Database: fes-local
    - User: fes-local-user
    - Password: fes-local-pass

## 環境構築

- クローン後、VS Code で本プロジェクトを開き、おすすめで表示される拡張機能を全てインストールしてください。もしくは、Extensions > ・・・ > Show Recommended Extensions）
- [backend](/backend/README.md)
- [frontend](/frontend/README.md)

## あると便利な VSCode 拡張

- Laravel Extension Pack
- PHP IntelliSense

```setting.json:json
  "php.validate.executablePath": "<php実行ファイルへのパス>",
  "php.executablePath": "<php実行ファイルへのパス>",
  "php.suggest.basic": true,
```

- PHP DocBlocker

```setting.json:json
  "php-docblocker.gap": false,
  "php-docblocker.useShortNames": true,
  "php-docblocker.qualifyClassNames": true,
  "php-docblocker.returnVoid": false,
  "git.autofetch": true
```

- PHP Namespace Resolver

```setting.json:json
  "namespaceResolver.showMessageOnStatusBar": true,
  "namespaceResolver.sortAlphabetically": true,
  "namespaceResolver.sortNatural": true,
  "namespaceResolver.autoSort": true,
  "namespaceResolver.sortOnSave": true,
```

- Todo+
