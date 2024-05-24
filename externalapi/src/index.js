const express = require("express");
const dotenv = require("dotenv");
const cors = require("cors");
const { authenticateToken } = require("./middleware");

const app = express();
app.use(express.json());
app.use(cors());

dotenv.config();

app.get("/", (req, res) => {
    res.send("API Deposit & Withdraw");
});

app.post('/deposit', authenticateToken, async (req, res) => {
    const data = req.body;
    if(!data.order_id || !data.amount){
        return res.json({
            status: false,
            code: 400,
            message: "Order ID and Amount is required"
        });
    }
    res.json({
        status: true,
        code: 200,
        message: "Deposit Success",
        data: {
            order_id: data.order_id,
            amount: parseFloat(data.amount.toFixed(2)),
            status: Math.ceil(Math.random() * 2),
        }
    });
});

app.post('/withdraw', authenticateToken, async (req, res) => {
    const data = req.body;
    if(!data.order_id || !data.amount){
        return res.json({
            status: false,
            code: 400,
            message: "Order ID and Amount is required"
        });
    }
    
    res.json({
        status: true,
        code: 200,
        message: "Withdraw Success",
        data: {
            order_id: data.order_id,
            amount: parseFloat(data.amount.toFixed(2)),
            status: Math.ceil(Math.random() * 2),
        }
    });
});

app.listen(process.env.PORT, () => {
    console.log("API running in http://" + process.env.LOCALHOST + ":" + process.env.PORT);
});
