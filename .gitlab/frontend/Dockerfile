FROM centos:7

# setup repo, install nginx dan cleanup
RUN yum install -y \
        http://mirror.telkomuniversity.ac.id/epel/epel-release-latest-7.noarch.rpm \
    && yum install -y \
        nginx-1.20.1 \
    && rm -rf \
        /var/cache/yum \
        /var/tmp/yum-*

ARG STAGE

RUN \
    # setup nginx
    sed -ri \
        -e '46a\\ \
        location / {\n\
            try_files \$uri \$uri/ @rewrites;\n\
        }\n\
\n\
        gzip_static  on;\n\
        gzip_proxied expired no-cache no-store private auth;\n\
\n\
        location @rewrites {\n\
            rewrite ^(.+)$ /index.html last;\n\
        }' \
        -e 's!^(\s*access_log\s+)\S+(\s*\S*);!\1/proc/self/fd/1\2;!g' \
        -e 's!^(\s*error_log\s+)\S+(\s*\S*);!\1/proc/self/fd/2\2;!g' \
        /etc/nginx/nginx.conf ; \
    # batasi 1 worker untuk devel
    if [[ ${STAGE} == devel ]] ; then \
            sed -ri \
                -e 's!^worker_processes auto;!worker_processes 1;!g' \
                /etc/nginx/nginx.conf ; \
        fi

EXPOSE 80/tcp
CMD ["/usr/sbin/nginx", "-g", "daemon off;"]

WORKDIR /usr/share/nginx/html
COPY --chown=root:root dist .
