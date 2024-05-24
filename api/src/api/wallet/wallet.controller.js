const express = require("express");
const { authenticateToken } = require("../../middleware/authentication");
const { findWallet, updateWalletBalance } = require("./wallet.service");

const router = express.Router();

router.get("/", authenticateToken ,async (req, res) => {
    const wallet = await findWallet(req.user.id);
    res.json({
        status: true,
        code: 200,
        message: 'Wallet Balance',
        data: {
            balance: parseFloat(wallet.balance.toFixed(2))
        }
    });
})

module.exports = router;

