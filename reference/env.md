# .env
project直下に　**.envファイル**作成し、[.env.example](/.env.example) の中身をペースト

# backend/.env
backendディレクトリに**.envファイル**作成し、[backend/.env.example](backend/.env.example) の中身をペーストし、以下の内容に書き換え
※ENV_AUTH_TOKEN,ENV_ACCEPTの記載は[こちら](/reference/MarkDownAPI.md)で説明


- APP_URL= http://localhost:8080
  - このprojectを実行するURLを入れます

\# マスターユーザー
- MASTER_USER_NAME=Master
- MASTER_USER_MAIL= YOUR_EMAIL
  - seeder実行で必須の情報となるため使われてないmailアドレスを入れるとよい
- MASTER_USER_PASSWORD= YOUR_PASSWORD
  -  seeder実行で必須の情報となるためpasswordを入力

- SANCTUM_STATEFUL_DOMAINS=localhost:3000
  - APIを呼び出すfrontendのドメイン 
- SESSION_DOMAIN=localhost
  - トップレベルドメイン 
