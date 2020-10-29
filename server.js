let app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);

http.listen(4848,function(){
    console.log('Listening to port 4848');
})