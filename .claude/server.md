# Server Configuration

## Production

The application runs on Apache in production.

## Local Development

For local development, use PHP's built-in server with the custom router file:

```bash
php -S localhost:8000 router.php
```

The `router.php` file is a workaround that simulates Apache's routing behavior, so the application works the same way locally as it does in production without needing code changes.

## Database

The application uses MySQL with the following configuration:
- Host: localhost
- User: root
- Password: (empty)
- Database: myrick

Start MySQL with:
```bash
brew services start mysql
```
