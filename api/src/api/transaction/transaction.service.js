const { inserTransaction, getTransactionsByUserId } = require('./transaction.repository')

const createTransaction = async (user, apiResponseJson ,transactionData) => {
    const transaction = await inserTransaction(user, apiResponseJson, transactionData)

    return transaction;
}

const findTransactionsByUserId = async (userId) => {
    const transactions = await getTransactionsByUserId(userId)
    const transactionsWithUser = transactions.map(transaction => {
        if(transaction.status === 1) {
            transaction.status = "Success"
        } else {
            transaction.status = "Failed"
        }
        return transaction
    })

    return transactionsWithUser;
}

module.exports = {
    createTransaction,
    findTransactionsByUserId
}

