# Hyperf production_service

Demo service in microservice architecture

## 指令

### 初始次部署
初次部署環境請記得建立 `hyperf_project_network` 網路，以利服務間的溝通。

`docker network create hyperf_project_network`

### 啟動環境
`docker compose up --build` 或 `docker compose up -d`
當你執行 `docker compose up` 後，將自動打開伺服器。

### 關閉開發環境
`docker-compose down`

### 初始化資料庫
`docker-compose exec hyperf_product_service php bin/hyperf.php migrate`

### 執行資料表資料填充
`docker-compose exec hyperf_product_service php bin/hyperf.php db:seed`
