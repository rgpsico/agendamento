// index.js
const { Server } = require("socket.io");
const http = require("http");

const server = http.createServer();

const io = new Server(server, {
  cors: {
    origin: [
      "https://rjpasseios.com.br",
      "https://admin.rjpasseios.com.br"
    ],
    methods: ["GET", "POST"],
  }
});

io.on("connection", (socket) => {
  console.log("🟢 Cliente conectado:", socket.id);

  socket.on("mensagem", (data) => {
    console.log("📩 Mensagem:", data);
    socket.broadcast.emit("mensagem", data);
  });

  socket.on("disconnect", () => {
    console.log("🔴 Cliente desconectado:", socket.id);
  });
});

server.listen(3001, () => {
  console.log("🚀 Socket.IO rodando na porta 3001");
});
