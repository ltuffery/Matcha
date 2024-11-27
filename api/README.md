# Database

Init migration
```bash
docker exec -it api bash
php database/migrate.php
```

# Validator

| Rule     | Error Code |
|----------|------------|
| required | 1          |
| email    | 2          |
| number   | 3          |