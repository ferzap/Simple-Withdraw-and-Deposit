const authenticateToken = async (req, res, next) => {
    const authHeader = req.headers["authorization"];
    const token = authHeader && authHeader.split(" ")[1];

    if (token == null) return res.status(401).json({ message: "ape" });

    const data = JSON.parse(atob(token))

    if (data.name == null) {
        return res.status(401).json({ message: "opa" });
    }

    next();
};

module.exports = { authenticateToken };
