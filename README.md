# Outlook autodiscover with nginx
Reimplementation of Outlook's autodiscover protocol with PHP working with NGINX.

## Requirements
- NGINX webserver
- PHP (with an installed XML module)

## Installation

1. Clone this repository (e.g. into the `/var/www/` directory)
2. Edit the `config.xml` file to suit your mailserver
3. Create the following DNS records (instead of example.com use your own domain):
```
Essential records:
  autoconfig.example.com		A	<ip of your server>
  _autodiscover._tcp.example.com	SRV 10	10 443 autoconfig.example.com.

Optional recommended records:
  _imaps._tcp.example.com		SRV 0	1 993 <your mailserver's subdomain>
  _submission._tcp.example.com		SRV 0	1 587 <your mailserver's subdomain>
```
4. Create a new NGINX virtual host and insert the correct paths, server name and PHP socket:
```
server {
        listen 443 ssl http2;
        listen [::]:443;

        root /path/to/the/repo;
        server_name autoconfig.example.com;

        ssl_certificate /path/to/the/certificate;
        ssl_certificate_key /path/to/the/key;

        location ~* ^/Autodiscover/Autodiscover.xml {
            fastcgi_split_path_info ^(.+\.php)(/.+)$;
            fastcgi_pass unix:/var/run/php/php7.3-fpm.sock; # change to your PHP socket
            include /etc/nginx/fastcgi_params;
            fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
            try_files /autodiscover-xml.php =404;
          }

        location ~* ^/Autodiscover/Autodiscover.json {
            fastcgi_split_path_info ^(.+\.php)(/.+)$;
            fastcgi_pass unix:/var/run/php/php7.3-fpm.sock; # change to your PHP socket
            include /etc/nginx/fastcgi_params;
            fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
            try_files /autodiscover-json.php =404;
        }
}
```
5. Reload NGINX: `nginx -s reload`
6. Done! You can check if everything is correct [here](https://testconnectivity.microsoft.com) or by doing a right click while holding CTRL on the Outlook icon in the taskbar and selecting "Email-Autodiscover test"

## Thunderbird autoconfig
This project also supports Thunderbird autoconfig. To activate it you have to add the following into the `server` block of the upper mentioned virtual host and change the PHP socket:
```
        location ~* ^/mail/config-v1.1.xml {
            fastcgi_split_path_info ^(.+\.php)(/.+)$;
            fastcgi_pass unix:/var/run/php/php7.3-fpm.sock; # change to your PHP socket
            include /etc/nginx/fastcgi_params;
            fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
            try_files /autoconfig.php =404;
        }
```
And then reload NGINX: `nginx -s reload`

## Other
Some older versions of Outlook may use the so-called Guesssmart which basically tries every possible POP3, IMAP and SMTP combination to find the correct settings.
This creates a lot of requests to the server, so please beware that you might have to adjust some anti-spam or anti-brute-force mechanisms on your server (e.g. fail2ban, ...).

## License
This project is licensed under the GNU General Public License Version 3 - see the LICENSE file for details.
