Install curl
$ sudo apt install curl

Install Docker and Docker-ce
$ sudo apt-get -y install docker-compose
$ sudo systemctl enable docker
Add your user to docker group:
$ sudo usermod -aG docker [YourUser]
$ newgrp docker

Install Fabrics Docker Images and binary/config files
*In / and /fabric-samples*:
$ curl -sSL https://bit.ly/2ysbOFE | bash -s -- -s

Install Go, ** do this if you would like to use Go
Download Go for Linux at https://golang.org/dl/
*In ~/Downloads*:
$ tar -C /usr/local -xzf go1.17.1.linux-amd64.tar.gz
*Edit .bashrc in home directory and add lines*:
export GOPATH=$HOME/[YourPathToClaimChainDev]
export GOROOT=/usr/local/go
export PATH=$PATH:$GOROOT/bin
*Write changes and restart startup file*:
$ source ~/.bashrc

Install nvm -> install nodejs, ** do this if you would like to use javascript
$ curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.38.0/install.sh | bash
$ sudo nvm install 14.13.1
$ sudo nvm use 14.13.1

