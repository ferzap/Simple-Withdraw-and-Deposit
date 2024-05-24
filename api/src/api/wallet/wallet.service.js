const { getWalletByUserId, insertWallet, updateWalletByUserId } = require("./wallet.repository");

const findWallet = async (userId) => {
    const wallet = await getWalletByUserId(userId);

    return wallet;
};

const createWallet = async (userId) => {
    const wallet = await insertWallet(userId);

    return wallet;
};

const updateWalletBalance = async (userId, walletData) => {
    const wallet = await getWalletByUserId(userId);
    const lastBalance = wallet.balance;
    let newBalance = 0;
    if (walletData.type == "deposit") {
        newBalance = lastBalance + walletData.amount;
    }
    if (walletData.type == "withdraw") {
        newBalance = lastBalance - walletData.amount;
    }
    await updateWalletByUserId(userId, parseFloat(newBalance.toFixed(2)));
};

module.exports = {
    findWallet,
    createWallet,
    updateWalletBalance,
};
