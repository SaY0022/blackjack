docker,PHPを使った ターミナルで動くブラックジャックゲームです。
zipでダウンロード後、解凍してください。

ターミナルでルートフォルダ(docker-compose.ymlのある階層)に移動していただき、
- docker-compose build
- docker-compose up -d
- docker-compose exec app php Main.php
でゲームが起動します。

ゲーム中はエンターを押しながら進行し、必要に応じて入力していきます。

ゲーム終了後はコマンド
- docker-compose down　
をお願いします。
