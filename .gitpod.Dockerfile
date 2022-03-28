FROM gitpod/workspace-mysql

USER root

# Update APT Database
### base ###
RUN apt-get update -q \
    && apt-get install -y php-dev

# Install XDebug
RUN curl -sSf http://xdebug.org/files/xdebug-3.1.3.tgz | tar xz \
    && cd xdebug-3.1.3 \
    && phpize \
    && ./configure \
    && make install -j$(nproc) \
    && printf 'zend_extension=xdebug\n[XDebug]\nxdebug.remote_enable=1\nxdebug.remote_autostart=1\n' > /etc/php/7.4/mods-available/xdebug.ini \
    && ln -sf /etc/php/7.4/mods-available/xdebug.ini "$(php-config --ini-dir)/20-xdebug.ini"

# Install latest composer v2 release
RUN curl -sSf https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer \
    && mkdir -p /home/gitpod/.config \
    && chown -R gitpod:gitpod /home/gitpod/.config

USER gitpod

# Install Changelogger
RUN composer global require churchtools/changelogger

# Add Workspace/Project composer bin folder to $PATH
ENV PATH="$PATH:$HOME/.config/composer/vendor/bin:$GITPOD_REPO_ROOT/vendor/bin"