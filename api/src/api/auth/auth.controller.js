const express = require("express");
const { generateAccessToken } = require("../../middleware/authentication");
const { getUserLogin, getUserByEmail ,createUser } = require("../user/user.service");
const { createWallet } = require("../wallet/wallet.service");


const router = express.Router();

router.post("/login", async (req, res) => {
    
    const userData = req.body;
    try {
        if(!userData.email || !userData.password){
            throw new Error("Email and password is required");
        }

        const user = await getUserLogin(userData)
    
        if(user){
            const token = generateAccessToken(user);
            res.status(200).json({
                status: true,
                code: 200,
                message: "Success login",
                data: {
                    user: user,
                    token: token,
                },
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

router.post("/register", async (req, res) => {
    const userData = req.body;
    try {
        const userByEmail = await getUserByEmail(userData.email)
        if(userByEmail){
            throw new Error("Email is already registered");
        }

        if(!userData.email){
            throw new Error("Email is required");
        }
        if(!userData.name){
            throw new Error("Name is required");
        }
        if(!userData.password){
            throw new Error("Password is required");
        }

        const user = await createUser(userData)
        if(user){
            const wallet = await createWallet(user.id)
            if (wallet) {
                res.status(200).json({
                    status: true,
                    code: 200,
                    message: "Success register",
                    data: user,
                });
            } 
        }
    } catch (error) {
        res.status(400).json({
            status: false,
            code: 400,
            message: error.message,
        });
    }
})

module.exports = router;
