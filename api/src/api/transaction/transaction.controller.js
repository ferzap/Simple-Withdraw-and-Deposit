const express = require("express");
const { authenticateToken } = require("../../middleware/authentication");
const { createTransaction, findTransactionsByUserId } = require("./transaction.service");
const { findWallet, updateWalletBalance } = require("../wallet/wallet.service");

const axios = require("axios");
const router = express.Router();

router.get("/history", authenticateToken, async (req, res) => {
    const user = req.user;
    const transactions = await findTransactionsByUserId(user.id);
    res.status(200).json({
        status: true,
        code: 200,
        message: "Success get transaction history",
        data: transactions,
    });
});

router.post("/deposit", authenticateToken, async (req, res) => {
    const depositData = req.body;
    const user = req.user;
    const url = process.env.EXTERNALAPIURL;
    const token = btoa(JSON.stringify({ name: user.name }));
    try {
        if (!depositData.order_id) {
            throw new Error("Order ID is required");
        }
        if (!depositData.amount) {
            throw new Error("Amount is required");
        } else if (depositData.amount < 0) {
            throw new Error("Amount must be greater than 0");
        }
        if (!depositData.type) {
            throw new Error("Type is required");
        } else {
            if (depositData.type !== "deposit" && depositData.type !== "withdraw") {
                throw new Error("Type must be deposit or withdraw");
            }
        }

        if(!depositData.timestamp) {
            throw new Error("Timestamp is required");
        }

        const response = await axios.post(
            url + "/deposit",
            {
                order_id: depositData.order_id,
                amount: parseFloat(depositData.amount.toFixed(2)),
                timestamp: depositData.timestamp,
            },
            {
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            }
        );

        //Response success from the third party API
        if (response.data.status) {
            const apiResponseJson = {
                order_id: response.data.data.order_id,
                amount: parseFloat(response.data.data.amount.toFixed(2)),
                status: response.data.data.status,
            };

            const deposit = await createTransaction(user, apiResponseJson, depositData);
            if (deposit) {
                const walletUpdateData = {
                    amount: parseFloat(apiResponseJson.amount.toFixed(2)),
                    type: "deposit",
                };
                if (apiResponseJson.status === 1) {
                    await updateWalletBalance(user.id, walletUpdateData);
                }
                res.status(201).json({
                    status: true,
                    code: 201,
                    message: "Success create deposit transaction",
                    data: deposit,
                });
            } else {
                res.status(400).json({
                    status: false,
                    code: 400,
                    message: "Failed create deposit transaction",
                });
            }
        } else {
            res.status(400).json({
                status: false,
                code: 400,
                message: "Failed create deposit transaction",
            });
        }
    } catch (error) {
        res.status(400).json({
            status: false,
            code: 400,
            message: error.message,
        });
    }
});

router.post("/withdraw", authenticateToken, async (req, res) => {
    const withdrawData = req.body;
    const user = req.user;
    const url = process.env.EXTERNALAPIURL;
    const token = btoa(JSON.stringify({ name: user.name }));

    try {
        if (!withdrawData.order_id) {
            throw new Error("Order ID is required");
        }

        if (!withdrawData.amount) {
            throw new Error("Amount is required");
        } else if (withdrawData.amount < 0) {
            throw new Error("Amount must be greater than 0");
        }

        if (!withdrawData.type) {
            throw new Error("Type is required");
        } else {
            if (withdrawData.type !== "deposit" && withdrawData.type !== "withdraw") {
                throw new Error("Type must be deposit or withdraw");
            }
        }

        if(!withdrawData.timestamp) {
            throw new Error("Timestamp is required");
        }

        const checkBalance = await findWallet(user.id);
        if (checkBalance.balance <= parseFloat(withdrawData.amount.toFixed(2))) {
            throw new Error("You don't have enough balance");
        }
        const response = await axios.post(
            url + "/withdraw",
            {
                order_id: withdrawData.order_id,
                amount: parseFloat(withdrawData.amount.toFixed(2)),
                timestamp: withdrawData.timestamp,
            },
            {
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            }
        );

        //Response success from the third party API
        if (response.data.status) {
            const apiResponseJson = {
                order_id: response.data.data.order_id,
                amount: parseFloat(response.data.data.amount.toFixed(2)),
                status: response.data.data.status,
            };

            const withdraw = await createTransaction(user, apiResponseJson, withdrawData);
            if (withdraw) {
                const walletUpdateData = {
                    amount: parseFloat(apiResponseJson.amount.toFixed(2)),
                    type: "withdraw",
                };
                if (apiResponseJson.status === 1) {
                    await updateWalletBalance(user.id, walletUpdateData);
                }
                res.status(201).json({
                    status: true,
                    code: 201,
                    message: "Success create withdraw transaction",
                    data: withdraw,
                });
            } else {
                res.status(400).json({
                    status: false,
                    code: 400,
                    message: "Failed create withdraw transaction",
                });
            }
        } else {
            res.status(400).json({
                status: false,
                code: 400,
                message: "Failed create withdraw transaction",
            });
        }
    } catch (error) {
        res.status(400).json({
            status: false,
            code: 400,
            message: error.message,
        });
    }
});

module.exports = router;
