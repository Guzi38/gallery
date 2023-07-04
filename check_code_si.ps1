$RESULT_FILE = "check_code.result.cache"
Remove-Item -Path $RESULT_FILE -ErrorAction SilentlyContinue
$null | Out-File -FilePath $RESULT_FILE

Write-Host "Installing dependencies..."
{
    composer install --no-interaction
    composer require --dev friendsofphp/php-cs-fixer --no-interaction
    composer require --dev squizlabs/php_codesniffer --no-interaction
    composer require --dev escapestudios/symfony2-coding-standard --no-interaction
    ./vendor/bin/phpcs --config-set installed_paths "vendor/escapestudios/symfony2-coding-standard"
    ./vendor/bin/phpcs --config-set default_standard Symfony
} > $null
Remove-Item -Path ".php-cs-fixer.dist.php" -ErrorAction SilentlyContinue
Remove-Item -Path ".php-cs-fixer.cache" -ErrorAction SilentlyContinue

Write-Host "Running php-cs-fixer..."
./vendor/bin/php-cs-fixer fix src/ --dry-run -vvv --rules='@Symfony,@PSR1,@PSR2,@PSR12' >> $RESULT_FILE
Remove-Item -Path ".php-cs-fixer.dist.php" -ErrorAction SilentlyContinue
Remove-Item -Path ".php-cs-fixer.cache" -ErrorAction SilentlyContinue

Write-Host "Running phpcs..."
./vendor/bin/phpcs --standard=Symfony src/ --ignore=Kernel.php >> $RESULT_FILE

Write-Host "Running debug:translation..."
{
    Write-Host "English translations:"
    ./bin/console debug:translation en --only-missing

    Write-Host "Polish translations:"
    ./bin/console debug:translation pl --only-missing
} >> $RESULT_FILE

Write-Host "Running DB schema and data fixtures..."
{
    Write-Host "Dropping database schema..."
    ./bin/console doctrine:schema:drop --no-interaction --full-database --force

    Write-Host "Running migrations..."
    ./bin/console doctrine:migrations:migrate --no-interaction

    Write-Host "Loading fixtures..."
    ./bin/console doctrine:fixtures:load --no-interaction
} >> $RESULT_FILE

Write-Host "Tearing down..."
{
    Write-Host "Dropping database schema..."
    ./bin/console doctrine:schema:drop --no-interaction --full-database --force

    Write-Host "Removing var directory..."
    Remove-Item -Path "var" -Recurse -Force

    Write-Host "Removing vendor directory..."
    Remove-Item -Path "vendor" -Recurse -Force
} > $null

Write-Host "Script execution completed. Check the $RESULT_FILE for inspection results."