FROM gitpod/workspace-base

USER root

# Update APT Database
### base ###
RUN sudo apt-get update -q \
    && sudo apt-get install -y php-dev php7.4-sqlite3

# Install XDebug
RUN wget http://xdebug.org/files/xdebug-2.9.1.tgz \
    && tar -xvzf xdebug-2.9.1.tgz \
    && cd xdebug-2.9.1 \
    && phpize \
    && ./configure \
    && make \
    && sudo mkdir -p /usr/lib/php/20190902 \
    && sudo cp modules/xdebug.so /usr/lib/php/20190902 \
    && sudo bash -c "echo -e '\nzend_extension = /usr/lib/php/20190902/xdebug.so\n[XDebug]\nxdebug.remote_enable = 1\nxdebug.remote_autostart = 1\n' >> /etc/php/7.4/cli/php.ini"

# Install latest composer v2 release
RUN curl -s https://getcomposer.org/installer | php \
    && sudo mv composer.phar /usr/bin/composer \
    && sudo mkdir -p /home/gitpod/.config \
    && sudo chown -R gitpod:gitpod /home/gitpod/.config

USER gitpod

# Install Changelogger
RUN COMPOSER_ALLOW_SUPERUSER=1 composer global require churchtools/changelogger

# Add Workspace/Project composer bin folder to $PATH
ENV PATH="$PATH:$HOME/.config/composer/vendor/bin:$GITPOD_REPO_ROOT/vendor/bin"