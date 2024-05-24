const prisma = require("../../connection");

const inserTransaction = async (user, apiResponseJson, transactionData) => {
    const transaction = await prisma.transaction.create({
        data: {
            user_id: user.id,
            order_id: apiResponseJson.order_id,
            amount: parseFloat(apiResponseJson.amount.toFixed(2)),
            timestamp: new Date(transactionData.timestamp).toISOString(),
            note: transactionData?.note,
            status: apiResponseJson.status,
            type: transactionData.type,
        },
    });

    return transaction;
};

const getTransactionsByUserId = async (userId) => {
    const transactions = await prisma.transaction.findMany({
        where: {
            user_id: userId,
        },
        orderBy: {
            id: 'desc'
        },
        include: {
            user: {
                select: {
                    name: true,
                },
            },
        },
    });

    return transactions;
};

module.exports = {
    getTransactionsByUserId,
    inserTransaction,
};
