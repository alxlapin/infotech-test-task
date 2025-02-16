init:
	@docker-compose up -d --build
	@mkdir -p components/Access/Rbac/Data && chmod 777 components/Access/Rbac/Data
	@docker exec -t srv-unit composer install
	@docker exec -t srv-unit ./yii migrate --interactive=0
	@docker exec -t srv-unit ./yii rbac/init