let app = require('express')();
let http = require('http').Server(app);
let io = require('socket.io')(http);
let Redis = require('ioredis');
let redis = new Redis();

let users=[];
http.listen(4848,function(){
    console.log('Listening to port 4848');
})
redis.subscribe('private-channel',function(){
    console.log('subscribed to pchannel');
});

redis.on('message', function(channel,message){
    message = JSON.parse(message);
    
    if(channel == 'private-channel'){
        console.log(message);
        let data = message.data.data;
        let receiver_id = data.receiver_id;
        let event = message.event;

        io.to(`${users[receiver_id]}`).emit(channel+':'+event, data);
    }
});

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