# Database

Init migration
```bash
docker exec -it api bash
php database/migrate.php
```

Fake data
```bash
docker exec -it api bash
php database/seeder.php
```