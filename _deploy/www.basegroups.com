
<virtualhost *:80>
	ServerName pluspanda.com
	DocumentRoot /var/www/pluspanda/production/current/public/
	<directory "/var/www/pluspanda/production/current/public/">
		Options Indexes FollowSymLinks MultiViews
		AllowOverride All
		Order allow,deny
		allow from all
	</directory>
</virtualhost>

<virtualhost *:80>
	ServerName castudentpulse.com
	DocumentRoot /prosepoint/
	<directory "/prosepoint/">
		Options Indexes FollowSymLinks MultiViews
		AllowOverride All
		Order allow,deny
		allow from all
	</directory>
</virtualhost>

<virtualhost *:80>
	ServerName stfugid.com
	DocumentRoot /var/www/pluspanda/staging/current/public/
	<directory "/var/www/pluspanda/staging/current/public/">
		Options Indexes FollowSymLinks MultiViews
		AllowOverride All
		Order allow,deny
		allow from all
	</directory>
</virtualhost>


<virtualhost *:80>
	ServerName pathly.com
	ServerAlias pathly.com
	DocumentRoot /var/www/utils/
	<directory "/var/www/utils/">
		Options Indexes FollowSymLinks MultiViews
		AllowOverride All
		Order allow,deny
		allow from all
	</directory>
</virtualhost>

<virtualhost *:80>
	ServerName default
	ServerAlias *
	DocumentRoot /home/jade/www/plusjade/public
	<directory /home/jade/www/plusjade/public/>
		Options Indexes FollowSymLinks MultiViews
		AllowOverride All
		Order allow,deny
		allow from all
	</directory>
</virtualhost>
