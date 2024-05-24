const prisma = require('../../connection')
const { hashPassword } = require('../utils/helper')

const findUser = async (email) => {
    const user = await prisma.user.findUnique({
        where: {
            email: email,
        },
    });

    return user
}

const insertUser = async (userData) => {
    const user = await prisma.user.create({
        data: {
            name: userData.name,
            email: userData.email,
            password:await hashPassword(userData.password),
        }
    })
    return user
}


module.exports = {
    findUser,
    insertUser
}

