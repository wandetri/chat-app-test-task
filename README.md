# Simple Chat Application
Simple Realtime Chat Application utilizing Laravel, MySQL, Redis & Socket.io<br>
The socket connection is authenticated using JWT.

[Installation](#installation)

## Features
### Login
<img src="https://raw.githubusercontent.com/wandetri/media-storage/master/chat-app-test-task/login-page.png" alt="Login">

### Register
<img src="https://raw.githubusercontent.com/wandetri/media-storage/master/chat-app-test-task/register-page.png" alt="Register">

### Group Chat
<img src="https://raw.githubusercontent.com/wandetri/media-storage/master/chat-app-test-task/group-chat.gif" alt="Group Chat">

### Private Chat
<img src="https://raw.githubusercontent.com/wandetri/media-storage/master/chat-app-test-task/private-chat.gif" alt="Private Chat">

### Lazy Loading Older Messages
<img src="https://raw.githubusercontent.com/wandetri/media-storage/master/chat-app-test-task/lazy-load-older-message.gif" alt="Group Chat">

### Online Member Indicator
<img src="https://raw.githubusercontent.com/wandetri/media-storage/master/chat-app-test-task/online-indicator.gif" alt="Online Indicator">

### Filter Online Member
<img src="https://raw.githubusercontent.com/wandetri/media-storage/master/chat-app-test-task/switch-online.gif" alt="Filter Online">


## Installation
Install pm2
```
sudo npm i -g pm2

```
Create database on MySQL
<br>
Clone this repository to your local machine
<br>
Duplicate the `env.example` and adjust with your DB configuration
```
cp .env.example .env
```
Run installation bash
```
bash run.sh
```
Now you can test the app, go to your browser `127.0.0.1:8000`
