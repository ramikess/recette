FROM node:22.14.0-bullseye

RUN echo '-= BUILD YARN IMAGE =-'

# set the directory in which will be launched any RUN, CMD, ENTRYPOINT, COPY and ADD following this WORKDIR command
WORKDIR /srv

RUN apt-get update && apt-get install -y --no-install-recommends \
    python3 build-essential \
    && ln -s /usr/bin/python3 /usr/bin/python \
    && apt install -y curl apt-transport-https \
    && apt-get clean && apt autoremove -y \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN curl -o- -L https://yarnpkg.com/install.sh | bash

RUN yarn install