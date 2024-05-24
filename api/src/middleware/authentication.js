const dotenv = require("dotenv");
const jwt = require("jsonwebtoken");
const { findUser } = require("../api/user/user.repository");

dotenv.config();

function generateAccessToken(user) {
    return jwt.sign(user, process.env.TOKEN_SECRET, { expiresIn: "48h" });
}

function authenticateToken(req, res, next) {
    const authHeader = req.headers["authorization"];
    const token = authHeader && authHeader.split(" ")[1];

    if (token == null) return res.status(401).json({
        status: false,
        code: 401,
        message: "Unauthorized"
    });

    jwt.verify(token, process.env.TOKEN_SECRET, async (err, user) => {

        if (err) return res.status(403).json({
            status: false,
            code: 403,
            message: "Forbidden"
        });

        const checkUser = await verifyToken(user.email)
        if(!checkUser){
            return res.status(401).json({
                status: false,
                code: 401,
                message: "Unauthorized"
            });
        }

        req.user = user;

        next();
    });
}

const verifyToken = async (email) => {
    const user = await findUser(email)
    if(user){
        return true
    } else {
        return false
    }
}

module.exports = {
    generateAccessToken,
    authenticateToken
}
