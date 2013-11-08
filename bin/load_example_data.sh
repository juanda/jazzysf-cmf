app/console doc:dat:drop --force
app/console doc:dat:create
app/console doc:php:ini:dba
app/console doc:php:repo:ini
app/console doc:php:work:purge
app/console doctrine:phpcr:workspace:import example.xml -p /