# [MarkDownAPI](https://docs.github.com/ja/github-ae@latest/rest/markdown)


MarkdownドキュメントをHTMLページあるいは生のテキストとしてレンダリングできるAPI


| status code|  Response  |
|:-----------|:----------:|
| 200        |     OK     |
| 300        |Not modified|


< POST /markdown

# 必要header情報
|            |            |
|:-----------|:----------:|
|  Accept    |application/vnd.github+json|
|Content-type| text/plain |
|Token       |\<YOUR-TOKEN>|

# \<YOUR-TOKEN>作成方法
[Token生成](https://rfs.jp/server/git/github/personal_access_tokens.html)
  
これらの情報はそのままコード上に上げるのはよろしくないので(***特にToken***)  
backend/.envに記載


- ENV_AUTH_TOKEN = YOUR_TOKEN  
  -　Githubで作成したTOKEN 
