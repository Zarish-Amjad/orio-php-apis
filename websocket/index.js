var http = require("http");

http.createServer(function (request, response) {
   // Send the HTTP header 
   // HTTP Status: 200 : OK
   // Content Type: text/plain
   response.writeHead(200, {'Content-Type': 'text/plain'});
   
   // Send the response body as "Hello World"
   response.end('Hello World\n');
}).listen(8081);

// Console will print the message
console.log('Server running at port 443');


const WebSocket = require("ws").Server;
const HttpServer = require("http").createServer;

server = HttpServer();
console.log("=================================");
console.log(server);
console.log("=================================");
socket = new WebSocket({
  server: server,
  rejectUnauthorized: false,
  noServer: true,
});

socket.on("connection", function connection(ws) {
  console.log("Connected Deveices to me");
  ws.on("message", function incoming(message) {
    if(message == "request to accept for permission to open modal")
    {
      ws.send("accepted");
    }
    console.log("received: %s", message);
  });
  // setTimeout(() => {
  //   ws.send("Message from Server");
  // }, 1000);
  // setInterval(function(){ ws.send("Message from Server after 3 seconds")},3000);
  // socket.close();
});
server.listen(3002, () => console.log("server is running on 3002") );