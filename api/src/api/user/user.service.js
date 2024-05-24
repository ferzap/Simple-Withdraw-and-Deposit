const { findUser, insertUser } = require("./user.repository");
const { verifyPassword, exclude } = require("../utils/helper");

const getUserLogin = async (userData) => {
    const user = await findUser(userData.email);
    if (!user) {
        throw new Error("Email or password is incorrect");
    }

    const verified = await verifyPassword(userData.password, user.password);

    if (!verified) {
        throw new Error("Email or password is incorrect");
    }

    const userWithoutPassword = exclude(user, ['password'])

    return userWithoutPassword;
};

const getUserByEmail = async (email) => {
    const user = await findUser(email);
    return user;
}

const createUser = async (userData) => {
    const user = await insertUser(userData);
    if (!user) {
        throw new Error("Failed to create user");
    }
    return user;
};

module.exports = {
    getUserLogin,
    getUserByEmail,
    createUser,
};
