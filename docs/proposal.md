# TripShare
## プロジェクト概要
TripShareは，ユーザーが旅行記を投稿・共有し，訪問都道府県の達成度を可視化できるWebアプリです．
旅行好きのユーザーが自分の旅行体験を記録・公開したり，他ユーザーの旅行記を参考に旅行計画を立てたりできます．

### 目的
- ユーザーの旅行体験を記録・共有する
- 訪問都道府県を可視化してモチベーション向上
- 他ユーザーの投稿から旅行先の参考情報を得られる

### 対象ユーザー
- 旅行好きの一般ユーザー
- 旅行プランの参考にしたい人

### 搭載予定の機能
- ユーザー登録，ログイン，ログアウト
- 旅行記投稿（タイトル，本文，写真，都道府県，旅行日）
    - 新規投稿
    - 編集
    - 削除
- 他ユーザーの投稿閲覧
    - すべて表示
    - 都道府県ごとに表示
- マイページ
    - プロフィール表示
    - 今までの投稿表示
    - 訪問都道府県を色分けした日本地図表示
        - それぞれの都道府県を選択すると，その都道府県の投稿を表示

## プロジェクト構成
```
tripshare/
├─ README.md
├─ .gitignore
├─ config/
│   ├─ config.php           # サイト設定（DB接続情報など）
│   ├─ db_connect.php       # DB接続用
│   └─ functions.php        # 共通関数（サニタイズ、スコア計算、地図データ取得など）
├─ public/
│   ├─ index.php            # 投稿一覧（全件表示）
│   ├─ register.php         # ユーザー登録
│   ├─ login.php            # ログイン
│   ├─ logout.php           # ログアウト
│   ├─ mypage.php           # マイページ（プロフィール＋投稿一覧＋訪問地図）
│   ├─ post_form.php        # 投稿フォーム（新規投稿 / 編集兼用）
│   ├─ post_save.php        # 投稿保存処理（新規 / 編集）
│   ├─ post_delete.php      # 投稿削除処理
│   ├─ prefecture.php       # 都道府県別投稿表示
│   ├─ post_detail.php      # 投稿詳細表示
│   ├─ assets/
│   │   ├─ css/style.css
│   │   ├─ js/main.js       # 自作JS + 地図描画
│   │   ├─ js/japanmap.min.js
│   │   ├─ images/
│   │   └─ uploads/        # 投稿画像保存先
│   └─ templates/
│       ├─ header.php
│       ├─ footer.php
│       └─ nav.php
├─ sql/
│   └─ tripshare_schema.sql  # DBスキーマ
└─ docs/
    ├─ ERD.md
    ├─ features.md
    ├─ proposal.md
    └─ screenflow.md
```

### 技術構成
- バックエンド: PHP
- フロントエンド: HTML / CSS / JavaScript
- データベース: MySQL（ユーザーテーブル、投稿テーブルなど複数テーブル）

### DB構成
- users：ユーザー情報（ID，ユーザー名，表示名，パスワード）
- posts：旅行記情報（ID，ユーザーID，タイトル，本文，画像パス，都道府県，旅行日，投稿日）

## 使い方
1. ユーザー登録
2. ログイン
3. マイページで自分の投稿・旅スコアを確認
4. 投稿一覧ページで他ユーザーの旅行記を閲覧
5. 投稿詳細ページで写真や本文を確認