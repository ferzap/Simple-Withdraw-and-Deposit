const bcrypt = require("bcrypt");
const saltRounds = 10;

const hashPassword = async (password) => {
    const hashedPassword = await bcrypt.hash(password, saltRounds);
    return hashedPassword;
};

const verifyPassword = async (password, hashedPassword) => {
    const isPasswordValid = await bcrypt.compare(password, hashedPassword);
    return isPasswordValid;
};

function exclude(user, keys) {
    return Object.fromEntries(Object.entries(user).filter(([key]) => !keys.includes(key)));
}

function getRandomInt(min, max) {
    const minCeiled = Math.ceil(min);
    const maxFloored = Math.floor(max);
    return Math.floor(Math.random() * (maxFloored - minCeiled) + minCeiled); // The maximum is exclusive and the minimum is inclusive
}

module.exports = {
    hashPassword,
    verifyPassword,
    exclude,
    getRandomInt
};
