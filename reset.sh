#!/bin/bash
docker-compose down -v &&
docker-compose up --build &&
sleep 2  &&
docker-compose exec hyperf_product_service php bin/hyperf.php migrate &&
docker-compose exec hyperf_product_service php bin/hyperf.php db:seed