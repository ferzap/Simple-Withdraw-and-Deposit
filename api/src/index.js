const express = require("express");
const dotenv = require("dotenv");
const cors = require('cors');

const app = express();
app.use(express.json());
app.use(cors());


dotenv.config();


app.get("/", (req, res) => {
    res.send("API V1 Deposit & Withdraw");
});

const AuthController = require('./api/auth/auth.controller');
const WalletController = require('./api/wallet/wallet.controller');
const TransactionController = require('./api/transaction/transaction.controller');
// const WithdrawController = require('./api/wirhdraw/wirhdraw.controller');
// const tokenController = require('./api/token.controller')

app.use('/', AuthController);
app.use('/wallet', WalletController);
app.use('/transaction', TransactionController);
// app.use('/withdraw', WithdrawController);

app.listen(process.env.PORT, () => {
    console.log("API running in http://" + process.env.LOCALHOST + ":" + process.env.PORT);
});
