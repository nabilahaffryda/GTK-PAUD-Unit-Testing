FROM centos:7

RUN sed -ri \
        -e 's!override_install_langs=en_US.utf8!override_install_langs=en_US,id_ID!g' \
        /etc/yum.conf \
    && ( yum reinstall -y glibc-common || yum update -y glibc-common ) \
    && yum install -y \
        https://rpms.remirepo.net/enterprise/remi-release-7.rpm \
        https://dl.fedoraproject.org/pub/epel/epel-release-latest-7.noarch.rpm \
        yum-utils \
    && yum-config-manager --enable remi-php80 \
    && yum install -y \
        php \
        php-bcmath \
        php-mbstring \
        php-mcrypt \
        php-mysqlnd \
        php-opcache \
        php-pecl-memcached \
        php-xml \
        php-zip \
        unzip \
        wkhtmltopdf-opt \
    && rm -rf \
        /var/cache/yum \
        /var/tmp/yum-*

ARG DOMAIN
ARG STAGE
ARG MEMCACHED

RUN \
    # setup httpd
    sed -ri \
        -e "s!ServerAdmin root@localhost!ServerAdmin admin@${DOMAIN}!g" \
        -e "s!#ServerName www.example.com:80!ServerName ${DOMAIN}:80!g" \
        -e 's!/var/www/html!/var/www/html/public!g' \
        -e '151s!.*!    AllowOverride All!g' \
        # redirect log
        -e 's!^(\s*CustomLog\s+)\S+!\1/proc/self/fd/1!g' \
        -e 's!^(\s*ErrorLog\s+)\S+!\1/proc/self/fd/2!g' \
        /etc/httpd/conf/httpd.conf ; \
    sed -ri \
        # disable local session handler setting
        -e 's!php_value session.save_handler!#php_value session.save_handler!g' \
        -e 's!php_value session.save_path!#php_value session.save_path!g' \
        -e 's!php_value soap.wsdl_cache_dir!#php_value soap.wsdl_cache_dir!g' \
        /etc/httpd/conf.d/php.conf ; \
    # setup php
    sed -ri \
        # hide php signature
        -e 's!expose_php = On!expose_php = Off!g' \
        # set session handler
        -e 's!session.save_handler = files!session.save_handler = memcached!g' \
        -e "s!;session.save_path = \"/tmp\"!session.save_path = \"${MEMCACHED}\"!g" \
        # update memory limit
        -e 's!;memory_limit = 128M!memory_limit = 256M!g' \
        # update timezona
        -e 's!;date.timezone =!date.timezone = Asia/Jakarta!g' \
        # update upload limit
        -e 's!upload_max_filesize = 2M!upload_max_filesize = 50M!g' \
        -e 's!post_max_size = 8M!post_max_size = 50M!g' \
        /etc/php.ini ; \
    # setup php memcache
    sed -ri \
        # disable session locking
        -e 's!;memcached.sess_locking = On!memcached.sess_locking = Off!g' \
        -e 's!;memcached.sess_lock_wait_max = 150;!memcached.sess_lock_wait_max = 2000;!g' \
        -e 's!;memcached.sess_lock_retries = 5;!memcached.sess_lock_retries = 10;!g' \
        /etc/php.d/50-memcached.ini ; \
    # batasi 1 worker untuk devel
    if [[ ${STAGE} == devel ]] ; then \
            sed -ri \
                -e '$a\\ \
\n\
\# Extra options \n\
StartServers 1 \n\
MinSpareServers 1 \n\
MaxSpareServers 2' \
                /etc/httpd/conf/httpd.conf ; \
        fi

EXPOSE 80/tcp
CMD ["/usr/sbin/httpd", "-DFOREGROUND"]

WORKDIR /var/www/html
COPY --chown=root:root . .

RUN chown -R apache:apache storage bootstrap
