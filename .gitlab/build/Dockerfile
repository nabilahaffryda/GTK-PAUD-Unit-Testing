FROM centos:7

RUN sed -ri \
        -e 's!override_install_langs=en_US.utf8!override_install_langs=en_US,id_ID!g' \
        /etc/yum.conf \
    && ( yum reinstall -y glibc-common || yum update -y glibc-common ) \
    && yum install -y \
        https://rpms.remirepo.net/enterprise/remi-release-7.rpm \
        https://dl.fedoraproject.org/pub/epel/epel-release-latest-7.noarch.rpm \
        http://opensource.wandisco.com/centos/7/git/x86_64/wandisco-git-release-7-2.noarch.rpm \
        yum-utils \
    && curl -sL https://rpm.nodesource.com/setup_10.x | bash - \
    && curl -sL https://dl.yarnpkg.com/rpm/yarn.repo | tee /etc/yum.repos.d/yarn.repo \
    && yum-config-manager --enable remi-php80 \
    && yum install -y \
        composer \
        gcc-c++ \
        git \
        make \
        nodejs \
        php-bcmath \
        php-cli \
        php-mbstring \
        php-mcrypt \
        php-mysqlnd \
        php-opcache \
        php-pecl-memcached \
        php-xml \
        php-zip \
        unzip \
        yarn \
    && rm -rf \
        /var/cache/yum \
        /var/tmp/yum-*

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('sha384', 'composer-setup.php') === '756890a4488ce9024fc62c56153228907f1545c228516cbf63f885e036d37e9a59d27d63f46af1d4d07ee0f76181c7d3') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php --version=2.0.9 --install-dir=/ \
    && php -r "unlink('composer-setup.php');"

RUN curl -L https://github.com/getsentry/sentry-cli/releases/download/1.62.0/sentry-cli-Linux-x86_64 -o /sentry-cli \
    && chmod a+x /sentry-cli

WORKDIR /source
