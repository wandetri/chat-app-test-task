let app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
let users=[];
http.listen(4848,function(){
    console.log('Listening to port 4848');
})

io.on('connection',function(socket){
    socket.on('user_conn',function(user_id){
        users[user_id]=socket.id;
        io.emit('updateUserStatus', users);
        console.log('user connected'+user_id);
    });

    socket.on('disconnect',function(){
        let i = users.indexOf(socket.id);
        users.splice(i,1,0);
        io.emit('updateUserStatus',users);
        console.log(users);
    })
});