const prisma = require('../../connection')

const getWalletByUserId = async (userId) => {
    const wallet = await prisma.wallet.findUnique({
        where: {
            user_id: userId
        }
    })

    return wallet
}

const insertWallet = async (userId) => {
    const wallet = await prisma.wallet.create({
        data: {
            user_id: userId,
            balance: 0
        },
    });

    return wallet
}

const updateWalletByUserId = async (userId, balance) => {
    const wallet = await prisma.wallet.update({
        where: {
            user_id: userId
        },
        data: {
            balance: parseFloat(balance.toFixed(2))
        }
    })

    return wallet
}

module.exports = {
    getWalletByUserId,
    insertWallet,
    updateWalletByUserId
}

