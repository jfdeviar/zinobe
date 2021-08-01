# Setup
Setup configuration command:

php cli.php setup [--debug] [--database_host] [--database_name] [--database_user] [--database_password] [--admin_email] [--admin_phone]

**Default:**

php cli.php setup --debug false --database_host localhost --database_name zinobe --database_user root --admin_email jd@ingenio.com.co --admin_phone 3185241383

# Test
php cli.php test:sms [phone]

**Example:**

php cli.php test:sms 3185241383
