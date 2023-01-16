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
  MarkDownの記述をpostで送信することによって
  HTMLに変換された文をResponseしてくれるMarkDownAPIの連携


## 使用ツール
1. Laravel
2. Docker
3. StopLight


Version:  
- Larvel ver8.75
- PHP ver8.1
- Docker ver20.10.21
- StopLight ver3.1.0


[FrontEnd](https://github.com/Aso2001385/editor-frontend/edit/main/README.md)

## 環境構築
[環境構築手順](/reference/backend.md)


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
