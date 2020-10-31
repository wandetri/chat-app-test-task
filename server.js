let app = require('express')();
let http = require('http').Server(app);
let io = require('socket.io')(http);
let Redis = require('ioredis');
let redis = new Redis();
let socketioJwt = require('socketio-jwt');
let myEnv = require('dotenv').config({path:'.env'});

let users=[];

http.listen(4848,function(){
    console.log('Listening to port 4848');
})
redis.subscribe('private-channel',function(){
    console.log('subscribed to private channel');
});

redis.subscribe('group-channel', function(){
    console.log('subscribed to group channel')
})


io.use(socketioJwt.authorize({
    secret: myEnv.parsed.JWT_SECRET,
    timeout: 15000, 
    handshake:true, 

}))


io.on('connection',function(socket){
    socket.on('user_conn',function(user_id){
        users[user_id]=socket.id;
        io.emit('updateUserStatus', users);
    });
    

    socket.on('disconnect',function(){
        let i = users.indexOf(socket.id);
        users.splice(i,1,0);
        io.emit('updateUserStatus',users);
    })
});


redis.on('message', function(channel,message){
    message = JSON.parse(message);
    let event = message.event;
    let data = message.data.data;

    if(channel == 'private-channel'){
        let receiver_id = data.receiver_id;
        io.to(`${users[receiver_id]}`).emit(channel+':'+event, data);
    }else if(channel == 'group-channel'){
        io.emit('groupMessage', data);
    }
});
